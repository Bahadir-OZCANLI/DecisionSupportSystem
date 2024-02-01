<?php require 'process.php' ; ?>
        <!-- ## SQL SORGULAR ## -->
<?php
        // ## AYLARA GÖRE KATEGORİ SATIŞ ##    
    $aylar=$db->prepare("SELECT tarih FROM siparisler GROUP BY tarih");
    $aylar->execute();
    if (!isset($_GET['time'])) {    
        $kat_satislar = $db->prepare("SELECT kategoriler.kategori_ad as kategori, COUNT(siparisler.siparis_id)as sayi, siparisler.tarih
        FROM siparisler, urunler, kategoriler
        WHERE siparisler.urun_id=urunler.urun_id AND urunler.kategori_id=kategoriler.kategori_id
        GROUP BY kategoriler.kategori_id");
        $kat_satislar->execute();
    }elseif($_GET['time']=='Hepsi'){
        $kat_satislar = $db->prepare("SELECT kategoriler.kategori_ad as kategori, COUNT(siparisler.siparis_id)as sayi
        FROM siparisler, urunler, kategoriler
        WHERE siparisler.urun_id=urunler.urun_id AND urunler.kategori_id=kategoriler.kategori_id
        GROUP BY kategoriler.kategori_id");
        $kat_satislar->execute();
    }
    else {
        $tarih = $_GET['time'];
        $kat_satislar = $db->prepare("SELECT kategoriler.kategori_ad as kategori, COUNT(siparisler.siparis_id)as sayi
        FROM siparisler, urunler, kategoriler
        WHERE siparisler.urun_id=urunler.urun_id AND urunler.kategori_id=kategoriler.kategori_id AND siparisler.tarih='$tarih'
        GROUP BY kategoriler.kategori_id");
        $kat_satislar->execute();
    }
        // ## KATEGORİLERE GÖRE AYLIK SATIŞ ##
    $kategoriler=$db->prepare("SELECT kategori_id as id, kategori_ad as kategori FROM kategoriler");
    $kategoriler->execute();

    if (!isset($_GET['kat'])) {  
        $kate_aylik_satis = $db->prepare("SELECT siparisler.tarih, COUNT(siparisler.siparis_id)as sayi
        FROM siparisler
        GROUP BY siparisler.tarih");
        $kate_aylik_satis->execute(); 
    }elseif($_GET["kat"]=='Hepsi'){
        $kate_aylik_satis = $db->prepare("SELECT siparisler.tarih, COUNT(siparisler.siparis_id)as sayi
        FROM siparisler
        GROUP BY siparisler.tarih");
        $kate_aylik_satis->execute(); 
        
    }else{
        $kate = $_GET["kat"];
        $kate_aylik_satis = $db->prepare("SELECT kategoriler.kategori_ad as ad, siparisler.tarih, COUNT(siparisler.siparis_id)as sayi
        FROM siparisler, kategoriler, urunler
        WHERE siparisler.urun_id=urunler.urun_id AND urunler.kategori_id=kategoriler.kategori_id AND kategoriler.kategori_id=$kate GROUP BY siparisler.tarih");
        $kate_aylik_satis->execute();
        $kategoriler1=$db->prepare("SELECT kategori_ad as ad FROM kategoriler WHERE kategoriler.kategori_id=$kate");
        $kategoriler1->execute();
        $kat1 = $kategoriler1->fetch(PDO::FETCH_ASSOC);
    }

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="reset.css">
    <title>Ana Sayfa</title>
    <!-- ### GRAFİKLER ### -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

            <!-- # KATEGORİ SATIŞ CHARTS # -->
    <script type="text/javascript">
        google.charts.load("current", {packages:['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ["Kategori", "Satış"],
                <?php 
                while ($kat_satis = $kat_satislar->fetch(PDO::FETCH_ASSOC)) {
                    echo "['".$kat_satis["kategori"]."', ".$kat_satis["sayi"]."],";
                }
                ?>
        ]);

            var view = new google.visualization.DataView(data);
                view.setColumns([0, 1,
                        { calc: "stringify",
                            sourceColumn: 1,
                            type: "string",
                            role: "annotation" }]);

            var options = {
                title: "Kategori Satış Miktarları",
                hAxis:{title: "Kategoriler", slantedText:true, slantedTextAngle:45 },
        };
            var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
            chart.draw(view, options);
        }
    </script> 
            <!-- # KATEGORİ SATIŞ CHARTS # -->
                    <!-- ------- -->
            <!-- # KATEGORİLERE GÖRE AYLIK SATIŞLAR CHARTS # -->
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
            ['Aylar', 'Satis'],
            <?php 
                while ($kat_satis = $kate_aylik_satis->fetch(PDO::FETCH_ASSOC)) {
                    echo "['".$kat_satis["tarih"]."', ".$kat_satis["sayi"]."],";
                }
                ?>
            ]);

            var options = {
            title: 'Kategori - Aylık Satış Miktarları',
            curveType: 'function',
            legend: { position: 'right' },
            hAxis:{title: "Aylar" , slantedText:true, slantedTextAngle:30 },
            colors:["red"],
            pointSize: 10,
                series: {
                    0: { pointShape: 'triangle' },}
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);
        }
    </script>
            <!-- # KATEGORİLERE GÖRE AYLIK SATIŞLAR CHARTS # -->

    <!-- ### GRAFİKLER ### -->

</head>
<body class="bg-body-tertiary">
    <div class="container">
        <div class="row">
            <!-- ### LEFT ASIDE MENU ### -->
            <div class="col-2 h-100 ms-1 text-center position-absolute start-0">
                <img class="col-sm-12 mt-2 border border-2 rounded-2 border-primary" src="kds.png" alt="Logo">
                <a href="/" class="col-sm-9 mt-5 text-primary btn btn-light border border-primary badge"  type="button">ANA SAYFA</a>
                <a href="satis.php" class="active col-sm-9 mt-3 text-primary btn btn-light border border-primary badge"  type="button">SATIŞLAR</a>
                <a href="urunler.php" class="col-sm-9 mt-3 text-primary btn btn-light border border-primary badge"  type="button">ÜRÜNLER</a>
                <a href="bolgeler.php" class="col-sm-9 mt-3 text-primary btn btn-light border border-primary badge"  type="button">BÖLGELER</a>
                <a href="login.php" class="col-sm-9 mt-3 text-primary btn btn-light border border-primary badge"  type="button">ÇIKIŞ YAP</a>
            </div>

            <!-- ### CONTENTT ### -->            
            <div style="position:relative;left:180px;top: 100px;" class="col-11 h-100 mt-2 bg-secondary-subtle text-center border border-2 rounded-2 border-primary ">
                
                <!-- # FORM-CHARTS # -->
                <div class="col-12 mb-3 mt-3">
                    <!-- # FORM-SELECT # -->
                    
                    <form class="mb-2" action="" method="get">
                        <div class="d-flex">
                            <select class="w-25 form-select form-select-sm border-primary" aria-label="Small select example" name="time">
                                <option value="Hepsi" selected>Ay Seç</option>
                                <?php 
                                        while ($ay = $aylar->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value=".$ay["tarih"].">".$ay["tarih"]."</option>";
                                        $a++;  }                               
                                ?>
                            </select>
                            <select class="w-25 form-select form-select-sm border-primary position-absolute start-50" aria-label="Small select example" name="kat">
                                <option value="Hepsi" selected>Kategori Seç</option>
                                <?php 
                                        while ($kat = $kategoriler->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value=".$kat["id"].">".$kat["kategori"]."</option>";
                                        $a++;  }                               
                                ?>
                            </select> 
                        </div>
                        <button class="badge text-primary mb-3 mt-3 border border-1 border-primary btn btn-light" type="submit" href="">Göster</button>
                    </form>
                    <!-- # FORM-SELECT # -->
                    <div class="mb-3 d-flex">
                        <div class=" badge bg-light text-primary text-wrap" style="width: 6rem;">
                            <?php if(isset($_GET["time"])){
                                echo $_GET["time"];
                            } ?>
                        </div>
                        <div class="badge bg-light text-primary text-wrap position-absolute start-50" style="width: 6rem;">
                            <?php 
                                if (!isset($_GET['kat'])) {
                                    echo "";
                                } elseif ($_GET['kat']=='Hepsi') {
                                    echo $_GET['kat'];
                                } 
                                else {
                                    echo $kat1["ad"];
                                }
                            ?>
                        </div>
                    </div>
                                        <!-- # CHARTS-HTML # -->
                    <div class="d-flex">
                        <div class="me-1 border rounded-1 border-primary" id="columnchart_values" style="width: 600px; height: 375px;"></div>
                        <div class="ms-1 border rounded-1 border-primary" id="curve_chart" style="width: 600px; height: 375px"></div>
                    </div>
                    <!-- # CHARTS-HTML # -->
                </div>
                <!-- # FORM-CHARTS # -->
            <div>
            <!-- ### CONTENTT ### -->
        </div>
    </div>
    
</body>
</html>