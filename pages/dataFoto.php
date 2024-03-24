<div class="container mt-5 pt-5">
   <div class="row">
      <?php
      if (!isset($_SESSION['user'])) {
         header("Location: auth.php");
      }
      $dataFoto = mysqli_query($conf, "SELECT * FROM foto WHERE UserID='$sesi[UserID]'");
      if($_GET['url']=='dataFoto' && isset($_GET['id'])){
         $selectFoto=mysqli_fetch_array(mysqli_query($conf, "SELECT * FROM foto WHERE FotoID='".@$_GET['id']."'"));
         $query="DELETE FROM foto WHERE FotoID=?";
         $stmt=$conf->prepare($query);
         $stmt->bind_param("s", $_GET['id']);
         $stmt->execute();
         unlink("uploads/img/".$selectFoto['NamaFile']);
         header("Location: ?url=dataFoto");
      }
        if (!empty(mysqli_num_rows($dataFoto))) :
         foreach ($dataFoto as $foto) : ?>
            <div class="col-6 col-md-4 col-lg-3">
               <div class="card">
                  <img src="uploads/img/<?= $foto['NamaFile'] ?>" class="w-100 rounded">
                  <div class="card-body">
                     <p><?= $foto['JudulFoto'] ?></p>
                     <a href="?url=upload&&id=<?= $foto['FotoID'] ?>" class="btn btn-warning">Edit</a>
                     <a href="?url=dataFoto&&id=<?= $foto['FotoID'] ?>" class="btn btn-danger">Hapus</a>
                  </div>
               </div>
            </div>
         <?php endforeach; ?>
      <?php else : ?>
         <div class="col-12">
            <p class="fs-3">Tidak ada foto</p>
         </div>
      <?php endif; ?>
   </div>
</div>