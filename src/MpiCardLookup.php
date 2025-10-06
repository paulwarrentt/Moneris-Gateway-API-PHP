<?php

namespace PaulWarrenTT\Moneris;


class MpiCardLookup extends Transaction
{

    private $template = [
        "order_id" => null,
        "data_key" => null,
        "pan" => null,
        "notification_url" => null,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->is3Dsecure2Transaction = true;
        $this->rootTag = "card_lookup";
        $this->data = $this->template;
    }

    public function setDataKey($data_key)
    {
        $this->data["data_key"] = $data_key;
    }

    public function setNotificationUrl($notification_url): void
    {
        $this->data["notification_url"] = $notification_url;
    }

    public function setOrderId($order_id)
    {
        $this->data["order_id"] = $order_id;
    }

    public function setPan($pan)
    {
        $this->data["pan"] = $pan;
    }
}
