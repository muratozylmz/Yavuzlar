<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
}else if ($_SESSION['role'] == 2) {
    header("Location: ../view/index.php?message=403 Yetkisiz Giriş");
}

$restaurant_id = $_GET['r_id'];
$restaurant = GetRestaurantById($restaurant_id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Comment for <?php echo $restaurant['name'] ?></title>
</head>

<body>
    <div class="container">
        <div class="login t<?php echo $_SESSION['role'] ?>">
            <h2><?php echo $restaurant['name']; ?> İçin Yorum Ekle</h2>
            <img src="<?php echo $restaurant['image_path'] ?>" alt="Restoran Fotoğrafı" class="medPhoto container_obj">
            <form action="addCommentQuery.php" method="post">
                <input type="hidden" name="restaurant_id" value="<?php echo $restaurant_id; ?>">
                <div class="container_obj">
                    <label for="title">Başlık</label><br>
                    <input type="text" name="title">
                </div>
                <div class="container_obj">
                    <label for="description">Yorum</label><br>
                    <input type="text" name="description">
                </div>
                <div class="container_obj">
                    <label for="score">Skor</label><br>
                    <input type="number" min="0" max="10" name="score">
                </div>
                <button type="submit">Ekle</button>
            </form>
        </div>
    </div>
</body>

</html>