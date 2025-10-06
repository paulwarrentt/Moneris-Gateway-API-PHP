<?php

namespace PaulWarrenTT\Moneris\Amex;

class AxTxi
{
    private $template = [
        'txi01' => null, 'txi02' => null, 'txi03' => null, 'txi06' => null,
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

    public function setTxi($txi01, $txi02, $txi03, $txi06)
    {
        $this->template['txi01'] = $txi01;
        $this->template['txi02'] = $txi02;
        $this->template['txi03'] = $txi03;
        $this->template['txi06'] = $txi06;

        $this->data[] = $this->template;
    }

}

