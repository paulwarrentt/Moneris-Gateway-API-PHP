<?php

namespace PaulWarrenTT\Moneris\Installments;

class LastInstallment
{
    // Properties
    public $installmentFee, $amount, $totalAmount;

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

    function getTotalAmount()
    {
        return $this->totalAmount;
    }

    function setAmount($amount)
    {
        $this->amount = $amount;
    }

    function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
    }
}
