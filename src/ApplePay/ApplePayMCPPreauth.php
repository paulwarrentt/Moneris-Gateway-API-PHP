<?php

namespace PaulWarrenTT\Moneris\ApplePay;

use PaulWarrenTT\Moneris\Transaction;

class ApplePayMCPPreauth extends Transaction
{
    private $template = [
        "order_id" => null,
        "cust_id" => null,
        "amount" => null,
        "displayName" => null,
        "network" => null,
        "version" => null,
        "data" => null,
        "signature" => null,
        "header" => null,
        "type" => null,
        "dynamic_descriptor" => null,
        "token_originator" => null,
        "final_auth" => null,
        "mcp_version" => null,
        "mcp_rate_token" => null,
        "cardholder_amount" => null,
        "cardholder_currency_code" => null,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->rootTag = "applepay_mcp_preauth";
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

    public function setData($data)
    {
        $this->data["data"] = $data;
    }

    public function setDisplayName($display_name)
    {
        $this->data["displayName"] = $display_name;
    }

    public function setDynamicDescriptor($dynamic_descriptor)
    {
        $this->data["dynamic_descriptor"] = $dynamic_descriptor;
    }

    public function setFinalAuth($final_auth)
    {
        $this->data["final_auth"] = $final_auth;
    }

    public function setHeader($public_key_hash, $ephemeral_public_key, $transaction_id)
    {
        $this->data["header"] = [
            "public_key_hash" => $public_key_hash,
            "ephemeral_public_key" => $ephemeral_public_key,
            "transaction_id" => $transaction_id,
        ];
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

    public function setSignature($signature)
    {
        $this->data["signature"] = $signature;
    }

    public function setTokenOriginator($store_id, $api_token)
    {
        $this->data["token_originator"] = [
            "store_id" => $store_id,
            "api_token" => $api_token,
        ];
    }

    public function setType($type)
    {
        $this->data["type"] = $type;
    }

    public function setVersion($version)
    {
        $this->data["version"] = $version;
    }
}
