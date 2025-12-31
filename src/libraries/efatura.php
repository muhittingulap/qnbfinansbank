<?php

namespace EFINANS\Libraries;

use EFINANS\Config\config;

class efatura extends config
{
    /** @var mixed */
    protected $parametre;

    /** @var mixed */
    protected $xml;

    /** @var mixed */
    protected $xmlData;

    /** @var mixed */
    protected $input;

    /** @var mixed */
    protected $belgeNo;

    private $data = array();
    private $belgeFormati = "";
    private $erpKodu = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function setConfig($config=array()){
        $this->vergiTcKimlikNo = $config["vergiTcKimlikNo"];
        $this->username = $config["username"];
        $this->password = $config["password"];
        $this->url = $config["url"];
        return $this;
    }

    public function setStart()
    {
        $this->soapUp(); /* soap başlatılıyor */

        return $this;
    }

    public function setBelgeNo($bn = 0)
    {
        /*
         * $bn -> belge noyu set ediyoruz uniq bir id olmalı
         * */
        $this->belgeNo = $bn;

        return $this;
    }

    public function seterpKodu($data)
    {
        /*
         * $bn -> belge noyu set ediyoruz uniq bir id olmalı
         * */
        $this->erpKodu = $data;

        return $this;
    }

    public function setData($data = array())
    {
        /*
         * $data -> dxml datasını array olarar set ediyoruz
         * */
        $this->data = $data;

        $this->setDataXml(); /* xxml datası oluşturuluyor.*/

        return $this;
    }

    public function setbelgeFormati($data = "PDF")
    {
        $this->belgeFormati = $data;
        return $this;
    }

    private function setPrefix()
    {
        $vergino = $this->data["cac:AccountingCustomerParty"]["cac:Party"]["cac:PartyIdentification"]["cbc:ID"];
        $this->prefix["cac:AccountingCustomerParty"]["cac:Party"]["cac:PartyIdentification"]["cbc:ID"]["value"] = (strlen($vergino) > 10 ? 'TCKN' : 'VKN');
        return $this;
    }

    private function setDataXml()
    {
        $element = 'Invoice xsi:schemaLocation="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2 ../xsdrt/maindoc/UBL-Invoice-2.1.xsd" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"';
        $this->xml = new \EFINANS\component\xml($element);

        $this->setPrefix();

        $this->xmlData = $this->xml->setParams($this->data, $this->prefix)->getFaturaSablonXml();

        return $this;
    }


    public function getFaturaNo($prefix="")
    {
        try {
            $this->parametre = array(
                "vknTckn" => $this->vergiTcKimlikNo,
                "faturaKodu" => $prefix,
            );

            $r = $this->api->faturaNoUret($this->parametre);
            $this->return = $r->return;
        } catch (\Exception $e) {
            $this->errors[__FUNCTION__][0] = $e;
        }
        return $this->return;
    }

    public function setEFatura()
    {
        try {
            $this->return = $this->api->belgeGonderExt([
            'parametreler' => [
                'belgeNo' => $this->belgeNo,
                'vergiTcKimlikNo' => $this->vergiTcKimlikNo,
                'belgeTuru' => 'FATURA_UBL',
                'veri' => $this->xmlData,
                'belgeHash' => md5($this->xmlData),
                'mimeType' => 'application/xml',
                'belgeVersiyon' => '3.0',
                'erpKodu' =>  $this->erpKodu,
            ]
        ]);

        } catch (\Exception $e) {
            $this->errors[__FUNCTION__][0] = $e;
        }
        return $this->return;
    }


    public function gidenBelgeDurumSorgula($belgeOid="")
    {
        try {
            $this->parametre = array(
                "vergiTcKimlikNo" => $this->vergiTcKimlikNo,
                "belgeOid" => $belgeOid,
            );
            $r = $this->api->gidenBelgeDurumSorgula($this->parametre);
            $this->return = $r->return;
        } catch (\Exception $e) {
            $this->errors[__FUNCTION__][0] = $e;
        }
        return $this->return;
    }

    public function gidenBelgeleriIndir($belgeOid="")
    {
        try {
            $this->parametre = array(
                "vergiTcKimlikNo" => $this->vergiTcKimlikNo,
                "belgeOidListesi" => [$belgeOid],
                "belgeTuru" => "FATURA",
                "belgeFormati" => $this->belgeFormati,

            );
            $r = $this->api->gidenBelgeleriIndir($this->parametre);
            $this->return = $r;
        } catch (\Exception $e) {
            $this->errors[__FUNCTION__][0] = $e;
        }
        return $this->return;
    }

    public function gidenBelgeleriListele()
    {
        try {
            $this->parametre = array(
                "parametreler" => array(
                    "baslangicGonderimTarihi" => "20251001",
                    "bitisGonderimTarihi" => "20251030",
                    "belgeTuru" => "FATURA",
                    "vkn" => $this->vergiTcKimlikNo,
                    'erpKodu' =>  $this->erpKodu,
                ),
            );
            $r = $this->api->gidenBelgeleriListele($this->parametre);
        } catch (\Exception $e) {
            $this->errors[__FUNCTION__][0] = $e;
        }
        return $r;
    }

    public function gelenBelgeleriListele()
    {
        try {
            $this->parametre = array(
                "vergiTcKimlikNo" => $this->vergiTcKimlikNo,
                "sonAlinanBelgeSiraNumarasi" => 0,
                "belgeTuru" => "FATURA",
            );
            $r = $this->api->gelenBelgeleriListele($this->parametre);
        } catch (\Exception $e) {
            $this->errors[__FUNCTION__][0] = $e;
        }

        return $r;
    }

    public function getEfaturaKullanicisi($vkn="")
    {
        try {
            $this->parametre = array(
                "vergiTcKimlikNo" => $vkn,
            );
            $r = $this->api->efaturaKullanicisi($this->parametre);

        } catch (\Exception $e) {
            $this->errors[__FUNCTION__][0] = $e;
        }

        return (int)$r->return;
    }

    public function getXmlData()
    {
        return $this->xmlData;
    }

    public function viewXmlData()
    {
        header("content-type:application/xml");
        print $this->xmlData;
        exit;
    }

    public function getErrors($function)
    {
        return $this->errors[$function] ?? [];
    }
}
