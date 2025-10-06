<?php

namespace PaulWarrenTT\Moneris\Installments;

class InstallmentInfo
{
    private $template = [
        'plan_id' => null,
        'plan_id_ref' => null,
        'tac_version' => null,
    ];

    private $data;

    public function __construct()
    {
        $this->data = $this->template;
    }

    public function setPlanId($value)
    {
        $this->data['plan_id'] = $value;
    }

    public function setPlanIdRef($value)
    {
        $this->data['plan_id_ref'] = $value;
    }

    public function setTacVersion($value)
    {
        $this->data['tac_version'] = $value;
    }

    public function toXML()
    {
        $xmlString = "";

        foreach ($this->template as $key => $value) {
            if ($this->data[$key] != null || $this->data[$key] != "") {
                $xmlString .= "<$key>".$this->data[$key]."</$key>";
            }
        }

        return "<installment_info>$xmlString</installment_info>";
    }

}//end class

