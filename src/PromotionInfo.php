<?php

namespace PaulWarrenTT\Moneris;

class PromotionInfo
{
    // Properties
    public $promotionCode, $promotionId;

    // Methods
    function getPromotionCode() {
        return  $this->promotionCode;
    }

    function setPromotionCode($promotionCode) {
        $this->promotionCode = $promotionCode;
    }

    function getPromotionId() {
        return  $this->promotionId;
    }

    function setPromotionId($promotionId) {
        $this->promotionId = $promotionId;
    }
}
