<p align="center"><img src="https://raw.githubusercontent.com/muhittingulap/qnbfinansbank/master/images/80864283-581ea400-8c8a-11ea-8bdd-ea37da892af8.jpg" width="300"></p>

<h3 align="center">E-Fatura / E-Arşiv<br>QNB Finansbank</h3>

<p align="center">QNB Finansbank E-Fatura ve E-Arşiv PHP Api</p>

<p align="center">
  <a href="https://packagist.org/packages/muhittingulap/qnbfinansbank"><img src="https://poser.pugx.org/muhittingulap/qnbfinansbank/v/stable.svg" alt="Latest Stable Version">
  <a href="https://packagist.org/packages/muhittingulap/qnbfinansbank"><img src="https://poser.pugx.org/muhittingulap/qnbfinansbank/d/total.svg" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/muhittingulap/qnbfinansbank"><img src="https://poser.pugx.org/muhittingulap/qnbfinansbank/license.svg" alt="License"></a>
</p>

<hr>


QNB Finansbank web servisleri için PHP ile yazılmış olan bu kütüphane ile E-Arşiv, E-Fatura bildirim işlemlerini yapabilirsiniz.

## Dokümantasyon (QNB FinansBank)
Dokümantasyon dosyalarına ulaşmak için [tıklayınız.](https://www.qnbefinans.com/tr/e-donusum-bilgi-merkezi/api-teknik)

## Kurulum

    $ composer require muhittingulap/qnbfinansbank
 
## Projenizde Kullanma
```php
    <?php     
    include('vendor/autoload.php');
```  
## Desteklenen Yöntemler

#### - E-Fatura

* **setEFatura()** : E-Fatura bildirme işlemi yapabilirsiniz.
* **getFaturaNo()** : Portaldaki en son gönderilmiş fatura numaranıza göre sonraki fatura numarasını üretir.
* **getEfaturaKullanicisi()** : İlgili kişinin vergi numarasından E-Fatura mükellefi olup olmadığı bilgisini getirir.
* **gidenBelgeDurumSorgula()** : Giden faturalarınızı durumunu sorgulayabilirsiniz..
* **gidenBelgeleriListele()** : Gönderilmiş faturalarınızı listeleyebilirsiniz.
* **gelenBelgeleriListele()** : Gelen faturalarınızı listeleyebilirsiniz.

#### - E-Arşiv

* **setEArsiv()** : E-Arşiv bildirme işlemi yapabilirsiniz.
* **getFaturaNo()** : Portaldaki en son gönderilmiş fatura numaranıza göre sonraki fatura numarasını üretir.
* **getFaturaSorgula()** : Fatura detay görüntüleme servisidir. Faturanın PDF/HTML bilgisi ve detayları döner.
* **callFaturaIptalEt()** : Fatura iptal etme servisidir.

     
## Bildirilecek Datanın Hazırlanması (UBL)

#### - Başlangıç datasının hazırlanması

E-Fatura ve E-Arşiv için ortak data hazılarması gerekmektedir. Aşağıdaki kodda görülen şekilde başlangıç datası oluşturulur ve ardından eklemeler yapılarak son data çıktısı alınacatır.Bu Alınan data daha sonrasında ister E-Arşiv istersenizde E-Faturaya gönderebileceksiniz.
```php
    <?php     
    
    $data = new \EFINANS\Component\data();
    
     /* otomaik benzersiz UUID oluşturur. */
    $uuid=$data->getUuid();
    
    $veri = array(
        "ID" => "",
        "ProfileID" => "",
        "UUID" => $uuid,
        "IssueDate" => "",
        "IssueTime" => "",
    );
    
    /* hazırlanan başlangıç datasını set ediyoruz */
    $data->setStartData($veri);
```
**Parameters:**

| Parametre      | Açıklama |
| ------         | -------- |
| ID             | Fatura numaranız 'TRA2020000000001' şeklinde boş bırakılması halinde finansbank tarafından otomatik oluşturulur. Finansbank'tan e-arşiv ve e-fatura için seri no belirleme için [tıklayınız.](https://github.com/muhittingulap/qnbfinansbank#e-fatura-ve-e-ar%C5%9Fiv-ek-methodlar)  |
| ProfileID      | 'TICARIFATURA','TEMELFATURA' veya 'EARSIVFATURA' yazabilirsiniz. E-Arşiv içinbu bilgi boş olduğunda otomaik oluşturulur.         |
| UUID           | Benzersiz işlem numarasıdır. Otomatik oluşturulması için yukarıda örnek kod bulunur.         |
| IssueDate      | Fatura Tarihi YYYY-MM-DD şeklinde girilmelidir. örn: 2020-07-01         |
| IssueTime      | Fatura Saati HH:ii:ss  şeklinde girilmelidir. 20:18:00       |

#### - Not Bilgilerinin Eklenmesi

Her Bir Notu set ediyoruz. Notlar için dokümanı inceleyiniz.
```php
    <?php     
    
    $veri = array(
            "ID" => 1, /* not benzersiz id si */
            "Value" => "", /* not tu buraya yazınız */
    );
    $data->setAddNote($veri);
```

Örnek:

```php
    // e faturada zorunluluk yoktur. e arşiv için zorunlu olduğundan aşağıdaki notu eklemeniz yeterlidir.
    <?php     
    
    $veri = array(
            "ID" => 1,
            "Value" => "Gönderim Şekli: ELEKTRONIK",
    );
    $data->setAddNote($veri);
```
**Parameters:**

| Parametre                    | Açıklama |
| ---------------------------- | -------- |
| Gönderim Şekli: ELEKTRONIK   | Bu not E-Arşiv için zorunludur. Diğer not lar için dokümantasyonu inceleyiniz. |
| #EFN_SERINO_TERCIHI#EF#      | Bu not E-Fatura No otomatik üretilmesi istenmesi durumunda başlangıç seri no maksimum 2 hane gönderilmelidir. Bu örnekte : EF |


#### - Satıcı ve Alıcı Bilgilerinin Eklenmesi

Aynı bu set işlemini 'Customer' içinde alıcı bilgilerini yazıp yapmalı ve aşağıdaki 'Supplier' yazan yeri değiştirip set etmelisiniz.
```php
    <?php     
    
      $veri = array(
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
            "CountryName" => "Türkiye",
        ),
    );    
    $data->setSupplierCustomerParty('Supplier', $veri);
```
**Parameters:**

| Parametre                    | Açıklama |
| ---------------------------- | -------- |
| WebsiteURI                   | Web site adresi bilgisi|
| PartyIdentificationID        | Vergi no veya TC kimlik no bilgisi|
| PartyName                    | Ad Soyad veya Ünvan bilgisi|
| Telephone                    | Telefon numarası bilgisi|
| Telefax                      | Fax numarası bilgisi|
| ElectronicMail               | Email adresi bilgisi|
| PartyTaxSchemeName           | Vergi Dairesi (varsa) bilgisi|
| StreetName                   | Açık Adresi bilgisi|
| BuildingNumber               | Kapı Numarası bilgisi|
| CitySubdivisionName          | İlçe bilgisi|
| CityName                     | İl bilgisi|
| PostalZone                   | Posta Kodu bilgisi|
| CountryName                  | Ülke bilgisi|

#### - Satıcı ve Alıcı Personel Bilgilerinin Eklenmesi

'Customer' için mutlaka set edin çünkü TC kimlik no olduğunda zorunludur. Supplier için zorunlu değil ama girebilirsiniz
```php
    <?php     
    
       $veri = array(
          "FirstName" => "",
          "FamilyName" => "",
       );
              
       $data->setPerson('Customer', $veri);
```
**Parameters:**

| Parametre                    | Açıklama |
| ---------------------------- | -------- |
| FirstName                    | Ad bilgisi |
| FamilyName                   | Soyad bilgisi |


#### - Ödeme Koşulları Girilmesi 

Bilgi notu şeklinde girilemsi zorunlu değildir.
```php
    <?php     
    
        $veri = array(
           "Note" => "Ziraat Bankası",
           "PaymentDueDate" => "2020-07-30",
       );
       $data->setPaymentTerms($veri);
```
**Parameters:**

| Parametre                    | Açıklama |
| ---------------------------- | -------- |
| Note                         | Not bilgisi |
| PaymentDueDate               | Son Ödeme Tarihi bilgisi. örn: 2020-07-30 |


#### - Fatura Satırlarının Girilmesi 

Bu adım da her satır için bu işlemi tekrarlamanız veya bir döngü içerisinde tüm fatura satırlarınızı set etmeniz gerekmektedir.
```php
    <?php     
            
    $veri = array(
        "ID" => "", 
        "InvoicedQuantity" => "",
        "LineExtensionAmount" => "",
        "BaseAmount" => 0,
        "TaxAmount" => 0,
        "TaxSubtotal" => array(
            0 => array(
                "TaxableAmount" => 0,
                "TaxAmount" => 0,
                "Percent" => 0,
                "TaxSchemeName" => "KDV",
                "TaxSchemeTaxTypeCode" => "0015",
            ), 
            1 => array(
                "TaxableAmount" => 0,
                "TaxAmount" => 0,
                "Percent" => 0,
                "TaxSchemeName" => "Özel İletişim Vergisi",
                "TaxSchemeTaxTypeCode" => "4080",
            ),
        ),
        "ItemName" => "",
        "PriceAmount" => 0,
    );
    
    $data->setInvoiceLine($veri);
```
**Parameters:**

| Parametre                                   | Açıklama |
| --------------------------------------------| -------- |
| ID                                          | Satır sıra no veya benzersiz ID her satır için uniq olmalı |
| InvoicedQuantity                            | Ürün Adet |
| LineExtensionAmount                         | Satır toplam vergiler hariç |
| BaseAmount                                  | Satır toplam vergiler dahil |
| TaxAmount                                   | Vergiler toplam tutarı |
| ItemName                                    | Satılan ürün adı |
| PriceAmount                                 | Satır birim fiyat |
| TaxSubtotal -> TaxableAmount                | Vergi toplam matrah |
| TaxSubtotal -> TaxAmount                    | Vergi toplam tutarı |
| TaxSubtotal -> Percent                      | Vergi Oranı |
| TaxSubtotal -> TaxSchemeName                | Vergi başlığı |
| TaxSubtotal -> TaxSchemeTaxTypeCode         | Vergi Kodu (dokümantasyon inceleyin) |


#### - Datanın Son Halinin Alınması

Bu adım da fatura satırlarından otomatik olarak  parasal toplamların ve vergisel toplamların heaplanıp, E-Arşiv ve E-Fatura ya gönderileck datanın son halinin, xml öncesi array datasının alıp bir değişkene atıyoruz ve artık bildirim aşamasına geçebiliriz.
```php
    <?php  
       
    $qnb_data = $data->setTotals()->getData();
```

## Servis Bağlantı İşlemleri

Web servislerine bağlantı için TEST veya CANLI ortam username ve password bilgilerini tanımlıyoruz. Bu bilgiler qnbfinansbank tarafından alınır. Email atıp bilgileri alabilirsiniz.
```php
    <?php  
    
    $config = new \EFINANS\Config\config();
    
    $options=$config->setUsername("CANLI_VEYA_TEST_USERNAME")
    ->setpassword("CANLI_VEYA_TEST_USERNAME")
    ->setvergiTcKimlikNo("CANLI_VEYA_TEST_VERGI_KIMLIKNO")
    ->setUrl("CANLI_VEYA_TEST_URL")
    ->getConfig();
```
**Parameters:**

| Parametre                                   | Açıklama |
| --------------------------------------------| -------- |
| setUsername                                 | Belirlenen kullanıcı adınız (bildirimi yapacak firmanın qnbfinansbank hesap bilgisi) |
| setpassword                                 | Belirnenen şifreniz (bildirimi yapacak firmanın qnbfinansbank hesap bilgisi) |
| setvergiTcKimlikNo                          | Vergi kimlik numaranız (bildirimi yapacak firmanın qnbfinansbank hesap bilgisi) |
| setUrl                                      | E-Arşiv veya E-Fatura, (test veya canlı) Web Servis Url si (qnbfinansbank tarafından alınır.) |
| getConfig                                   | Son olarak oluşturulan ayarların array olarak alıyoruz.  |

Test Url

- E-Arşiv : https://earsivtest.efinans.com.tr/earsiv/ws/EarsivWebService?wsdl
- E-Fatura : https://erpefaturatest.cs.com.tr:8443/efatura/ws/connectorService?wsdl

## E-Fatura İşlemleri

E-Faturaya bildirim işlemlerini yapabilmek için class ı çağırıyoruz ve set işlemlerine başlıyoruz.
```php
    <?php  
    
    $efatura = new \EFINANS\Libraries\efatura();
    
    $belgeNo=uniqid();
    
    $return= $efatura->setConfig($options)
    ->setStart()
    ->setBelgeNo($belgeNo)
    ->setData($qnb_data)
    ->setEFatura();
    
    print $return->belgeOid; /* e faturadan bildirim sonrası dönen key */
```
**REQUEST Parameters:**

| Parametre     | Açıklama |
| --------------| -------- |
| setConfig     | Yukarıda ayarladığımız ayar bilgileri gönderiyoruz. |
| setStart      | Servise başlangıç tetiği veriyoruz.(ilgili ayarlara göre soap başlatır.) |
| setBelgeNo    | Varitabanınızda fatura Id si veya uniq bir değer olabilir. |
| setData       | Yukarıda ilk önce hazırladığıız datayı gönderiyoruz. |
| setEFatura    | Son olarak E-Fatura bildirim servisini çalıştırıyoruz. |

**RESPONSE Parameters:**

| Parametre     | Açıklama |
| --------------| -------- |
| belgeOid      | Bildirim sonrası bize qnbfinansbank tarafından gönderilen benzersiz bildirim keyi bu keyi kullanarak e fatura sorgulama işlemi yapacağız. |


## E-Arşiv İşlemleri

E-Arşiv bildirim işlemlerini yapabilmek için class ı çağırıyoruz ve set işlemlerine başlıyoruz.
```php
    <?php  
    
    $earsiv = new \EFINANS\Libraries\earsiv();
    
    $return= $earsiv->setConfig($options)
    ->setStart()
    ->setSube("DFLT")
    ->setKasa("DFLT")
    ->setData($qnb_data)
    ->setEArsiv();    

    print '<pre>'; /* düzgün çıktı görseli için */
    print_r($return);
``` 

**REQUEST Parameters:**

| Parametre     | Açıklama |
| --------------| -------- |
| setConfig     | Yukarıda ayarladığımız ayar bilgileri gönderiyoruz. (eğer e arşiv için başka ayarlar varsa option değişkenini o ayarlara göre tekrar oluşturun.) |
| setStart      | Servise başlangıç tetiği veriyoruz.(ilgili ayarlara göre soap başlatır.) |
| setSube       | QNB Finansbank portal yapılandırma ayarlarında WEB SERVİS için tanımlanan Şube bilgisi |
| setKasa       | QNB Finansbank portal yapılandırma ayarlarında WEB SERVİS için tanımlanan Kasa bilgisi |
| setData       | Yukarıda ilk önce hazırladığıız datayı gönderiyoruz. |
| setEArsiv     | Son olarak E-Arşiv bildirim servisini çalıştırıyoruz. |

**RESPONSE Parameters:**

| Parametre             | Açıklama |
| ----------------------| -------- |
| resultCode            | İşlemin durum kodu başarılı için (AE00000) diğer kodlar için dokümantasyon inceleyin. |
| resultText            | İşlem sonucu mesajı |
| resultExtra->entry    | Başarılı olduğunda dönen kullanabileceğiniz parametreler array şeklide döner. |
|        --faturaURL    | entry arrayi içinde fatura url si |
|        --uuid         | entry arrayi içinde gönderdiğinzi UUID bilgisi |
|        --faturaNo     | entry arrayi içinde sizin bildirdiğiniz veya otomatik bildirilmesini istediğiniz fatura no örn. TMA202000000001  |


## E-Fatura ve E-Arşiv Ek Methodlar

E-Fatura veya E-Arşiv için ihtiyaca göre ek methodlar kullanabilirsiniz.

#### - E-Fatura
* **getFaturaNo()** : Portaldaki en son gönderilmiş fatura numaranıza göre sonraki fatura numarasını üretir.

E-Fatura Numarası üretmek için <b>getFaturaNo()</b> Methodu ile geriye dönen değeri <b>$ID</b> değişkenine alıp başlangıç datasına gönderdiğimizde otomatik değil bizim gönderdiğimiz fatura No yu kabul eder. 
```php
    <?php      
    
     $efatura = new \EFINANS\Libraries\efatura();
                
     $ID= $efatura->setConfig($options)
     ->setStart()
     ->getFaturaNo("BASLANGIC_SERI_NO") ; 
``` 
**Parameters:**

| Parametre                           | Açıklama |
| ------------------------------------| -------- |
| setConfig                           | Servis ayalarını set ediyoruz yukarıdaki gibi |
| setStart                            | Servisi başlatıyoruz |
| getFaturaNo                         | Fatura no üretmesi için başlangıç seri no gönderiyoruz örn. TRE |

* **getEfaturaKullanicisi()** : İlgili kişinin vergi numarasından E-Fatura mükellefi olup olmadığı bilgisini getirir.
  
Hazırladığımı datanın E-Fatura ya mı E-Arşiv emi gönderecileceğini tespit etmek için  <b>getEfaturaKullanicisi()</b> methodu ile kontrol ediyoruz. Eğer (true veya 1) bir değer dönerse e fatura mükellefi demektir ve hazırladığımız datanın E-Fatura ya gönderileceği anlaşılmaktadır. Aksi durumda ise E-Arşive gönderebilirsiniz.
```php
    <?php      
    
     $efatura = new \EFINANS\Libraries\efatura();
                
     $return= $efatura->setConfig($options)
     ->setStart()
     ->getEfaturaKullanicisi("VERGI_VEYA_TC_KIMLIK_NO") ; 
```   
**Parameters:**

| Parametre                           | Açıklama |
| ------------------------------------| -------- |
| setConfig                           | Servis ayalarını set ediyoruz yukarıdaki gibi |
| setStart                            | Servisi başlatıyoruz |
| getEfaturaKullanicisi               | E-Fatura mükellefimi değilmi öğrenebilmek için fatura sahibinin TC veya VERGİ KİMLİK NO su. |


#### - E-Arşiv

* **getFaturaNo()** : En Son üretilen e arşiv fatura nosu her çalıştığında otomatik olarak counter artarak döner.

E-Arşiv Numarası üretmek için <b>getFaturaNo()</b> Methodu ile geriye dönen değeri <b>$ID</b> değişkenine alıp başlangıç datasına gönderdiğimizde otomatik değil bizim gönderdiğimiz fatura No yu kabul eder. E-Arşiv olduğu için her çalıştırıldığında yeni bir numara üretir. 
```php
    <?php      
    
    $earsiv = new \EFINANS\Libraries\earsiv();
                
     $ID= $earsiv->setConfig($options)
     ->setStart()
     ->setUuid($data->getUuid())
     ->getFaturaNo("BASLANGIC_SERI_NO");
```
**Parameters:**

| Parametre                           | Açıklama |
| ------------------------------------| -------- |
| setConfig                           | Servis ayalarını set ediyoruz yukarıdaki gibi |
| setStart                            | Servisi başlatıyoruz |
| setUuid                             | Başlangıç datası hazırladığımızdaki uuid oluşturma işlemi bu uid le sonrasında e arşiv faturamınız bildireceğiz. |
| getFaturaNo                         | Fatura no üretmesi için başlangıç seri no gönderiyoruz örn. TRA |


## Otomatik Bildirim İşlemleri (Hazırlanıyor..)

E-Fatura veya E-Arşiv gönderisinin otomatik bir şekilde tespit edilip tek bir class üzerinden göndermek siterseniz aşağıdaki yöntemi kullanabilirsiniz.


## Destek

Bir hata bulduğunuzu düşünüyorsanız, lütfen [GitHub issue tracker](https://github.com/muhittingulap/qnbfinansbank/issues) kullanarak bildirin,
ya da daha iyisi, kütüphaneyi çatallayın ve bir çekme isteği gönderin.

## License

MIT Lisansı (MIT). Daha fazla bilgi için lütfen [License File](LICENSE) bakın.
