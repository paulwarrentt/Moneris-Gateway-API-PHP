<?php

namespace PaulWarrenTT\Moneris;

use InvalidArgumentException;

class SurchargeInfo
{
    private $params = [];
    private $surchargeTemplate = ['surcharge_amount'];

    public function __construct()
    {
    }

    public function setSurchargeAmount($value)
    {
        if ( ! is_numeric($value) || $value < 0) {
            throw new InvalidArgumentException("Surcharge amount must be a non-negative numeric value.");
        }
        $this->params['surcharge_amount'] = $value;
    }

    public function toXML(): string
    {
        $xmlString = "<surcharge_info>";
        foreach ($this->surchargeTemplate as $tag) {
            if (isset($this->params[$tag])) {
                $xmlString .= "<$tag>".htmlspecialchars($this->params[$tag], ENT_XML1, 'UTF-8')."</$tag>";
            }
        }
        $xmlString .= "</surcharge_info>";

        return $xmlString;
    }
}
