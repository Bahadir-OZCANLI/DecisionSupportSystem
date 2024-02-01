<?php require "connect_db.php"; 

$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

// #####  LOGINN  #####
if (isset($_POST['login'])) {
        $username = $_POST["user"];
        $password = $_POST["passw"]; //md5 encryption for security reasons
        $check = $db->prepare("SELECT * FROM users WHERE user_name=? AND passwrd=?");
        $check->execute([$username,$password]);
        echo $say = $check->rowCount();
        if ($say == 1) {
                header("Location:index.php");
        } else {
                header("Location:login.php?durum=başarısız");
        }
}



// #### INDEX ####

        //INDEX.PHP # SİPARİS SAYILARI # 
$siparis_sayi = $db->prepare("SELECT sum(adet)AS sayi FROM siparisler");
$siparis_sayi->execute();
$siparis=$siparis_sayi->fetch(PDO::FETCH_ASSOC);

        //INDEX.PHP # MUSTERİ SAYILARI # 
$musteri_sayi = $db->prepare("SELECT count(*)as sayi FROM musteriler");
$musteri_sayi->execute();
$musteri=$musteri_sayi->fetch(PDO::FETCH_ASSOC);

        //INDEX.PHP # URUN SAYILARI # 
$urun_sayi = $db->prepare("SELECT COUNT(urunler.urun_id)as sayi FROM urunler");
$urun_sayi->execute();
$urun=$urun_sayi->fetch(PDO::FETCH_ASSOC);

        //INDEX.PHP # CİNSİYET ORANLARI #
$cinsiyet = $db->prepare("SELECT musteriler.cinsiyet as cins, COUNT(musteriler.musteri_id)AS sayi
FROM musteriler GROUP BY musteriler.cinsiyet");
$cinsiyet->execute();

        //INDEX.PHP # SATIŞ YAPILAN ŞEHİR SAYISI #
$il_sayi = $db->prepare("SELECT COUNT(*)as sayi FROM (SELECT COUNT(iller.il_id)as il FROM siparisler, iller, musteriler, ilceler WHERE siparisler musteri_id=musteriler.musteri_id AND musteriler.ilce_id=ilceler.ilce_id AND ilceler.il_id=iller.il_id GROUP BY iller.il_id)as iller");
$il_sayi->execute();
$il_say=$il_sayi->fetch(PDO::FETCH_ASSOC);

        //INDEX.PHP # KATEGORİLERE GÖRE SİPARİS SAYILARI # 
$kategori_satis=$db->prepare("SELECT kategoriler.kategori_ad as kategori, COUNT(siparisler.siparis_id)as sayi
FROM siparisler, urunler, kategoriler
WHERE siparisler.urun_id=urunler.urun_id AND urunler.urun_id=kategoriler.kategori_id GROUP BY kategoriler.kategori_id");
$kategori_satis->execute();

        //INDEX.PHP # AYLARA GÖRE SATIŞ SAYILARI # 
$aylik_satislar = $db->prepare("SELECT siparisler.tarih, COUNT(siparisler.siparis_id)as sayi
FROM siparisler
GROUP BY siparisler.tarih");
$aylik_satislar->execute();

        //INDEX.PHP # BÖLGELERE GÖRE SATIŞ SAYILARI # 
$bolge_grup = $db->prepare("SELECT bolge.bolge_ad as bolge, SUM(siparisler.adet)AS sayi
FROM siparisler INNER JOIN musteriler ON siparisler.musteri_id = musteriler.musteri_id
                INNER JOIN ilceler ON musteriler.ilce_id=ilceler.ilce_id
                INNER JOIN iller ON ilceler.il_id=iller.il_id  
                INNER JOIN bolge on iller.bolge_id=bolge.bolge_id
GROUP BY bolge.bolge_id");
$bolge_grup->execute();


// #### BOLGELER ####

        //BOLGELER.PHP # BÖLGELERE VE ŞEHİRLERE GÖRE SATIŞ SAYILARI # 
$bolge_satis = $db->prepare("SELECT bolge.bolge_ad as bolge, iller.il_ad as il, SUM(siparisler.adet)AS sayi, musteri_sayi.musteri as musteri
FROM siparisler INNER JOIN musteriler ON siparisler.musteri_id = musteriler.musteri_id
                INNER JOIN ilceler ON musteriler.ilce_id=ilceler.ilce_id
                INNER JOIN iller ON ilceler.il_id=iller.il_id  
                INNER JOIN bolge on iller.bolge_id=bolge.bolge_id
                INNER JOIN musteri_sayi ON musteri_sayi.il_id=iller.il_id
GROUP BY iller.il_id");
$bolge_satis->execute();

        //BOLGELER.PHP # İLLER # 
$iller = $db->prepare("SELECT iller.il_id as id, iller.il_ad as il, bolge.bolge_ad as bolge, sube_sayi.sube
FROM iller, bolge, sube_sayi
WHERE iller.bolge_id=bolge.bolge_id AND sube_sayi.il_id=iller.il_id");
$iller->execute();

if (isset($_GET['cit'])) {
        $cit = $_GET["cit"];
        $sube_ekle = $db->prepare("UPDATE ilceler SET sube = 1 WHERE ilceler.ilce_id = $cit");
        $sube_ekle->execute();
        $sehir = $db->prepare("SELECT ilceler.il_id as id FROM ilceler WHERE ilceler.ilce_id=$cit");
        $sehir->execute();
        $seh = $sehir->fetch(PDO::FETCH_ASSOC);
        $seh1 = $seh['id'];
        header("Location:b_show.php?id=$seh1");
} 
if (isset($_GET['sube_sil'])) {
        $cikart = $_GET["sube_sil"];
        $sube_cikar = $db->prepare("UPDATE ilceler SET sube = 0 WHERE ilceler.ilce_id = $cikart");
        $sube_cikar->execute();
        $sehir = $db->prepare("SELECT ilceler.il_id as id FROM ilceler WHERE ilceler.ilce_id=$cikart");
        $sehir->execute();
        $seh = $sehir->fetch(PDO::FETCH_ASSOC);
        $seh1 = $seh['id'];
        header("Location:b_show.php?id=$seh1");
}



// #### ÜRÜNLER ####

        //URUNLER.PHP # SATIŞ SAYILARINA GÖRE ÜRÜNLER # 
$urunler = $db->prepare("SELECT urunler.urun_id, urunler.urun_ad, urunler.puan, urunler.fiyat, sum(adet)AS adet
FROM siparisler, urunler 
WHERE siparisler.urun_id=urunler.urun_id
GROUP BY urunler.urun_id
ORDER BY adet DESC
LIMIT 20");
$urunler->execute();

        //URUNLER.PHP # YENI URUN KAYIT #
if (isset($_GET["dene"])) {
        $urun_ekle = $db->prepare("INSERT INTO urunler set 
                urun_ad=:urun_ad,
                puan=:puan,
                fiyat=:fiyat,
                kategori_id=:kategori_id
                ");
        $insert = $urun_ekle->execute(array(
                'urun_ad' => $_GET["urun"],
                'puan' => $_GET["puan"],
                'fiyat' => $_GET["fiyat"],
                'kategori_id' => $_GET["kategori"] 
        ));
        
        if ($insert) {
                header("Location:urunler.php");
        } else {
                header("Location:urunler.php");
        }
}




?>