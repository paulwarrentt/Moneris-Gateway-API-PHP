<?php

namespace PaulWarrenTT\Moneris\MasterCard;

class MpgMcLevel23
{

    private $template = [
        'mccorpac' => null,
        'mccorpai' => null,
        'mccorpas' => null,
        'mccorpal' => null,
        'mccorpar' => null,
    ];

    private array $data;

    public function __construct()
    {
        $this->data = $this->template;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setMcCorpac(McCorpac $mcCorpac)
    {
        $this->data['mccorpac'] = $mcCorpac->getData();
    }

    public function setMcCorpai(McCorpai $mcCorpai)
    {
        $this->data['mccorpai'] = $mcCorpai->getData();
    }

    public function setMcCorpal(McCorpal $mcCorpal)
    {
        $this->data['mccorpal'] = $mcCorpal->getData();
    }

    public function setMcCorpar(McCorpar $mcCorpar)
    {
        $this->data['mccorpar'] = $mcCorpar->getData();
    }

    public function setMcCorpas(McCorpas $mcCorpas)
    {
        $this->data['mccorpas'] = $mcCorpas->getData();
    }

    public function toXML(): string
    {
        $xmlString = $this->toXML_low($this->data, "0");

        return $xmlString;
    }

    private function toXML_low($dataArray, $root): string
    {
        $xmlRoot = "";

        foreach ($dataArray as $key => $value) {
            if ( ! is_numeric($key) && $value != "" && $value != null) {
                $xmlRoot .= "<$key>";
            } elseif (is_numeric($key) && $key != "0") {
                $xmlRoot .= "</$root><$root>";
            }

            if (is_array($value)) {
                $xmlRoot .= $this->toXML_low($value, $key);
            } else {
                $xmlRoot .= $value;
            }

            if ( ! is_numeric($key) && $value != "" && $value != null) {
                $xmlRoot .= "</$key>";
            }
        }

        return $xmlRoot;
    }
}//end class


