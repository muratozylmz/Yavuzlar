<?php
  session_start();
  if(!isset($_SESSION["username"])&& empty($_SESSION["username"])){
    header("Location: login.php");
  }
  else{
    if($_SESSION["isAdmin"]=="1"){
      if(isset($_GET["id"]))
      {
        $degisecekSoru=$_GET["id"];
        include "functions/functions.php";
        $soru=duzenlenecekSoru($degisecekSoru);
       
      }
      else{
        header("Location:index.php?message=duzenleme gelmedi");
      }
    
  

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
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
    h1{
    text-align: center;
    }
    label{
    font-size: 20px;
    margin-bottom: 10px;
    display: block;
    }
    input[type="text"],
    select {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border-radius: 3px;
    border: 1px solid #ccc;
    }
    .button{
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
    <div class="container">
        <div class="navbarContainer">
            <form action="duzenleQuery.php" method="post">
                <input type="hidden" name="degisecekSoru" value="<?php echo $degisecekSoru; ?>">
                <label for="soru">Soruyu Yazınız:</label>">
                <input type="text"  name="soru" value="<?php echo $soru['soru']; ?>" required>
                <label for="cevap1">A seçeneği nedir?</label>
                <input type="text" name="cevap1" value="<?php echo $soru['cevap1']; ?>" required>
                <label for="cevap2">B Seçeneği nedir?</label>
                <input type="text"  name="cevap2" value="<?php echo $soru['cevap2']; ?>" required>
                <label for="cevap3">C Seçeneği nedir?</label>
                <input type="text" name="cevap3" value="<?php echo $soru['cevap3']; ?>" required>
                <label for="cevap4">D seçeneği nedir?</label>
                <input type="text" name="cevap4" value="<?php echo $soru['cevap4']; ?>" required>
                <label for="dogruCevap">Doğru Cevap:</label>
                <input type="text" name="dogruCevap" value="<?php echo $soru['dogruCevap']; ?>" required>
                <label for="zorluk">Zorluk Derecesi</label>
                <input type="text" name="zorluk" value="<?php echo $soru['zorluk']; ?>" required>
                <button class="button" type="submit">Kaydet</button>
                <button class="button" type="button" onclick="goToHomePage()">Anasayfa</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php 
  } 
  else
  {
    header("Location: admin.php?mesaj=bir sorun oluştu");
  }
  }
?>