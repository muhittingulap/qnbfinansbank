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
Dokümantasyon dosyalarına ulşamak için [tıklayınız.](https://www.qnbefinans.com/tr/e-donusum-bilgi-merkezi/api-teknik)

## Kurulum

    $ composer require muhittingulap/qnbfinansbank
    
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

## Projenizde Kullanma

    <?php     
    include('vendor/autoload.php');
    
## Bildirilecek Datanın Hazırlanması

#### - Başlangıç datasının hazırlanması

E-Fatura ve E-Arşiv için ortak data hazılarması gerekmektedir. Aşağıdaki kodda görülen şekilde başlangıç datası oluşturulur ve ardından eklemeler yapılarak son data çıktısı alınacatır.Bu Alınan data daha sonrasında ister E-Arşiv istersenizde E-Faturaya gönderebileceksiniz.

    <?php     
    
    $data = new \EFINANS\Component\data();
    
     /* otomaik benzersiz UUID oluşturur. */
    $uuid=$data->getUuid();
    
    $veri = array(
        "ID"" => "",
        "ProfileID" => "",
        "UUID" => $uuid,
        "IssueDate" => "",
        "IssueTime" => "",
    );
    
    /* hazırlanan başlangıç datasını set ediyoruz */
    $data->setStartData($veri);

**Parameters:**

| Parametre      | Açıklama |
| ------         | -------- |
| ID             | Fatura numaranız 'TRA2020000000001' şeklinde boş bırakılması halinde finansbank tarafından otomatik oluşturulur. Finansbank'tan e-arşiv ve e-fatura için seri no belirleme için tıklayınız.??  |
| ProfileID      | 'TICARIFATURA','TEMELFATURA' veya 'EARSIVFATURA' yazabilirsiniz. E-Arşiv içinbu bilgi boş olduğunda otomaik oluşturulur.         |
| UUID           | Benzersiz işlem numarasıdır. Otomatik oluşturulması için yukarıda örnek kod bulunur.         |
| IssueDate      | Fatura Tarihi YYYY-MM-DD şeklinde girilmelidir. örn: 2020-07-01         |
| IssueTime      | Fatura Saati HH:ii:ss  şeklinde girilmelidir. 20:18:00       |

#### - Not Bilgilerinin Eklenmesi

Her Bir Notu set ediyoruz. Notlar için dökümanı inceleyiniz.

    <?php     
    
    $veri = array(
            "ID" => 1, /* not benzersiz id si */
            "Value" => "", /* not tu buraya yazınız */
    );
    $data->setAddNote($veri);

**Parameters:**

| Parametre                    | Açıklama |
| ---------------------------- | -------- |
| Gönderim Şekli: ELEKTRONIK   | Bu not E-Arşiv için zorunludur. Diğer not lar için dökümantasyonu inceleyiniz. |
| #EFN_SERINO_TERCIHI#EF#      | Bu not E-Fatura No otomatik üretilmesi istenmesi durumunda başlangıç seri no maksimum 2 hane gönderilmelidir. Bu örnekte : EF |


#### - Satıcı ve Alıcı Bilgilerinin Eklenmesi

Aynı bu set işlemini 'Customer' içinde alıcı bilgilerini yazıp yapmalı ve aşağıdaki 'Supplier' yazan yeri değiştirip set etmelisiniz.

    <?php     
    
      $veri = array(
        "Party" => array(
            "WebsiteURI" => "",
            "PartyIdentificationID" => ",
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

#### - Satıcı ve Alıcı Personel  Bilgilerinin Eklenmesi

'Customer' için mutlaka set edin çünkü TC kimlik no olduğunda zorunludur. Supplier için zorunlu değil ama girebilirsiniz

    <?php     
    
       $veri = array(
          "FirstName" => "",
          "FamilyName" => "",
       );
              
       $data->setPerson('Customer', $veri);

**Parameters:**

| Parametre                    | Açıklama |
| ---------------------------- | -------- |
| FirstName                    | Ad bilgisi |
| FamilyName                   | Soyad bilgisi |


#### - Ödeme Koşulları Girilmesi 

Bilgi notu şeklinde girilemsi zorunlu değildir.

    <?php     
    
        $veri = array(
           "Note" => "Ziraat Bankası",
           "PaymentDueDate" => "2020-07-30",
       );
       $data->setPaymentTerms($veri);

**Parameters:**

| Parametre                    | Açıklama |
| ---------------------------- | -------- |
| Note                         | Not bilgisi |
| PaymentDueDate               | Son Ödeme Tarihi bilgisi. örn: 2020-07-30 |


#### - Fatura Satırlarının Girilmesi 

Bu adım açıklamasının hazırlanması devam etmektetir.



## Destek

Bir hata bulduğunuzu düşünüyorsanız, lütfen [GitHub issue tracker](https://github.com/muhittingulap/qnbfinansbank/issues) kullanarak bildirin,
ya da daha iyisi, kütüphaneyi çatallayın ve bir çekme isteği gönderin.

## License

MIT Lisansı (MIT). Daha fazla bilgi için lütfen [License File](LICENSE) bakın.
