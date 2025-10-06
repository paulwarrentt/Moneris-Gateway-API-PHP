<?php

namespace PaulWarrenTT\Moneris;

class RiskTransaction
{
    var $txn;
    var $attributeAccountInfo = null;
    var $sessionAccountInfo = null;

    public function __construct($txn)
    {
        $this->txn = $txn;
    }

    public function getAttributeAccountInfo()
    {
        return $this->attributeAccountInfo;
    }

    public function setAttributeAccountInfo($attributeAccountInfo)
    {
        $this->attributeAccountInfo = $attributeAccountInfo;
    }

    public function getSessionAccountInfo()
    {
        return $this->sessionAccountInfo;
    }

    public function setSessionAccountInfo($sessionAccountInfo)
    {
        $this->sessionAccountInfo = $sessionAccountInfo;
    }

    public function getTransaction()
    {
        return $this->txn;
    }
}//end class RiskTransaction
