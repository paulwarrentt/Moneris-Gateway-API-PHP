<?php

namespace PaulWarrenTT\Moneris\Amex;

class AxRef
{
    private $template = [
        'ref01' => null, 'ref02' => null,
    ];

    private $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function getData()
    {
        return $this->data;
    }

    public function setRef($ref01, $ref02)
    {
        $this->template['ref01'] = $ref01;
        $this->template['ref02'] = $ref02;

        $this->data[] = $this->template;
    }
}

