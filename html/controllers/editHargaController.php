<?php
include "./database.php";
session_start();

$q = $connect->prepare("SELECT * FROM `registry_admin` WHERE id=?");
$q->bind_param('i',$_SESSION['id']);
$q->execute();
$result = $q->get_result()->fetch_assoc();
$q->close();

if($result['role'] != 'SuperAdmin'){
    $_SESSION['error']='Anda tidak memiliki hak akses untuk mengakses halaman ini';
    header("Location: ./../pembelian.php");
    die();
}

if(isset($_POST['harga'])){
    $harga = $_POST['harga'];
    $q = $connect->prepare("UPDATE `registry_price` SET `ticketprice`=? WHERE id=1");
    $q->bind_param('i',$harga);
    $q->execute();
    $q->close();
    $_SESSION['success']='Harga tiket berhasil diubah';
    header("Location: ./../admin.php");
}
