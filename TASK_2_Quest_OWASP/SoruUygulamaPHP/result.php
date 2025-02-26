<?php 
include "functions/functions.php";
include "functions/database.php";
session_start();
$puan = $_SESSION['dogruCevap'];

session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .navbarContainer {
        background-color: rgb(255, 255, 255);
        padding:50px;
        width: 400px;
        border-radius: 10px;
        align-items: center;
        margin-top: 100px;
        box-shadow: #040202 0px 0px 10px;
    }
    .result {
        font-size: 30px;
        text-align: center;
    }
    </style>
</head>
<body>
    <div class="navbarContainer">
        <h1 class="result">Sonucunuz:</h1>
        <p>Toplam Puanınız: <?php echo $puan; ?></p>
        <a href="index.php"><input class="button" type="button" value="Anasayfaya Dön"></a>
    </div>
</body>
</html>