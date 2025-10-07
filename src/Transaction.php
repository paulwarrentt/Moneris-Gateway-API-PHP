<?php

namespace PaulWarrenTT\Moneris;

use PaulWarrenTT\Moneris\Traits\ToXML;

class Transaction
{
    use ToXML;
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
}
