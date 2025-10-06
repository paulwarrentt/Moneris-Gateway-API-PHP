<?php

namespace PaulWarrenTT\Moneris\Installments;

class FirstInstallment
{
    // Properties
    public $upfrontFee, $installmentFee, $amount;

    // Methods
    function getUpfrontFee() {
        return  $this->upfrontFee;
    }

    function setUpfrontFee($upfrontFee): void
    {
        $this->upfrontFee = $upfrontFee;
    }

    function getInstallmentFee() {
        return  $this->installmentFee;
    }

    function setInstallmentFee($installmentFee) {
        $this->installmentFee = $installmentFee;
    }

    function getAmount() {
        return  $this->amount;
    }

    function setAmount($amount) {
        $this->amount = $amount;
    }
}
