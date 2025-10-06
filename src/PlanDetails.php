<?php

namespace PaulWarrenTT\Moneris;

class PlanDetails
{

    // Properties
    private $planId, $planIdRef, $name, $type, $numInstallments, $installmentFrequency, $apr, $totalFees, $totalPlanCost;
    private $tac, $promotionInfo, $firstInstallment, $lastInstallment;

    // Methods

    function getAPR()
    {
        return $this->apr;
    }

    function getFirstInstallment()
    {
        return $this->firstInstallment;
    }

    function getInstallmentFrequency()
    {
        return $this->installmentFrequency;
    }

    function getLastInstallment()
    {
        return $this->lastInstallment;
    }

    function getName()
    {
        return $this->name;
    }

    function getNumInstallments()
    {
        return $this->numInstallments;
    }

    function getPlanId()
    {
        return $this->planId;
    }

    function setPlanId($planId)
    {
        $this->planId = $planId;
    }

    function getPlanIdRef()
    {
        return $this->planIdRef;
    }

    function getPromotionInfo()
    {
        return $this->promotionInfo;
    }

    function getTac()
    {
        return $this->tac;
    }

    function setTac($tac)
    {
        $this->tac = $tac;
    }

    function getTotalFees()
    {
        return $this->totalFees;
    }

    function getTotalPlanCost()
    {
        return $this->totalPlanCost;
    }

    function getType()
    {
        return $this->type;
    }

    function setAPR($apr)
    {
        $this->apr = $apr;
    }

    function setFirstInstallment($firstInstallment)
    {
        $this->firstInstallment = $firstInstallment;
    }

    function setInstallmentFrequency($installmentFrequency)
    {
        $this->installmentFrequency = $installmentFrequency;
    }

    function setLastInstallment($lastInstallment)
    {
        $this->lastInstallment = $lastInstallment;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function setNumInstallments($numInstallments)
    {
        $this->numInstallments = $numInstallments;
    }

    function setPlanIdRef($planIdRef)
    {
        $this->planIdRef = $planIdRef;
    }

    function setPromotionInfo($promotionInfo)
    {
        $this->promotionInfo = $promotionInfo;
    }

    function setTotalFees($totalFees)
    {
        $this->totalFees = $totalFees;
    }

    function setTotalPlanCost($totalPlanCost)
    {
        $this->totalPlanCost = $totalPlanCost;
    }

    function setType($type)
    {
        $this->type = $type;
    }
}
