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
    <link rel="stylesheet" href="../public/css/style.css">
    <title>Update Password</title>
</head>

<body>
    <div class="container">
        <div class="login t<?php echo $_SESSION['role']; ?>">
            <h1 class="searchbox">Şifreni Güncelle</h1>
            <form action="../scripts/passwordQuery.php" method="post">
                <div>
                    <div class="container_obj">
                        <label for="current_password">Mevcut Şifre:</label><br>
                        <input type="password" name="current_password" required />
                    </div>

                    <div class="container_obj">
                        <label for="new_password">Yeni Şifre:</label><br>
                        <input type="password" name="new_password" required />
                    </div>

                    <div class="container_obj">
                        <label for="confirm_new_password">Yeni Şifreyi Tekrarlayın:</label><br>
                        <input type="password" name="confirm_new_password" required />
                    </div>
                    <button type="submit">Şifreyi Güncelle</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>