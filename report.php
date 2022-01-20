<?php
	session_start();
	include "./controllers/database.php";

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
    <title>Laporan </title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
	<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500&display=swap');
</style>
</head>
<body>
<?php include './navbar.php'; ?>

<div class="container pt-4">
	<form action="" method="GET">
	<div class="row justify-content-center">
		<input type="hidden" value="1" name="run">
		<div class="col" align="center">
			Dari <input type=date name="from">
		</div>
		<div class="col" align="center">
			Sampai <input type=date name="to">
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col" align="center">
			<input type="submit" class="btn btn-primary" value="Kirim">
		</div>
	</form>
</div>

<?php
	if(!empty($_GET['run'])){
		if(!empty($_GET['from'])){
			$from = $_GET['from'];
		}else{
			$from = "1970-01-01";
		}
		if(!empty($_GET['to'])){
			$to = $_GET['to']." 23:59:59";
		}else{
			$to = date("Y-m-d")." 23:59:59";
		}

		$q = $connect->prepare("SELECT * FROM `registry_data` WHERE date>? AND date<? ORDER BY id DESC");
		$q->bind_param('ss',$from,$to);
		$q->execute();
		$result = $q->get_result();
		$q->close();

		echo "DARI: ".$from."<br>";
		echo "SAMPAI: ".$to."<br><br>";

		?>
		<form method="post" action="export.php">
			<input type="hidden" name="from" value="<?=$from?>">
			<input type="hidden" name="to" value="<?=$to?>">
			<input type="submit" class="btn btn-primary" value="Export">
		</form>
		

		<table class='table mt-5'>
			<thead>
				<tr>
					<th scope="col">Nomor Tiket</th>
					<th scope="col">Nomor HP</th>
					<th scope="col">Tanggal</th>
					<th scope="col">Total Penjualan</th>
					<th scope="col">Jumlah Tiket</th>
				</tr>
			</thead>
			<tbody>
					
		<?php
		while($row = $result->fetch_assoc()){
	  		?>
	  			<tr>
					<th scope="col"><?=$row['id']?></th>
					<th scope="col"><?=$row['phoneno']?></th>
					<th scope="col"><?=$row['date']?></th>
					<th scope="col"><?=$row['totalprice']?></th>
					<th scope="col"><?=$row['jumlahticket']?></th>
				</tr>

	  		<?php
	  	}
	 }
	?>
</tbody>
</table>
</form>
</div>
<script type="text/javascript" src="./css/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="./css/bootstrap.min.js"></script>
</body>
</html>