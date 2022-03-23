<?php
	session_start();


	if(!isset($_SESSION['id'])){
		header("location:./index.php");
	}

	if(!isset($_SESSION['qr'])){
		echo "Tiket tidak ditemukan!";
	}

	$qr = $_SESSION['qr'];
	$guestuser = $_SESSION['guestuser'];
	$guestpassword = $_SESSION['guestpassword'];

	$mi = new MultipleIterator();
	$mi->attachIterator(new ArrayIterator($guestuser));
	$mi->attachIterator(new ArrayIterator($guestpassword));
	$mi->attachIterator(new ArrayIterator($qr));

	foreach ($mi as $value) {
		echo "Nomor Tiket : ".$_SESSION['nomortiket']."<br><br>";
		echo "Rp".number_format($_SESSION['hargapertiket'], 0, ',', '.')."/Ticket<br>";
		list($username,$password,$qraddress) = $value;
		echo "Username: ".$username."<br>";
		echo "Password: ".$password."<br><br>";
		echo '<img src="./tmp/'.$qraddress.'"><br><br>';
		echo "<br><br><br><br>";
		echo "<hr>";
		echo "<br><br><br><br><br><br><br><br><br>";
	}


?>
<script type="text/javascript">
	window.print();
</script>
