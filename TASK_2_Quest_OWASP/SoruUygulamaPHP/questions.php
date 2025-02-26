<?php
include "functions/database.php";
include "functions/functions.php";
session_start();

if(!isset($_SESSION["username"])&& empty($_SESSION["username"])){
    header("Location: login.php");
  }

if(!isset($_SESSION['id'])){

    $_SESSION['id']=0;
    $_SESSION['dogruCevap']=0;
}

$questions = getall();

if($_SESSION['id'] >= count($questions)){

    header("Location: result.php");
    exit();
}

$mevcut = $questions[$_SESSION['id']];
print_r($mevcut);

if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_POST['cevap']) && $_POST['cevap'] == $mevcut['dogruCevap']){
        $_SESSION['dogruCevap']++;
        puanEkle(5);
    }
    $_SESSION['id']++;
    header("Location: questions.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SORU <?php echo $_SESSION['id']+1; ?></title>
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
        .cevap{
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 1.25rem;
            margin-bottom: 1rem;
        }
        
        header{
            flex-direction: column;
            gap: 2rem;
            padding: 2rem;
            text-align: center;
        
        }
        label{
            font-size: 20px;
        }
        input{
            width: fit-content;
            text-align: center;
        }
        button{
            width: 100%;
            padding: 10px;
            background-color: rgb(208, 222, 151);
            color: black;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="navbarContainer">
        <h1 class="header">Soru  <?php echo $_SESSION['id'] + 1; ?>: <?php echo $mevcut['soru']; ?> </h1>
        <form action="questions.php" method="post">
            <ol type="A" class="cevap">
                <li><input type="radio" name="cevap" value="cevap1" required><label for="cevap1" > <?php echo $mevcut['cevap1']; ?></label></li>
                <li><input type="radio" name="cevap" value="cevap2" required><label for="cevap2" > <?php echo $mevcut['cevap2']; ?></label></li>
                <li><input type="radio" name="cevap" value="cevap3" required><label for="cevap3" > <?php echo $mevcut['cevap3']; ?></label></li>
                <li><input type="radio" name="cevap" value="cevap4" required><label for="cevap4" > <?php echo $mevcut['cevap4']; ?></label></li>
            </ol>
            <button type="submit" class="button">Sonraki</button>
        </form>
    </div>
</body>
</html>


