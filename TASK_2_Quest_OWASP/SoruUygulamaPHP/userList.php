<?php 
  session_start();
  include "functions/functions.php";
  
  if (!isset($_SESSION['id']) && !isset($_SESSION['username']) ) {
    header("Location: login.php?message=You are not logged in!");
  }

  $liste = GetUsers();


 
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Üyeler</title>

  <link rel="stylesheet" href="style.css">

</head>

<style>
  .userList {
    background-color: rgb(255, 255, 255);
    padding:50px;
    width: 400px;
    border-radius: 10px;
    align-items: center;
    margin-top: 100px;
    box-shadow: #040202 0px 0px 10px;
  }
  #homePageButton{
    background-color: rgb(208, 222, 151);
    width: 90%;
    padding: 5px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  th,td{
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border-radius: 3px;
    border: 1px #ccc;
  }
</style>

<body>
  <div class="userList">
    <button style="width: 200px;" id="homePageButton" onclick="goToHomePage()">Anasayfa</button>
    <table>
      <thead>
        <tr>
          
          <?php
          if($_SESSION['isAdmin']):?>
            <th>ID</th>
          <?php endif?>
            <th>Kullanıcı Adı</th>
            <th>Şifresi</th>
          <?php if( $_SESSION['isAdmin']):?>
            <th>Admin Mi</th>
            <th colspan="2">İşlemler</th>
          <?php endif?>
        </tr>
      </thead>
      <tbody>
        <?php foreach($liste as $user):?>
          <tr>
            <?php if($_SESSION['isAdmin']):?>
              <td><?php echo $user['id'];?></td>
            <?php endif?>
              <td><?php echo $user['username'];?></td>
              <td><?php echo $user['password'];?></td>
              <?php if( $_SESSION['isAdmin']):?>
              <td><?php $a = $user['isAdmin'] ? "Evet" : "Hayir"; echo $a; ?></td>
              <td><a href='deleteUser.php?id=<?php echo $user["id"]?>'>Sil</a></td>
            <?php endif?>
          </tr>
        <?php endforeach?>
      </tbody>
    </table>
  </div>

  <script src="script.js"></script>
</body>

</html>
