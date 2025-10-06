<?php

namespace PaulWarrenTT\Moneris\Installments;

class LastInstallment
{
    // Properties
    public $installmentFee, $amount;

    // Methods

    function getAmount()
    {
        return $this->amount;
    }

    function getInstallmentFee()
    {
        return $this->installmentFee;
    }

    function setInstallmentFee($installmentFee)
    {
        $this->installmentFee = $installmentFee;
    }

    function setAmount($amount)
    {
        $this->amount = $amount;
    }
}
