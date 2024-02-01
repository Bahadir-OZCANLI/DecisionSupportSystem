<?php require 'process.php' ; ?>
        <!-- ## SQL SORGULAR ## -->
<?php
        // ## AYLARA GÖRE KATEGORİ SATIŞ ## 
    $aylar=$db->prepare("SELECT tarih FROM siparisler GROUP BY tarih");
    $aylar->execute();
    if(!isset($_GET["time"])){
        $bolge_aylik=$db->prepare("SELECT bolge.bolge_ad as bolge, COUNT(siparisler.siparis_id)AS sayi
        FROM siparisler, musteriler, ilceler, iller, bolge
        WHERE siparisler.musteri_id=musteriler.musteri_id AND musteriler.ilce_id=ilceler.ilce_id AND ilceler.il_id=iller.il_id AND iller.bolge_id=bolge.bolge_id GROUP BY bolge.bolge_id");
        $bolge_aylik->execute();
    }elseif($_GET["time"]=='Hepsi'){
        $bolge_aylik=$db->prepare("SELECT bolge.bolge_ad as bolge, COUNT(siparisler.siparis_id)AS sayi
        FROM siparisler, musteriler, ilceler, iller, bolge
        WHERE siparisler.musteri_id=musteriler.musteri_id AND musteriler.ilce_id=ilceler.ilce_id AND ilceler.il_id=iller.il_id AND iller.bolge_id=bolge.bolge_id GROUP BY bolge.bolge_id");
        $bolge_aylik->execute();
    }else{
        $tarih = $_GET["time"];
        $bolge_aylik=$db->prepare("SELECT bolge.bolge_ad as bolge, COUNT(siparisler.siparis_id)AS sayi
        FROM siparisler, musteriler, ilceler, iller, bolge
        WHERE siparisler.musteri_id=musteriler.musteri_id AND musteriler.ilce_id=ilceler.ilce_id AND ilceler.il_id=iller.il_id AND iller.bolge_id=bolge.bolge_id AND siparisler.tarih='$tarih' GROUP BY bolge.bolge_id");
        $bolge_aylik->execute();
    }
        // ## KATEGORİLERE GÖRE AYLIK SATIŞ ## 
    $bolge_ad=$db->prepare("SELECT bolge.bolge_id as id, bolge.bolge_ad as bolge FROM bolge");
    $bolge_ad->execute();
    if(!isset($_GET["bol"])){
        $bolgeler=$db->prepare("SELECT siparisler.tarih, COUNT(siparisler.siparis_id)AS sayi
        FROM siparisler, musteriler, ilceler, iller, bolge
        WHERE siparisler.musteri_id=musteriler.musteri_id AND musteriler.ilce_id=ilceler.ilce_id AND ilceler.il_id=iller.il_id AND iller.bolge_id=bolge.bolge_id GROUP BY siparisler.tarih");
        $bolgeler->execute();
    }elseif($_GET["bol"]=='Hepsi'){
        $bolgeler=$db->prepare("SELECT siparisler.tarih, COUNT(siparisler.siparis_id)AS sayi
        FROM siparisler, musteriler, ilceler, iller, bolge
        WHERE siparisler.musteri_id=musteriler.musteri_id AND musteriler.ilce_id=ilceler.ilce_id AND ilceler.il_id=iller.il_id AND iller.bolge_id=bolge.bolge_id GROUP BY siparisler.tarih");
        $bolgeler->execute();
    }else{
        $bolg = $_GET["bol"];
        $bolgeler=$db->prepare("SELECT siparisler.tarih, COUNT(siparisler.siparis_id)AS sayi
        FROM siparisler, musteriler, ilceler, iller, bolge
        WHERE siparisler.musteri_id=musteriler.musteri_id AND musteriler.ilce_id=ilceler.ilce_id AND ilceler.il_id=iller.il_id AND iller.bolge_id=bolge.bolge_id AND bolge.bolge_id=$bolg GROUP BY siparisler.tarih");
        $bolgeler->execute();
        $bolgeler1=$db->prepare("SELECT bolge_ad as ad FROM bolge WHERE bolge_id=$bolg");
        $bolgeler1->execute();
        $bolge1 = $bolgeler1->fetch(PDO::FETCH_ASSOC);
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
    <!-- ### GRAFİK ### -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

        <!-- # BUBLE CHARTS # -->
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawSeriesChart);

        function drawSeriesChart() {
            var data = google.visualization.arrayToDataTable([
                ['İL', 'Müşteri', 'Sipariş', 'Bölgeler', 'Miktar'],
                <?php 
                    while ($bolge = $bolge_satis->fetch(PDO::FETCH_ASSOC)) {
                echo "['".$bolge["il"]."', ".$bolge["musteri"].", ".$bolge["sayi"].", '".$bolge["bolge"]. "', ".$bolge["sayi"]." ],";
                    }
                ?>
      ]);
            var options = {
                title: 'Bölgelere ve İllere Göre Satış Miktarları ve Müşteri Sayıları.' +
                    ' X=Satış Miktarı, Y=Müşteri Sayısı, Balon boyut=Satış Miktarı, Balon renk=Bölgeler',
                 hAxis: {title: 'Müşteri Sayısı'},
                vAxis: {title: 'Satış Miktarı'},
                bubble: {textStyle: {fontSize: 11}},
                };

                var chart = new google.visualization.BubbleChart(document.getElementById('series_chart_div'));
                    chart.draw(data, options);
        }
    </script>
        <!-- # BUBLE CHARTS # -->

        <!-- # AYLARA GORE BOLGE SATIS COLUMN CHARTS # -->
    <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                ['Aylar', 'Satis'],
                <?php 
                    while ($bolge = $bolgeler->fetch(PDO::FETCH_ASSOC)) {
                        echo "['".$bolge["tarih"]."', ".$bolge["sayi"]."],";
                    }
                    ?>
                ]);

                var options = {
                title: 'Bölgelere Göre Aylık Satış Miktarları',
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
        <!-- # AYLARA GORE BOLGE SATIS COLUMN CHARTS # -->

        <!-- # BÖLGELERE GÖRE AYLIK SATIS LINE CHARTS # -->
    <script type="text/javascript">
        google.charts.load("current", {packages:['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ["Kategori", "Satış"],
                <?php 
                while ($bolge_ay = $bolge_aylik->fetch(PDO::FETCH_ASSOC)) {
                    echo "['".$bolge_ay["bolge"]."', ".$bolge_ay["sayi"]."],";
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
                title: "Aylara Göre Bölgelerin Satış Miktarları",
                hAxis:{title: "Bölgeler", slantedText:true, slantedTextAngle:45 },
        };
            var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
            chart.draw(view, options);
        }
    </script> 
        <!-- # BÖLGELERE GÖRE AYLIK SATIS LINE CHARTS # -->

    <!-- ### GRAFİK ### -->
</head>
<body class="bg-body-tertiary">

    <div class="container">
        <div class="row">
            <!-- ### LEFT ASIDE MENU ### -->
            <div class="col-2 h-100 ms-1 text-center position-absolute start-0">
                <img class="col-sm-12 mt-2 border border-2 rounded-2 border-primary" src="kds.png" alt="Logo">
                <a href="/" class="col-sm-9 mt-5 fs-7 text-primary btn btn-light border border-primary badge"  type="button">ANA SAYFA</a>
                <a href="satis.php" class="col-sm-9 mt-3 text-primary btn btn-light border border-primary badge"  type="button">SATIŞLAR</a>
                <a href="urunler.php" class="col-sm-9 mt-3 text-primary btn btn-light border border-primary badge"  type="button">ÜRÜNLER</a>
                <a href="bolgeler.php" class="active col-sm-9 mt-3 text-primary btn btn-light border border-primary badge"  type="button">BÖLGELER</a>
                <a href="login.php" class="col-sm-9 mt-3 text-primary btn btn-light border border-primary badge"  type="button">ÇIKIŞ YAP</a>
            </div>

            <!-- ### CONTENTT ### -->
            <div style="position:relative;left:180px" class="col-11 h-100 mt-2 bg-secondary-subtle text-center border border-2 rounded-2 border-primary">
                <a href="b_sehir.php" class="col-sm-9 mt-3 fs-6 w-25 text-primary btn btn-light border border-primary badge"  type="button">Şehirleri Gör</a>
                <!-- # FORM-SELECT # -->
                <form action="" method="get">
                    <div class="d-flex mt-5">
                        <select class="w-25 form-select form-select-sm border-primary" aria-label="Small select example" name="time">
                            <option value='Hepsi' selected>Aylara Göre</option>
                            <?php 
                                    while ($ay = $aylar->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value=".$ay["tarih"].">".$ay["tarih"]."</option>";
                                    $a++;  }                               
                            ?>
                        </select>
                        <select class="w-25 form-select form-select-sm border-primary position-absolute start-50" aria-label="Small select example" name="bol">
                            <option value='Hepsi' selected>Bölgelere Göre</option>
                            <?php 
                                    while ($bol = $bolge_ad->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value=".$bol["id"].">".$bol["bolge"]."</option>";
                                    $a++;  }                               
                            ?>
                        </select> 
                    </div>
                    <button class="badge text-primary mb-3 mt-3 border border-1 border-primary btn btn-light" type="submit" href="">Göster</button>
                </form>
                <!-- # FORM-SELECT # -->
                <!-- # CHARTS # -->
                <div class="mb-2 d-flex">
                    <div class=" badge bg-light text-primary text-wrap" style="width: 6rem;">
                        <?php if(isset($_GET["time"])){
                            echo $_GET["time"];
                        } ?>
                    </div>
                    <div class="badge bg-light text-primary text-wrap position-absolute start-50" style="width: 6rem;">
                        <?php 
                            if (!isset($_GET['bol'])) {
                                echo "";
                            } elseif ($_GET['bol']=='Hepsi') {
                                echo $_GET['bol'];
                            } 
                            else {
                                echo $bolge1["ad"];
                            }
                        ?>
                    </div>
                </div>
                <div style="position:relative;" class="col-12 mb-3 mt-3">
                    <div class="d-flex mb-2">
                        <div class="me-1 border rounded-1 border-primary" id="columnchart_values" style="width: 600px; height: 375px;"></div>
                        <div class="border rounded-1 border-primary" id="curve_chart" style="width: 600px; height: 375px"></div>
                    </div>
                    <div class="me-1 border rounded-1 border-primary" id="series_chart_div" style="width: 1180px; height: 550px;"></div>
                </div>
                

                <!-- # CHARTS # -->
            <div>

        </div>
    </div>
    
</body>
</html>