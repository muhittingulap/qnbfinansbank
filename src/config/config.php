<?php

namespace EFINANS\Config;

class config
{
    protected $efatura_test_url = "https://erpefaturatest.cs.com.tr:8043/efatura/ws/connectorService?wsdl";
    protected $efaura_url = "https://efaturaconnector.efinans.com.tr/connector/ws/connectorService?wsdl";

    protected $earsiv_test_url = "https://earsivtest.efinans.com.tr/earsiv/ws/EarsivWebService?wsdl";
    protected $earsiv_url = "https://earsivconnector.efinans.com.tr/earsiv/ws/EarsivWebService?wsdl";

    protected $url = "";

    protected $username = "";
    protected $password = "";
    protected $vergiTcKimlikNo = "";

    protected $context = "";
    protected $soapOptions = "";
    protected $errors = array();

    protected $api;
    protected $mode = 1;

    protected $prefix = array();

    public function __construct() {

        $this->prefix = array(
            'cac:AccountingSupplierParty' => array(
                'cac:Party' => array(
                    'cac:PartyIdentification' => array(
                        "cbc:ID" => array(
                            "name" => "schemeID",
                            "value" => 'VKN',
                        ),
                    ),
                ),
            ),
            'cac:AccountingCustomerParty' => array(
                'cac:Party' => array(
                    'cac:PartyIdentification' => array(
                        "cbc:ID" => array(
                            "name" => "schemeID",
                            "value" => 'TCKN',
                        ),
                    ),
                ),
            ),
            'cac:LegalMonetaryTotal' => array(
                'cbc:LineExtensionAmount' => array(
                    "name" => "currencyID",
                    "value" => 'TRY',
                ),
                'cbc:TaxExclusiveAmount' => array(
                    "name" => "currencyID",
                    "value" => 'TRY',
                ),
                'cbc:TaxInclusiveAmount' => array(

                    "name" => "currencyID",
                    "value" => 'TRY',
                ),
                'cbc:AllowanceTotalAmount' => array(
                    "name" => "currencyID",
                    "value" => 'TRY',
                ),
                'cbc:PayableAmount' => array(
                    "name" => "currencyID",
                    "value" => 'TRY',
                ),
            ),
            'cac:InvoiceLine' => array(
                'cbc:InvoicedQuantity' => array(
                    "name" => "unitCode",
                    "value" => 'NIU',
                ),
                'cbc:LineExtensionAmount' => array(
                    "name" => "currencyID",
                    "value" => 'TRY',
                ),
            ),
            "cac:TaxTotal" => array(
                'cbc:TaxAmount' => array(
                    "name" => "currencyID",
                    "value" => 'TRY',
                ),
            ),
            "cac:TaxSubtotal" => array(
                'cbc:TaxAmount' => array(
                    "name" => "currencyID",
                    "value" => 'TRY',

                ),
                'cbc:TaxableAmount' => array(
                    "name" => "currencyID",
                    "value" => 'TRY',
                ),
            ),
            'cac:AllowanceCharge' => array(
                'cbc:Amount' => array(
                    "name" => "currencyID",
                    "value" => 'TRY',
                ),
                'cbc:BaseAmount' => array(
                    "name" => "currencyID",
                    "value" => 'TRY',
                ),
            ),
            'cac:Price' => array(
                'cbc:PriceAmount' => array(
                    "name" => "currencyID",
                    "value" => 'TRY',
                ),
            ),
        );

    }

    protected function soapUp()
    {
        try {
            ini_set("soap.wsdl_cache_enabled", "0");
            $this->context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ]);
            $this->soapOptions = array('encoding' => 'UTF-8', "trace" => 1, "exceptions" => 1, 'stream_context' => $this->context);

            $this->api = new \SoapClient($this->url, $this->soapOptions);
            $this->api->__setSoapHeaders($this->soapClientWSSecurityHeader());

        } catch (Exception $e) {
            $this->errors[__FUNCTION__][0] = $e;
        }

        return $this;
    }

    protected function soapClientWSSecurityHeader()
    {
        $tm_created = gmdate('Y-m-d\TH:i:s\Z');
        $tm_expires = gmdate('Y-m-d\TH:i:s\Z', gmdate('U') + 180);

        $simple_nonce = mt_rand();
        $encoded_nonce = base64_encode($simple_nonce);

        $passdigest = base64_encode(sha1($simple_nonce . $tm_created . $this->password, true));

        $ns_wsse = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
        $ns_wsu = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
        $password_type = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest';
        $encoding_type = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary';

        $root = new \SimpleXMLElement('<root/>');

        $security = $root->addChild('wsse:Security', null, $ns_wsse);

        $timestamp = $security->addChild('wsu:Timestamp', null, $ns_wsu);
        $timestamp->addAttribute('wsu:Id', 'Timestamp-28');
        $timestamp->addChild('wsu:Created', $tm_created, $ns_wsu);
        $timestamp->addChild('wsu:Expires', $tm_expires, $ns_wsu);


        $usernameToken = $security->addChild('wsse:UsernameToken', null, $ns_wsse);
        $usernameToken->addChild('wsse:Username', $this->username, $ns_wsse);
        $usernameToken->addChild('wsse:Password', $this->password, $ns_wsse)->addAttribute('Type', $password_type);
        $usernameToken->addChild('wsse:Nonce', $encoded_nonce, $ns_wsse)->addAttribute('EncodingType', $encoding_type);
        $usernameToken->addChild('wsu:Created', $tm_created, $ns_wsu);

        $root->registerXPathNamespace('wsse', $ns_wsse);
        $full = $root->xpath('/root/wsse:Security');
        $auth = $full[0]->asXML();

        return new \SoapHeader($ns_wsse, 'Security', new \SoapVar($auth, XSD_ANYXML), true);

    }

}
