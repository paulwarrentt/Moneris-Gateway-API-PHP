<?php

namespace PaulWarrenTT\Moneris\GooglePay;

use PaulWarrenTT\Moneris\Transaction;

class GooglePayTokenTempAdd extends Transaction
{
    private $template = [
        "payment_token" => null,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->rootTag = "googlepay_token_temp_add";
        $this->data = $this->template;
    }

    public function setPaymentToken($signature, $protocol_version, $signed_message): void
    {
        $this->data["payment_token"] = [
            "signature" => $signature,
            "protocol_version" => $protocol_version,
            "signed_message" => $signed_message,
        ];
    }
}
