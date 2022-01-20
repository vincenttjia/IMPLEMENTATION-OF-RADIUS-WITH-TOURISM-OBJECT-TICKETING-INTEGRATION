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

if(isset($_POST['username'])){
    $username = $_POST['username'];
}else{
    $_SESSION['error']='Nama Pegawai Tidak Boleh Kosong';
    header("Location: ./../buatAdminBaru.php");
    die();
}

if(isset($_POST['role'])){
    if($_POST['role']!="SuperAdmin" && $_POST['role']!="Admin"){
        $_SESSION['error']='Role tidak valid';
        header("Location: ./../buatAdminBaru.php");
        die();
    }else{
        $role = $_POST['role'];
    }
}else{
    $_SESSION['error']='Role tidak boleh kosong';
    header("Location: ./../buatAdminBaru.php");
    die();
}

// Check if username exists in database
$q = $connect->prepare("SELECT * FROM `registry_admin` WHERE username=?");
$q->bind_param('s',$username);
$q->execute();
$result = $q->get_result()->num_rows;
$q->close();

if($result > 0){
    $_SESSION['error']='Username sudah ada';
    header('Location: ../buatAdminBaru.php');
    die();
}

// Generate random password
$password = substr(md5(rand()), 0, 15);

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

# Create a new user
$q = $connect->prepare("INSERT INTO `registry_admin` (`username`,`password`,`role`) VALUES (?,?,?)");
$q->bind_param('sss',$username,$hashedPassword,$role);
$q->execute();
$q->close();

$_SESSION['success']=$role.' berhasil dibuat dengan password '.$password;

header('location:../admin.php');