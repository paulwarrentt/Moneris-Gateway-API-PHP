<?php

namespace PaulWarrenTT\Moneris;

class MpgAccountNameInfo
{
    var $params;
    var $accountNameTemplate = ['first_name', 'middle_name', 'last_name'];

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function toXML(): string
    {
        $xmlString = "";

        foreach ($this->accountNameTemplate as $tag) {
            $xmlString .= "<$tag>".$this->params[$tag]."</$tag>";
        }

        return "<account_name_verification>$xmlString</account_name_verification>";
    }
}
