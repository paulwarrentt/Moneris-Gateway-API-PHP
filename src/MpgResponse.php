<?php

namespace PaulWarrenTT\Moneris;

use PaulWarrenTT\Moneris\Installments\EligibleInstallmentPlans;
use PaulWarrenTT\Moneris\Installments\FirstInstallment;
use PaulWarrenTT\Moneris\Installments\InstallmentResults;
use PaulWarrenTT\Moneris\Installments\LastInstallment;
use XMLParser;

class MpgResponse
{
    private array $responseData = [];

    private XMLParser $XMLParser; //parser

    var $currentTag;
    var $currentTagValue;
    var $purchaseHash = array();
    var $refundHash;
    var $correctionHash = [];
    var $isBatchTotals;
    var $term_id;
    var $receiptHash = [];
    var $ecrHash = [];
    var $CardType;
    var $currentTxnType;
    var $ecrs = [];
    var $cards = [];
    var $cardHash = [];

    //specifically for Resolver transactions
    var $resolveData;
    var $resolveDataHash;
    var $data_key="";
    var $DataKeys = [];
    var $isResolveData;

    //specifically for VdotMe transactions
    var $vDotMeInfo;
    var $isVdotMeInfo;

    //specifically for MasterPass transactions
    var $isPaypass;
    var $isPaypassInfo;
    var $masterPassData = array();

    //specifically for MPI transactions
    var $ACSUrl;
    var $isMPI = false;

    //specifically for MPI 2 transactions
    var $isMPI2 = false;

    //specifically for Risk transactions
    var $isResults;
    var $isRule;
    var $ruleName;
    var $results = array();
    var $rules = array();

    //specifically for MCP transaction
    var $mcpRatesDataHash = array();
    var $mcpRateData;
    var $isMCPRatesData;

    //KountInfo
    var $isKount = false;

    //specifically for Installment Plans
    var $currentPlanID;
    var $tacHash = array();
    var $planDataHash = array();
    var $tacDataHash = array();
    var $installmentResHash = array();

    var $isInstallmentPlan = false;
    var $isInstallmentResult = false;
    var $inTac = false;
    var $inPromotion = false;
    var $inFirstInstallment = false;
    var $inLastInstallment = false;

    public function __construct($xmlString)
    {
        $this->XMLParser = xml_parser_create();
        xml_parser_set_option($this->XMLParser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($this->XMLParser, XML_OPTION_TARGET_ENCODING, "UTF-8");
        xml_set_object($this->XMLParser, $this);
        xml_set_element_handler($this->XMLParser, "startHandler", "endHandler");
        xml_set_character_data_handler($this->XMLParser, "characterHandler");
        xml_parse($this->XMLParser, $xmlString);
        xml_parser_free($this->XMLParser);
    }    //end of constructor

    public function getMpgResponseData(): array
    {
        return ($this->responseData);
    }


    public function getEligibleInstallmentPlans(): EligibleInstallmentPlans
    {
        if(is_null($this->planDataHash) or !($this->planDataHash))
        {
            $installmentPlans = array();
            $eligibleInstallmentPlans = new EligibleInstallmentPlans();
            $eligibleInstallmentPlans->setInstallmentPlans($installmentPlans);
            return $eligibleInstallmentPlans;
        }

        $planCount = count($this->planDataHash);

        $pIndx = 0;
        $installmentPlans = array();

        foreach($this->planDataHash as $planID=>$plan_value)
        {
            $installmentPlans[$pIndx] = new PlanDetails();
            $installmentPlans[$pIndx]->setPlanId($planID);
            $installmentPlans[$pIndx]->setPlanIdRef($this->planDataHash[$planID]["PlanDetails"]["PlanIdRef"]);
            $installmentPlans[$pIndx]->setName($this->planDataHash[$planID]["PlanDetails"]["Name"]);
            $installmentPlans[$pIndx]->setType($this->planDataHash[$planID]["PlanDetails"]["Type"]);
            $installmentPlans[$pIndx]->setNumInstallments($this->planDataHash[$planID]["PlanDetails"]["NumInstallments"]);
            $installmentPlans[$pIndx]->setInstallmentFrequency($this->planDataHash[$planID]["PlanDetails"]["InstallmentFrequency"]);
            $installmentPlans[$pIndx]->setAPR($this->planDataHash[$planID]["PlanDetails"]["Apr"]);
            $installmentPlans[$pIndx]->setTotalFees($this->planDataHash[$planID]["PlanDetails"]["TotalFees"]);
            $installmentPlans[$pIndx]->setTotalPlanCost($this->planDataHash[$planID]["PlanDetails"]["TotalPlanCost"]);

            $promotionInfo = new PromotionInfo();
            $promotionInfo->setPromotionCode($this->planDataHash[$planID]["PromotionInfo"]["PromotionCode"]);
            $promotionInfo->setPromotionId($this->planDataHash[$planID]["PromotionInfo"]["PromotionId"]);
            $installmentPlans[$pIndx]->setPromotionInfo($promotionInfo);

            $firstInstallment = new FirstInstallment();
            $firstInstallment->setUpfrontFee($this->planDataHash[$planID]["FirstInstallment"]["UpfrontFee"]);
            $firstInstallment->setInstallmentFee($this->planDataHash[$planID]["FirstInstallment"]["InstallmentFee"]);
            $firstInstallment->setAmount($this->planDataHash[$planID]["FirstInstallment"]["Amount"]);
            $installmentPlans[$pIndx]->setFirstInstallment($firstInstallment);

            $lastInstallment = new LastInstallment();
            $lastInstallment->setInstallmentFee($this->planDataHash[$planID]["LastInstallment"]["InstallmentFee"]);
            $lastInstallment->setAmount($this->planDataHash[$planID]["LastInstallment"]["Amount"]);
            $installmentPlans[$pIndx]->setLastInstallment($lastInstallment);

            $tacCount = count($this->tacHash[$planID]);
            $tacs = [];
            $tacIdx = 0;

            foreach ($this->tacHash[$planID] as $tacHash_key => $tac) {
                $tacs[$tacIdx] = new TacDetails();
                $tacs[$tacIdx]->setText($tac["Text"]);
                $tacs[$tacIdx]->setUrl($tac["Url"]);
                $tacs[$tacIdx]->setVersion($tac["Version"]);
                $tacs[$tacIdx]->setLanguageCode($tac["LanguageCode"]);

                $tacIdx++;
            }

            $tac = new Tac();
            $tac->setTacs($tacs);
            $installmentPlans[$pIndx]->setTac($tac);

            $pIndx++;
        }

        $eligibleInstallmentPlans = new EligibleInstallmentPlans();
        $eligibleInstallmentPlans->setInstallmentPlans($installmentPlans);

        return $eligibleInstallmentPlans;
    }

    public function getInstallmentResults(): InstallmentResults
    {
        $installmentResults = new InstallmentResults();
        if(!($this->installmentResHash) or is_null($this->installmentResHash))
        {
            return $installmentResults;
        }

        $installmentResults->setPlanId($this->installmentResHash["PlanId"]);
        $installmentResults->setPlanIdRef($this->installmentResHash["PlanIdRef"]);
        $installmentResults->setPlanAcceptanceId($this->installmentResHash["PlanAcceptanceId"]);
        $installmentResults->setPlanResponse($this->installmentResHash["PlanResponse"]);
        $installmentResults->setPlanStatus($this->installmentResHash["PlanStatus"]);
        $installmentResults->setTacVersion($this->installmentResHash["TacVersion"]);

        return $installmentResults;
    }

    //To prevent Undefined Index Notices
    private function getMpgResponseValue($responseData, $value)
    {
        return ($responseData[$value] ?? '');
    }

    public function getRecurSuccess()
    {
        return $this->getMpgResponseValue($this->responseData, 'RecurSuccess');
    }

    public function getStatusCode()
    {
        return $this->getMpgResponseValue($this->responseData, 'status_code');
    }

    public function getStatusMessage()
    {
        return $this->getMpgResponseValue($this->responseData, 'status_message');
    }

    public function getAvsResultCode()
    {
        return $this->getMpgResponseValue($this->responseData,'AvsResultCode');
    }

    public function getCvdResultCode()
    {
        return $this->getMpgResponseValue($this->responseData,'CvdResultCode');
    }

    public function getCardType()
    {
        return $this->getMpgResponseValue($this->responseData,'CardType');
    }

    public function getTransAmount()
    {
        return $this->getMpgResponseValue($this->responseData,'TransAmount');
    }

    public function getTxnNumber()
    {
        return $this->getMpgResponseValue($this->responseData,'TransID');
    }

    public function getReceiptId()
    {
        return $this->getMpgResponseValue($this->responseData, 'ReceiptId');
    }

    public function getTransType()
    {
        return $this->getMpgResponseValue($this->responseData,'TransType');
    }

    public function getReferenceNum()
    {
        return $this->getMpgResponseValue($this->responseData,'ReferenceNum');
    }

    public function getResponseCode()
    {
        return $this->getMpgResponseValue($this->responseData,'ResponseCode');
    }

    public function getISO()
    {
        return $this->getMpgResponseValue($this->responseData,'ISO');
    }

    public function getBankTotals()
    {
        return $this->getMpgResponseValue($this->responseData,'BankTotals');
    }

    public function getMessage()
    {
        return $this->getMpgResponseValue($this->responseData,'Message');
    }

    public function getAuthCode()
    {
        return $this->getMpgResponseValue($this->responseData,'AuthCode');
    }

    public function getComplete()
    {
        return $this->getMpgResponseValue($this->responseData,'Complete');
    }

    public function getTransDate()
    {
        return $this->getMpgResponseValue($this->responseData,'TransDate');
    }

    public function getTransTime()
    {
        return $this->getMpgResponseValue($this->responseData,'TransTime');
    }

    public function getTicket()
    {
        return $this->getMpgResponseValue($this->responseData,'Ticket');
    }

    public function getFastFundsIndicator()
    {
        return $this->getMpgResponseValue($this->responseData,'FastFundsIndicator');
    }

    public function getTimedOut()
    {
        return $this->getMpgResponseValue($this->responseData,'TimedOut');
    }

    public function getCorporateCard()
    {
        return $this->getMpgResponseValue($this->responseData,'CorporateCard');
    }

    public function getCavvResultCode()
    {
        return $this->getMpgResponseValue($this->responseData,'CavvResultCode');
    }

    public function getCardLevelResult()
    {
        return $this->getMpgResponseValue($this->responseData,'CardLevelResult');
    }

    public function getITDResponse()
    {
        return $this->getMpgResponseValue($this->responseData,'ITDResponse');
    }

    public function getIsVisaDebit()
    {
        return $this->getMpgResponseValue($this->responseData,'IsVisaDebit');
    }

    public function getMaskedPan()
    {
        return $this->getMpgResponseValue($this->responseData,'MaskedPan');
    }

    public function getCfSuccess()
    {
        return $this->getMpgResponseValue($this->responseData,'CfSuccess');
    }

    public function getCfStatus()
    {
        return $this->getMpgResponseValue($this->responseData,'CfStatus');
    }

    public function getFeeAmount()
    {
        return $this->getMpgResponseValue($this->responseData,'FeeAmount');
    }

    public function getFeeRate()
    {
        return $this->getMpgResponseValue($this->responseData,'FeeRate');
    }

    public function getFeeType()
    {
        return $this->getMpgResponseValue($this->responseData,'FeeType');
    }

    public function getHostId()
    {
        return $this->getMpgResponseValue($this->responseData,'HostId');
    }

    public function getIssuerId()
    {
        return $this->getMpgResponseValue($this->responseData,'IssuerId');
    }

    //NT Response
    public function getNTResponseCode()
    {
        return $this->getMpgResponseValue($this->responseData,'NTResponseCode');
    }
    public function getNTMessage()
    {
        return $this->getMpgResponseValue($this->responseData,'NTMessage');
    }
    public function getNTUsed()
    {
        return $this->getMpgResponseValue($this->responseData,'NTUsed');
    }
    public function getNTTokenBin()
    {
        return $this->getMpgResponseValue($this->responseData,'NTTokenBin');
    }
    public function getNTTokenLast4()
    {
        return $this->getMpgResponseValue($this->responseData,'NTTokenLast4');
    }
    public function getNTTokenExpDate()
    {
        return $this->getMpgResponseValue($this->responseData,'NTTokenExpDate');
    }

    public function getNTMaskedToken()
    {
        return $this->getMpgResponseValue($this->responseData,'NTMaskedToken');
    }
    public function getSourcePanLast4()
    {
        return $this->getMpgResponseValue($this->responseData,'SourcePanLast4');
    }

    //--------------------------- RecurUpdate response fields ----------------------------//

    public function getRecurUpdateSuccess()
    {
        return $this->getMpgResponseValue($this->responseData,'RecurUpdateSuccess');
    }

    public function getNextRecurDate()
    {
        return $this->getMpgResponseValue($this->responseData,'NextRecurDate');
    }

    public function getRecurEndDate()
    {
        return $this->getMpgResponseValue($this->responseData,'RecurEndDate');
    }

    //--------------------------- MCP response fields ----------------------------//

    //MCP Fields
    /*
    public function getMerchantSettlementAmount()
    {
        return $this->getMpgResponseValue($this->responseData,"MerchantSettlementAmount");
    }

    public function getCardholderAmount()
    {
        return $this->getMpgResponseValue($this->responseData,"CardholderAmount");
    }

    public function getMCPRate()
    {
        return $this->getMpgResponseValue($this->responseData,"MCPRate");
    }

    public function getMCPErrorStatusCode()
    {
        return $this->getMpgResponseValue($this->responseData,"MCPErrorStatusCode");
    }

    public function getMCPErrorMessage()
    {
        return $this->getMpgResponseValue($this->responseData,"MCPErrorMessage");
    }
    */

    public function getMCPRateToken()
    {
        return $this->getMpgResponseValue($this->responseData,"MCPRateToken");
    }

    public function getRateTxnType()
    {
        return $this->getMpgResponseValue($this->responseData,"RateTxnType");
    }

    public function getRateInqStartTime()
    {
        return $this->getMpgResponseValue($this->responseData,"RateInqStartTime");
    }

    public function getRateInqEndTime()
    {
        return $this->getMpgResponseValue($this->responseData,"RateInqEndTime");
    }

    public function getRateValidityStartTime()
    {
        return $this->getMpgResponseValue($this->responseData,"RateValidityStartTime");
    }

    public function getRateValidityEndTime()
    {
        return $this->getMpgResponseValue($this->responseData,"RateValidityEndTime");
    }

    public function getRateValidityPeriod()
    {
        return $this->getMpgResponseValue($this->responseData,"RateValidityPeriod");
    }

    public function getCardholderCurrencyCode($index = '')
    {
        if($index !== '')
        {
            return $this->getMpgResponseValue($this->mcpRatesDataHash[$index],"CardholderCurrencyCode");
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,"CardholderCurrencyCode");
        }
    }

    public function getCardholderAmount($index = '')
    {
        if($index !== '')
        {
            return $this->getMpgResponseValue($this->mcpRatesDataHash[$index],"CardholderAmount");
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,"CardholderAmount");
        }
    }

    public function getMerchantSettlementCurrency($index = '')
    {
        if($index !== '')
        {
            return $this->getMpgResponseValue($this->mcpRatesDataHash[$index],"MerchantSettlementCurrency");
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,"MerchantSettlementCurrency");
        }
    }

    public function getMerchantSettlementAmount($index = '')
    {
        if($index !== '')
        {
            return $this->getMpgResponseValue($this->mcpRatesDataHash[$index],"MerchantSettlementAmount");
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,"MerchantSettlementAmount");
        }
    }

    public function getMCPRate($index = '')
    {
        if($index !== '')
        {
            return $this->getMpgResponseValue($this->mcpRatesDataHash[$index],"MCPRate");
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,"MCPRate");
        }
    }

    public function getMCPErrorStatusCode($index = '')
    {
        if($index !== '')
        {
            return $this->getMpgResponseValue($this->mcpRatesDataHash[$index],"MCPErrorStatusCode");
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,"MCPErrorStatusCode");
        }
    }

    public function getMCPErrorMessage($index = '')
    {
        if($index !== '')
        {
            return $this->getMpgResponseValue($this->mcpRatesDataHash[$index],"MCPErrorMessage");
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,"MCPErrorMessage");
        }
    }

    public function getRatesCount()
    {
        return count($this->mcpRatesDataHash);
    }

    //-------------------------- Resolver response fields --------------------------------//

    public function getDataKey()
    {
        return $this->getMpgResponseValue($this->responseData,'DataKey');
    }

    public function getResSuccess()
    {
        return $this->getMpgResponseValue($this->responseData,'ResSuccess');
    }

    public function getPaymentType()
    {
        return $this->getMpgResponseValue($this->responseData,'PaymentType');
    }

    //MAC CODE
    public function getAdviceCode()
    {
        return $this->getMpgResponseValue($this->responseData,'AdviceCode');
    }

    //AccountName
    public function getAccountNameResult()
    {
        return $this->getMpgResponseValue($this->responseData,'AccountNameVerificationResult');
    }

    //------------------------------------------------------------------------------------//

    public function getResolveData()
    {
        if($this->responseData['ResolveData']!='null'){
            return ($this->resolveData);
        }

        return $this->getMpgResponseValue($this->responseData,'ResolveData');
    }

    public function setResolveData($data_key)
    {
        $this->resolveData=$this->resolveDataHash[$data_key];
    }

    public function getResolveDataHash()
    {
        return ($this->resolveDataHash);
    }

    public function getDataKeys()
    {
        return ($this->DataKeys);
    }

    public function getResDataDataKey()
    {
        return $this->getMpgResponseValue($this->resolveData,'data_key');
    }

    public function getResDataPaymentType()
    {
        return $this->getMpgResponseValue($this->resolveData,'payment_type');
    }

    public function getResDataCustId()
    {
        return $this->getMpgResponseValue($this->resolveData,'cust_id');
    }

    public function getResDataPhone()
    {
        return $this->getMpgResponseValue($this->resolveData,'phone');
    }

    public function getResDataEmail()
    {
        return $this->getMpgResponseValue($this->resolveData,'email');
    }

    public function getResDataNote()
    {
        return $this->getMpgResponseValue($this->resolveData,'note');
    }

    public function getResDataPan()
    {
        return $this->getMpgResponseValue($this->resolveData,'pan');
    }

    public function getResDataMaskedPan()
    {
        return $this->getMpgResponseValue($this->resolveData,'masked_pan');
    }

    public function getResDataExpDate()
    {
        return $this->getMpgResponseValue($this->resolveData,'expdate');
    }

    public function getResDataAvsStreetNumber()
    {
        return $this->getMpgResponseValue($this->resolveData,'avs_street_number');
    }

    public function getResDataAvsStreetName()
    {
        return $this->getMpgResponseValue($this->resolveData,'avs_street_name');
    }

    public function getResDataAvsZipcode()
    {
        return $this->getMpgResponseValue($this->resolveData,'avs_zipcode');
    }

    public function getResDataCryptType()
    {
        return $this->getMpgResponseValue($this->resolveData,'crypt_type');
    }

    public function getResDataSec()
    {
        return $this->getMpgResponseValue($this->resolveData,'sec');
    }

    public function getResDataCustFirstName()
    {
        return $this->getMpgResponseValue($this->resolveData,'cust_first_name');
    }

    public function getResDataCustLastName()
    {
        return $this->getMpgResponseValue($this->resolveData,'cust_last_name');
    }

    public function getResDataCustAddress1()
    {
        return $this->getMpgResponseValue($this->resolveData,'cust_address1');
    }

    public function getResDataCustAddress2()
    {
        return $this->getMpgResponseValue($this->resolveData,'cust_address2');
    }

    public function getResDataCustCity()
    {
        return $this->getMpgResponseValue($this->resolveData,'cust_city');
    }

    public function getResDataCustState()
    {
        return $this->getMpgResponseValue($this->resolveData,'cust_state');
    }

    public function getResDataCustZip()
    {
        return $this->getMpgResponseValue($this->resolveData,'cust_zip');
    }

    public function getResDataRoutingNum()
    {
        return $this->getMpgResponseValue($this->resolveData,'routing_num');
    }

    public function getResDataAccountNum()
    {
        return $this->getMpgResponseValue($this->resolveData,'account_num');
    }

    public function getResDataMaskedAccountNum()
    {
        return $this->getMpgResponseValue($this->resolveData,'masked_account_num');
    }

    public function getResDataCheckNum()
    {
        return $this->getMpgResponseValue($this->resolveData,'check_num');
    }

    public function getResDataAccountType()
    {
        return $this->getMpgResponseValue($this->resolveData,'account_type');
    }

    public function getResDataPresentationType()
    {
        return $this->getMpgResponseValue($this->resolveData,'presentation_type');
    }

    public function getResDataPAccountNumber()
    {
        return $this->getMpgResponseValue($this->resolveData,'p_account_number');
    }

    //-------------------------- VdotMe specific fields --------------------------------//
    public function getVDotMeData()
    {
        return($this->vDotMeInfo);
    }

    public function getCurrencyCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'currencyCode');
    }

    public function getPaymentTotal()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'total');
    }

    public function getUserFirstName()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'userFirstName');
    }

    public function getUserLastName()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'userLastName');
    }

    public function getUserName()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'userName');
    }

    public function getUserEmail()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'userEmail');
    }

    public function getEncUserId()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'encUserId');
    }

    public function getCreationTimeStamp()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'creationTimeStamp');
    }

    public function getNameOnCard()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'nameOnCard');
    }

    public function getExpirationDateMonth()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['expirationDate'],'month');
    }

    public function getExpirationDateYear()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['expirationDate'],'year');
    }

    public function getBillingId()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'id');
    }

    public function getLastFourDigits()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'lastFourDigits');
    }

    public function getBinSixDigits()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'binSixDigits');
    }

    public function getCardBrand()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'cardBrand');
    }

    public function getVDotMeCardType()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'cardType');
    }

    public function getBillingPersonName()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'personName');
    }

    public function getBillingAddressLine1()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'line1');
    }

    public function getBillingCity()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'city');
    }

    public function getBillingStateProvinceCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'stateProvinceCode');
    }

    public function getBillingPostalCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'postalCode');
    }

    public function getBillingCountryCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'countryCode');
    }

    public function getBillingPhone()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'phone');
    }

    public function getBillingVerificationStatus()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'verificationStatus');
    }

    public function getIsExpired()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'expired');
    }

    public function getPartialShippingCountryCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['partialShippingAddress'],'countryCode');
    }

    public function getPartialShippingPostalCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['partialShippingAddress'],'postalCode');
    }

    public function getShippingPersonName()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'personName');
    }

    public function getShippingCity()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'city');
    }

    public function getShippingStateProvinceCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'stateProvinceCode');
    }

    public function getShippingPostalCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'postalCode');
    }

    public function getShippingCountryCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'countryCode');
    }

    public function getShippingPhone()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'phone');
    }

    public function getShippingDefault()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'default');
    }

    public function getShippingId()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'id');
    }

    public function getShippingVerificationStatus()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'verificationStatus');
    }

    public function getBaseImageFileName()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'baseImageFileName');
    }

    public function getHeight()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'height');
    }

    public function getWidth()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'width');
    }

    public function getIssuerBid()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'issuerBid');
    }

    public function getRiskAdvice()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['riskData'],'advice');
    }

    public function getRiskScore()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['riskData'],'score');
    }

    public function getAvsResponseCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['riskData'],'avsResponseCode');
    }

    public function getCvvResponseCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['riskData'],'cvvResponseCode');
    }

    //--------------------------- MasterPass response fields -----------------------------//

    public function getCardBrandId()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBrandId');
    }


    public function getCardBrandName()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBrandName');
    }


    public function getCardBillingAddressCity()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressCity');
    }


    public function getCardBillingAddressCountry()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressCountry');
    }


    public function getCardBillingAddressCountrySubdivision()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressCountrySubdivision');
    }


    public function getCardBillingAddressLine1()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressLine1');
    }


    public function getCardBillingAddressLine2()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressLine2');
    }


    public function getCardBillingAddressPostalCode()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressPostalCode');
    }


    public function getCardBillingAddressRecipientPhoneNumber()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressRecipientPhoneNumber');
    }


    public function getCardBillingAddressRecipientName()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressRecipientName');
    }


    public function getCardCardHolderName()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardCardHolderName');
    }


    public function getCardExpiryMonth()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardExpiryMonth');
    }


    public function getCardExpiryYear()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardExpiryYear');
    }


    public function getContactEmailAddress()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ContactEmailAddress');
    }


    public function getContactFirstName()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ContactFirstName');
    }


    public function getContactLastName()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ContactLastName');
    }


    public function getContactPhoneNumber()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ContactPhoneNumber');
    }


    public function getShippingAddressCity()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressCity');
    }


    public function getShippingAddressCountry()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressCountry');
    }


    public function getShippingAddressCountrySubdivision()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressCountrySubdivision');
    }

    public function getShippingAddressLine2()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressLine2');
    }


    public function getShippingAddressPostalCode()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressPostalCode');
    }


    public function getShippingAddressRecipientName()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressRecipientName');
    }


    public function getShippingAddressRecipientPhoneNumber()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressRecipientPhoneNumber');
    }


    public function getPayPassWalletIndicator()
    {
        return $this->getMpgResponseValue($this->masterPassData,'PayPassWalletIndicator');
    }


    public function getAuthenticationOptionsAuthenticateMethod()
    {
        return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsAuthenticateMethod');
    }


    public function getAuthenticationOptionsCardEnrollmentMethod()
    {
        return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsCardEnrollmentMethod');
    }


    public function getCardAccountNumber()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardAccountNumber');
    }


    public function getAuthenticationOptionsEciFlag()
    {
        return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsEciFlag');
    }


    public function getAuthenticationOptionsPaResStatus()
    {
        return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsPaResStatus');
    }


    public function getAuthenticationOptionsSCEnrollmentStatus()
    {
        return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsSCEnrollmentStatus');
    }


    public function getAuthenticationOptionsSignatureVerification()
    {
        return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsSignatureVerification');
    }


    public function getAuthenticationOptionsXid()
    {
        return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsXid');
    }


    public function getAuthenticationOptionsCAvv()
    {
        return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsCAvv');
    }


    public function getTransactionId()
    {
        return $this->getMpgResponseValue($this->masterPassData,'TransactionId');
    }

    public function getMPRequestToken()
    {
        return $this->getMpgResponseValue($this->responseData,'MPRequestToken');
    }

    public function getMPRedirectUrl()
    {
        return $this->getMpgResponseValue($this->responseData,'MPRedirectUrl');
    }

    //------------------- VDotMe & MasterPass shared response fields ---------------------//

    public function getShippingAddressLine1()
    {
        if ($this->isPaypass)
        {
            return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressLine1');
        }
        else
        {
            return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'line1');
        }
    }
//------------------- MPI response fields ---------------------//
    public function getMpiType()
    {
        return $this->getMpgResponseValue($this->responseData,'MpiType');
    }

    public function getMpiSuccess()
    {
        if ($this->isMPI === false)
        {
            return $this->getMpgResponseValue($this->responseData,'MpiSuccess');
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,'success');
        }
    }

    public function getMpiMessage()
    {
        if ($this->isMPI === false)
        {
            return $this->getMpgResponseValue($this->responseData,'MpiMessage');
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,'message');
        }
    }

    public function getMpiPaReq()
    {
        if ($this->isMPI === false)
        {
            return $this->getMpgResponseValue($this->responseData,'MpiPaReq');
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,'PaReq');
        }
    }

    public function getMpiTermUrl()
    {
        if ($this->isMPI === false)
        {
            return $this->getMpgResponseValue($this->responseData,'MpiTermUrl');
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,'TermUrl');
        }
    }

    public function getMpiMD()
    {
        if ($this->isMPI === false)
        {
            return $this->getMpgResponseValue($this->responseData,'MpiMD');
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,'MD');
        }
    }

    public function getMpiACSUrl()
    {
        if ($this->isMPI === false)
        {
            return $this->getMpgResponseValue($this->responseData,'MpiACSUrl');
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,'ACSUrl');
        }
    }

    public function getMpiCavv()
    {
        if($this->isMPI2)
        {
            return $this->getMpgResponseValue($this->responseData,'Cavv');
        }
        else if ($this->isMPI === false)
        {
            return $this->getMpgResponseValue($this->responseData,'MpiCavv');
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,'cavv');
        }
    }

    public function getMpiEci()
    {
        if($this->isMPI2)
        {
            return $this->getMpgResponseValue($this->responseData,'ECI');
        }
        else if ($this->isMPI === false)
        {
            return $this->getMpgResponseValue($this->responseData,'MpiEci');
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,'eci');
        }
    }


    public function getMpiPAResVerified()
    {
        if ($this->isMPI === false)
        {
            return $this->getMpgResponseValue($this->responseData,'MpiPAResVerified');
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,'PAResVerified');
        }
    }

    public function getMpiResponseData()
    {
        return($this->responseData);
    }

    public function getMpiMessageType()
    {
        return $this->getMpgResponseValue($this->responseData,"MessageType");
    }

    public function getMpiThreeDSMethodURL()
    {
        return $this->getMpgResponseValue($this->responseData,"ThreeDSMethodURL");
    }

    public function getMpiThreeDSMethodData()
    {
        return $this->getMpgResponseValue($this->responseData,"ThreeDSMethodData");
    }

    public function getMpiThreeDSServerTransId()
    {
        return $this->getMpgResponseValue($this->responseData,"ThreeDSServerTransId");
    }

    public function getMpiDSTransId()
    {
        return $this->getMpgResponseValue($this->responseData,"DSTransId");
    }

    public function getMpiTransStatus()
    {
        return $this->getMpgResponseValue($this->responseData,"TransStatus");
    }

    public function getMpiChallengeURL()
    {
        return $this->getMpgResponseValue($this->responseData,"ChallengeURL");
    }

    public function getMpiChallengeData()
    {
        return $this->getMpgResponseValue($this->responseData,"ChallengeData");
    }

    public function getMpiChallengeCompletionIndicator()
    {
        return $this->getMpgResponseValue($this->responseData,"ChallengeCompletionIndicator");
    }

    public function getThreeDSVersion()
    {
        return $this->getMpgResponseValue($this->responseData,"ThreeDSVersion");
    }

    public function getMpiThreeDSAcsTransID()
    {
        return $this->getMpgResponseValue($this->responseData,"ThreeDSAcsTransID");
    }

    public function getMpiThreeDSAuthTimeStamp()
    {
        return $this->getMpgResponseValue($this->responseData,"ThreeDSAuthTimeStamp");
    }

    public function getMpiAuthenticationType()
    {
        return $this->getMpgResponseValue($this->responseData,"AuthenticationType");
    }

    public function getMpiCardholderInfo()
    {
        return $this->getMpgResponseValue($this->responseData,"CardholderInfo");
    }

    public function getMpiTransStatusReason()
    {
        return $this->getMpgResponseValue($this->responseData,"TransStatusReason");
    }

    public function getMpiInLineForm()
    {

        $inLineForm ='<html><head><title>Title for Page</title></head><SCRIPT LANGUAGE="Javascript" >' .
                     "<!--
				function OnLoadEvent()
				{
					document.downloadForm.submit();
				}
				-->
				</SCRIPT>" .
                     '<body onload="OnLoadEvent()">
					<form name="downloadForm" action="' . $this->getMpiACSUrl() .
                     '" method="POST">
					<noscript>
					<br>
					<br>
					<center>
					<h1>Processing your 3-D Secure Transaction</h1>
					<h2>
					JavaScript is currently disabled or is not supported
					by your browser.<br>
					<h3>Please click on the Submit button to continue
					the processing of your 3-D secure
					transaction.</h3>
					<input type="submit" value="Submit">
					</center>
					</noscript>
					<input type="hidden" name="PaReq" value="' . $this->getMpiPaReq() . '">
					<input type="hidden" name="MD" value="' . $this->getMpiMD() . '">
					<input type="hidden" name="TermUrl" value="' . $this->getMpiTermUrl() .'">
				</form>
				</body>
				</html>';

        return $inLineForm;
    }

    public function getMpiPopUpWindow()
    {
        $popUpForm ='<html><head><title>Title for Page</title></head><SCRIPT LANGUAGE="Javascript" >' .
                    "<!--
					function OnLoadEvent()
					{
						window.name='mainwindow';
						//childwin = window.open('about:blank','popupName','height=400,width=390,status=yes,dependent=no,scrollbars=yes,resizable=no');
						//document.downloadForm.target = 'popupName';
						document.downloadForm.submit();
					}
					-->
					</SCRIPT>" .
                    '<body onload="OnLoadEvent()">
						<form name="downloadForm" action="' . $this->getMpiAcsUrl() .
                    '" method="POST">
						<noscript>
						<br>
						<br>
						<center>
						<h1>Processing your 3-D Secure Transaction</h1>
						<h2>
						JavaScript is currently disabled or is not supported
						by your browser.<br>
						<h3>Please click on the Submit button to continue
						the processing of your 3-D secure
						transaction.</h3>
						<input type="submit" value="Submit">
						</center>
						</noscript>
						<input type="hidden" name="PaReq" value="' . $this->getMpiPaReq() . '">
						<input type="hidden" name="MD" value="' . $this->getMpiMD() . '">
						<input type="hidden" name="TermUrl" value="' . $this->getMpiTermUrl() .'">
						</form>
					</body>
					</html>';

        return $popUpForm;
    }


    //-----------------  Risk response fields  ---------------------------------------------------------//

    public function getRiskResponse()
    {
        return ($this->responseData);
    }

    public function getResults()
    {
        return ($this->results);
    }

    public function getRules()
    {
        return ($this->rules);
    }

    //--------------------------- BatchClose response fields -----------------------------//

    public function getTerminalStatus($ecr_no)
    {
        return $this->getMpgResponseValue($this->ecrHash,$ecr_no);
    }

    public function getPurchaseAmount($ecr_no,$card_type)
    {
        return ($this->purchaseHash[$ecr_no][$card_type]['Amount']=="" ? 0:$this->purchaseHash[$ecr_no][$card_type]['Amount']);
    }

    public function getPurchaseCount($ecr_no,$card_type)
    {
        return ($this->purchaseHash[$ecr_no][$card_type]['Count']=="" ? 0:$this->purchaseHash[$ecr_no][$card_type]['Count']);
    }

    public function getRefundAmount($ecr_no,$card_type)
    {
        return ($this->refundHash[$ecr_no][$card_type]['Amount']=="" ? 0:$this->refundHash[$ecr_no][$card_type]['Amount']);
    }

    public function getRefundCount($ecr_no,$card_type)
    {
        return ($this->refundHash[$ecr_no][$card_type]['Count']=="" ? 0:$this->refundHash[$ecr_no][$card_type]['Count']);
    }

    public function getCorrectionAmount($ecr_no,$card_type)
    {
        return ($this->correctionHash[$ecr_no][$card_type]['Amount']=="" ? 0:$this->correctionHash[$ecr_no][$card_type]['Amount']);
    }

    public function getCorrectionCount($ecr_no,$card_type)
    {
        return ($this->correctionHash[$ecr_no][$card_type]['Count']=="" ? 0:$this->correctionHash[$ecr_no][$card_type]['Count']);
    }

    public function getTerminalIDs()
    {
        return ($this->ecrs);
    }

    public function getCreditCardsAll()
    {
        return (array_keys($this->cards));
    }

    public function getCreditCards($ecr)
    {
        return $this->getMpgResponseValue($this->cardHash,$ecr);
    }

    public function getKountResult()
    {
        return $this->getMpgResponseValue($this->responseData,"KountResult");
    }

    public function getKountTransactionId()
    {
        return $this->getMpgResponseValue($this->responseData,"KountTransactionId");
    }

    public function getKountScore()
    {
        return $this->getMpgResponseValue($this->responseData,"KountScore");
    }

    public function getKountInfo()
    {
        return $this->getMpgResponseValue($this->responseData,"KountInfo");
    }

    public function getGooglepayPaymentMethod()
    {
        return $this->getMpgResponseValue($this->responseData,"GooglepayPaymentMethod");
    }

    public function getPar()
    {
        return $this->getMpgResponseValue($this->responseData,"Par");
    }

    private function characterHandler($parser,$data)
    {
        $this->currentTagValue .= $data;

    }//end characterHandler



    private function startHandler($parser,$name,$attrs)
    {
        $this->currentTag=$name;
        $this->currentTagValue = "";

        if($this->currentTag == "ResolveData")
        {
            $this->isResolveData=1;
        }
        elseif($this->isResolveData)
        {
            $this->resolveData[$this->currentTag]="";
        }
        elseif($this->currentTag == "MpiResponse")
        {
            $this->isMPI=true;
        }
        elseif($this->currentTag == "Mpi2Response")
        {
            $this->isMPI2=true;
        }
        elseif($this->currentTag == "VDotMeInfo")
        {
            $this->isVdotMeInfo=1;
        }
        elseif($this->isVdotMeInfo)
        {
            switch($name){
                case "billingAddress":
                {
                    $this->ParentNode=$name;
                    break;
                }
                case "partialShippingAddress":
                {
                    $this->ParentNode=$name;
                    break;
                }
                case "shippingAddress":
                {
                    $this->ParentNode=$name;
                    break;
                }
                case "riskData":
                {
                    $this->ParentNode=$name;
                    break;
                }
                case "expirationDate":
                {
                    $this->ParentNode=$name;
                    break;
                }
            }
        }
        else if($this->currentTag == "PayPassInfo")
        {
            $this->isPaypassInfo=1;
            $this->isPaypass=1;
        }
        elseif($this->currentTag == "BankTotals")
        {
            $this->isBatchTotals=1;
        }
        elseif($this->currentTag == "Purchase")
        {
            $this->purchaseHash[$this->term_id][$this->CardType]=array();
            $this->currentTxnType="Purchase";
        }
        elseif($this->currentTag == "Refund")
        {
            $this->refundHash[$this->term_id][$this->CardType]=array();
            $this->currentTxnType="Refund";
        }
        elseif($this->currentTag == "Correction")
        {
            $this->correctionHash[$this->term_id][$this->CardType]=array();
            $this->currentTxnType="Correction";
        }
        elseif($this->currentTag == "Result")
        {
            $this->isResults=1;
        }
        elseif($this->currentTag == "Rule")
        {
            $this->isRule=1;
        }
        elseif($this->currentTag == "Rate")
        {
            $this->isMCPRatesData=1;
            $this->mcpRateData = array();
        }
        elseif($this->isMCPRatesData)
        {
            $this->mcpRateData[$this->currentTag]="";
        }
        elseif($this->currentTag == "KountInfo")
        {
            $this->isKount = true;
        }
        elseif ($this->currentTag == "EligibleInstallmentPlans")
        {
            $this->isInstallmentPlan = true;
            $this->planDataHash = array();
        }
        elseif ($this->isInstallmentPlan)
        {
            if($this->currentTag == "TacDetails") {
                $this->tacDataHash = array();
                $this->inTac = true;
            }
            if($this->currentTag == "PromotionInfo") {
                $this->inPromotion = true;
            }
            if($this->currentTag == "FirstInstallment")
                $this->inFirstInstallment = true;
            if($this->currentTag == "LastInstallment")
                $this->inLastInstallment = true;
        }
        elseif ($this->currentTag == "InstallmentResults")
        {
            $this->isInstallmentResult = true;
            $this->installmentResHash = array();
        }
    }

    private function endHandler($parser,$name)
    {
        $this->currentTag=$name;

        if($this->isBatchTotals)
        {
            switch($this->currentTag)
            {
                case "term_id"    :
                {
                    $this->term_id=$this->currentTagValue;
                    array_push($this->ecrs,$this->term_id);
                    $this->cardHash[$this->currentTagValue]=array();
                    break;
                }

                case "closed"     :
                {
                    $ecrHash=$this->ecrHash;
                    $ecrHash[$this->term_id]=$this->currentTagValue;
                    $this->ecrHash = $ecrHash;
                    break;
                }

                case "CardType"   :
                {
                    $this->CardType=$this->currentTagValue;
                    $this->cards[$this->currentTagValue]=$this->currentTagValue;
                    array_push($this->cardHash[$this->term_id],$this->currentTagValue) ;
                    break;
                }

                case "Amount"     :
                {
                    if($this->currentTxnType == "Purchase")
                    {
                        $this->purchaseHash[$this->term_id][$this->CardType]['Amount']=$this->currentTagValue;
                    }
                    elseif( $this->currentTxnType == "Refund")
                    {
                        $this->refundHash[$this->term_id][$this->CardType]['Amount']=$this->currentTagValue;
                    }
                    elseif( $this->currentTxnType == "Correction")
                    {
                        $this->correctionHash[$this->term_id][$this->CardType]['Amount']=$this->currentTagValue;
                    }
                    break;
                }

                case "Count"     :
                {
                    if($this->currentTxnType == "Purchase")
                    {
                        $this->purchaseHash[$this->term_id][$this->CardType]['Count']=$this->currentTagValue;
                    }
                    elseif( $this->currentTxnType == "Refund")
                    {
                        $this->refundHash[$this->term_id][$this->CardType]['Count']=$this->currentTagValue;
                    }
                    else if( $this->currentTxnType == "Correction")
                    {
                        $this->correctionHash[$this->term_id][$this->CardType]['Count']=$this->currentTagValue;
                    }
                    break;
                }
            }

        }
        elseif($this->isResolveData && $this->currentTag != "ResolveData")
        {
            if($this->currentTag == "data_key")
            {
                $this->data_key=$this->currentTagValue;
                array_push($this->DataKeys,$this->data_key);
                $this->resolveData[$this->currentTag] = $this->currentTagValue;
            }
            else
            {
                $this->resolveData[$this->currentTag] = $this->currentTagValue;
            }
        }
        elseif($this->isVdotMeInfo)
        {
            if($this->ParentNode != "")
                $this->vDotMeInfo[$this->ParentNode][$this->currentTag] = $this->currentTagValue;
            else
                $this->vDotMeInfo[$this->currentTag] = $this->currentTagValue;
        }
        else if ($this->isPaypassInfo)
        {
            $this->masterPassData[$this->currentTag] = $this->currentTagValue;
        }
        elseif($this->isResults)
        {
            $this->results[$this->currentTag] = $this->currentTagValue;

        }
        elseif($this->isRule)
        {

            if ($this->currentTag == "RuleName")
            {
                $this->ruleName=$this->currentTagValue;
            }
            $this->rules[$this->ruleName][$this->currentTag] = $this->currentTagValue;

        }
        elseif($this->isMCPRatesData)
        {
            $this->mcpRateData[$this->currentTag] = $this->currentTagValue;
        }
        else if($this->isKount)
        {
            $this->responseData["KountInfo"] .= "<" .$this->currentTag . ">" . $this->currentTagValue . "</" . $this->currentTag . ">";
        }
        elseif($this->isInstallmentPlan)
        {
            if ($this->currentTag == "PlanId")
            {
                $this->currentPlanID = $this->currentTagValue;
                // $this->planDataHash[$this->currentPlanID]=array();

                $this->planDataHash[$this->currentPlanID]=array("PlanDetails" => array("PlanId"=>$this->currentPlanID),
                    "PromotionInfo" => array(),
                    "FirstInstallment" => array(),
                    "LastInstallment" => array());

                // $this->planDataHash[$this->currentPlanID]=array("PromotionInfo" => array());
                // $this->planDataHash[$this->currentPlanID]=array("FirstInstallment" => array());
                // $this->planDataHash[$this->currentPlanID]=array("LastInstallment" => array());
                // $this->planDataHash[$this->currentPlanID]["PlanDetails"]=array("PlanId"=>$this->currentPlanID);

                $this->tacHash[$this->currentPlanID]=array();
            }
            elseif ($this->inTac and is_null($this->currentPlanID) == 0)
            {
                if ($this->currentTag == "LanguageCode")
                {
                    $this->tacDataHash[$this->currentTag]=$this->currentTagValue;
                    array_push($this->tacHash[$this->currentPlanID],$this->tacDataHash);
                    // $this->inTac = false;
                }
                else
                {
                    $this->tacDataHash[$this->currentTag]=$this->currentTagValue;
                }
            }
            elseif ($this->inPromotion and is_null($this->currentPlanID) == 0)
            {
                $this->planDataHash[$this->currentPlanID]["PromotionInfo"][$this->currentTag]=$this->currentTagValue;
            }
            elseif ($this->inFirstInstallment and is_null($this->currentPlanID) == 0)
            {
                $this->planDataHash[$this->currentPlanID]["FirstInstallment"][$this->currentTag]=$this->currentTagValue;
            }
            elseif ($this->inLastInstallment and is_null($this->currentPlanID) == 0)
            {
                $this->planDataHash[$this->currentPlanID]["LastInstallment"][$this->currentTag]=$this->currentTagValue;
            }
            elseif (is_null($this->currentPlanID) == 0)
            {
                $this->planDataHash[$this->currentPlanID]["PlanDetails"][$this->currentTag]=$this->currentTagValue;
            }
        }
        elseif ($this->isInstallmentResult && $this->currentTagValue != 'null')
        {
            $this->installmentResHash[$this->currentTag]=$this->currentTagValue;
        }
        else
        {
            $this->responseData[$this->currentTag] = $this->currentTagValue;
        }

        //------------------ Storing data in hash done --------------------

        if($this->currentTag == "ResolveData")
        {
            $this->isResolveData=0;
            if($this->data_key!="")
            {
                $this->resolveDataHash[$this->data_key]=$this->resolveData;
                $this->resolveData=array();
            }
        }
        elseif($this->currentTag == "VDotMeInfo")
        {
            $this->isVdotMeInfo=0;
        }
        elseif($this->isVdotMeInfo)
        {
            switch($this->currentTag){
                case "billingAddress":
                {
                    $this->ParentNode="";
                    break;
                }
                case "partialShippingAddress":
                {
                    $this->ParentNode="";
                    break;
                }
                case "shippingAddress":
                {
                    $this->ParentNode="";
                    break;
                }
                case "riskData":
                {
                    $this->ParentNode="";
                    break;
                }
                case "expirationDate":
                {
                    $this->ParentNode="";
                    break;
                }
            }
        }
        elseif($name == "BankTotals")
        {
            $this->isBatchTotals=0;
        }
        else if($this->currentTag == "PayPassInfo")
        {
            $this->isPaypassInfo=0;
        }
        elseif($name == "Result")
        {
            $this->isResults=0;
        }
        elseif($this->currentTag == "Rule")
        {
            $this->isRule=0;
        }
        elseif($this->currentTag == "Rate")
        {
            array_push($this->mcpRatesDataHash, $this->mcpRateData);

            $this->isMCPRatesData=0;
        }
        elseif($this->currentTag == "KountInfo")
        {
            $this->isKount = false;
        }
        elseif ($this->currentTag == "EligibleInstallmentPlans")
        {
            $this->isInstallmentPlan=0;
        }
        elseif ($this->currentTag == "TacDetails")
        {
            $this->inTac=0;
        }
        elseif ($this->currentTag == "PromotionInfo")
        {
            $this->inPromotion=0;
        }
        elseif ($this->currentTag == "FirstInstallment")
        {
            $this->inFirstInstallment=0;
        }
        elseif ($this->currentTag == "LastInstallment")
        {
            $this->inLastInstallment=0;
        }
        elseif ($this->currentTag == "InstallmentResults")
        {
            $this->isInstallmentResult=0;
        }

        $this->currentTag="/dev/null";
    }

}//end class mpgResponse
