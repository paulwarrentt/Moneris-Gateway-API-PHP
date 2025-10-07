<?php

namespace PaulWarrenTT\Moneris;

class MpgHttpsPost
{
    private string $api_token;
    private string $store_id;
    private mixed $app_version;
    private MpgRequest $mpgRequest;
    private MpgResponse $mpgResponse;
    private string $xmlString = "";
//    var $txnType;
    private bool $isMPI;
    private bool $isMPI2;

    public function __construct(string $store_id, string $api_token, MpgRequest $mpgRequestOBJ)
    {
        $this->store_id = $store_id;
        $this->api_token = $api_token;
        $this->app_version = null;
        $this->mpgRequest = $mpgRequestOBJ;
        $this->isMPI = $mpgRequestOBJ->getIsMPI();
        $this->isMPI2 = $mpgRequestOBJ->getIsMPI2();
        $dataToSend = $this->toXML();

        $url = $this->mpgRequest->getURL();

        $httpsPost = new HttpsPost($url, $dataToSend);
        $response = $httpsPost->getHttpsResponse();

        if ( ! $response) {
            $response = "<?xml version=\"1.0\"?><response><receipt>".
                        "<ReceiptId>Global Error Receipt</ReceiptId>".
                        "<ReferenceNum>null</ReferenceNum><ResponseCode>null</ResponseCode>".
                        "<AuthCode>null</AuthCode><TransTime>null</TransTime>".
                        "<TransDate>null</TransDate><TransType>null</TransType><Complete>false</Complete>".
                        "<Message>Global Error Receipt</Message><TransAmount>null</TransAmount>".
                        "<CardType>null</CardType>".
                        "<TransID>null</TransID><TimedOut>null</TimedOut>".
                        "<CorporateCard>false</CorporateCard><MessageId>null</MessageId>".
                        "</receipt></response>";
        }

        $this->mpgResponse = new MpgResponse($response);
    }

    public function getMpgResponse(): MpgResponse
    {
        return $this->mpgResponse;
    }

    public function setAppVersion(mixed $app_version): void
    {
        $this->app_version = $app_version;
    }

    public function toXML(): string
    {
        $req = $this->mpgRequest;
        $reqXMLString = $req->toXML();

        if ($this->isMPI2 === true) {
            $this->xmlString .= "<?xml version=\"1.0\"?>".
                                "<Mpi2Request>".
                                "<store_id>$this->store_id</store_id>".
                                "<api_token>$this->api_token</api_token>";

            if ($this->app_version != null) {
                $this->xmlString .= "<app_version>$this->app_version</app_version>";
            }

            $this->xmlString .= $reqXMLString.
                                "</Mpi2Request>";
        } elseif ($this->isMPI === true) {
            $this->xmlString .= "<?xml version=\"1.0\"?>".
                                "<MpiRequest>".
                                "<store_id>$this->store_id</store_id>".
                                "<api_token>$this->api_token</api_token>";

            if ($this->app_version != null) {
                $this->xmlString .= "<app_version>$this->app_version</app_version>";
            }

            $this->xmlString .= $reqXMLString.
                                "</MpiRequest>";
        } else {
            $this->xmlString .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>".
                                "<request>".
                                "<store_id>$this->store_id</store_id>".
                                "<api_token>$this->api_token</api_token>";

            if ($this->app_version != null) {
                $this->xmlString .= "<app_version>$this->app_version</app_version>";
            }

            $this->xmlString .= $reqXMLString.
                                "</request>";
        }

        return ($this->xmlString);
    }

}
