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
    <title>Update Profile</title>
</head>

<body>
    <div class="container">
        <div class="login t<?php echo $_SESSION['role']; ?>">
            <h1 class="searchbox">Profilini Güncelle</h1>
            <form action="designProfileQuery.php" method="post">
                <div>
                    <div class="container_obj">
                        <label for="name">İsim:</label><br>
                        <input type="text" name="name" placeholder="İsim" value="<?php echo $_SESSION['name']; ?>" required />
                    </div>
                    <div class="container_obj">
                        <label for="surname">Soyisim:</label><br>
                        <input type="text" name="surname" placeholder="Soyisim" value="<?php echo $_SESSION['surname']; ?>" required />
                    </div>
                    <div class="container_obj">
                        <label for="username">Kullanıcı Adı:</label><br>
                        <input type="text" name="username" placeholder="Kullanıcı Adı" value="<?php echo $_SESSION['username']; ?>" required />
                    </div>
                    <button type="submit">Profili Güncelle</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>