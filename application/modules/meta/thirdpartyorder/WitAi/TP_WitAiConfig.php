<?php

namespace Meta\ThirdPartyOrder\WitAi;

use Meta\ThirdPartyOrder\ConfigInterface;
use Meta\ThirdPartyOrder\ThirdPartyConfig;
use Meta\ThirdPartyOrder\WitAi\Api\GetAllEntities;
use Meta\ThirdPartyOrder\WitAi\Api\GetAllIntents;
use Meta\ThirdPartyOrder\WitAi\Api\GetAllUtterances;
use Meta\ThirdPartyOrder\WitAi\Api\SetCategoryIntent;
use Meta\ThirdPartyOrder\WitAi\Api\SetCategoryItemEntity;
use Meta\ThirdPartyOrder\WitAi\Api\SetUtterance;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class TP_WitAiConfig extends ThirdPartyConfig implements ConfigInterface
{
    /**
     * @var integer
     */
    protected $companyCode = -2;

    /**
     * @var string
     */
    protected $configModel = 'messenger_model';

    /**
     * @var array
     */
    protected $config = [
        [
            'conf_key' => 'witServerToken',
            'conf_value' => '',
        ],
        [
            'conf_key' => 'witClientToken',
            'conf_value' => '',
        ],
        [
            'conf_key' => 'categoryIntent',
            'conf_value' => 'category_questions',
        ],
        [
            'conf_key' => 'itemEntity',
            'conf_value' => 'category',
        ],
        [
            'conf_key' => 'itemEntityWithRole',
            'conf_value' => 'category:category',
        ],
        [
            'conf_key' => 'witUtterances',
            'conf_value' => '[
                    {"text":"show me categories","intent":"category_questions","entity":""},
                    {"text":"browse menus","intent":"category_questions","entity":""},
                    {"text":"is pizza available?","intent":"category_questions","entity":"pizza"},
                    {"text":"I want masala dosa","intent":"category_questions","entity":"masala dosa"},
                    {"text":"hello","intent":"out_of_scope","entity":""},
                    {"text":"what is this?","intent":"out_of_scope","entity":""},
                    {"text":"I want thai food and khichuri","intent":"category_questions","entity":"thai food,khichuri"}
                ]',
        ],
    ];

    /**
     * @var array
     */
    private $utteranceAttr;

    /**
     * Run config
     *
     * @return TP_MessengerConfig
     */
    public function run(): ConfigInterface
    {
        try {
            $this->setCategoryIntent();
            $this->setItemEntity();
            $this->setUtterance();
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        return $this;
    }

    public function setCategoryIntent()
    {
        $i = new GetAllIntents($this);
        $intents = $i->init();

        if (!is_array($intents) || !in_array($this->getConfig('categoryIntent'), array_column($intents, 'name'))) {
            // user intent not exists in current
            $s = new SetCategoryIntent($this);
            $s->init();
        }
    }

    public function setItemEntity()
    {
        $e = new GetAllEntities($this);
        $entities = $e->init();

        if (!is_array($entities) || !in_array($this->getConfig('itemEntity'), array_column($entities, 'name'))) {
            // user entity not exists in current
            $s = new SetCategoryItemEntity($this);
            $s->init();
        }
    }

    public function setUtterance()
    {
        // build utterance arrays
        // texts, intents, entities
        $userUtterancesArr = $this->getConfig('witUtterances');
        $userUtterancesTexts = static::getProp($userUtterancesArr, ['text'], []);
        $userUtterancesIntents = static::getProp($userUtterancesArr, ['intent'], []);
        $userUtterancesEntities = static::getProp($userUtterancesArr, ['entity'], []);

        // get current utterances
        $u = new GetAllUtterances($this);
        $utterances = $u->init();
        $newUtterances = array();

        foreach ($userUtterancesTexts as $utk => $utteranceText) {
            // build utterance props
            $utteranceIntent = static::getProp($userUtterancesIntents, [$utk], '');
            $utteranceEntity = static::getProp($userUtterancesEntities, [$utk], '');
            $utteranceObj = ['text' => $utteranceText, 'intent' => $utteranceIntent, 'entity' => $utteranceEntity];

            if (!is_array($utterances) || !in_array($utteranceText, array_column($utterances, 'text'))) {
                // user utterance not exists in current
                $newUtterances[] = $utteranceObj;
            }

            // set utterance obj in attribute
            $this->utteranceAttr[] = $utteranceObj;
        }

        if (count($newUtterances)) {
            // found new utterance
            // proccess upload new utterances
            $s = new SetUtterance($this);
            $s->setUtterances($newUtterances);
            $s->init();
        }
    }

    public function getUtteranceAttribute(): string
    {
        return json_encode($this->utteranceAttr);
    }
}
