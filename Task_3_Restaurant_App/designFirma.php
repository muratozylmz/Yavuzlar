<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] == 0) {
     $company_id = $_GET['id'];
     $company = GetCompanyById($company_id);
 } else if ($_SESSION['role'] == 0) {
     $company = GetCompanyById($_SESSION['id']);
 }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update <?php echo $company['name']; ?> Company</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .centerDiv {
            text-align: center;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 24px;
            color: #333;
        }
        .medPhoto {
            width: 150px;
            height: 150px;
            border-radius: 50%;
        }
        form {
            margin-top: 20px;
        }
        .container_obj {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .login.t0 {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login t<?php echo $_SESSION['role']; ?>">
            <div class="centerDiv">
                <h1><?php echo $company['name']; ?> Firmasını Güncelle</h1>
                <img src="<?php echo $company['logo_path']; ?>" alt="Firma Logosu" class="medPhoto">
            </div>
            <form action="designFirmaQuery.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="company_id" value="<?php echo $company['id']; ?>" />
                <div class="container_obj">
                    <label for="name">Firma Adı</label><br>
                    <input type="text" value="<?php echo $company['name']; ?>" placeholder="Firma Adı" name="name" required />
                </div>
                <div class="container_obj">
                    <label for="description">Açıklama</label><br>
                    <input type="text" value="<?php echo $company['description']; ?>" name="description" placeholder="Açıklama" required />
                </div>
                <div class="container_obj">
                    <label for="image">Firma Logosu:</label><br>
                    <input type="file" name="image" accept="image/*" required />
                </div>
                <button type="submit">Güncelle</button>
            </form>
        </div>
    </div>
</body>

</html>
