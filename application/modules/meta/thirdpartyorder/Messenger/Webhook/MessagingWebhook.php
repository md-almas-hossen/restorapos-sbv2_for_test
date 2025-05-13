<?php

namespace Meta\ThirdPartyOrder\Messenger\Webhook;

use Meta\ThirdPartyOrder\Exceptions\NoCategoriesFoundException;
use Meta\ThirdPartyOrder\Exceptions\NoProductsFoundException;
use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\Messenger\Api\SendGreetings;
use Meta\ThirdPartyOrder\Messenger\Api\SendMenus;
use Meta\ThirdPartyOrder\Messenger\Api\SendMessage;
use Meta\ThirdPartyOrder\Messenger\Api\SendProducts;
use Meta\ThirdPartyOrder\Messenger\MessengerWebhook;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MessagingWebhook extends MessengerWebhook
{
    /**
     * @var string
     */
    protected $platform;

    /**
     * @var int
     */
    protected $senderId;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var array
     */
    protected $quickReply;

    /**
     * @var array
     */
    protected $postback;

    /**
     * @var array
     */
    protected $nlp;

    /**
     * Parse inputs
     *
     * @return void
     */
    public function parse()
    {
        $this->platform  = static::getProp($this->input, ['object'], 'messenger');

        if ($messegeInfo = static::getProp($this->input, ['entry', 0, 'messaging', 0])) {
            // Is valid message from messenger
            // build sender id and message text
            $this->senderId     = static::getProp($messegeInfo, ['sender', 'id']);
            $this->message      = static::getProp($messegeInfo, ['message', 'text'], '');
            $this->quickReply   = static::getProp($messegeInfo, ['message', 'quick_reply'], '');
            $this->nlp          = static::getProp($messegeInfo, ['message', 'nlp'], '');
            $this->postback     = static::getProp($messegeInfo, ['postback'], '');
        }
    }

    /**
     * @return void
     */
    private function sendGreetings()
    {
        $sg = new SendGreetings;
        $sg->setRecipient($this->senderId);
        $sg->init();
    }

    /**
     * @return void
     */
    private function sendCategories()
    {
        $sm = new SendMenus;
        $sm->setRecipient($this->senderId);
        $sm->setTypingAction($this->platform !== 'instagram');
        $sm->init();
    }

    /**
     * @param integer $menuId
     * @return void
     */
    private function sendProducts(int $menuId = null, array $entities = null)
    {
        $sp = new SendProducts;
        $sp->setRecipient($this->senderId);
        $menuId && $sp->setMenuId($menuId);
        $entities && $sp->setSearchEntities($entities);
        $sp->setTypingAction($this->platform !== 'instagram');
        $sp->init();
    }

    private function sendItemByQuery(array $entities)
    {
        try {
            // search for product
            $this->sendProducts(null, $entities);
        } catch (NoProductsFoundException $e) {
            // product query result is empty
            // send ops message
            $sm = new SendMessage;
            $sm->setRecipient($this->senderId);
            $sm->setText("Sorry! We coudn't found any products you are looking for. \nHowerver! You can also browse our menus");
            $sm->init();

            // send available categories
            $this->sendCategories();
        }
    }

    /**
     * Initialize webhook
     *
     * @return void
     */
    public function init()
    {
        try {
            // $this->log('INFO', print_r($this->input, true), 'messenger-webhook-payload');

            if ($this->postback && ($postbackPayload = static::getProp($this->postback, ['payload']))) {
                if (preg_match('/^GET_STARTED$/', $postbackPayload)) {
                    // Requested for get started
                    $this->sendGreetings();
                    goto return_response;
                }

                if (preg_match('/^SHOW_CATEGORIES$/', $postbackPayload)) {
                    // Requested for list menus
                    $this->sendCategories();
                    goto return_response;
                }
            }

            if ($this->quickReply && ($quickReplyPayload = static::getProp($this->quickReply, ['payload']))) {
                if (preg_match('/^MENU_([0-9]+)$/', $quickReplyPayload, $matches)) {
                    // Requested for list products
                    $this->sendProducts($matches[1]);
                    goto return_response;
                }
            }

            if ($this->nlp && is_array($this->nlp)) {
                // Message processed by Natural Language Processing
                // Check entities for item query
                $nlpIntents = static::getProp($this->nlp, ['intents'], []);
                $nlpEntities = static::getProp($this->nlp, ['entities'], []);

                if ($searchableItems = static::getProp($nlpEntities, ['category:category'], [])) {
                    // Requested for product query
                    $itemsArr = array_column($searchableItems, 'value');
                    $this->sendItemByQuery($itemsArr);
                    goto return_response;
                }

                if (in_array('category_questions', array_column($nlpIntents, 'name'))) {
                    // Requested for list menus
                    $this->sendCategories();
                    goto return_response;
                }
            }
        } catch (ThirdPartyRequestException $rq_e) {
            $this->log('ERROR', json_encode($rq_e->getArray()), 'messenger-webhook');
            http_response_code(200);
            exit();
        } catch (\Exception $e) {
            $this->log('ERROR', $e->getMessage(), 'messenger-webhook');
            http_response_code(200);
            exit();
        }

        return_response:
        http_response_code(201);
        exit();
    }
}
