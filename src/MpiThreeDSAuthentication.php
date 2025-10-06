<?php

namespace PaulWarrenTT\Moneris;


class MpiThreeDSAuthentication extends Transaction
{

    private $template = [
        "order_id" => null,
        "data_key" => null,
        "cardholder_name" => null,
        "pan" => null,
        "expdate" => null,
        "amount" => null,
        "currency" => null,
        "threeds_completion_ind" => null,
        "request_type" => null,
        "notification_url" => null,
        "purchase_date" => null,
        "challenge_windowsize" => null,
        "bill_address1" => null,
        "bill_province" => null,
        "bill_city" => null,
        "bill_postal_code" => null,
        "bill_country" => null,
        "ship_address1" => null,
        "ship_province" => null,
        "ship_city" => null,
        "ship_postal_code" => null,
        "ship_country" => null,
        "browser_useragent" => null,
        "browser_java_enabled" => null,
        "browser_screen_height" => null,
        "browser_screen_width" => null,
        "browser_language" => null,
        "browser_ip" => null,
        "email" => null,
        "request_challenge" => null,
        "message_category" => null,
        "device_channel" => null,
        "decoupled_request_indicator" => null,
        "decoupled_request_max_time" => null,
        "decoupled_request_async_url" => null,
        "ri_indicator" => null,
        "prior_authentication_info" => null,
        "recurring_expiry" => null,
        "recurring_frequency" => null,
        "work_phone" => null,
        "mobile_phone" => null,
        "home_phone" => null,

    ];

    public function __construct()
    {
        parent::__construct();
        $this->is3Dsecure2Transaction = true;
        $this->rootTag = "threeds_authentication";
        $this->data = $this->template;
    }

    public function setAmount($amount): void
    {
        $this->data["amount"] = $amount;
    }

    public function setBillAddress1($bill_address1): void
    {
        $this->data["bill_address1"] = $bill_address1;
    }

    public function setBillCity($bill_city): void
    {
        $this->data["bill_city"] = $bill_city;
    }

    public function setBillCountry($bill_country): void
    {
        $this->data["bill_country"] = $bill_country;
    }

    public function setBillPostalCode($bill_postal_code): void
    {
        $this->data["bill_postal_code"] = $bill_postal_code;
    }

    public function setBillProvince($bill_province): void
    {
        $this->data["bill_province"] = $bill_province;
    }

    public function setBrowserIP($browser_ip): void
    {
        $this->data["browser_ip"] = $browser_ip;
    }

    public function setBrowserJavaEnabled($browser_java_enabled): void
    {
        $this->data["browser_java_enabled"] = $browser_java_enabled;
    }

    public function setBrowserLanguage($browser_language): void
    {
        $this->data["browser_language"] = $browser_language;
    }

    public function setBrowserScreenHeight($browser_screen_height): void
    {
        $this->data["browser_screen_height"] = $browser_screen_height;
    }

    public function setBrowserScreenWidth($browser_screen_width): void
    {
        $this->data["browser_screen_width"] = $browser_screen_width;
    }

    public function setBrowserUserAgent($browser_useragent): void
    {
        $this->data["browser_useragent"] = $browser_useragent;
    }

    public function setCardholderName($cardholder_name): void
    {
        $this->data["cardholder_name"] = $cardholder_name;
    }

    public function setChallengeWindowSize($challenge_windowsize): void
    {
        $this->data["challenge_windowsize"] = $challenge_windowsize;
    }

    public function setCurrency($currency): void
    {
        $this->data["currency"] = $currency;
    }

    public function setDataKey($data_key): void
    {
        $this->data["data_key"] = $data_key;
    }

    public function setDecoupledRequestAsyncUrl($decoupled_request_async_url): void
    {
        $this->data["decoupled_request_async_url"] = $decoupled_request_async_url;
    }

    public function setDecoupledRequestIndicator($decoupled_request_indicator): void
    {
        $this->data["decoupled_request_indicator"] = $decoupled_request_indicator;
    }

    public function setDecoupledRequestMaxTime($decoupled_request_max_time): void
    {
        $this->data["decoupled_request_max_time"] = $decoupled_request_max_time;
    }

    public function setDeviceChannel($device_channel): void
    {
        $this->data["device_channel"] = $device_channel;
    }

    public function setEmail($email): void
    {
        $this->data["email"] = $email;
    }

    public function setExpdate($expdate): void
    {
        $this->data["expdate"] = $expdate;
    }

    public function setHomePhone($homePhone): void
    {
        $this->data["home_phone"] = $homePhone;
    }

    public function setMessageCategory($message_category): void
    {
        $this->data["message_category"] = $message_category;
    }

    public function setMobilePhone($mobilePhone): void
    {
        $this->data["mobile_phone"] = $mobilePhone;
    }

    public function setNotificationURL($notification_url): void
    {
        $this->data["notification_url"] = $notification_url;
    }

    public function setOrderId($order_id): void
    {
        $this->data["order_id"] = $order_id;
    }

    public function setPan($pan): void
    {
        $this->data["pan"] = $pan;
    }

    public function setPriorAuthenticationInfo($priorAuthenticationInfo): void
    {
        $this->data["prior_authentication_info"] = $priorAuthenticationInfo;
    }

    public function setPurchaseDate($purchase_date): void
    {
        $this->data["purchase_date"] = $purchase_date;
    }

    public function setRecurringExpiry($recurringExpiry): void
    {
        $this->data["recurring_expiry"] = $recurringExpiry;
    }

    public function setRecurringFrequency($recurringFrequency): void
    {
        $this->data["recurring_frequency"] = $recurringFrequency;
    }

    public function setRequestChallenge($request_challenge): void
    {
        $this->data["request_challenge"] = $request_challenge;
    }

    public function setRequestType($request_type): void
    {
        $this->data["request_type"] = $request_type;
    }

    public function setRiIndicator($ri_indicator): void
    {
        $this->data["ri_indicator"] = $ri_indicator;
    }

    public function setShipAddress1($ship_address1): void
    {
        $this->data["ship_address1"] = $ship_address1;
    }

    public function setShipCity($ship_city): void
    {
        $this->data["ship_city"] = $ship_city;
    }

    public function setShipCountry($ship_country): void
    {
        $this->data["ship_country"] = $ship_country;
    }

    public function setShipPostalCode($ship_postal_code): void
    {
        $this->data["ship_postal_code"] = $ship_postal_code;
    }

    public function setShipProvince($ship_province): void
    {
        $this->data["ship_province"] = $ship_province;
    }

    public function setThreeDSCompletionInd($threeds_completion_ind): void
    {
        $this->data["threeds_completion_ind"] = $threeds_completion_ind;
    }

    public function setWorkPhone($workPhone): void
    {
        $this->data["work_phone"] = $workPhone;
    }
}

