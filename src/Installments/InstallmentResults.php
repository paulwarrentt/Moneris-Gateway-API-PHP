<?php

namespace PaulWarrenTT\Moneris\Installments;

class InstallmentResults
{
    // Properties
    public $planId, $planIdRef, $tacVersion, $planAcceptanceId, $planStatus, $PlanResponse;

    // Methods

    function getPlanAcceptanceId()
    {
        return $this->planAcceptanceId;
    }

    function getPlanIDRef()
    {
        return $this->planIdRef;
    }

    function getPlanId()
    {
        return $this->planId;
    }

    function setPlanId($planId)
    {
        $this->planId = $planId;
    }

    function getPlanResponse()
    {
        return $this->PlanResponse;
    }

    function getPlanStatus()
    {
        return $this->planStatus;
    }

    function getTacVersion()
    {
        return $this->tacVersion;
    }

    function setPlanAcceptanceId($planAcceptanceId)
    {
        $this->planAcceptanceId = $planAcceptanceId;
    }

    function setPlanIdRef($planIdRef)
    {
        $this->planIdRef = $planIdRef;
    }

    function setPlanResponse($PlanResponse)
    {
        $this->PlanResponse = $PlanResponse;
    }

    function setPlanStatus($planStatus)
    {
        $this->planStatus = $planStatus;
    }

    function setTacVersion($tacVersion)
    {
        $this->tacVersion = $tacVersion;
    }
}
