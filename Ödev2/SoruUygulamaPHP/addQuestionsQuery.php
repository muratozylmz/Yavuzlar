<?php 
session_start();
include "functions/functions.php";
include "functions/database.php";
if(!$_SESSION['isAdmin']) {
    header("Location: index.php?message=You are not authorized to view this page!");
    die();
}

if(isset($_POST["soru"]) &&isset($_POST["cevap1"]) && isset($_POST["cevap2"]) && isset($_POST["cevap3"]) && isset($_POST["cevap4"]) && isset($_POST["dogruCevap"]) && isset($_POST["zorluk"])){
    $soru = $_POST['soru'];
    $cevap1 = $_POST['cevap1'];
    $cevap2 = $_POST['cevap2'];
    $cevap3 = $_POST['cevap3'];
    $cevap4 = $_POST['cevap4'];
    $dogruCevap = $_POST['dogruCevap'];
    $zorluk = $_POST['zorluk'];

    addQuestions($soru, $cevap1, $cevap2, $cevap3, $cevap4, $dogruCevap, $zorluk);
    header("Location: admin.php?message=Soru eklendi");

}
else{
    header("Location: addQuestions.php?message=Tekrar Deneyiniz");
}

?>