<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Login Page</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/my-login.css">
	<style>
	@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500&display=swap');
	</style>
</head>

<body class="my-login-page">
	<section class="h-100">
		<div class="container h-100">
			<div class="row justify-content-md-center h-100">
				<div class="card-wrapper">
					<div class="brand">
						<img src="img/logo-taman-bunga-celosia.png" height="100" alt="logo">
					</div>
					<div class="card fat">
						<div class="card-body">
							<h4 style = "text-align: center;" class="card-title">Login</h4>
							<form action="./controllers/logincontroller.php" method="POST" class="my-login-validation">
								<?php
								session_start();
								if(isset($_SESSION['error'])){?>
									<div class="alert alert-danger" role="alert">
  										<?=$_SESSION['error']?>
									</div>
								<?php 
								unset($_SESSION['error']);
								}
								?>
								<div class="form-group">
									<label for="username">Nama Pegawai</label>
									<input id="username" type="text" class="form-control" name="username" value="" required autofocus>
									<div class="invalid-feedback">
										Nama Pegawai Salah
									</div>
								</div>

								<div class="form-group">
									<label for="password">Kata Sandi
									</label>
									<input id="password" type="password" class="form-control" name="password" required data-eye>
								    <div class="invalid-feedback">
								    	Kata Sandi Dibutuhkan
							    	</div>
								</div>


								<div class="form-group m-0">
									<button style="background-color: #118240; border-color:#118240;" type="submit" class="btn btn-primary btn-block">
										Login
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</body>
</html>