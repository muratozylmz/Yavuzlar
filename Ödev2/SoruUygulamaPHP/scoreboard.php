<?php
    session_start();

    if(!isset($_SESSION["username"]) && empty($_SESSION["username"]))
    {
      header("Location: login.php?message=giriş yapınız");
    }
    else
    {
        include "functions/functions.php";
   }

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
            justify-content: center;    
            margin-top: 100px;
            box-shadow: #040202 0px 0px 10px;
        }
        .table{
            display: flex;
            text-align: center;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            gap: 1.25rem;
            margin-bottom: 1rem;
            font-size: 20px;
        }
        td{
            padding: 10px;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 20px;
            
        }
    </style>
</head>
<body> 
    <div class="navbarContainer">
        <table class="table">
    <?php
    echo "<tr>" .
    "<th>No</th>" .
    "<th>Kullanıcı Adı</th>" .
    "<th>Puan</th>" .
  "</tr>";
    

  foreach (scoreBoard() as $value) {
   echo '<tr>';
   echo '<td>'.  $value["id"] .'</td>';
   echo '<td>'.  $value["username"] .'</td>';
   echo '<td>'.  $value["toplam"] .'</td>';
  
   echo '</tr>';
}

?>
        </table>
    </div>
</body>
</html>