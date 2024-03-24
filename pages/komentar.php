<?php 
if (!isset($_SESSION['user'])) {
   header("Location: auth.php");
}
$fotoid=@$_GET['id'];
$userid=@$sesi['UserID'];
$tanggalKomentar=date("Y-m-d");
$komentarID=@$_GET['komentarid'];
$komentar=@$_POST["komentar"];
$komentarBaru=@$_POST["komentarBaru"];
if(isset($_POST['TambahKomentar'])){
   $query="INSERT INTO komentarfoto(FotoID, UserID, IsiKomentar, TanggalKomentar) VALUES(?, ?, ?, ?)";
   $stmt=$conf->prepare($query);
   $stmt->bind_param("ssss", $fotoid, $userid, $komentar, $tanggalKomentar);
   $stmt->execute();
   header("Location: ?url=detail&&id=".$fotoid);
}elseif(@$_GET['proses']=='hapus'){
   $query="DELETE FROM komentarfoto WHERE KomentarID=? AND FotoID=? ";
   $stmt=$conf->prepare($query);
   $stmt->bind_param("ss", $komentarID, $fotoid);
   if ($stmt->execute()) {header("Location: ?url=detail&id=".$fotoid);} else {echo "Error :".$stmt->error;}
}elseif(@$_GET['proses']=='edit'){
   $query="UPDATE komentarfoto SET IsiKomentar=?, TanggalKomentar=? WHERE KomentarID=?";
   $stmt=$conf->prepare($query);
   $stmt->bind_param("sss", $komentarBaru, $tanggalKomentar, $komentarID);
   if ($stmt->execute()) {header("Location: ?url=detail&id=".$fotoid);} else {echo "Error :".$stmt->error;}
}
?>