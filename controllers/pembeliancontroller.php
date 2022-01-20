<?php
	session_start();
	

	if(!isset($_SESSION['id'])){
		header("location:./../index.php");
	}

require './database.php';
include './phpqrcode/qrlib.php';

$tempDir = "./../tmp/";

function generateRandomString($length = 4) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

date_default_timezone_set('Asia/Jakarta');

function getDateFormat(){
	return date("d M Y",strtotime(date("d M Y") . "+1 days"));
}


if(isset($_POST['hp'])){
	$hp = $_POST['hp'];
}else{
	$hp = "";

}

if(isset($_POST['jumlah'])){
	$jumlah = $_POST['jumlah'];
	if($jumlah<1){
		$_SESSION['error']="Jumlah Tiket Tidak Valid";
		header("location:./../pembelian.php");
		die();	
	}
}else{
	$_SESSION['error']="Jumlah Tiket Tidak Valid";
	header("location:./../pembelian.php");
	die();
}

$q = $connect->prepare("SELECT * FROM `registry_price` WHERE 1");
$q->execute();
$price = $q->get_result()->fetch_assoc()['ticketprice'];
$q->close();


$total = $price*$jumlah;
$q = $connect->prepare("INSERT INTO `registry_data`(`phoneno`, `date`, `totalprice`, `jumlahticket`) VALUES (?,now(),?,?)");
$q->bind_param('sii',$hp,$total,$jumlah);
$q->execute();
$_SESSION['nomortiket'] = $connect->insert_id;
$q->close();
$_SESSION['qty'] = $jumlah;
$_SESSION['jumlahbayar'] = $total;
$_SESSION['hargapertiket'] = $price;

$_SESSION['qr'] = array();
$_SESSION['guestuser'] = array();
$_SESSION['guestpassword'] = array();

for($i=1;$i<=$jumlah;$i++){
	$q=$connect->prepare("SELECT id FROM `radcheck` ORDER BY id DESC LIMIT 1");
	$q->execute();
	$id = $q->get_result()->fetch_assoc()['id'];
	$id = $id+1;
	$username = "user".$id;
	$password = generateRandomString();
	$q = $connect->prepare("SELECT * FROM `radcheck` WHERE username=?");
	$q->bind_param('s',$username);
	$q->execute();
	$result = $q->get_result();
	$q->close();
	if($result->num_rows<1){

		$q = $connect->prepare("INSERT INTO `radcheck`(`username`, `attribute`, `op`, `value`) VALUES (?,'Cleartext-Password',':=',?)");
		$q->bind_param('ss',$username,$password);
		$q->execute();
		$q->close();

		$q = $connect->prepare("INSERT INTO `radcheck`(`username`, `attribute`, `op`, `value`) VALUES (?,'User-Profile',':=','guest_profile')");
		$q->bind_param('s',$username);
		$q->execute();
		$q->close();

		$q = $connect->prepare("INSERT INTO `radcheck`(`username`, `attribute`, `op`, `value`) VALUES (?,'Expiration',':=',?)");
		$tanggal = getDateFormat();
		$q->bind_param('ss',$username,$tanggal);
		$q->execute();
		$q->close();

		$codeContents = 'http://freewifi.kemendesa.go.id/login?user='.$username.'&password='.$password;
		$randname = mt_rand().".png";
		QRcode::png($codeContents, $tempDir.$randname, QR_ECLEVEL_L, 3);

		$_SESSION['qr'][]=$randname;
		$_SESSION['guestuser'][]=$username;
		$_SESSION['guestpassword'][]=$password;

	}
}



?>
<script type="text/javascript">
	var w = open('./../output.php','Tiket','toolbar=no,menubar=no');
	window.location.replace("./../pembelian.php");
</script>