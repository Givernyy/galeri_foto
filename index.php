<?php include 'db/conf.php';
session_start();
$sesi = @$_SESSION['user'];
$url = @$_GET['url'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Galeri Photo</title>
   <link rel="stylesheet" href="assets/bs/css/bootstrap.min.css">
   <link rel="stylesheet" href="assets/style.css">
   <link rel="stylesheet" href="assets/faw/css/all.min.css">
   <link rel="stylesheet" href="assets/faw/webfonts/fa-solid-900.ttf">
   <link rel="icon" href="assets/img/putri1.png" type="image/x-icon">
</head>

<body>
   <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
      <div class="container">
         <a class="navbar-brand" href="?url=home"><i class="fa-solid fa-thumbtack fa-fw"></i>Galeri Photo</a>
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <form action="?url=search" method="get" class="d-flex w-50 justify-content-end gap-2">
               <input type="text" class="form-control rounded-pill" placeholder="Cari Berdasarkan Judul Foto..." name="keyword" required>
               <button type="submit" class="btn btn-dark rounded-pill d-none"><i class="fa-solid fa-fw fa-magnifying-glass"></i></button>
            </form>
            <div class="navbar-nav ms-auto gap-2">
               <a class="nav-link<?= $url == 'home' || !isset($url) || $url == 'd,,,,K, ,,etail' ? ' active' : ''; ?>" href="?url=home">Home</a>
               <?php if (isset($sesi)) : ?>
                  <a class="nav-link<?= $url == 'upload' ? ' active' : ''; ?>" href="?url=upload">Upload</a>
                  <a class="nav-link<?= $url == 'album' ? ' active' : ''; ?>" href="?url=album">AlbumKu</a>
                  <div class="dropdown">
                     <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= ucwords($sesi['Username']) ?>
                     </a>
                     <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?url=profile"><i class="fa-solid fa-fw fa-user"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="?url=logout"><i class="fa-solid fa-right-from-bracket fa-fw"></i> Logout</a></li>
                     </ul>
                  </div>
               <?php else : ?>
                  <a class="nav-link" href="?url=login">Login</a>
                  <a class="nav-link" href="?url=daftar">Daftar</a>
               <?php endif; ?>
            </div>
         </div>
      </div>
   </nav>
   <?php
   switch ($url) {
      case 'home':
         include 'pages/home.php';
         break;
      case 'album':
         include 'pages/album.php';
         break;
      case 'profile':
         include 'pages/profile.php';
         break;
      case 'upload':
         include 'pages/upload.php';
         break;
      case 'detail':
         include 'pages/detail.php';
         break;
      case 'like':
         include 'pages/like.php';
         break;
      case 'komentar':
         include 'pages/komentar.php';
         break;
      case 'dataFoto':
         include 'pages/dataFoto.php';
         break;
      case 'daftar':
         header("Location: auth.php?page=daftar");
         break;
      case 'login':
         header("Location: auth.php");
         break;
      case 'logout':
         session_destroy();
         header("Location: ./");
         break;
      default:
         include 'pages/home.php';
         break;
   }
   ?>
   <script src="assets/bs/js/bootstrap.bundle.min.js"></script>
</body>

</html>