<?php 

    include "functions/database.php";
    include "functions/functions.php";

    if(isset($_POST['degisecekSoru']) && isset($_POST['soru']) && isset($_POST['cevap1']) && isset($_POST['cevap2']) && isset($_POST['cevap3']) && isset($_POST['cevap4']) && isset($_POST['dogruCevap']) && isset($_POST['zorluk'])) {
        $id = $_POST['id'];
        $soru = $_POST['soru'];
        $cevap1 = $_POST['cevap1'];
        $cevap2 = $_POST['cevap2'];
        $cevap3 = $_POST['cevap3'];
        $cevap4 = $_POST['cevap4'];
        $dogruCevap = $_POST['dogruCevap'];
        $zorluk = $_POST['zorluk'];
        $degisecekSoru = $_POST['degisecekSoru'];
        soruGuncelle($degisecekSoru, $soru, $cevap1, $cevap2, $cevap3, $cevap4, $dogruCevap, $zorluk, $degisecekSoru);
        header("Location: admin.php");
        exit();
    }

?>