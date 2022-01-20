<?php
include "./controllers/database.php";
session_start();

if(isset($_SESSION['error'])){?>
	<div class="alert alert-danger" role="alert">
		<?=$_SESSION['error']?>
	</div>
	<?php 
	unset($_SESSION['error']);
	}

	if(!isset($_SESSION['id'])){
		header("location:./index.php");
	}


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

?>

<html>
<head>
    <title>SuperAdmin Panel</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
</head>

<body>
    <?php include './navbar.php'; ?>
	<div class="container" style="padding-top: 100px">
    <a href="./buatAdminBaru.php" class="btn btn-primary">Buat Admin Baru</a>
    <!-- <a href="./editAdmin.php" class="btn btn-primary">Edit Admin</a> -->
    <a href="./editKecepatan.php" class="btn btn-primary">Edit Kecepatan</a>
    <a href="./editHarga.php" class="btn btn-primary">Edit Harga Ticket</a>

</div>
<script type="text/javascript" src="./css/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="./css/bootstrap.min.js"></script>
</body>
</html>