<?php

session_start();

$q = $connect->prepare("SELECT * FROM `registry_admin` WHERE id=?");
$q->bind_param('i',$_SESSION['id']);
$q->execute();
$result = $q->get_result()->fetch_assoc();
$q->close();