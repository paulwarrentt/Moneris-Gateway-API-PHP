<?php

namespace PaulWarrenTT\Moneris;

class Transaction
{
    protected ?array $data;
    protected ?string $rootTag;
    protected bool $is3Dsecure2Transaction = false;

    public function __construct()
    {
    }

    public function getIs3DSecure2Transaction(): bool
    {
        return $this->is3Dsecure2Transaction;
    }

    public function getTransactionType(): ?string
    {
        return $this->rootTag;
    }

    public function toXML(): string
    {
        $xmlString = "<".$this->rootTag.">";
        $xmlString .= $this->toXML_low($this->data, $this->rootTag);
        $xmlString .= "</".$this->rootTag.">";

        return $xmlString;
    }

    private function toXML_low(array $dataArray, string $root): string
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
}
