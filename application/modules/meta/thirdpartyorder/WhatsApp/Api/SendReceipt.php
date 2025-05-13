<?php

namespace Meta\ThirdPartyOrder\WhatsApp\Api;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class SendReceipt extends SendMessage
{
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
     * Initialize
     *
     * @throws ThirdPartyRequestException
     * @return void
     */
    public function init()
    {
        $this->initThankYouText();
        $this->initInvoice();
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function initThankYouText()
    {
        // init send text message
        $s = new SendTextMessage;
        $s->setRecipient($this->to);

        // build thank you text
        $thankYouText = preg_replace('/{{ORDER_NUMBER}}/', $this->orderInfo->saleinvoice, $this->thirdPartyConfig->getConfig('orderConfirmedText'));

        // set message body and send text
        $s->setText($thankYouText);
        return $s->init();
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function initInvoice()
    {
        // init send text message
        $s = new SendTextMessage;
        $s->setRecipient($this->to);

        // build invoice template
        $invoice = $this->getInvoiceTemplate();

        // set message body and send text
        $s->setText($invoice);
        return $s->init();
    }

    /**
     * @return string
     */
    private function getInvoiceTemplate(): string
    {
        $t = "*ORDER SUMMERY*\n";
        $t .= "--------------------------------------\n";

        // order id and time
        $orderId = $this->orderInfo->saleinvoice;
        $orderedAt = $this->orderInfo->order_date . ' ' . $this->orderInfo->order_time;
        $orderedAtFormatted = date('M j, Y h:iA', strtotime($orderedAt));

        $t .= "*Order ID: {$orderId}*\n";
        $t .= "Ordered At: {$orderedAtFormatted}\n";

        // customer name and address
        $customerName = $this->cusInfo->customer_name;
        $customerAddress = $this->cusInfo->customer_address;

        $t .= "Customer Name: {$customerName}\nCustomer Address: {$customerAddress}\n";
        $t .= "--------------------------------------\n";

        // ordered items
        foreach ($this->getInvoiceItems() as $item) {
            $t .= sprintf(
                "🖋️ %d X %s (%s) - %s\n",
                $item->pr_qty,
                $item->pr_name,
                $item->pr_variant_name,
                $this->getCurr($item->pr_price * $item->pr_qty)
            );
        }
        $t .= "--------------------------------------\n";

        // order summery
        $totalAmount = $this->getCurr($this->billInfo->total_amount);
        $totalDiscount = $this->getCurr($this->billInfo->discount);
        $totalServiceCharge = $this->getCurr($this->billInfo->service_charge);
        $totalVat = $this->getCurr($this->billInfo->VAT);
        $subTotal = $this->getCurr(
            $this->billInfo->total_amount +
                $this->billInfo->service_charge +
                $this->billInfo->VAT -
                $this->billInfo->discount
        );

        $t .= "Total Amount\t\t: {$totalAmount}\n";
        $t .= "Discount\t\t\t: {$totalDiscount}\n";
        $totalServiceCharge && $t .= "Service Charge\t: {$totalServiceCharge}\n";
        $totalVat && $t .= "VAT\t\t\t\t: {$totalVat}\n";
        $t .= "*Subtotal\t\t\t: {$subTotal}*";

        // return template
        return $t;
    }

    /**
     * @return array
     */
    private function getInvoiceItems(): array
    {
        return $this->CI->db
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
    }
}
