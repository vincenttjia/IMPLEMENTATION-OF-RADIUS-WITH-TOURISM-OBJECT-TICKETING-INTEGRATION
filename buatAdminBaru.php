<?php
include "./controllers/database.php";
session_start();

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
    <title>Buat Admin Baru</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
</head>

<body>
<?php include './navbar.php'; ?>
	<div class="container" style="padding-top: 100px">
    <form action="./controllers/createNewAdmin.php" method="POST">
       	<div class="form-group">
			<label for="username" class="control-label">Nama Pengguna:</label>
                <input type="text" class="form-control input-lg" id="username" name="username" placeholder="Nama Pengguna" autofocus>
        </div>
        <div class="form-group">
			<label for="role" class="control-label">Role: </label>
            <select class="form-control input-lg" id="role" name="role">
                <option value="Admin">Admin</option>
                <option value="SuperAdmin">SuperAdmin</option>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" id="loginbtn" value="KONFIRMASI" class="btn btn-primary btn-block btn-lg">
        </div>
    </form>

</div>
<script type="text/javascript" src="./css/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="./css/bootstrap.min.js"></script>
</body>
</html>