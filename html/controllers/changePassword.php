<?php
require './database.php';
session_start();

if(isset($_POST['oldPassword'])){
    $oldPassword = $_POST['oldPassword'];
}else{
    $_SESSION['error']='Password lama tidak boleh kosong';
    die();
}

if(isset($_POST['newPassword'])){
    $newPassword = $_POST['newPassword'];
}else{
    $_SESSION['error']='Password baru tidak boleh kosong';
    die();
}

if(isset($_POST['confirmPassword'])){
    $confirmPassword = $_POST['confirmPassword'];
}else{
    $_SESSION['error']='Konfirmasi password tidak boleh kosong';
    die();
}

$q = $connect->prepare("SELECT * FROM `registry_admin` WHERE id=?");
$q->bind_param('i',$_SESSION['id']);
$q->execute();
$result = $q->get_result()->fetch_assoc();
$q->close();
$iscorrect = password_verify($oldPassword,$result['password']);

if($iscorrect){
    if($newPassword == $confirmPassword){
        $newHashedPassword = password_hash($newPassword,PASSWORD_DEFAULT);
        $q = $connect->prepare("UPDATE `registry_admin` SET `password`=?,needReset=0 WHERE id=?");
        $q->bind_param('si',$newHashedPassword,$_SESSION['id']);
        $q->execute();
        $q->close();
        $_SESSION['success']='Password berhasil diubah';
        $_SESSION['needReset']=0;
        header("location:./../pembelian.php");
    }else{
        $_SESSION['error']='Password baru dan konfirmasi password tidak sama';
        header("location:./../gantiPassword.php");
    }
}else{
    $_SESSION['error']='Password lama tidak sama';
    header("location:./../gantiPassword.php");
}
