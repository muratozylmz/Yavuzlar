<?php
session_start();
include "functions/functions.php";
include "functions/database.php";

if(!isset($_SESSION["username"]) && empty($_SESSION["username"])) {
  header("Location: login.php?message=You are not logged in!");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sorular</title>
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
    th, td, tr {
        width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border-radius: 3px;
    border: 1px solid #ccc;
    }
    .table {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid;
        font-size: 20px;
        margin-bottom: 10px;
        text-align: center;
    }
    .button{
            width:100%;
            padding: 10px;
            background-color: rgb(208, 222, 151);
            color: black;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }   
    
    </style>
</head>
<body>
    <div class="navbarContainer">
        <table class="table">
    <?php
    echo "<tr>" .
    "<th>Soru</th>" .
    "<th>Sil</th>" .
    "<th>Düzenle</th>" .
  "</tr>";
    

  foreach (getall() as $value) {
   echo '<tr>';
   echo '<td>'.  $value["soru"] .'</td>';
   echo '<td class="hide"><a class= "button" href="./deleteQuestions.php?id=' . $value["id"]  . '">Sil</a></td>';
   echo '<td class="hide"><a  class= "button" href="./duzenle.php?id=' . $value["id"]  . '">Düzenle</a></td>';
   echo '</tr>';
}

    ?>
    </table>
    </div>
</body>
</html>