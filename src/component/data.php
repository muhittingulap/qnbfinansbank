<?php


namespace EFINANS\Component;


class data
{
    /* data */
    public $data = array();

    public function __construct()
    {

    }

    public function setStartData($veri = array())
    {
        $this->data = array(
            "cbc:UBLVersionID" => "2.1",
            "cbc:CustomizationID" => "TR1.2",
            "cbc:ProfileID" => $veri["ProfileID"],
            "cbc:ID" => ($veri["ID"]?$veri["ID"]:""),
            "cbc:CopyIndicator" => "false",
            "cbc:UUID" => ($veri["UUID"]?$veri["UUID"]:$this->getUuid()),
            "cbc:IssueDate" => $veri["IssueDate"],
            "cbc:IssueTime" => $veri["IssueTime"],
            "cbc:InvoiceTypeCode" => ($veri["InvoiceTypeCode"] ? $veri["InvoiceTypeCode"] : "SATIS"),
            "cbc:Note" => array(),
            "cbc:DocumentCurrencyCode" => ($veri["DocumentCurrencyCode"] ? $veri["DocumentCurrencyCode"] : "TRY"),
            "cbc:LineCountNumeric" => 1,
            "cac:AccountingSupplierParty" => array(
                "cac:Party" => array(
                    "cbc:WebsiteURI" => "http://www.aaa.com.tr/",
                    "cac:PartyIdentification" => array(
                        "cbc:ID" => "8720616074",
                    ),
                    "cac:PartyName" => array(
                        "cbc:Name" => "AAA Anonim Şirketi",
                    ),
                    "cac:PostalAddress" => array(
                        "cbc:ID" => 1,
                        "cbc:StreetName" => "Papatya Caddesi Yasemin Sokak",
                        "cbc:BuildingNumber" => "21",
                        "cbc:CitySubdivisionName" => "Beşiktaş",
                        "cbc:CityName" => "İstanbul",
                        "cbc:PostalZone" => "34100",
                        "cac:Country" => array(
                            "cbc:Name" => "Türkiye",
                        ),
                    ),
                    "cac:PartyTaxScheme" => array(
                        "cac:TaxScheme" => array(
                            "cbc:Name" => "Büyük Mükellefler",
                        ),
                    ),
                    "cac:Contact" => array(
                        "cbc:Telephone" => "(212) 925 51515",
                        "cbc:Telefax" => "(212) 925505015",
                        "cbc:ElectronicMail" => "aa@aaa.com.tr",
                    ),
                ),
            ),
            "cac:AccountingCustomerParty" => array(
                "cac:Party" => array(
                    "cbc:WebsiteURI" => "",
                    "cac:PartyIdentification" => array(
                        "cbc:ID" => "39667363036",
                    ),
                    "cac:PartyName" => array(
                        "cbc:Name" => "AAA Anonim Şirketi",
                    ),
                    "cac:Person" => array(
                        "cbc:FirstName" => "Muhittin",
                        "cbc:FamilyName" => "GÜLAP",
                    ),
                    "cac:PostalAddress" => array(
                        "cbc:ID" => 1,
                        "cbc:StreetName" => "ATATÜRK MAH. 6. Sokak",
                        "cbc:BuildingNumber" => "1",
                        "cbc:CitySubdivisionName" => "Beşiktaş",
                        "cbc:CityName" => "İstanbul",
                        "cbc:PostalZone" => "34100",
                        "cac:Country" => array(
                            "cbc:Name" => "Türkiye",
                        ),
                    ),
                    "cac:PartyTaxScheme" => array(
                        "cac:TaxScheme" => array(
                            "cbc:Name" => "",
                        ),
                    ),
                    "cac:Contact" => array(
                        "cbc:Telephone" => "(212) 925 51515",
                        "cbc:Telefax" => "(212) 925505015",
                        "cbc:ElectronicMail" => "aa@aaa.com.tr",
                    ),
                ),
            ),
            "cac:PaymentTerms" => array(
                "cbc:Note" => "BBB Bank Otomatik Ödeme",
                "cbc:PaymentDueDate" => "2020-06-30",
            ),
            "cac:TaxTotal" => array(
                "cbc:TaxAmount" => 2.73,
                "cac:TaxSubtotal" => array(
                    0 => array(
                        "cbc:TaxableAmount" => 15.15,
                        "cbc:TaxAmount" => 2.73,
                        "cac:TaxCategory" => array(
                            "cac:TaxScheme" => array(
                                "cbc:Name" => "KDV",
                                "cbc:TaxTypeCode" => "0015",
                            ),
                        ),
                    ),
                    1 => array(
                        "cbc:TaxableAmount" => 15.15,
                        "cbc:TaxAmount" => 2.73,
                        "cac:TaxCategory" => array(
                            "cac:TaxScheme" => array(
                                "cbc:Name" => "ÖİV",
                                "cbc:TaxTypeCode" => "0016",
                            ),
                        ),
                    ),
                ),
            ),
            "cac:LegalMonetaryTotal" => array(
                "cbc:LineExtensionAmount" => 15.15,
                "cbc:TaxExclusiveAmount" => 15.15,
                "cbc:TaxInclusiveAmount" => 17.88,
                "cbc:PayableAmount" => 17.88,
            ),
            "cac:InvoiceLine" => array(),
        );
        return $this;
    }

    public function getUuid($prefix = '')
    {
        $chars = md5(uniqid(mt_rand(), true));
        $parts = [substr($chars, 0, 8), substr($chars, 8, 4), substr($chars, 12, 4), substr($chars, 16, 4), substr($chars, 20, 12)];
        return $prefix . implode($parts, '-');
    }

    public function getFaturaNo($prefix='TRA'){

    }

    public function setSupplierCustomerParty($type = "Supplier", $data = array())
    {
        /* example data
           $type => Supplier veya  Customer  gönderilmelidir.
           $data = array(
            "Person" => array(
                "FirstName" => "",
                "FamilyName" => "",
            ),
           "Party" => array(
                "WebsiteURI" => "",
                "PartyIdentificationID" => "",
                "PartyName" => "",
                "Telephone" => "",
                "Telefax" => "",
                "ElectronicMail" => "",
                "PartyTaxSchemeName" => "",
            ),
            "PostalAddress" => array(
                "StreetName" => "",
                "BuildingNumber" => "",
                "CitySubdivisionName" => "",
                "CityName" => "",
                "PostalZone" => "",
                "CountryName" => "",
            ),
        );
        */

        $this->data["cac:Accounting" . $type . "Party"] = array(
            "cac:Party" => array(
                "cbc:WebsiteURI" => $data["Party"]["WebsiteURI"],
                "cac:PartyIdentification" => array(
                    "cbc:ID" => $data["Party"]["PartyIdentificationID"],
                ),
                "cac:PartyName" => array(
                    "cbc:Name" => $data["Party"]["PartyName"],
                ),
                "cac:PostalAddress" => array(
                    "cbc:ID" => 1,
                    "cbc:StreetName" => $data["PostalAddress"]["StreetName"],
                    "cbc:BuildingNumber" => $data["PostalAddress"]["BuildingNumber"],
                    "cbc:CitySubdivisionName" => $data["PostalAddress"]["CitySubdivisionName"],
                    "cbc:CityName" => $data["PostalAddress"]["CityName"],
                    "cbc:PostalZone" => $data["PostalAddress"]["PostalZone"],
                    "cac:Country" => array(
                        "cbc:Name" => $data["PostalAddress"]["CountryName"],
                    ),
                ),
                "cac:PartyTaxScheme" => array(
                    "cac:TaxScheme" => array(
                        "cbc:Name" => $data["Party"]["PartyTaxSchemeName"],
                    ),
                ),

                "cac:Contact" => array(
                    "cbc:Telephone" => $data["Party"]["Telephone"],
                    "cbc:Telefax" => $data["Party"]["Telefax"],
                    "cbc:ElectronicMail" => $data["Party"]["ElectronicMail"],
                ),
            ),
        );

        return $this;
    }

    public function setPaymentTerms($data = array())
    {
        $this->data["cac:PaymentTerms"] = array(
            "cbc:Note" => $data["Note"],
            "cbc:PaymentDueDate" => $data["PaymentDueDate"],
        );
        return $this;
    }

    public function setInvoiceLine($data = array())
    {
        /* example data
        $data = array(
            "cbc:ID" => 1,
            "cbc:InvoicedQuantity" => 101,
            "cbc:LineExtensionAmount" => 15.15,
            "cac:AllowanceCharge" => array(
                "cbc:ChargeIndicator" => "false",
                "cbc:MultiplierFactorNumeric" => 0.0,
                "cbc:Amount" => 0,
                "cbc:BaseAmount" => 15.15,
            ),
            "cac:TaxTotal" => array(
                "cbc:TaxAmount" => 2.73,
                "cac:TaxSubtotal" => array(
                    0 => array(
                        "cbc:TaxableAmount" => 15.15,
                        "cbc:TaxAmount" => 2.73,
                        "cbc:Percent" => 18.0,
                        "cac:TaxCategory" => array(
                            "cac:TaxScheme" => array(
                                "cbc:Name" => "KDV",
                                "cbc:TaxTypeCode" => "0015",
                            ),
                        ),
                    ),
                    1 => array(
                        "cbc:TaxableAmount" => 15.15,
                        "cbc:TaxAmount" => 2.73,
                        "cbc:Percent" => 18.0,
                        "cac:TaxCategory" => array(
                            "cac:TaxScheme" => array(
                                "cbc:Name" => "Öiv",
                                "cbc:TaxTypeCode" => "0016",
                            ),
                        ),
                    ),
                ),
            ),
            "cac:Item" => array(
                "cbc:Name" => "Tükenmez Kalem",
            ),
            "cac:Price" => array(
                "cbc:PriceAmount" => 10.0,
            ),
        );
        */

        if (count($data["TaxSubtotal"]) > 0) {
            foreach ($data["TaxSubtotal"] as $k => $v) {
                $TaxSubTotal[] = array(
                    "cbc:TaxableAmount" => $v["TaxableAmount"],
                    "cbc:TaxAmount" => $v["TaxAmount"],
                    "cbc:Percent" => $v["Percent"],
                    "cac:TaxCategory" => array(
                        "cac:TaxScheme" => array(
                            "cbc:Name" => $v["TaxSchemeName"],
                            "cbc:TaxTypeCode" => $v["TaxSchemeTaxTypeCode"],
                        ),
                    ),
                );
            }
        }

        $this->data["cac:InvoiceLine"][$data["ID"]] = array(
            "cbc:ID" => $data["ID"],
            "cbc:InvoicedQuantity" => $data["InvoicedQuantity"],
            "cbc:LineExtensionAmount" => $data["LineExtensionAmount"],
            "cac:AllowanceCharge" => array(
                "cbc:ChargeIndicator" => "false",
                "cbc:MultiplierFactorNumeric" => 0.0,
                "cbc:Amount" => 0,
                "cbc:BaseAmount" => $data["BaseAmount"],
            ),
            "cac:TaxTotal" => array(
                "cbc:TaxAmount" => $data["TaxAmount"],
                "cac:TaxSubtotal" => $TaxSubTotal,
            ),
            "cac:Item" => array(
                "cbc:Name" => $data["ItemName"],
            ),
            "cac:Price" => array(
                "cbc:PriceAmount" => $data["PriceAmount"],
            ),
        );

        return $this;
    }

    public function setAddNote($data = "")
    {
        /*
         * not eklemek için
         *
         * example data
         * $data=array(
         *      "ID" => 1;
                "Value" => "Gönderim Şekli: ELEKTRONIK";
         * );
         * */

        $this->data["cbc:Note"][$data["ID"]] = $data["Value"];
        return $this;
    }

    public function setPerson($type="Supplier",$data = "")
    {
        /*
         * Personel eklemek için
         *
         * example data
         *
         * $type => Supplier / Customer
         *
         * $data=array(
         *      "FirstName" => "Muhittin"
                "FamilyName" => "Gülap"
         * );
         * */

        $this->data["cac:Accounting".$type."Party"]["cac:Party"]["cac:Person"] = array(
            "cbc:FirstName" => $data["FirstName"],
            "cbc:FamilyName" => $data["FamilyName"],
        );
        return $this;
    }

    public function setTotals()
    {
        /*
         * aşağıdaki alanlar bu fonksiyonda otomatik olarak hesaplanır
        LineCountNumeric
        TaxTotal
        LegalMonetaryTotal
        */

        /* toplam satır adedi giriliyor.*/
        $this->data["cbc:LineCountNumeric"] = count($this->data["cac:InvoiceLine"]);

        $vergiler = array();
        $toplamTutar = $toplamVergiTutari = $toplamMalHizmetTutar = 0;
        if (count($this->data["cac:InvoiceLine"]) > 0) {
            foreach ($this->data["cac:InvoiceLine"] as $key => $Satir) {
                $toplamTutar += $Satir["cbc:LineExtensionAmount"];
                $toplamMalHizmetTutar += $Satir["cbc:LineExtensionAmount"];

                if (count($Satir["cac:TaxTotal"]["cac:TaxSubtotal"]) > 0) {
                    foreach ($Satir["cac:TaxTotal"]["cac:TaxSubtotal"] as $k => $Vergi) {
                        if (!@$vergiler[@$Vergi["cac:TaxCategory"]["cac:TaxScheme"]["cbc:TaxTypeCode"]]) {
                            $vergiler[@$Vergi["cac:TaxCategory"]["cac:TaxScheme"]["cbc:TaxTypeCode"]] = $Vergi;
                        } else {
                            $vergiler[@$Vergi["cac:TaxCategory"]["cac:TaxScheme"]["cbc:TaxTypeCode"]]["cbc:TaxAmount"] += @$Vergi["cbc:TaxAmount"];
                        }
                        $toplamTutar += @$Vergi["cbc:TaxAmount"];
                        $toplamVergiTutari += @$Vergi["cbc:TaxAmount"];
                    }
                }
            }
        }

        $this->data["cac:TaxTotal"]["cbc:TaxAmount"] = $toplamVergiTutari;
        $this->data["cac:TaxTotal"]["cac:TaxSubtotal"] = $vergiler;

        $this->data["cac:LegalMonetaryTotal"] = array(
            "cbc:LineExtensionAmount" => $toplamMalHizmetTutar,
            "cbc:TaxExclusiveAmount" => $toplamTutar - $toplamVergiTutari,
            "cbc:TaxInclusiveAmount" => $toplamTutar,
            "cbc:PayableAmount" => $toplamTutar,
        );

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

}
