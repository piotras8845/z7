<?php
header("Location: index2.php");
$nazwa=$_POST['nazwa'];
$usr=$_COOKIE['userlogin'];
mkdir ("users/$usr/$nazwa", 0777);
?>