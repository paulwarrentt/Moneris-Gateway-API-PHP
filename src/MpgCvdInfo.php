<?php

namespace PaulWarrenTT\Moneris;

class MpgCvdInfo
{
    var $params;
    var $cvdTemplate = ['cvd_indicator', 'cvd_value'];

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function toXML(): string
    {
        $xmlString = "";

        foreach ($this->cvdTemplate as $tag) {
            $xmlString .= "<$tag>".$this->params[$tag]."</$tag>";
        }

        return "<cvd_info>$xmlString</cvd_info>";
    }

}
