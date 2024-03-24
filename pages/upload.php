<?php
if (!isset($_SESSION['user'])) {
   header("Location: auth.php");
}
$album = mysqli_query($conf, "SELECT * FROM album WHERE UserID='$sesi[UserID]'");
//Proses untuk Tambah Album berikut kodenya
// Membuat variabel untuk menampung data dari form
$judul = mysqli_real_escape_string($conf, htmlspecialchars(trim(@$_POST['judul'])));
$deskripsi = mysqli_real_escape_string($conf, htmlspecialchars(trim(@$_POST['deskripsi'])));
$albumid = mysqli_real_escape_string($conf, htmlspecialchars(trim(@$_POST['album'])));
$userid = mysqli_real_escape_string($conf, htmlspecialchars(trim(@$_POST['userid'])));
$tanggal = mysqli_real_escape_string($conf, htmlspecialchars(trim(@$_POST['tanggal'])));
$foto = @$_FILES['foto']['name'];
$tmp_foto = @$_FILES['foto']['tmp_name'];
$size_foto = @$_FILES['foto']['size'];
$explode = explode('.', $foto);
$ext_foto = strtolower(end($explode));
$ext = array('jpg', 'png');

if (isset($_POST["SimpanGambar"])) {
   //Validasi Input Harus Diisi
   if (!empty($foto)) {
      //Validasi Extensi Yang Diizinkan
      if (in_array($ext_foto, $ext)) {
         // Validasi Ukuran File yang diupload Tidak Melebihi 20MB
         if ($size_foto < 10485760) {
            $new_name = date("Ymd") . "_" . date("His") . "." . $ext_foto;
            if (move_uploaded_file($tmp_foto, "uploads/img/" . $new_name)) {
               $query = "INSERT INTO foto (JudulFoto, DeskripsiFoto, TanggalUnggah, NamaFile, AlbumID, UserID) VALUES (?, ?, ?, ?, ?, ?)";
               $stmt = $conf->prepare($query);
               $stmt->bind_param("ssssss", $judul, $deskripsi, $tanggal, $new_name, $albumid, $userid);
               if ($stmt->execute()) {
                  // Jika query berhasil, maka akan tampil alert berikut
                  $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <strong>Foto Berhasil Disimpan</strong>
                                  </div>';
                  echo '<meta http-equiv="refresh" content="0.9; url=?url=upload">';
               }
               // Jika query gagal, maka akan tampil alert berikut
               else {
                  $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <strong>Foto Gagal Disimpan</strong> Terjadi Kesalahan!
                                  </div>';
                  echo '<meta http-equiv="refresh" content="0.9; url=?url=upload">';
               }
               // Menutup statement
               $stmt->close();
            }
         } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                           <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                           <strong>Foto Gagal Disimpan</strong> Ukuran file terlalu besar
                         </div>';
            echo '<meta http-equiv="refresh" content="0.9; url=?url=upload">';
         }
      } else {
         $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>Foto Gagal Disimpan</strong> Extensi file tidak didukung
                      </div>';
         echo '<meta http-equiv="refresh" content="0.9; url=?url=upload">';
      }
   } else {
      $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     <strong>Belum ada file yang dipilih</strong> Silakan pilih file foto
                   </div>';
      echo '<meta http-equiv="refresh" content="0.9; url=?url=upload">';
   }
} elseif (isset($_GET['id'])) {
   $fotoVal = mysqli_fetch_array(mysqli_query($conf, "SELECT * FROM foto WHERE FotoID='" . @$_GET['id'] . "'"));
   if (isset($_POST['UbahGambar'])) {
      if (strlen($foto) == 0) {
         $query = "UPDATE foto SET JudulFoto=?, DeskripsiFoto=?, TanggalUnggah=?, AlbumID=? WHERE FotoID=?";
         $stmt = $conf->prepare($query);
         $stmt->bind_param("sssss", $judul, $deskripsi, $tanggal, $albumid, $_GET['id']);
         if ($stmt->execute()) {
            $alert = '<div class="alert alert-success"><strong>Berhasil DiUbah!!</strong></div>';
            echo '<meta http-equiv="refresh" content="0.8; url=?url=upload">';
         } else {
            $alert = '<div class="alert alert-danger"><strong>Gagal DiUbah!!</strong> Kesalahan Server</div>';
            echo '<meta http-equiv="refresh" content="0.8; url=?url=upload">';
         }
      } else {
         if (in_array($ext_foto, $ext)) {
            // Validasi Ukuran File yang diupload Tidak Melebihi 20MB
            if ($size_foto < 10485760) {
               $new_name = date("Ymd") . "_" . date("His") . "." . $ext_foto;
               if (file_exists("uploads/img/" . $fotoVal["NamaFile"])) {
                  unlink("uploads/img/" . $fotoVal["NamaFile"]);
                  if (move_uploaded_file($tmp_foto, "uploads/img/" . $new_name)) {
                     $query = "UPDATE foto SET JudulFoto=?, DeskripsiFoto=?, TanggalUnggah=?, NamaFile=?, AlbumID=? WHERE FotoID=?";
                     $stmt = $conf->prepare($query);
                     $stmt->bind_param("ssssss", $judul, $deskripsi, $tanggal, $new_name, $albumid, $_GET['id']);
                     if ($stmt->execute()) {
                        // Jika query berhasil, maka akan tampil alert berikut
                        $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <strong>Foto Berhasil Diubah</strong>
                                  </div>';
                        echo '<meta http-equiv="refresh" content="0.9; url=?url=upload">';
                     }
                     // Jika query gagal, maka akan tampil alert berikut
                     else {
                        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <strong>Foto Gagal Diubah</strong> Terjadi Kesalahan!
                                  </div>';
                        echo '<meta http-equiv="refresh" content="0.9; url=?url=upload">';
                     }
                     // Menutup statement
                     $stmt->close();
                  }
               }
            } else {
               $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                           <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                           <strong>Foto Gagal Diubah</strong> Ukuran file terlalu besar
                         </div>';
               echo '<meta http-equiv="refresh" content="0.9; url=?url=upload">';
            }
         } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>Foto Gagal Diubah</strong> Extensi file tidak didukung
                      </div>';
            echo '<meta http-equiv="refresh" content="0.9; url=?url=upload">';
         }
      }
   }
}
?>
<div class="container pt-5 mt-5 mt-lg-0 pt-lg-0 mt-md-0 pt-md-0">
   <div class="row vh-100 justify-content-center align-items-center">
      <div class="col-9">
         <div class="card">
            <div class="card-body">
               <div class="row justify-content-center gap-5">
                  <div class="col-lg-5 col-md-8 col-11 rounded-4 d-flex justify-content-center align-items-center" style="background-color: #dddddd; height: 50vh;">
                     <label for="imgUpload" class="text-center" style="cursor: pointer;">
                        <i class="fa-solid fa-fw fa-upload text-white bg-dark rounded-circle fa-xl text-center" style="width: 50px; height: 50px; line-height: 50px;"></i>
                        <p class="mt-2 fw-semibold" id="showPreview">Upload Gambar</p>
                     </label>
                  </div>
                  <div class="col-lg-5 col-md-8 col-11">
                     <?php if (!isset($_GET['id'])) : ?>
                        <form action="?url=upload" method="post" enctype="multipart/form-data">
                           <?= @$alert ?>
                           <input type="hidden" name="tanggal" value="<?= date("Y-m-d") ?>">
                           <input type="hidden" name="userid" value="<?= $sesi['UserID'] ?>">
                           <input type="file" name="foto" id="imgUpload" class="d-none" onchange="imgTitle()">
                           <div class="mb-3">
                              <label for="judul">Judul Foto</label>
                              <input type="text" class="form-control rounded-3" name="judul" id="judul" required>
                           </div>
                           <div class="mb-3">
                              <label for="deskripsi">Deskripsi Foto</label>
                              <textarea name="deskripsi" id="deskripsi" cols="30" rows="8" required class="form-control rounded-3"></textarea>
                           </div>
                           <div class="mb-3">
                              <label for="album">Album Foto</label>
                              <select name="album" class="form-select rounded-3" id="album">
                                 <?php foreach ($album as $a) : ?>
                                    <option value="<?= $a['AlbumID'] ?>"><?= $a['NamaAlbum'] ?></option>
                                 <?php endforeach; ?>
                              </select>
                           </div>
                           <button type="submit" class="btn btn-primary" name="SimpanGambar">Simpan <i class="fa-solid fa-floppy-disk fa-fw"></i></button>
                           <a href="?url=dataFoto" class="btn btn-dark">Data Foto <i class="fa-solid fa-fw fa-images"></i></a>
                        </form>
                     <?php else : ?>
                        <form action="?url=upload&&id=<?= $_GET['id'] ?>" method="post" enctype="multipart/form-data">
                           <?= @$alert ?>
                           <input type="hidden" name="tanggal" value="<?= date("Y-m-d") ?>">
                           <input type="hidden" name="userid" value="<?= $sesi['UserID'] ?>">
                           <input type="file" name="foto" id="imgUpload" class="d-none" onchange="imgTitle()">
                           <div class="mb-3">
                              <label for="judul">Judul Foto</label>
                              <input type="text" class="form-control rounded-3" value="<?= $fotoVal['JudulFoto'] ?>" name="judul" id="judul" required>
                           </div>
                           <div class="mb-3">
                              <label for="deskripsi">Deskripsi Foto</label>
                              <textarea name="deskripsi" id="deskripsi" cols="30" rows="8" required class="form-control rounded-3"><?= $fotoVal['DeskripsiFoto'] ?></textarea>
                           </div>
                           <div class="mb-3">
                              <label for="album">Album Foto</label>
                              <select name="album" class="form-select rounded-3" id="album">
                                 <?php foreach ($album as $a) : ?>
                                    <option value="<?= $a['AlbumID'] ?>" <?= $a['AlbumID'] == $fotoVal['AlbumID'] ? 'selected' : ''; ?>><?= $a['NamaAlbum'] ?></option>
                                 <?php endforeach; ?>
                              </select>
                           </div>
                           <button type="submit" class="btn btn-primary" name="UbahGambar">Simpan perubahan <i class="fa-solid fa-floppy-disk fa-fw"></i></button>
                           <a href="?url=dataFoto" class="btn btn-dark">Data Foto <i class="fa-solid fa-fw fa-images"></i></a>
                        </form>
                     <?php endif; ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   function imgTitle() {
      const fileInput = document.getElementById('imgUpload');
      const fileName = fileInput.files[0].name;
      const showPreview = document.getElementById('showPreview');
      showPreview.textContent = fileName;
   }
</script>