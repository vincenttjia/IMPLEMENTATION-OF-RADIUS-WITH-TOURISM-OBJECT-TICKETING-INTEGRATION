<?php
include "./database.php";
session_start();

$q = $connect->prepare("SELECT * FROM `registry_admin` WHERE id=?");
$q->bind_param('i',$_SESSION['id']);
$q->execute();
$result = $q->get_result()->fetch_assoc();
$q->close();

if($result['role'] != 'SuperAdmin'){
    $_SESSION['error']='Anda tidak memiliki hak akses untuk mengakses halaman ini';
    header("Location: ./../pembelian.php");
    die();
}

if(isset($_POST['limitDown'])){
    $limitDown = $_POST['limitDown'];
    if(!preg_match("/\dM$|\dK$|\dG$/",$limitDown)){
        $_SESSION['error']='Format limit down tidak valid';
        header("Location: ./../editKecepatan.php");
        die();
    }
}else{
    $_SESSION['error']='Format limit down tidak valid';
    header("Location: ./../editKecepatan.php");
    die();
}

if(isset($_POST['limitUp'])){
    $limitUp = $_POST['limitUp'];
    if(!preg_match("/\dM$|\dK$|\dG$/",$limitUp)){
        $_SESSION['error']='Format limit up tidak valid';
        header("Location: ./../editKecepatan.php");
        die();
    }
}else{
    $_SESSION['error']='Format limit up tidak valid';
    header("Location: ./../editKecepatan.php");
    die();
}

if(isset($_POST['burstDown'])){
    $burstDown = $_POST['burstDown'];
    if(!preg_match("/\dM$|\dK$|\dG$/",$burstDown)){
        $_SESSION['error']='Format burst down tidak valid';
        header("Location: ./../editKecepatan.php");
        die();
    }
}else{
    $_SESSION['error']='Format burst down tidak valid';
    header("Location: ./../editKecepatan.php");
    die();
}

if(isset($_POST['burstUp'])){
    $burstUp = $_POST['burstUp'];
    if(!preg_match("/\dM$|\dK$|\dG$/",$burstUp)){
        $_SESSION['error']='Format burst up tidak valid';
        header("Location: ./../editKecepatan.php");
        die();
    }
}else{
    $_SESSION['error']='Format burst up tidak valid';
    header("Location: ./../editKecepatan.php");
    die();
}

if(isset($_POST['batasanBurstDown'])){
    $batasanBurstDown = $_POST['batasanBurstDown'];
    if(!preg_match("/\dM$|\dK$|\dG$/",$batasanBurstDown)){
        $_SESSION['error']='Format batasan burst down tidak valid';
        header("Location: ./../editKecepatan.php");
        die();
    }
}else{
    $_SESSION['error']='Format batasan burst down tidak valid';
    header("Location: ./../editKecepatan.php");
    die();
}

if(isset($_POST['batasanBurstUp'])){
    $batasanBurstUp = $_POST['batasanBurstUp'];
    if(!preg_match("/\dM$|\dK$|\dG$/",$batasanBurstUp)){
        $_SESSION['error']='Format batasan burst up tidak valid';
        header("Location: ./../editKecepatan.php");
        die();
    }
}else{
    $_SESSION['error']='Format batasan burst up tidak valid';
    header("Location: ./../editKecepatan.php");
    die();
}

if(isset($_POST['durasiBurstDown'])){
    $durasiBurstDown = $_POST['durasiBurstDown'];
    if(!is_numeric($durasiBurstDown)){
        $_SESSION['error']='Format durasi burst down tidak valid';
        header("Location: ./../editKecepatan.php");
        die();
    }
}else{
    $_SESSION['error']='Format durasi burst down tidak valid';
    header("Location: ./../editKecepatan.php");
    die();
}

if(isset($_POST['durasiBurstUp'])){
    $durasiBurstUp = $_POST['durasiBurstUp'];
    if(!is_numeric($durasiBurstUp)){
        $_SESSION['error']='Format durasi burst up tidak valid';
        header("Location: ./../editKecepatan.php");
        die();
    }
}else{
    $_SESSION['error']='Format durasi burst up tidak valid';
    header("Location: ./../editKecepatan.php");
    die();
}

$limit = $limitDown.'/'.$limitUp.' '.$burstDown.'/'.$burstUp.' '.$batasanBurstDown.'/'.$batasanBurstUp.' '.$durasiBurstDown.'/'.$durasiBurstUp;

echo $limit;

$q = $connect->prepare("UPDATE `radgroupreply` SET `value`=? WHERE groupname='guest' AND attribute='Mikrotik-Rate-Limit'");
$q->bind_param('s',$limit);
$q->execute();
$q->close();

$_SESSION['success']='Berhasil mengubah kecepatan';
header("Location: ./../admin.php");
