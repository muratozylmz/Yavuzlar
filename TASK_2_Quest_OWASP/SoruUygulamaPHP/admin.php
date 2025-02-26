<?php
session_start();


if(!isset($_SESSION["username"]) && empty($_SESSION["username"])) {
  header("Location: login.php?message=You are not logged in!");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
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
    .navbarButton {
      background-color: rgb(208, 222, 151);
      width: 90%;
      padding: 10px;
      border-radius: 10px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: black;
    }
    .logout {
      background-color: rgb(208, 222, 151);
      width: 30%;
      padding: 5px;
      border-radius: 10px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: black;
    }
    </style>
</head>
<body>
    <div class="navbarContainer">
        <form action="logout.php" method="post">
            <button class="logout" type="submit">Çıkış Yap</button></button>
        </form>

        <div class="header">
            <h1>Admin Paneli</h1>
        </div>

        <div class="navbar">
            <a href="addQuestions.php">
                <div class="navbarButton">Soru Ekle</div>
            </a>
            <a href="list.php">
                <div class="navbarButton">Soru Listesi</div>
            </a>
        </div>
    </div>
    
</body>
</html>