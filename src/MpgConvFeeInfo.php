<?php

namespace PaulWarrenTT\Moneris;

class MpgConvFeeInfo
{
    var $params;
    var array $convFeeTemplate = ['convenience_fee'];

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function toXML(): string
    {
        $xmlString = "";

        foreach ($this->convFeeTemplate as $tag) {
            $xmlString .= "<$tag>".$this->params[$tag]."</$tag>";
        }

        return "<convfee_info>$xmlString</convfee_info>";
    }

}//end class
