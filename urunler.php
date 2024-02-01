<?php require 'process.php' ; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="reset.css">
    <title>Ana Sayfa</title>
    <!-- ## GRAFİK ## -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- ## TABLO ## -->
    <!-- ## TABLO ## -->
    
    <!-- ## GRAFİK ## -->
</head>
<body class="bg-body-tertiary">
    <div class="container">
        <div class="row">
            <!-- ## LEFT ASIDE MENU ## -->
            <div class="col-2 h-100 ms-1 text-center position-absolute start-0">
                <img class="col-sm-12 mt-2 border border-2 rounded-2 border-primary" src="kds.png" alt="Logo">
                <a href="/" class="col-sm-9 mt-5 text-primary btn btn-light border border-primary badge"  type="button">ANA SAYFA</a>
                <a href="satis.php" class="col-sm-9 mt-3 text-primary btn btn-light border border-primary badge"  type="button">SATIŞLAR</a>
                <a href="urunler.php" class="active col-sm-9 text-primary mt-3 btn btn-light border border-primary badge"  type="button">ÜRÜNLER</a>
                <a href="bolgeler.php" class="col-sm-9 mt-3 text-primary btn btn-light border border-primary badge"  type="button">BÖLGELER</a>
                <a href="login.php" class="col-sm-9 mt-3 text-primary btn btn-light border border-primary badge"  type="button">ÇIKIŞ YAP</a>
            </div>
            <!-- ## CONTENTT ## -->
            <div style="position:relative;left:180px" class="col-11 h-100 mt-2 bg-secondary-subtle text-center border border-2 rounded-2 border-primary">
                <div class="badge card text-bg-light mb-3 mt-3 ms-3 border-primary" style="max-width: 18rem;">
                    <div class="card-header text-center"><h4>Ürün Sayısı</h4></div>
                    <div class="card-body text-center">
                        <h3 class="card-title"><?php echo $urun["sayi"];?></h3>
                    </div>
                </div>
                <div>
                    <a class="badge mb-3 mt-3 text-primary btn btn-light border border-1 border-primary" href="urunler_ekle.php">Ürün Ekle</a>
                </div>

                <form class="d-flex mb-2" role="search" action="urun_git.php" method="get">
                    <input name="ara" class="form-control me-2 border border-1 border-primary" type="search" placeholder="İstediğiniz ürünü aratabilirsiniz..." aria-label="Search">
                    <button class="text-primary btn btn-light border border-1 border-primary" type="submit">Ara</button>
                </form>
              
                <table class="table table-striped table-bordered table-light table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">En Çok Satılan 20 Ürün</th>
                            <th scope="col">Puan</th>
                            <th scope="col">Fiyat</th>
                            <th scope="col">Git</th>
                            <th scope="col">Sil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php ;    
                            $x = 1;
                            while ($urun = $urunler->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>
                                <th scope='row'>$x</th>
                                <td>".$urun['urun_ad']."</td>
                                <td>".$urun['puan']."</td>
                                <td>".$urun['fiyat']."</td>" ?>
                                <td><a href="urun_git.php?id=<?php echo $urun['urun_id'];?>" class="badge text-dark btn btn-light border-dark" type="button">Git</a></td>
                                <td><a href="urun_git.php?id=<?php echo $urun['urun_id'];?>" class="badge text-dark btn btn-light border-dark" type="button">Sil</a></td>
                            <?php echo "</tr>"; $x++;
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    

</body>
</html>