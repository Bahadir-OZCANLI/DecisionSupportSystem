<?php 
    try {
        
        $db = new PDO("mysql:host=localhost;dbname=kds;charset=utf8","root","");
        
        //echo "Bağlandı";

    }catch (PDOException $e) {
        echo $e->get_Message();
    }

    
    ?>
