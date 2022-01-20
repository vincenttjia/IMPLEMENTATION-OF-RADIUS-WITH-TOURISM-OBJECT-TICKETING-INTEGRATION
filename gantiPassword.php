<?php
include "./controllers/database.php";
session_start();

	if(!isset($_SESSION['id'])){
		header("location:./index.php");
	}
    
    if(isset($_SESSION['error'])){?>
        <div class="alert alert-danger" role="alert">
            <?=$_SESSION['error']?>
        </div>
        <?php 
            unset($_SESSION['error']);
            }
            

?>
<html>
<head>
    <title>Ganti Password</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
</head>

<body>
    <?php include './navbar.php'; ?>
	<div class="container" style="padding-top: 100px">
    <form action="./controllers/changePassword.php" method="POST">
       	<div class="form-group">
			<label for="oldPassword" class="control-label">Password Lama:</label>
            <input type="password" class="form-control input-lg" id="oldPassword" name="oldPassword" placeholder="Password Lama" autofocus>
        </div>
        <div class="form-group">
			<label for="newPassword" class="control-label">Password Baru:</label>
            <input type="password" class="form-control input-lg" id="newPassword" name="newPassword" placeholder="Password Baru" autofocus>
        </div>
        <div class="form-group">
			<label for="confirmPassword" class="control-label">Password Baru:</label>
            <input type="password" class="form-control input-lg" id="confirmPassword" name="confirmPassword" placeholder="Konfirmasi Password Baru" autofocus>
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