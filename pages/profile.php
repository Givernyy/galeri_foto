<?php
if (!isset($_SESSION['user'])) {
   header("Location: auth.php");
}
$query = mysqli_query($conf, "SELECT * FROM user WHERE UserID='$sesi[UserID]'");
$user = mysqli_fetch_array($query);
if (isset($_POST['editprofile'])) {
   $nama = $_POST['nama'];
   $email = $_POST['email'];
   $username = $_POST['username'];
   $alamat = $_POST['alamat'];
   if (isset($username) && isset($email)) {
      if ($username == $user['Username'] && $email == $user['Email'] && $alamat == $user['Alamat']) {
         $ubah = mysqli_query($conf, "UPDATE user SET NamaLengkap='$nama' WHERE UserID='$sesi[UserID]'");
         $session = mysqli_fetch_assoc(mysqli_query($conf, "SELECT * FROM user WHERE UserID='$sesi[UserID]'"));
         if ($ubah) {
            $_SESSION['user'] = $session;
            $alert = '<div class="alert alert-success"><strong>Ubah Nama Berhasil!!</strong></div>';
            echo '<meta http-equiv="refresh" content="0.8; url=?url=profile">';
         } else {
            $alert = '<div class="alert alert-danger"><strong>Ubah Nama Gagal!!</strong></div>';
            echo '<meta http-equiv="refresh" content="0.8; url=?url=profile">';
         }
      } else if ($username == $user['Username'] && $email == $user['Email'] && $nama == $user['NamaLengkap']) {
         $ubah = mysqli_query($conf, "UPDATE user SET Alamat='$alamat' WHERE UserID='$sesi[UserID]'");
         if ($ubah) {
            $alert = '<div class="alert alert-success"><strong>Ubah Alamat Berhasil!!</strong></div>';
            echo '<meta http-equiv="refresh" content="0.8; url=?url=profile">';
         } else {
            $alert = '<div class="alert alert-danger"><strong>Ubah Alamat Gagal!!</strong></div>';
            echo '<meta http-equiv="refresh" content="0.8; url=?url=profile">';
         }
      } else if ($username == $user['Username'] && $alamat == $user['Alamat'] && $nama == $user['NamaLengkap']) {
         $ubah = mysqli_query($conf, "UPDATE user SET Email='$email' WHERE UserID='$sesi[UserID]'");
         $session = mysqli_fetch_assoc(mysqli_query($conf, "SELECT * FROM user WHERE UserID='$sesi[UserID]'"));
         if ($ubah) {
            $_SESSION['user'] = $session;
            $alert = '<div class="alert alert-success"><strong>Ubah Email Berhasil!!</strong></div>';
            echo '<meta http-equiv="refresh" content="0.8; url=?url=profile">';
         } else {
            $alert = '<div class="alert alert-danger"><strong>Ubah Email Gagal!!</strong></div>';
            echo '<meta http-equiv="refresh" content="0.8; url=?url=profile">';
         }
      } else if ($email == $user['Email'] && $alamat == $user['Alamat'] && $nama == $user['NamaLengkap']) {
         $ubah = mysqli_query($conf, "UPDATE user SET Username='$username' WHERE UserID='$sesi[UserID]'");
         $session = mysqli_fetch_assoc(mysqli_query($conf, "SELECT * FROM user WHERE UserID='$sesi[UserID]'"));
         if ($ubah) {
            $_SESSION['user'] = $session;
            $alert = '<div class="alert alert-success"><strong>Ubah Username Berhasil!!</strong></div>';
            echo '<meta http-equiv="refresh" content="0.8; url=?url=profile">';
         } else {
            $alert = '<div class="alert alert-danger"><strong>Ubah Username Gagal!!</strong></div>';
            echo '<meta http-equiv="refresh" content="0.8; url=?url=profile">';
         }
      }
   }
} else if (isset($_POST['editpassword'])) {
   $password = md5($_POST['password']);
   if ($password != $user['Password']) {
      $ubah = mysqli_query($conf, "UPDATE user SET Password='$password' WHERE UserID='$sesi[UserID]'");
      if ($ubah) {
         $alert = '<div class="alert alert-success"><strong>Ubah Password Berhasil!!</strong></div>';
         echo '<meta http-equiv="refresh" content="0.8; url=?url=profile">';
      } else {
         $alert = '<div class="alert alert-danger"><strong>Ubah Password Gagal!!</strong></div>';
         echo '<meta http-equiv="refresh" content="0.8; url=?url=profile">';
      }
   }
} else if (isset($_POST['editpp'])) {
   $ppbaru = @$_FILES['ppbaru']['name'];
   $tmp_foto = @$_FILES['ppbaru']['tmp_name'];
   if (move_uploaded_file($tmp_foto, "uploads/user_profile/" . $ppbaru)) {
      $ubahpp = mysqli_query($conf, "UPDATE user SET FotoProfile='$ppbaru' WHERE UserID='$sesi[UserID]'");
      if ($ubahpp) {
         $alert = '<div class="alert alert-success"><strong>Ubah Foto Profil Berhasil!!</strong></div>';
         echo '<meta http-equiv="refresh" content="0.8; url=?url=profile">';
      } else {
         $alert = '<div class="alert alert-danger"><strong>Ubah Foto Profil Gagal!!</strong></div>';
         echo '<meta http-equiv="refresh" content="0.8; url=?url=profile">';
      }
   }
}
?>
<div class="container">
   <div class="row g-2 justify-content-center align-items-center vh-100">
      <div class="col-12 col-md-4 col-lg-4">
         <div class="card">
            <div class="card-body text-center">
               <a href="?url=profile&&proses=editpp"><img src="uploads/user_profile/<?= $user['FotoProfile'] ?>" alt="<?= $user['NamaLengkap'] ?>" style="width: 130px; height: 130px;" class="rounded-circle"></a>
               <h5 class="mt-2 mb-1"><?= $user['NamaLengkap'] ?></h5>
               <p class="mb-1">@<?= $user['Username'] ?></p>
               <p class="mb-3"><?= $user['Alamat'] ?></p>
               <a href="./" class="btn btn-dark fw-semibold"><i class="fa-solid fa-arrow-left"></i></a>
               <a href="?url=profile&&proses=editprofile" class="btn btn-danger">Edit Profil</a>
               <a href="?url=profile&&proses=editpassword" class="btn btn-primary">Edit Password</a>
            </div>
         </div>
      </div>
      <div class="col-12 col-md-8 col-lg-8">
         <div class="card">
            <div class="card-body <?php if (@$_GET['proses'] == "") : echo "py-0";
                                    else : echo "";
                                    endif; ?>">
               <?= @$alert ?>
               <?php if (@$_GET['proses'] == 'editprofile') : ?>
                  <form action="?url=profile" method="post">
                     <div class="input-group mb-3">
                        <label for="nama" class="input-group-text"><i class="fa-solid fa-circle-user fa-fw fa-lg"></i></label>
                        <input type="text" class="form-control" value="<?= $user['NamaLengkap'] ?>" id="nama" name="nama" required placeholder="Masukan Nama Lengkap">
                     </div>
                     <div class="input-group mb-3">
                        <label for="email" class="input-group-text"><i class="fa-solid fa-envelope fa-fw fa-lg"></i></label>
                        <input type="email" class="form-control" value="<?= $user['Email'] ?>" id="email" name="email" required placeholder="Masukan Email Anda">
                     </div>
                     <div class="input-group mb-3">
                        <label for="username" class="input-group-text"><i class="fa-solid fa-at fa-fw fa-lg"></i></label>
                        <input type="text" class="form-control" value="<?= $user['Username'] ?>" id="username" name="username" required placeholder="Masukan Username">
                     </div>
                     <div class="input-group mb-4">
                        <label for="alamat" class="input-group-text"><i class="fa-solid fa-address-book fa-fw fa-lg"></i></label>
                        <input type="text" class="form-control" id="alamat" value="<?= $user['Alamat'] ?>" name="alamat" required placeholder="Masukan Alamat Lengkap">
                     </div>
                     <a href="?url=profile" class="btn btn-dark fw-semibold"><i class="fa-solid fa-arrow-left"></i></a>
                     <input type="submit" value="Simpan Perubahan" name="editprofile" class="btn btn-primary fw-semibold">
                  </form>
               <?php elseif (@$_GET['proses'] == 'editpassword') : ?>
                  <form action="?url=profile" method="post">
                     <div class="input-group mb-4">
                        <label for="password" class="input-group-text"><i class="fa-solid fa-lock fa-fw fa-lg"></i></label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Masukan Password Baru">
                     </div>
                     <a href="?url=profile" class="btn btn-dark fw-semibold"><i class="fa-solid fa-arrow-left"></i></a>
                     <input type="submit" value="Simpan Perubahan" name="editpassword" class="btn btn-primary fw-semibold">
                  </form>
               <?php elseif (@$_GET['proses'] == 'editpp') : ?>
                  <form action="?url=profile" method="post" enctype="multipart/form-data">
                     <div class="input-group mb-4">
                        <label for="ppbaru" class="input-group-text"><i class="fa-solid fa-user-circle fa-fw fa-lg"></i></label>
                        <input type="file" class="form-control" id="ppbaru" name="ppbaru" required placeholder="Masukan Password Baru">
                     </div>
                     <a href="?url=profile" class="btn btn-dark fw-semibold"><i class="fa-solid fa-arrow-left"></i></a>
                     <input type="submit" value="Simpan Perubahan" name="editpp" class="btn btn-primary fw-semibold">
                  </form>
               <?php else : ?>
                  <div class="table-responsive">
                     <table class="table table-white table-hover">
                        <tr>
                           <th style="width: 20%;" class="py-3">Nama Lengkap</th>
                           <td class="py-3 text-muted"><?= $user['NamaLengkap'] ?></td>
                        </tr>
                        <tr>
                           <th style="width: 20%;" class="py-3">Email</th>
                           <td class="py-3 text-muted"><?= $user['Email'] ?></td>
                        </tr>
                        <tr>
                           <th style="width: 20%;" class="py-3">Username</th>
                           <td class="py-3 text-muted"><?= $user['Username'] ?></td>
                        </tr>
                        <tr>
                           <th style="width: 20%;" class="py-3">Alamat</th>
                           <td class="py-3 text-muted"><?= $user['Alamat'] ?></td>
                        </tr>
                     </table>
                  </div>
               <?php endif; ?>
            </div>
         </div>
      </div>
   </div>
</div>
?>