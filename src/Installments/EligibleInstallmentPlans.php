<?php

namespace PaulWarrenTT\Moneris\Installments;

class EligibleInstallmentPlans
{

    // Properties
    public $installmentPlans;

    // Methods

    function getInstallmentPlans()
    {
        return $this->installmentPlans;
    }

    function setInstallmentPlans($installmentPlans)
    {
        $this->installmentPlans = $installmentPlans;
    }

    function getPlanCount()
    {
        return count($this->installmentPlans);
    }
}
