<?php
$servername = "localhost";
$username = "";
$password = "";
$database = "radius";

$connect = mysqli_connect($servername,$username,$password,$database);

if($connect->connect_error){
	echo "Could Not Connect";
	die("Could Not Connect To Database");
}else{
	//echo "Connection Success<br>";
}

?>
