<?php

namespace PaulWarrenTT\Moneris;


class CofInfo
{
    private array $template = [
        'payment_indicator' => null,
        'payment_information' => null,
        'issuer_id' => null,
    ];

    private array $data;

    public function __construct()
    {
        $this->data = $this->template;
    }

    public function setIssuerId($issuer_id): void
    {
        $this->data['issuer_id'] = $issuer_id;
    }

    public function setPaymentIndicator($payment_indicator): void
    {
        $this->data['payment_indicator'] = $payment_indicator;
    }

    public function setPaymentInformation($payment_information): void
    {
        $this->data['payment_information'] = $payment_information;
    }

    public function toXML(): string
    {
        $xmlString = "";

        foreach ($this->template as $key => $value) {
            if ($this->data[$key] != null || $this->data[$key] != "") {
                $xmlString .= "<$key>".$this->data[$key]."</$key>";
            }
        }

        return "<cof_info>$xmlString</cof_info>";
    }
}
