 <?php
   if (!isset($_SESSION['user'])) {
      header("Location: auth.php");
   }
   //Proses untuk Tambah Album berikut kodenya
   // Membuat variabel untuk menampung data dari form
   $dataAlbum = mysqli_query($conf, "SELECT * FROM album WHERE UserID='$sesi[UserID]'");
   $album = mysqli_real_escape_string($conf, htmlspecialchars(trim(@$_POST['album'])));
   $deskripsi = mysqli_real_escape_string($conf, htmlspecialchars(trim(@$_POST['deskripsi'])));
   $tanggal = mysqli_real_escape_string($conf, htmlspecialchars(trim(@$_POST['tanggal'])));
   $userid = mysqli_real_escape_string($conf, htmlspecialchars(trim(@$_POST['userid'])));
   if (isset($_POST['SimpanAlbum'])) {
      // Melakukan query ke database menggunakan prepared statement
      $query = "INSERT INTO album(NamaAlbum, Deskripsi, TanggalDibuat, UserID) VALUES(?, ?, ?, ?)";
      $stmt = $conf->prepare($query);
      $stmt->bind_param("ssss", $album, $deskripsi, $tanggal, $userid);
      // Jika query gagal, maka akan tampil alert berikut
      if (!$stmt->execute()) {
         $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      <strong>Album gagal dibuat</strong> Coba lagi nanti!
      </div>';
         echo '<meta http-equiv="refresh" content="0.9; url=?url=album">';
      }
      // Jika query berhasil, maka akan tampil alert berikut
      else {
         $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      <strong>Album berhasil dibuat</strong>
      </div>';
         echo '<meta http-equiv="refresh" content="0.9; url=?url=album">';
      }
      // Menutup statement
      $stmt->close();
   }elseif (isset($_POST['UbahAlbum'])) {
      // Melakukan query ke database menggunakan prepared statement
      $query = "UPDATE album SET NamaAlbum=?, Deskripsi=?, TanggalDibuat=? WHERE AlbumID=?";
      $stmt = $conf->prepare($query);
      $stmt->bind_param("ssss", $album, $deskripsi, $tanggal, $_GET['id']);
      // Jika query gagal, maka akan tampil alert berikut
      if (!$stmt->execute()) {
         $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      <strong>Album gagal diubah</strong> Coba lagi nanti!
      </div>';
         echo '<meta http-equiv="refresh" content="0.9; url=?url=album">';
      }
      // Jika query berhasil, maka akan tampil alert berikut
      else {
         $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      <strong>Album berhasil diubah</strong>
      </div>';
         echo '<meta http-equiv="refresh" content="0.9; url=?url=album">';
      }
      // Menutup statement
      $stmt->close();
   }elseif(isset($_GET['hapus'])){
      $query = "DELETE FROM album WHERE AlbumID=?";
      $stmt = $conf->prepare($query);
      $stmt->bind_param("s", $_GET['id']);
      $stmt->execute();
      header("Location: ?url=album");
   }
   ?>
 <div class="container">
    <div class="row vh-100 justify-content-center align-items-center">
       <div class="col-12 col-md-8 col-lg-5">
          <div class="card">
             <div class="card-body">
                <?php if (isset($_GET['edit'])) : $albumVal=mysqli_fetch_array(mysqli_query($conf, "SELECT * FROM album WHERE AlbumID='".@$_GET['id']."' AND UserID='$sesi[UserID]' ")); ?>
                   <form action="?url=album&&edit&&id=<?= $_GET['id'] ?>" method="post">
                      <?= @$alert ?>
                      <input type="hidden" name="userid" value="<?= $sesi['UserID'] ?>">
                      <input type="hidden" name="tanggal" value="<?= date("Y-m-d") ?>">
                      <div class="mb-3">
                         <label for="album">Nama Album</label>
                         <input type="text" name="album" id="album" value="<?= $albumVal['NamaAlbum'] ?>" required class="form-control rounded-3">
                      </div>
                      <div class="mb-3">
                         <label for="deskripsi">Deskripsi Album</label>
                         <textarea name="deskripsi" id="deskripsi" cols="30" rows="10" class="form-control rounded-3" required><?= $albumVal['Deskripsi'] ?></textarea>
                      </div>
                      <button type="submit" class="btn btn-primary" name="UbahAlbum">Simpan perubahan <i class="fa-solid fa-floppy-disk fa-fw"></i></button>
                   </form>
                <?php else : ?>
                   <form action="?url=album" method="post">
                      <?= @$alert ?>
                      <input type="hidden" name="userid" value="<?= $sesi['UserID'] ?>">
                      <input type="hidden" name="tanggal" value="<?= date("Y-m-d") ?>">
                      <div class="mb-3">
                         <label for="album">Nama Album</label>
                         <input type="text" name="album" id="album" required class="form-control rounded-3">
                      </div>
                      <div class="mb-3">
                         <label for="deskripsi">Deskripsi Album</label>
                         <textarea name="deskripsi" id="deskripsi" cols="30" rows="10" class="form-control rounded-3" required></textarea>
                      </div>
                      <button type="submit" class="btn btn-primary" name="SimpanAlbum">Simpan <i class="fa-solid fa-floppy-disk fa-fw"></i></button>
                   </form>
                <?php endif; ?>
             </div>
          </div>
       </div>
       <div class="col-12 col-md-8 col-lg-7">
          <div class="card">
             <div class="card-body">
                <div class="table-responsive">
                   <table class="table table-striped table-hover">
                      <thead>
                         <tr>
                            <th>No.</th>
                            <th>Nama Album</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                         </tr>
                      </thead>
                      <tbody>
                         <?php
                           $i = 1;
                           foreach ($dataAlbum as $a) :
                           ?>
                            <tr>
                               <td><?= $i++ ?></td>
                               <td><?= $a['NamaAlbum'] ?></td>
                               <td><?= $a['TanggalDibuat'] ?></td>
                               <td>
                                  <a href="?url=album&&edit&&id=<?= $a['AlbumID'] ?>" class="btn btn-warning">Edit</a>
                                  <a href="?url=album&&hapus&&id=<?= $a['AlbumID'] ?>" class="btn btn-danger">Hapus</a>
                               </td>
                            </tr>
                         <?php endforeach; ?>
                      </tbody>
                   </table>
                </div>
             </div>
          </div>
       </div>
    </div>
 </div>