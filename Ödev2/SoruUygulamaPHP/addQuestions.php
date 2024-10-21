<?php
  session_start();
  if (!$_SESSION['isAdmin']) {
    header("Location: index.php?message=You are not authorized to view this page!");
    die();
  }
  if (!isset($_SESSION['username']) && !isset($_SESSION['password'])) {
    header("Location: login.php?message=You are not logged in!");
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soru Ekleme Formu</title>
    <link rel="stylesheet" href="style.css">
    <style>
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
    <div class="konteyner">
        <div class="addQuestionForm">
            <h2 style="margin-bottom: 20px;"> Yeni Soru Ekleme Formu</h2>
            <form action="addQuestionsQuery.php" method="post" enctype="multipart/form-data">
            <label for="soru">Soruyu Yazınız</label>
            <input type="text" name="soru" placeholder="Soru">
            <label for="cevap1">A seçeneği nedir?</label>
            <input type="text" name="cevap1" required>
            <label for="cevap2">B Seçeneği nedir?</label>
            <input type="text" name="cevap2" required>
            <label for="cevap3">C Seçeneği nedir?</label>
            <input type="text" name="cevap3" required>
            <label for="cevap4">D seçeneği nedir?</label>
            <input type="text" name="cevap4" required>
            <label for="dogruCevap">Doğru Cevap:</label>
            <select name="dogruCevap" required>
                <option value="cevap1">A</option>
                <option value="cevap2">B</option>
                <option value="cevap3">C</option>
                <option value="cevap4">D</option>
            </select>
            <label for="zorluk">Zorluk Derecesi</label>
            <select name="zorluk">
                <option value="Kolay">Kolay</option>
                <option value="Orta">Orta</option>
                <option value="Zor">Zor</option>
            </select>
            <button type="submit">Soru Ekle</button>
        </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>