<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] != 0) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Add Company</title>
</head>

<body>
    <div class="container">
        <div class="login t<?php echo $_SESSION['role']; ?>">
            <h1>Firma Ekle</h1>
            <form action="addFirmaQuery.php" method="post" enctype="multipart/form-data">
                <div class="container_obj">
                    <label for="name">Firma Adı</label><br>
                    <input type="text" name="name" placeholder="Firma Adı" required />
                </div>
                <div class="container_obj">
                    <label for="description">Açıklama</label><br>
                    <input type="text" name="description" placeholder="Açıklama" required />
                </div>
                <div class="container_obj">
                    <label for="image">Firma Logosu:</label><br>
                    <input type="file" name="image" accept="image/*" required>
                </div>
                <button type="submit">Kaydet</button>
            </form>
        </div>
    </div>
    
</body>

</html>