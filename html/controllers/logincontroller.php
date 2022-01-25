<?php
require './database.php';
session_start();
if(isset($_POST['username'])){
	$username = $_POST['username'];
}else{
	$_SESSION['error']='Nama Pegawai Tidak Boleh Kosong';
	die();
}

if(isset($_POST['password'])){
	$password = $_POST['password'];
}else{
	$_SESSION['error']='Password Tidak Boleh Kosong';
	die();
}

$q = $connect->prepare("SELECT * FROM `registry_admin` WHERE username=?");
$q->bind_param('s',$username);
$q->execute();
$result = $q->get_result()->fetch_assoc();
$q->close();
$iscorrect = password_verify($password,$result['password']);

if($iscorrect){
	session_regenerate_id();
	$_SESSION['id']=$result['id'];
	$_SESSION['username']=$result['username'];
	$_SESSION['role'] = $result['role'];
	if($result['needReset']==1){
		$_SESSION['needReset']=1;
		header('Location: ./../gantiPassword.php');
	}else{
		$_SESSION['needReset']=0;
		header("location:./../pembelian.php");
	}
}else{
	$_SESSION['error']="Nama Pegawai atau Password Salah";
	header("location:./../index.php");
}
