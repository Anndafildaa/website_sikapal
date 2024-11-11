<?php
session_start();
include "../config.php";   //memasukan koneksi
include "AES.php"; //memasukan file AES

$idfile    = $mysqli->real_escape_string($_POST['fileid']);
$pwdfile   = $mysqli->real_escape_string(substr(md5($_POST["pwdfile"]), 0, 16));
$query     = "SELECT password FROM file WHERE id_file='$idfile' AND password='$pwdfile'";
$sql       = $mysqli->query($query);
if (mysqli_num_rows($sql) > 0) {
    $query1     = "SELECT * FROM file WHERE id_file='$idfile'";
    $sql1       = $mysqli->query($query1);
    $data       = mysqli_fetch_assoc($sql1);

    $file_path  = $data["file_url"];
    $key        = $data["password"];
    $file_name  = $data["file_name_source"];
    $size       = $data["file_size"];

    $file_size  = filesize($file_path);

    $query2     = "UPDATE file SET status='2' WHERE id_file='$idfile'";
    $sql2       = $mysqli->query($query2);

    $mod        = $file_size % 16;

    $aes        = new AES($key);
    $fopen1     = fopen($file_path, "rb");
    $plain      = "";
    $cache      = "file_dekripsi/$file_name";
    $fopen2     = fopen($cache, "wb");

    if ($mod == 0) {
        $banyak = $file_size / 16;
    } else {
        $banyak = ($file_size - $mod) / 16;
        $banyak = $banyak + 1;
    }

    ini_set('max_execution_time', -1);
    ini_set('memory_limit', -1);
    for ($bawah = 0; $bawah < $banyak; $bawah++) {

        $filedata    = fread($fopen1, 16);
        $plain       = $aes->dekripsi($filedata);
        fwrite($fopen2, $plain);
    }

    echo ("<script language='javascript'>
       window.open('download.php', '_blank');
       window.location.href='file.php';
       window.alert('Berhasil mendekripsi file.');
       </script>
       ");
} else {
    echo ("<script language='javascript'>
    window.location.href='dekripsi-file.php?id_file=$idfile';
    window.alert('Maaf, Password tidak sesuai.');
    </script>");
}
