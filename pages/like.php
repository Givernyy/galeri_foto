<?php
if (!isset($_SESSION['user'])) {
   header("Location: auth.php");
}
//Membuat variable untuk menampung data dari url dan session 
$fotoid=@$_GET['id'];
$userid=@$sesi['UserID'];
$tanggalLike=date("Y-m-d");
$cekUserLike=mysqli_num_rows(mysqli_query($conf, "SELECT * FROM likefoto WHERE FotoID='$fotoid' AND UserID='$userid'"));
if(@$_GET['url']=="like"){
   if($cekUserLike==0){ //jika belum like maka akan di tambahkan ke database
      $query="INSERT INTO likefoto(FotoID, UserID, TanggalLike) VALUES(?,?,?)";
      $stmt=$conf->prepare($query);
      $stmt->bind_param("sss", $fotoid, $userid, $tanggalLike);
      $stmt->execute();
      header("Location: ?url=detail&&id=".$fotoid);
   }else{ //jika user sudah like maka akan menghapus dari database
      $query="DELETE FROM likefoto WHERE FotoID=? AND UserID=?";
      $stmt=$conf->prepare($query);
      $stmt->bind_param("ss", $fotoid,$userid);
      $stmt->execute();
      header("Location: ?url=detail&&id=".$fotoid);
   }
}
