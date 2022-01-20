<?php
	include './controllers/database.php';
	session_start();

	$q = $connect->prepare("SELECT * FROM `registry_admin` WHERE id=?");
	$q->bind_param('i',$_SESSION['id']);
	$q->execute();
	$result = $q->get_result()->fetch_assoc();
	$q->close();

	if($result['role'] != 'SuperAdmin'){
		$_SESSION['error']='Anda tidak memiliki hak akses untuk mengakses halaman ini';
		header("location:./pembelian.php");
		die();
	}

	header('Content-Type: text/csv; charset=utf-8');  
	header('Content-Disposition: attachment; filename=data.csv'); 

	$output = fopen("php://output", "w");
	fputcsv($output, array('Nomor Tiket', 'Nomor Telp', 'Tanggal', 'Total Penjualan', 'Jumlah Tiket')); 

	if(!empty($_POST['from'])){
		$from = $_POST['from'];
	}else{
		$from = "1990-01-01";
	}
	if(!empty($_POST['to'])){
		$to = $_POST['to']." 23:59:59";
	}else{
		$to = date("Y-m-d")." 23:59:59";
	}

	$q = $connect->prepare("SELECT * FROM `registry_data` WHERE date>? AND date<? ORDER BY id ASC");
	$q->bind_param('ss',$from,$to);
	$q->execute();
	$result = $q->get_result();
	$q->close();

	while($row = $result->fetch_assoc()){
		fputcsv($output, $row);
	}
	fputcsv($output,["","","","=sum(D2:D".($result->num_rows+1).")"]);
	fclose($output);
?>