<?php 
  session_start();
  if (isset($_SESSION['id']) && isset($_SESSION['username']) ) {
    header("Location: index.php?message=You are already logged in!");
  }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Giriş Sayfası</title>
  <link rel="stylesheet" href="style.css">
<style>
  .loginInput {
    width: 90%;
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 20px;
  }
  #buton{
    background-color: rgb(208, 222, 151);
    width: 90%;
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

</style>
</head>

<body>

  <div class="konteyner">
    <div class="login">
      <h1>Giriş Yap</h1>
      <div class="loginForm">
        <form action="loginQuery.php" method="post">
          <input class="loginInput" type="text" name="username" placeholder="Kullanıcı Adı" required>
          <input class="loginInput" type="password" name="password" placeholder="Şifre" required>
          <button id="buton" type="submit">Giriş Yap</button>
        </form>
      </div>
    </div>

  </div>

</body>

</html>