<?php
if($_SESSION['needReset']==0){?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	    <div class="navbar-collapse collapse w-100 dual-collapse2 order-1 order-md-0">
	        <ul class="navbar-nav ml-auto text-center">
	            <li class="nav-item">
	                <a class="nav-link" href="./pembelian.php">Pembelian Tiket</a>
	            </li>
                <li class="nav-item">
	                <a class="nav-link" target="_blank" href="./output.php">Cetak Tiket Terakhir</a>
	            </li>
                <?php if($_SESSION['role'] == 'SuperAdmin'){?>
                    <li class="nav-item">
                        <a class="nav-link" href="./report.php">Laporan</a>
                    </li>
                <?php }?>
	        </ul>
	    </div>
	    <div class="mx-auto my-2 pl-3 pr-3 order-0 order-md-1 position-relative">
	        <a class="mx-auto" href="#">
	            <img src="./img/logo.png" height="100" class="rounded-circle">
	        </a>
	        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
	            <span class="navbar-toggler-icon"></span>
	        </button>
	    </div>
	    <div class="navbar-collapse collapse w-100 dual-collapse2 order-2 order-md-2">
	        <ul class="navbar-nav mr-auto text-center">
                <?php if($_SESSION['role'] == 'SuperAdmin'){?>
                    <li class="nav-item">
                        <a class="nav-link" href="./admin.php">Admin Panel</a>
                    </li>
                <?php }?>
                <li class="nav-item">
	                <a class="nav-link" href="./gantiPassword.php">Ganti Password</a>
	            </li>
	            <li class="nav-item">
	                <a class="nav-link" href="./logout.php">Keluar</a>
	            </li>
	        </ul>
	    </div>
	</nav>

    <?php if(isset($_SESSION['error'])){?>
	<div class="alert alert-danger" role="alert">
		<?=$_SESSION['error']?>
	</div>
	<?php
        unset($_SESSION['error']);
        }



        if(isset($_SESSION['success'])){?>
            <div class="alert alert-success" role="alert">
                <?=$_SESSION['success']?>
            </div>
            <?php
            unset($_SESSION['success']);
            }
    }
    ?>
