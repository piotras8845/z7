<?php
error_reporting( 0 );
header("Location: index2.php");
$nazwa=$_POST['nazwa'];
$usr=$_COOKIE['userlogin'];
mkdir ("users/$usr/$nazwa", 0777);
?>