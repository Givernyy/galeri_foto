<div class="container wrapper pt-5">
   <?php
   if (isset($_GET['keyword'])) {
      $keyword = $_GET['keyword'];
      $stmt = $conf->prepare("SELECT * FROM foto WHERE JudulFoto LIKE ?");
      $searchTerm = '%' . $keyword . '%';
      $stmt->bind_param("s", $searchTerm); +
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows != 0) :
         while ($foto = $result->fetch_assoc()) : ?>
            <a href="?url=detail&&id=<?= $foto['FotoID'] ?>" class="box text-center"><img src="uploads/img/<?= $foto['NamaFile'] ?>" alt="<?= $foto['JudulFoto'] ?>"></a>
         <?php endwhile;
      else : ?>
         <h4 class="text-muted">Tidak Ada Foto</h4>
      <?php endif;
      $stmt->close();
   } else {
      $result=mysqli_query($conf, "SELECT * FROM foto");
      while ($foto = $result->fetch_assoc()) : ?>
         <a href="?url=detail&&id=<?= $foto['FotoID'] ?>" class="box text-center"><img src="uploads/img/<?= $foto['NamaFile'] ?>" alt="<?= $foto['JudulFoto'] ?>"></a>
   <?php endwhile;
   } ?>
</div>