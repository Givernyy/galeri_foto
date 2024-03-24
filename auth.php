<?php
include 'db/conf.php';
session_start();
if (isset($_SESSION['user'])) {
   header("Location: ./");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>PINTREND</title>
   <link rel="stylesheet" href="assets/bs/css/bootstrap.min.css">
   <link rel="stylesheet" href="assets/faw/css/all.min.css">
   <link rel="stylesheet" href="assets/faw/webfonts/fa-solid-900.ttf">
   <link rel="icon" href="assets/img/putri1.png" type="image/x-icon">
   <style>
      @import url('assets/Poppins/Poppins-Black.ttf');
      @import url('assets/Poppins/Poppins-Bold.ttf');
      @import url('assets/Poppins/Poppins-ExtraBold.ttf');
      @import url('assets/Poppins/Poppins-Medium.ttf');
      @import url('assets/Poppins/Poppins-Regular.ttf');
      @import url('assets/Poppins/Poppins-SemiBold.ttf');

      * {
         padding: 0;
         margin: 0;
         box-sizing: border-box;
      }

      body {
         background-color: #eee;
         height: 93.2vh;
         font-family: 'Poppins', sans-serif;
         background: linear-gradient(to top, #0d6efd 10%, #052c65 90%) no-repeat;
      }

      .wrapper {
         max-width: 500px;
         border-radius: 10px;
         margin: 50px auto;
         padding: 30px 40px;
         box-shadow: 20px 20px 80px rgb(206, 206, 206);
      }

      .h2 {
         font-family: 'Poppins', cursive;
         font-size: 3.5rem;
         font-weight: bold;
         color: #0d6efd;
         font-style: italic;
      }

      .h4 {
         font-family: 'Poppins', sans-serif;
      }

      .input-field {
         border-radius: 5px;
         padding: 5px;
         display: flex;
         align-items: center;
         cursor: pointer;
         border: 1px solid #0d6efd;
         color: #0d6efd;
      }

      .input-field:hover {
         color: #052c65;
         border: 1px solid #052c65;
      }

      input {
         border: none;
         outline: none;
         box-shadow: none;
         width: 100%;
         padding: 0px 2px;
         font-family: 'Poppins', sans-serif;
      }

      .fa-eye-slash.btn {
         border: none;
         outline: none;
         box-shadow: none;
      }

      a {
         text-decoration: none;
         color: #0d6efd;
         font-weight: 700;
      }

      a:hover {
         text-decoration: none;
         color: #052c65;
      }

      .btn.btn-block {
         border-radius: 20px;
         background-color: #0d6efd;
         color: #fff;
         width: 100%;
      }

      .btn.btn-block:hover {
         background-color: #052c65;
      }

      @media(max-width: 768px) {
         .wrapper {
            margin: 10px;
         }
      }

      @media(max-width:424px) {
         .wrapper {
            padding: 30px 10px;
            margin: 5px;
         }

         .option {
            position: relative;
            padding-left: 22px;
         }

         .option label.text-muted {
            font-size: 0.95rem;
         }

         .checkmark {
            position: absolute;
            top: 2px;
         }

         .option .checkmark:after {
            top: 50%;
         }

         #forgot {
            font-size: 0.95rem;
         }
      }
   </style>
</head>

<body>
   <?php
   //Proses untuk Registrasi berikut kodenya
   // Membuat variabel untuk menampung data dari form
   $namaLengkap = mysqli_real_escape_string($conf, htmlspecialchars(trim(@$_POST['namaLengkap'])));
   $email = mysqli_real_escape_string($conf, htmlspecialchars(trim(@$_POST['email'])));
   $username = mysqli_real_escape_string($conf, htmlspecialchars(trim(@$_POST['username'])));
   $password = md5(@$_POST['password']);
   $alamat = mysqli_real_escape_string($conf, htmlspecialchars(trim(@$_POST['alamat'])));
   $fotoprofile = 'user.png';
   if (isset($_POST['daftar'])) {
      if (mysqli_num_rows(mysqli_query($conf, "SELECT * FROM user WHERE Username='$username' OR Email='$email'")) == 0) {
         // Melakukan query ke database menggunakan prepared statement
         $query = "INSERT INTO user (NamaLengkap, Email, Username, Password, Alamat, FotoProfile) VALUES (?, ?, ?, ?, ?, ?)";
         $stmt = $conf->prepare($query);
         $stmt->bind_param("ssssss", $namaLengkap, $email, $username, $password, $alamat, $fotoprofile);
         // Jika query gagal, maka akan tampil alert berikut
         if (!$stmt->execute()) {
            $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Registrasi gagal</strong> Coba lagi nanti!
            </div>';
            echo '<meta http-equiv="refresh" content="0.9; url=auth.php?page=daftar">';
         }
         // Jika query berhasil, maka akan tampil alert berikut
         else {
            $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Registrasi berhasil</strong> Silahkan Login!
            </div>';
            echo '<meta http-equiv="refresh" content="0.9; url=auth.php">';
         }
         // Menutup statement
         $stmt->close();
      } else {
         $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         <strong>Akun sudah ada</strong> Silahkan ulangi!
         </div>';
      }
      //Proses untuk Registrasi berikut kodenya
   } elseif (isset($_POST['login'])) {
      $query = "SELECT * FROM user WHERE Username=? AND Password=?";
      $stmt = $conf->prepare($query);
      $stmt->bind_param("ss", $username, $password);
      $stmt->execute();
      $result = $stmt->get_result();
      // Jika query gagal, maka akan tampil alert berikut
      if ($result->num_rows == 0) {
         $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                 <strong>Login gagal</strong> Coba lagi nanti!
                 </div>';
      } else {
         // Jika query berhasil, maka akan mendapatkan data user
         $user = $result->fetch_assoc();
         session_start();
         $_SESSION['user'] = $user;
         // Redirect ke halaman dashboard
         header('Location: ./');
      }
      // Menutup statement
      $stmt->close();
   }
   if (!isset($_GET['page']) || @$_GET['page'] === 'login') :
   ?>
      <div class="wrapper bg-white">
         <div class="h2 text-center"><i class="fa-solid fa-thumbtack fa-fw"></i>Galeri Photo</div>
         <div class="h4 text-muted text-center pt-2">Masukan detail login anda</div>
         <form class="pt-3" action="auth.php" method="post">
            <?= @$alert; ?>
            <div class="form-group py-2">
               <div class="input-field"> <span class="fa-solid fa-user p-2"></span> <input type="text" placeholder="Masukan Username..." required name="username"> </div>
            </div>
            <div class="form-group py-1 pb-2">
               <div class="input-field"> <span class="fa-solid fa-lock p-2"></span> <input type="password" placeholder="Masukan Password..." required name="password" id="inputPw"> <label for="showPw" class="btn bg-white text-muted"><span class="fa-solid fa-eye fa-fw"></span></label><input type="checkbox" class="d-none" id="showPw"></div>
            </div>
            <button type="submit" name="login" class="btn btn-block text-center my-3">Sign in</button>
            <div class="text-center pt-3 text-muted">Belum punya akun? <a href="?page=daftar">Sign up</a></div>
         </form>
      </div>
   <?php else : ?>
      <div class="wrapper bg-white">
         <div class="h2 text-center"><i class="fa-solid fa-thumbtack fa-fw"></i>Galeri Photo</div>
         <div class="h4 text-muted text-center pt-2">Masukan data anda</div>
         <form class="pt-3" action="auth.php?page=daftar" method="post">
            <?= @$alert; ?>
            <div class="form-group py-2">
               <div class="input-field"> <span class="fa-solid fa-at p-2"></span> <input type="text" placeholder="Masukan Nama Lengkap..." required name="namaLengkap"> </div>
            </div>
            <div class="form-group py-2">
               <div class="input-field"> <span class="fa-solid fa-envelope p-2"></span> <input type="email" placeholder="Masukan Email..." required name="email"> </div>
            </div>
            <div class="form-group py-2">
               <div class="input-field"> <span class="fa-solid fa-user p-2"></span> <input type="text" placeholder="Masukan Username..." required name="username"> </div>
            </div>
            <div class="form-group py-1 pb-2">
               <div class="input-field"> <span class="fa-solid fa-lock p-2"></span> <input type="password" placeholder="Masukan Password..." required name="password" id="inputPw"> <label for="showPw" class="btn bg-white text-muted"><span class="fa-solid fa-eye fa-fw"></span></label><input type="checkbox" class="d-none" id="showPw"></div>
            </div>
            <div class="form-group py-2">
               <div class="input-field"> <span class="fa-solid fa-location-dot p-2"></span> <input type="text" placeholder="Masukan Alamat..." required name="alamat"> </div>
            </div>
            <button type="submit" name="daftar" class="btn btn-block text-center my-3">Sign up</button>
            <div class="text-center pt-3 text-muted">Sudah punya akun? <a href="?page=login">Sign in</a></div>
         </form>
      </div>
   <?php endif; ?>
   <script>
      let z = document.getElementById("showPw");
      let x = document.getElementById("inputPw");
      let y = document.querySelector(".fa-eye");
      z.addEventListener('change', (e) => {
         if (e.target.checked == true) {
            x.type = "text";
            y.classList.add("fa-eye-slash")
            y.classList.remove("fa-eye")
         } else {
            x.type = "password";
            y.classList.remove("fa-eye-slash")
            y.classList.add("fa-eye")
         }
      })
   </script>
   <script src="assets/bs/js/bootstrap.bundle.min.js"></script>
</body>

</html>