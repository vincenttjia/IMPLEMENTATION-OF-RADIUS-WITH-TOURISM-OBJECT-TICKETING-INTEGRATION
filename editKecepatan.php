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

$q = $connect->prepare("SELECT * FROM `radgroupreply` WHERE groupname='guest' AND attribute='Mikrotik-Rate-Limit'");
$q->execute();
$result = $q->get_result();
$q->close();

if($result->num_rows > 0){
    $row = $result->fetch_assoc()['value'];

}else{
    $q = $connect->prepare("INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES ('guest','Mikrotik-Rate-Limit',':=','2M/2M 4M/4M 2M/2M 40/40')");
    $q->execute();
    $q->close();
    
    $q = $connect->prepare("INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES ('guest','Framed-Pool',':=','guest_pool')");
    $q->execute();
    $q->close();

    $q = $connect->prepare("INSERT INTO `radgroupcheck` (`groupname`,`attribute`,`op`,`value`) VALUES ('guest','Framed-Protocol','==','PPP')");
    $q->execute();
    $q->close();

    $q = $connect->prepare("INSERT INTO radusergroup (username,groupname,priority) values ('guest_profile','guest',10);");
    $q->execute();
    $q->close();

    $row = '2M/2M 4M/4M 2M/2M 40/40';
}

$string = explode(" ",$row);
$string1 = explode("/",$string[0]);
$string2 = explode("/",$string[1]);
$string3 = explode("/",$string[2]);
$string4 = explode("/",$string[3]);

?>
<html>
<head>
    <title>Edit Kecepatan</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
</head>

<body>
<?php include './navbar.php'; ?>
    <form action="./controllers/editKecepatanController.php" method="POST">
        <div class="container" style="padding-top: 100px">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="limitDown" class="control-label">Limit Down:</label>
                        <input type="text" class="form-control input-lg" required id="limitDown" name="limitDown" value=<?=$string1[0]?> autofocus onfocus="var temp_value=this.value; this.value=''; this.value=temp_value">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="limitUp" class="control-label">Limit Up:</label>
                        <input type="text" class="form-control input-lg" required id="limitUp" name="limitUp" value=<?=$string1[1]?>>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="burstDown" class="control-label">Burst Down:</label>
                        <input type="text" class="form-control input-lg" required id="burstDown" name="burstDown" value=<?=$string2[0]?>>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="burstUp" class="control-label">Burst Up:</label>
                        <input type="text" class="form-control input-lg" required id="burstUp" name="burstUp" value=<?=$string2[1]?>>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="batasanBurstDown" class="control-label">Batasan Burst Down:</label>
                        <input type="text" class="form-control input-lg" required id="batasanBurstDown" name="batasanBurstDown" value=<?=$string3[0]?>>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="batasanBurstUp" class="control-label">Batasan Burst Up:</label>
                        <input type="text" class="form-control input-lg" required id="batasanBurstUp" name="batasanBurstUp" value=<?=$string3[1]?>>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="durasiBurstDown" class="control-label">Durasi Burst Down(Detik):</label>
                        <input type="number" class="form-control input-lg" required id="durasiBurstDown" name="durasiBurstDown" value=<?=$string4[0]?>>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="durasiBurstUp" class="control-label">Durasi Burst Up(Detik):</label>
                        <input type="number" class="form-control input-lg" required id="durasiBurstUp" name="durasiBurstUp" value=<?=$string4[1]?>>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <input type="submit" id="loginbtn" value="KONFIRMASI" class="btn btn-primary btn-block btn-lg">
            </div>
        </div>
    </form>

</div>
<script type="text/javascript" src="./css/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="./css/bootstrap.min.js"></script>
</body>
</html>