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

	echo "Nomor Tiket : ".$_SESSION['nomortiket']."<br><br>";
	echo "Rp".number_format($_SESSION['hargapertiket'], 0, ',', '.')."/Ticket<br>";
	echo $_SESSION['qty']." Ticket<br>";
	echo "Jumlah : Rp".number_format($_SESSION['jumlahbayar'], 0, ',', '.')."<br>";

	foreach ($mi as $value) {
		echo "<hr>";
		list($username,$password,$qraddress) = $value;
		echo "Username: ".$username."<br>";
		echo "Password: ".$password."<br><br>";
		echo '<img src="./tmp/'.$qraddress.'"><br><br>';
	}

	echo"<hr>";


?>
<script type="text/javascript">
	window.print();
</script>
