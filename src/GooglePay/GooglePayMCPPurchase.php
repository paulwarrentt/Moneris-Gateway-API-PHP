<?php

namespace PaulWarrenTT\Moneris\GooglePay;

use PaulWarrenTT\Moneris\Transaction;

class GooglePayMCPPurchase extends Transaction
{
    private $template = [
        "order_id" => null,
        "amount" => null,
        "cust_id" => null,
        "network" => null,
        "data_key" => null,
        "threeds_server_trans_id" => null,
        "ds_trans_id" => null,
        "threeds_version" => null,
        "cavv" => null,
        "dynamic_descriptor" => null,
        "mcp_version" => null,
        "mcp_rate_token" => null,
        "cardholder_amount" => null,
        "cardholder_currency_code" => null,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->rootTag = "googlepay_mcp_purchase";
        $this->data = $this->template;
    }

    public function setAmount($amount)
    {
        $this->data["amount"] = $amount;
    }

    public function setCardholderAmount($cardholder_amount)
    {
        $this->data["cardholder_amount"] = $cardholder_amount;
    }

    public function setCardholderCurrencyCode($cardholder_currency_code)
    {
        $this->data["cardholder_currency_code"] = $cardholder_currency_code;
    }

    public function setCustId($cust_id)
    {
        $this->data["cust_id"] = $cust_id;
    }

    public function setDynamicDescriptor($dynamicDescriptor)
    {
        $this->data["dynamic_descriptor"] = $dynamicDescriptor;
    }

    public function setMCPRateToken($mcp_rate_token)
    {
        $this->data["mcp_rate_token"] = $mcp_rate_token;
    }

    public function setMCPVersion($mcp_version)
    {
        $this->data["mcp_version"] = $mcp_version;
    }

    public function setNetwork($network)
    {
        $this->data["network"] = $network;
    }

    public function setOrderId($order_id)
    {
        $this->data["order_id"] = $order_id;
    }

    public function setPaymentToken($signature, $protocol_version, $signed_message)
    {
        $this->data["payment_token"] = [
            "signature" => $signature,
            "protocol_version" => $protocol_version,
            "signed_message" => $signed_message,
        ];
    }
}

