<?php

namespace PaulWarrenTT\Moneris\Amex;

class AxIt106s
{
    private $template = [
        'it10618' => null, 'it10719' => null,
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

    public function setIt10618($it10618)
    {
        $this->data['it10618'] = $it10618;
    }

    public function setIt10719($it10719)
    {
        $this->data['it10719'] = $it10719;
    }
}

