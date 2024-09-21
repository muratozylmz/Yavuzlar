<?php
include "functions/functions.php";
if (IsUserLoggedIn()) {
    header("Location: index.php");
} ?>
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
            background-image: url("img/Yavuzlar.jpeg");
        }
        .form{
            width: 500px;
            height: auto;
            background-color: #fff;
            margin-top: 100px;
            border: 1px solid #000;
            border-radius: 10px;
            padding: 20px;
        }
        input, button, select{
            border: 1px solid #000;
            border-radius: 5px;
            padding: 5px;
            width: 100%;
        }
        label{
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
    <title>Register</title>
  </head>
  <body>
        <h4 class="text-center" style="color: #fff;">Kayıt Ol</h4>

    <div class="container">
    <form class="form mx-auto" action="registerQuery.php" method="POST">
        <div class="mb-3 mt-5">
            <label for="isimLabel" class="form-label">İsim</label>
            <input type="text" class="loginInput" name="name" required>
        </div><div class="mb-3 mt-5">
            <label for="soyisimLabel" class="form-label">Soyisim</label>
            <input type="text" class="loginInput" name="surname" required>
        </div><div class="mb-3 mt-5">
            <label for="usernameLabel" class="form-label">Username</label>
            <input type="text" class="loginInput" name="username" required>
        </div>
        <div class="mb-3">
            <label for="labelPassword" class="form-label">Password</label>
            <input type="password" class="loginInput" name="password" required>
        </div>
        
        <button type="submit" class="btn btn-primary mt-3">Kayıt Ol</button>
    </form>
    </div>
  </body>
</html>
