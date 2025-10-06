<?php

namespace PaulWarrenTT\Moneris;

class MpiCavvLookup extends Transaction
{

    private $template = [
        "cres" => null,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->is3Dsecure2Transaction = true;
        $this->rootTag = "cavv_lookup";
        $this->data = $this->template;
    }

    public function setCRes($cres): void
    {
        $this->data["cres"] = $cres;
    }
}
