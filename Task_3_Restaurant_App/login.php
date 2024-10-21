<?php
// include "functions/functions.php";
// if (IsUserLoggedIn()) {
//     header("Location: index.php");
// } ?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        body{
            background-image: url("img/Yavuzlar.jpeg"); ;
        }
        
        .form{
            width: 500px;
            height: 300px;
            background-color: #fff;
            margin-top: 100px;
            border: 1px solid #000;
            border-radius: 10px;
            padding: 20px;
        }
        input, button{
            border: 1px solid #000;
            border-radius: 5px;
            padding: 5px;
            width: 100%;
        }
        form{
            font-size: 20px;
            font-weight: bold;

        }
        .loginInput{
            border: 1px solid #000;
            border-radius: 5px;
            padding: 5px;
            width: 100%;
        }

    </style>
    <title>Login</title>
  </head>
  <body>
        <h4 class="text-center" style="color: #fff">Giriş Yap</h4>

     <div class="container">
    <form class="form mx-auto" action="loginQuery.php" method="post">
  <div class="mb-3">
    <label for="usernameLabel" class="form-label">Username</label>
    <input type="text" class="loginInput" name="username">
   </div>
  <div class="mb-3">
    <label for="passwordLabel" class="form-label">Password</label>
    <input type="password" class="loginInput" name="password">
   
  
  <button type="submit" class="btn btn-primary mt-3">Giriş Yap</button>
</form>
    </div> 
    


</body>
</html>