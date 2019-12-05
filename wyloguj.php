<?php
setcookie("user", "", time() - 3600);
setcookie("user_n", "", time() - 3600);
$dalej="index2.php";
header("Location: $dalej");
?>