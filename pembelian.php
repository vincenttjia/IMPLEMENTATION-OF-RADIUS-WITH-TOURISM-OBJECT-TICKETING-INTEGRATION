<?php
	session_start();
	if(!isset($_SESSION['id'])){
		header("location:./index.php");
	}
?>
<html>
<head>
    <title>Pembelian Tiket</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500&display=swap');
    </style>
</head>

<body>
	<?php include './navbar.php'; ?>
	<div class="container" style="padding-top: 100px">
    <form onsubmit="return confirm('Apakah Data Sudah betul\nNo HP: '+document.querySelector('#No_HP').value+'\nJenis Tiket:'+document.querySelector('#jenistiket').value+'Jumlah Tiket: '+document.querySelector('#jumlahTiket'))" action="./controllers/pembeliancontroller.php" method="POST">
       	<div class="form-group">
			<label for="No HP" class="control-label">No Hp:</label>
                <input type="text" class="form-control input-lg" id="No_HP" name="hp" placeholder="No HP" autofocus>
        </div>
        <div class="form-group">
			<label for="jumlahTiket" class="control-label">Jumlah Tiket: </label>
            <input type="number" class="form-control input-lg" id="jumlahTiket" name="jumlah" placeholder="Masukan Jumlah" min=1 value=1 required>
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