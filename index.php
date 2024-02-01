<?php require 'process.php' ; ?>
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

            <!-- # KATOGORİ PIE CHART # -->
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Kategori', 'Sayı'],
          <?php
          while($kategori = $kategori_satis->fetch(PDO::FETCH_ASSOC)){
            echo "['".$kategori['kategori']."', ".$kategori['sayi']."],";
          }
          ?>
        ]);

        var options = {'title':'Kategori Dağılımı', is3D:true,
        "backgroundColor":"white"};

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
    </script>
            <!-- # KATOGORİ PIE CHART # -->

            <!-- # CİNSİYET PIE CHART # -->
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Kategori', 'Sayı'],
          <?php
          while($cins = $cinsiyet->fetch(PDO::FETCH_ASSOC)){
            echo "['".$cins['cins']."', ".$cins['sayi']."],";
          }
          ?>
        ]);

        var options = {'title':'Cinsiyet Dağılımı', is3D:true,
        "backgroundColor":"white"};

        var chart = new google.visualization.PieChart(document.getElementById('pie_cins_chart_3d'));
        chart.draw(data, options);
      }
    </script>
            <!-- # CİNSİYET PIE CHART # -->

            <!-- # BOLGELER PIE CHART # -->
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Kategori', 'Sayı'],
          <?php
          while($bolge = $bolge_grup->fetch(PDO::FETCH_ASSOC)){
            echo "['".$bolge['bolge']."', ".$bolge['sayi']."],";
          }
          ?>
        ]);

        var options = {'title':'Bölge Dağılımı', is3D:true,
        "backgroundColor":"white"};

        var chart = new google.visualization.PieChart(document.getElementById('pie_bolge_chart_3d'));
        chart.draw(data, options);
      }
    </script>
            <!-- # BOLGELER PIE CHART # -->

            <!-- # AYLIK SATIŞLAR SUTUN CHART # -->
    <script type="text/javascript">
        google.charts.load("current", {packages:['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ["Tarih", "Aylık Satış"],
                <?php 
                while ($aylik_satis = $aylik_satislar->fetch(PDO::FETCH_ASSOC)) {
                    echo "['".$aylik_satis["tarih"]."', ".$aylik_satis["sayi"]."],";
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
                title: "Aylara Göre Satış Miktarları",
                hAxis:{title: "Aylar" , slantedText:true, slantedTextAngle:45 
                    
                },
        };
            var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
            chart.draw(view, options);
        }
    </script>     
            <!-- # AYLIK SATIŞLAR SUTUN CHART # -->

    <!-- ### GRAFİK ### -->

</head>
<body class="bg-body-tertiary">

    <div class="container">
        <div class="row">
            <!-- ### LEFT ASIDE MENU ### -->
            <div class="col-2 h-100 ms-1 text-center  position-absolute start-0">
                <img class="col-sm-12 mt-2 border border-2 rounded-2 border-primary" src="kds.png" alt="Logo">
                <a href="/" class="active col-sm-9 mt-5 text-primary btn btn-light border border-primary badge"  type="button">ANA SAYFA</a>
                <a href="satis.php" class="col-sm-9 mt-3 text-primary btn btn-light border border-primary badge"  type="button">SATIŞLAR</a>
                <a href="urunler.php" class="col-sm-9 mt-3 text-primary btn btn-light border border-primary badge"  type="button">ÜRÜNLER</a>
                <a href="bolgeler.php" class="col-sm-9 mt-3 text-primary btn btn-light border border-primary badge"  type="button">BÖLGELER</a>
                <a href="login.php" class="col-sm-9 mt-3 text-primary btn btn-light border border-primary badge"  type="button">ÇIKIŞ YAP</a>
            </div>
            <!-- ### CONTENTT ### -->
            <div style="position:relative;left:180px" class="col-11 h-100 mt-2 bg-secondary-subtle text-center border border-2 rounded-2 border-primary">

                <div class="badge card text-bg-light mb-3 mt-3 border-primary" style="max-width: 18rem;">
                    <div class="card-header text-center"><h4>Müşteri Sayısı</h4></div>
                    <div class="card-body text-center">
                        <h3 class="card-title"><?php echo $musteri["sayi"];?></h3>
                    </div>
                </div>
                <div class="badge card text-bg-light mb-3 mt-3 ms-3 border-primary" style="max-width: 18rem;">
                    <div class="card-header text-center"><h4>Satış Sayısı</h4></div>
                    <div class="card-body text-center">
                        <h3 class="card-title"><?php echo $siparis["sayi"];?></h3>
                    </div>
                </div>
                <div class="badge card text-bg-light mb-3 mt-3 ms-3 border-primary" style="max-width: 18rem;">
                    <div class="card-header text-center"><h4>Ürün Sayısı</h4></div>
                    <div class="card-body text-center">
                        <h3 class="card-title"><?php echo $urun["sayi"];?></h3>
                    </div>
                </div>
                <!-- # CHARTS-HTML # -->
                <div class="d-flex col-12 mb-3 mt-3">
                    <div class="me-1 border rounded-1 border-primary" id="piechart_3d" style="width: 600px; height: 375px;"></div>
                    <div class="ms-1 border rounded-1 border-primary" id="pie_cins_chart_3d" style="width: 600px; height: 375px;"></div>    
                </div>
                <div class="d-flex col-12 mb-3 mt-3">
                    <div class="me-1 border rounded-1 border-primary" id="pie_bolge_chart_3d" style="width: 600px; height: 375px;"></div>    
                    <div class="ms-1 border rounded-1 border-primary" id="columnchart_values" style="width: 600px; height: 375px;"></div>
                </div>
                
                <!-- # CHARTS-HTML # -->
            </div>

        </div>
    </div>
    
</body>
</html>