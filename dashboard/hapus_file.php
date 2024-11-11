<?php
session_start();
include "../config.php";

$id_file = $_GET['id_file'];

$get_file = "SELECT file_name_source, file_name_finish FROM file WHERE id_file='$id_file'";
$data_file = mysqli_query($mysqli, $data_file);

$query = mysqli_query($mysqli, "DELETE FROM file WHERE id_file='$id_file'");

if ($query) {
    echo ("<script language='javascript'>
       window.open('hapus.php', '_blank');
       window.location.href='dekripsi.php';
       window.alert('Berhasil Menghapus File.');
       </script>
       ");
    header('location: file.php');
} else {
    echo ("<script language='javascript'>
       window.open('hapus.php', '_blank');
       window.location.href='dekripsi.php';
       window.alert('Gagal Menghapus File.');
       </script>
       ");
    header('location: file.php');
}
