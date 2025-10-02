<?php

namespace PaulWarrenTT\Moneris;

class MpgTransaction
{
    var $custInfo = null;
    var $recur = null;
    var $cvd = null;
    var $convFee = null;
    var $ach = null;
    var $sessionAccountInfo = null;
    var $attributeAccountInfo = null;
    var $level23Data = null;
    var $mcpRateInfo = null;
    var $installmentInfo = null;
    var $anv = null;
    private array $txn;
    private CofInfo|null $cof = null;
    private MpgAvsInfo|null $avs = null;

    public function __construct(array $txn)
    {
        $this->txn = $txn;
    }

    public function getAccountNameVerification()
    {
        return $this->anv;
    }

    public function getAchInfo()
    {
        return $this->ach;
    }

    public function getAttributeAccountInfo()
    {
        return $this->attributeAccountInfo;
    }

    public function setAttributeAccountInfo($attributeAccountInfo): void
    {
        $this->attributeAccountInfo = $attributeAccountInfo;
    }

    public function getAvsInfo(): ?MpgAvsInfo
    {
        return $this->avs;
    }

    public function getCofInfo(): ?CofInfo
    {
        return $this->cof;
    }

    public function getConvFeeInfo()
    {
        return $this->convFee;
    }

    public function getCustInfo()
    {
        return $this->custInfo;
    }

    public function setCustInfo($custInfo)
    {
        $this->custInfo = $custInfo;
        $this->txn[] = $custInfo;
    }

    public function getCvdInfo()
    {
        return $this->cvd;
    }

    public function getExpiryDate()
    {
        return $this->expdate;
    }

    public function getInstallmentInfo()
    {
        return $this->installmentInfo;
    }

    public function setInstallmentInfo($installmentInfo)
    {
        $this->installmentInfo = $installmentInfo;
    }

    public function getLevel23Data()
    {
        return $this->level23Data;
    }

    public function setLevel23Data($level23Object)
    {
        $this->level23Data = $level23Object;
    }

    public function getMCPRateInfo()
    {
        return $this->mcpRateInfo;
    }

    public function setMCPRateInfo($mcpRate)
    {
        $this->mcpRateInfo = $mcpRate;
    }

    public function getRecur()
    {
        return $this->recur;
    }

    public function setRecur($recur)
    {
        $this->recur = $recur;
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

    public function setAccountNameVerification($anv)
    {
        $this->anv = $anv;
    }

    public function setAchInfo($ach)
    {
        $this->ach = $ach;
    }

    public function setAvsInfo(MpgAvsInfo $avs): void
    {
        $this->avs = $avs;
    }

    public function setCofInfo(CofInfo $cof): void
    {
        $this->cof = $cof;
    }

    public function setConvFeeInfo($convFee)
    {
        $this->convFee = $convFee;
    }

    public function setCvdInfo($cvd)
    {
        $this->cvd = $cvd;
    }

    public function setExpiryDate($expdate)
    {
        $this->expdate = $expdate;
    }

}
