<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
   exit();
} ?> 
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    
    <title>YAVUZLAR RESTORAN</title>
  </head>
  <body>
 
  <!-- Menü Başlangıç -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">YAVUZLAR RESTORANI</a>

  <!-- Kullanıcı oturum açtıysa ad ve soyadını göster -->
  <?php if (isset($_SESSION['name']) && isset($_SESSION['surname'])): ?>
    <h4>Hoşgeldin <?php echo $_SESSION['name'] . " " . $_SESSION['surname']; ?>!</h4>
  <?php endif; ?>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">Anasayfa <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="listFood.php">Yemekler</a>
      </li>
      <li class="nav-item">
        <li class="nav-item">
          <a class="nav-link" href="firma.php">Firmalar</a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link" href="customer.php">Müşteriler</a>
        </li> -->
        <a class="nav-link" href="listKupon.php">Kuponlar</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="listRestaurant.php">Restoranlar</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="orders.php">Sipariş</a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="basket.php">Sepet</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="customerorders.php">Sipariş Yönetim Sayfası</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="addComment.php">Yorumlar</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profile.php">Profil</a>
      </li>
      
      <?php if (isset($_SESSION['user_id'])): ?>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Çıkış Yap</a>
        </li>
      <?php else: ?>
        <li class="nav-item">
          <a class="nav-link" href="login.php">Giriş Yap</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="register.php">Kayıt Ol</a>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>


  <!-- Menü Bitis -->
  <!-- Slider Baslangıc -->

  <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
  </ol>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="img/Yavuzlar.jpeg" class="d-block w-100 resimSlider" alt="...">
    </div>
    <div class="carousel-item">
      <img src="img/1.jpg" class="d-block w-100 resimSlider" alt="...">
    </div>
    <div class="carousel-item">
      <img src="img/2.jpg" class="d-block w-100 resimSlider" alt="...">
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

  <!-- Slider Bitis -->

  <!-- Hakkımızda Baslangıc -->

  <section class="p-5 text-center hakkimizda">
    <div class="container">
      <h2 class="mb-5 font-weight-bold">Hakkımızda</h2>
      <hr>
      <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. In cupiditate molestiae distinctio veniam aliquid quae non mollitia rerum similique eaque, minus iure eum beatae voluptates saepe laudantium, nihil enim sint!</p>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi blanditiis, unde debitis officiis maiores earum deleniti ratione eius cumque. Non odio sit ducimus ipsam, harum libero reprehenderit nostrum voluptate temporibus?</p>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloremque, earum.</p>
      <p>Lorem ipsum dolor sit amet.</p>
    </div>
  </section>
  <!-- Hakkımızda Bitis --> 
  <!-- Yemekler Baslangıc -->
  <section class="p-5 text-center yemekler">
    <div class="container">
      <h2 class="mb-5 font-weight-bold">Yemekler</h2>
      <hr>

      <div class="card-deck">
        <div class="row">
          
  <div class="card shadow restoranCard">
    <img src="img/Yavuzlar.jpeg" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title">Yavzuzlar Restoran</h5>
      <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
    </div>
  </div>

  <div class="card shadow restoranCard">
    <img src="img/Yavuzlar.jpeg" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title">Yavuz Restoran</h5>
      <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
    </div>
  </div>

  <div class="card shadow restoranCard">
    <img src="img/Yavuzlar.jpeg" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title">7/24 Yavuzlar</h5>
      <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
    </div>
    </div>

    </div>
    <div class="card-deck">
        <div class="row mt-5">
  <div class="card shadow restoranCard">
    <img src="img/Yavuzlar.jpeg" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title">Yavzuzlar Restoran</h5>
      <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
    </div>
  </div>

  <div class="card shadow restoranCard">
    <img src="img/Yavuzlar.jpeg" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title">Yavuz Restoran</h5>
      <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
    </div>
  </div>

  <div class="card shadow restoranCard">
    <img src="img/Yavuzlar.jpeg" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title">7/24 Yavuzlar</h5>
      <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
    </div>
    </div>

  </div>
</div>
    </div>
  </section>

  <!-- Yemekler Bitis -->

  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

     </body>
</html>