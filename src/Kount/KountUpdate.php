<?php

namespace PaulWarrenTT\Moneris\Kount;

use PaulWarrenTT\Moneris\Transaction;


class KountUpdate extends Transaction
{
    private $template = [
        "kount_merchant_id" => null,
        "kount_api_key" => null,
        "order_id" => null,
        "session_id" => null,
        "kount_transaction_id" => null,
        "evaluate" => null,
        "refund_status" => null,
        "payment_response" => null,
        "avs_response" => null,
        "cvd_response" => null,
        "last4" => null,
        "financial_order_id" => null,
        "payment_token" => null,
        "payment_type" => null,
        "data_key" => null,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->rootTag = "kount_update";
        $this->data = $this->template;
    }

    public function setAvsResponse($avs_response)
    {
        $this->data["avs_response"] = $avs_response;
    }

    public function setCvdResponse($cvd_response)
    {
        $this->data["cvd_response"] = $cvd_response;
    }

    public function setDataKey($data_key)
    {
        $this->data["data_key"] = $data_key;
    }

    public function setEvaluate($evaluate)
    {
        $this->data["evaluate"] = $evaluate;
    }

    public function setFinancialOrderId($financial_order_id)
    {
        $this->data["financial_order_id"] = $financial_order_id;
    }

    public function setKountApiKey($kount_api_key)
    {
        $this->data["kount_api_key"] = $kount_api_key;
    }

    public function setKountMerchantId($kount_merchant_id)
    {
        $this->data["kount_merchant_id"] = $kount_merchant_id;
    }

    public function setKountTransactionId($kount_transaction_id)
    {
        $this->data["kount_transaction_id"] = $kount_transaction_id;
    }

    public function setLast4($last4)
    {
        $this->data["last4"] = $last4;
    }

    public function setOrderId($order_id)
    {
        $this->data["order_id"] = $order_id;
    }

    public function setPaymentResponse($payment_response)
    {
        $this->data["payment_response"] = $payment_response;
    }

    public function setPaymentToken($payment_token)
    {
        $this->data["payment_token"] = $payment_token;
    }

    public function setPaymentType($payment_type)
    {
        $this->data["payment_type"] = $payment_type;
    }

    public function setRefundStatus($refund_status)
    {
        $this->data["refund_status"] = $refund_status;
    }

    public function setSessionId($session_id)
    {
        $this->data["session_id"] = $session_id;
    }
}
