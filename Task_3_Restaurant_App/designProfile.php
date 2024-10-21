<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: #007bff;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .container_obj {
            margin-bottom: 15px;
            text-align: left;
        }
    </style>
    <title>Update Profile</title>
</head>

<body>
    <div class="container">
        <div class="login t<?php echo $_SESSION['role']; ?>">
            <h1 class="searchbox">Profilini Güncelle</h1>
            <form action="designProfileQuery.php" method="post">
                <div class="container_obj">
                    <label for="name">İsim:</label>
                    <input type="text" name="name" placeholder="İsim" value="<?php echo $_SESSION['name']; ?>" required />
                </div>
                <div class="container_obj">
                    <label for="surname">Soyisim:</label>
                    <input type="text" name="surname" placeholder="Soyisim" value="<?php echo $_SESSION['surname']; ?>" required />
                </div>
                <div class="container_obj">
                    <label for="username">Kullanıcı Adı:</label>
                    <input type="text" name="username" placeholder="Kullanıcı Adı" value="<?php echo $_SESSION['username']; ?>" required />
                </div>
                <button type="submit">Profili Güncelle</button>
            </form>
        </div>
    </div>

</body>

</html>
