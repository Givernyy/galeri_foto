<?php
if (!isset($_GET['id'])) {
   header("Location: ./");
}
$fotoid = @$_GET["id"];
$userid = @$sesi['UserID'];
$cekUserLike = mysqli_num_rows(mysqli_query($conf, "SELECT * FROM likefoto WHERE FotoID='$fotoid' AND UserID='$userid'"));
$countLike = mysqli_num_rows(mysqli_query($conf, "SELECT * FROM likefoto WHERE FotoID='$fotoid'"));
$dataFoto = mysqli_fetch_array(mysqli_query($conf, "SELECT * FROM foto WHERE FotoID='$fotoid'"));
$komentarEdit = mysqli_fetch_array(mysqli_query($conf, "SELECT * FROM komentarfoto WHERE FotoID='$_GET[id]' AND UserID='" . @$sesi['UserID'] . "'"));
?>
<div class="container pt-5 mt-5 mt-lg-0 pt-lg-0 mt-md-5 pt-md-0">
   <div class="row vh-100 justify-content-center align-items-center">
      <div class="col-10 col-md-7 col-lg-4">
         <div class="card border-0 rounded-4 shadow">
            <div class="card-body p-0">
               <img src="uploads/img/<?= $dataFoto['NamaFile'] ?>" alt="" class="mw-100 rounded-4">
            </div>
         </div>
      </div>
      <div class="col-10 col-md-7 col-lg-5">
         <div class="card rounded-4 border-0 shadow">
            <div class="card-body">
               <div class="overflow-auto" style="height: 500px;">
                  <?php $komentar = mysqli_query($conf, "SELECT * FROM komentarfoto WHERE komentarfoto.FotoID='" . @$_GET['id'] . "'"); ?>
                  <?php if (mysqli_num_rows($komentar) != 0) : ?>
                     <?php
                     while ($komens = mysqli_fetch_array($komentar)) :
                        $username = mysqli_fetch_array(mysqli_query($conf, "SELECT * FROM user WHERE UserID='$komens[UserID]'"));
                        if ($username["UserID"] == @$sesi['UserID'] && isset($sesi['UserID'])) :
                     ?>
                           <div class="d-flex justify-content-between">
                              <p class="mb-0 fw-bold"><?= $username['Username'] ?></p>
                              <div class="dropdown">
                                 <a href="#" data-bs-toggle="dropdown" role="button" class="btn"><i class="fa-solid fa-ellipsis-vertical fa-fw"></i></a>
                                 <div class="dropdown-menu">
                                    <a href="?url=detail&&edit&&id=<?= @$_GET['id'] ?>" class="dropdown-item">Edit</a>
                                    <a href="?url=komentar&&proses=hapus&&id=<?= @$_GET['id'] ?>&&komentarid=<?= $komens['KomentarID'] ?>" class="dropdown-item">Hapus</a>
                                 </div>
                              </div>
                           </div>
                           <p class="mb-1"><?= $komens['IsiKomentar'] ?></p>
                           <p class="mb-0 text-muted small"><?= $komens['TanggalKomentar'] ?></p>
                           <hr>
                        <?php else : ?>
                           <p class="mb-0 fw-bold"><?= $username['Username'] ?></p>
                           <p class="mb-1"><?= $komens['IsiKomentar'] ?></p>
                           <p class="mb-0 text-muted small"><?= $komens['TanggalKomentar'] ?></p>
                           <hr>
                        <?php endif; ?>
                     <?php endwhile; ?>
                  <?php else : ?>
                     <p class="text-center text-muted">Belum ada Komentar</p>
                     <hr>
                  <?php endif; ?>
               </div>
               <?php if (!isset($_GET['edit'])) : ?>
                  <form action="?url=komentar&&id=<?= $fotoid ?>" method="post">
                     <div class="mb-3 d-flex align-items-center justify-content-end gap-2">
                        <p class="fw-semibold mb-0"><?= $countLike ?></p>
                        <a href="?url=like&&id=<?= $fotoid ?>" class="d-block rounded-circle text-center" style="line-height: 51px; background-color: #eeeeee; width: 50px; height: 50px;"><i class="fa-xl <?= $cekUserLike == 0 ? 'text-dark fa-regular' :  'text-danger fa-solid' ?> fa-heart fa-fw"></i></a>
                     </div>
                     <div class="d-flex gap-3 justify-content-center align-items-center">
                        <a href="./" class="btn btn-dark rounded-pill"><i class="fa-solid fa-arrow-left fa-fw"></i></a>
                        <input type="text" name="komentar" class="form-control rounded-pill">
                        <button type="submit" class="btn btn-dark rounded-pill" name="TambahKomentar"><i class="fa-solid fa-paper-plane fa-fw"></i></button>
                     </div>
                  </form>
               <?php else : ?>
                  <form action="?url=komentar&&proses=edit&&id=<?= $fotoid ?>&&komentarid=<?= $komentarEdit['KomentarID'] ?>" method="post">
                     <div class="mb-3 d-flex align-items-center justify-content-end gap-2">
                        <p class="fw-semibold mb-0"><?= $countLike ?></p>
                        <a href="?url=like&&id=<?= $fotoid ?>" class="d-block rounded-circle text-center" style="line-height: 51px; background-color: #eeeeee; width: 50px; height: 50px;"><i class="fa-xl <?= $cekUserLike == 0 ? 'text-dark fa-regular' :  'text-danger fa-solid' ?> fa-heart fa-fw"></i></a>
                     </div>
                     <div class="d-flex gap-3 justify-content-center align-items-center">
                        <a href="./" class="btn btn-dark rounded-pill"><i class="fa-solid fa-arrow-left fa-fw"></i></a>
                        <input type="text" value="<?= $komentarEdit['IsiKomentar'] ?>" name="komentarBaru" class="form-control rounded-pill">
                        <button type="submit" class="btn btn-dark rounded-pill" name="UbahKomentar"><i class="fa-solid fa-paper-plane fa-fw"></i></button>
                     </div>
                  </form>
               <?php endif; ?>
            </div>
         </div>
      </div>
   </div>
</div>