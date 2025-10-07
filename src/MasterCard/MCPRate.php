<?php

namespace PaulWarrenTT\Moneris\MasterCard;

use PaulWarrenTT\Moneris\Traits\ToXML;

class MCPRate
{
    use ToXML;
    private $template = [
        "merchant_settlement_amount" => null,
        "cardholder_amount" => null,
        "cardholder_currency_code" => null,
    ];

    private $data;
    private $mcp_rate;

    public function __construct()
    {
        $this->mcp_rate = [];
    }

    public function setCardholderAmount($cardholder_amount, $cardholder_currency_code)
    {
        $this->data = $this->template;
        $this->data['cardholder_amount'] = $cardholder_amount;
        $this->data['cardholder_currency_code'] = $cardholder_currency_code;

        $this->mcp_rate[] = $this->data;
    }

    public function setMerchantSettlementAmount($merchant_settlement_amount, $cardholder_currency_code)
    {
        $this->data = $this->template;
        $this->data['merchant_settlement_amount'] = $merchant_settlement_amount;
        $this->data['cardholder_currency_code'] = $cardholder_currency_code;

        $this->mcp_rate[] = $this->data;
    }

    public function toXML()
    {
        $final_data['rate'] = $this->mcp_rate;

        $xmlString = $this->toXML_low($final_data, "rate");

        return $xmlString;
        //return "<rate>". $xmlString. "</rate>";
    }
}
