<?php

namespace PaulWarrenTT\Moneris\GooglePay;

use PaulWarrenTT\Moneris\Transaction;

class GooglePayTokenPreauth extends Transaction
{
    private $template = [
        "order_id" => null,
        "amount" => null,
        "crypt_type" => null,
        "cust_id" => null,
        "network" => null,
        "dynamic_descriptor" => null,
        "data_key" => null,
        "threeds_server_trans_id" => null,
        "ds_trans_id" => null,
        "threeds_version" => null,
        "cavv" => null,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->rootTag = "googlepay_token_preauth";
        $this->data = $this->template;
    }

    public function setAmount($amount)
    {
        $this->data["amount"] = $amount;
    }

    public function setCavv($cavv)
    {
        $this->data["cavv"] = $cavv;
    }

    public function setCryptType($crypt_type)
    {
        $this->data["crypt_type"] = $crypt_type;
    }

    public function setCustId($cust_id)
    {
        $this->data["cust_id"] = $cust_id;
    }

    public function setDSTransId($dsTransId)
    {
        $this->data["ds_trans_id"] = $dsTransId;
    }

    public function setDataKey($dataKey)
    {
        $this->data["data_key"] = $dataKey;
    }

    public function setDynamicDescriptor($dynamicDescriptor)
    {
        $this->data["dynamic_descriptor"] = $dynamicDescriptor;
    }

    public function setNetwork($network)
    {
        $this->data["network"] = $network;
    }

    public function setOrderId($order_id)
    {
        $this->data["order_id"] = $order_id;
    }

    public function setThreeDSServerTransId($threedsServerTransId)
    {
        $this->data["threeds_server_trans_id"] = $threedsServerTransId;
    }

    public function setThreeDSVersion($threedsVersion)
    {
        $this->data["threeds_version"] = $threedsVersion;
    }

}
