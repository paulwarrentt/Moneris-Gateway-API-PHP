<?php

namespace PaulWarrenTT\Moneris\Visa;

use PaulWarrenTT\Moneris\Traits\ToXML;

class MpgVsLevel23
{
    use ToXML;
    private $template = [
        'corpai' => null,
        'corpas' => null,
        'vspurcha' => null,
        'vspurchl' => null,
    ];

    private $data;

    public function __construct()
    {
        $this->data = $this->template;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setVsCorpa(VsCorpai $vsCorpai, VsCorpas $vsCorpas)
    {
        $this->data['vspurcha'] = null;
        $this->data['vspurchal'] = null;

        $this->data['corpai'] = $vsCorpai->getData();
        $this->data['corpas'] = $vsCorpas->getData();
    }

    public function setVsPurch(VsPurcha $vsPurcha, VsPurchl $vsPurchl)
    {
        $this->data['corpai'] = null;
        $this->data['corpas'] = null;

        $this->data['vspurcha'] = $vsPurcha->getData();
        $this->data['vspurchl'] = $vsPurchl->getData();
    }

    public function toXML(): string
    {
        return $this->toXML_low($this->data, "0");
    }
}//end class

