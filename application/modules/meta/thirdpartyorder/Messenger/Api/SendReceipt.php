<?php

namespace Meta\ThirdPartyOrder\Messenger\Api;

use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\Messenger\Api\Traits\TypingAction;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class SendReceipt extends SendMessage
{
    use TypingAction;

    /**
     * @var object
     */
    private $cusInfo;

    /**
     * @var object
     */
    private $orderInfo;

    /**
     * @var object
     */
    private $billInfo;

    /**
     * @param object $cusInfo
     * @return SendReceipt
     */
    public function setCustomerInfo(object $cusInfo): SendReceipt
    {
        $this->cusInfo = $cusInfo;
        return $this;
    }

    /**
     * @param object $billInfo
     * @return SendReceipt
     */
    public function setBillInfo(object $billInfo): SendReceipt
    {
        $this->billInfo = $billInfo;
        return $this;
    }

    /**
     * @param object $orderInfo
     * @return SendReceipt
     */
    public function setOrderInfo(object $orderInfo): SendReceipt
    {
        $this->orderInfo = $orderInfo;
        return $this;
    }

    /**
     * @return string
     */
    private function getPaymethod(): string
    {
        return $this->CI->db
            ->select('payment_method')
            ->from('payment_method')
            ->where('payment_method_id', $this->billInfo->payment_method_id)
            ->get()
            ->row()
            ->payment_method ?? 'Cash Payment';
    }

    /**
     * @return array
     */
    private function getSummery(): array
    {
        return [
            'subtotal' => (float) ($this->billInfo->total_amount - $this->billInfo->discount),
            'shipping_cost' => (float) $this->billInfo->service_charge,
            'total_tax' => (float) $this->billInfo->VAT,
            'total_cost' => (float) $this->billInfo->bill_amount,
        ];
    }

    /**
     * @return array
     */
    private function getElements(): array
    {
        $odrPrs = $this->CI->db
            ->select(
                'f.ProductName AS pr_name,
                v.variantName AS pr_variant_name,
                m.menuqty AS pr_qty,
                m.price AS pr_price,
                f.medium_thumb AS pr_image'
            )
            ->from('order_menu m')
            ->join('item_foods f', 'm.menu_id = f.ProductsID')
            ->join('variant v', 'm.varientid = v.variantid')
            ->where('m.order_id', $this->orderInfo->order_id)
            ->get()
            ->result();

        return array_map(function ($v) {
            return array(
                'title' => $v->pr_name,
                'subtitle' => $v->pr_variant_name,
                'quantity' => (int) $v->pr_qty,
                'price' => (float) $v->pr_price,
                'currency' => $this->getAppConfig('currencyname'),
                'image_url' => base_url($v->pr_image ?: 'assets/img/no-image.png'),
            );
        }, $odrPrs);
    }

    /**
     * Initialize
     *
     * @throws ThirdPartyRequestException
     * @return void
     */
    public function init()
    {
        $this->initThankYouText();
        $this->initInvoice();
        $this->initCustomerOrderHistory();
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function initThankYouText()
    {
        // build thank you text
        $thankYouText = preg_replace('/{{ORDER_NUMBER}}/', $this->orderInfo->saleinvoice, $this->thirdPartyConfig->getConfig('orderConfirmedText'));

        // set message body and send text
        $this->setText($thankYouText);
        return parent::init();
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function initInvoice()
    {
        // build invoice body
        $this->requestBody['message']['attachment'] = [
            'type' => 'template',
            'payload' => [
                'template_type' => 'receipt',
                'recipient_name' => $this->cusInfo->customer_name,
                'order_number' => $this->orderInfo->order_id,
                'currency' => $this->getAppConfig('currencyname'),
                'payment_method' => $this->getPaymethod(),
                'elements' => $this->getElements(),
                'summary' => $this->getSummery(),
            ]
        ];

        return parent::init();
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function initCustomerOrderHistory()
    {
        // build button body
        $this->requestBody['message']['attachment'] = [
            "type" => "template",
            "payload" => [
                "template_type" => "button",
                "text" => 'You can see your order history here.',
                "buttons" => [
                    [
                        "type" => "web_url",
                        "url" => base_url('myorderlist'),
                        "title" => "My Orders",
                        "messenger_extensions" => TRUE,
                        "webview_height_ratio" => "FULL"
                    ]
                ]
            ]
        ];

        return parent::init();
    }
}
