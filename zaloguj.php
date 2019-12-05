<?php
$adresip = $_SERVER["REMOTE_ADDR"];
function ip_details($ip)
{
    $json = file_get_contents("http://ipinfo.io/{$ip}/geo");
    $details = json_decode($json);
    return $details;
}
$details = ip_details($adresip);
$ip = $details->ip;

$godzina = date("Y-m-d H:i:s", time());
$user = strtolower($_POST['user']);
$pass = $_POST['pass'];

require_once('connect.php');

$polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
if (!$polaczenie) {
    echo "Błąd: " . mysqli_connect_errno() . " " . mysqli_connect_error();
}
mysqli_query($polaczenie, "SET NAMES 'utf8'");
$zapytaniesql = "SELECT * FROM users WHERE login='$user'";
$rezultat = mysqli_query($polaczenie, $zapytaniesql);
$wiersz = mysqli_fetch_array($rezultat);
$idu = $wiersz[0];
$login = $wiersz[1];
$haslo = $wiersz[2];
$zapytaniesql = "SELECT * FROM blednelogi WHERE users_idu='$idu'";
$rezultat = mysqli_query($polaczenie, $zapytaniesql);
$wiersz1 = mysqli_fetch_array($rezultat);
$proba = $wiersz1[3];

if (!$wiersz) {
    echo "<font color=\"red\">Brak takiego użytkownika!</font><br><br>";
    sleep(10);
    echo "<script>location.href='wyloguj.php';</script>";
} else { // Jeśli $wiersz istnieje
    if ($haslo == $pass) {
        $spr = substr($proba, 0, 2);

        if ($spr == "b-") {
            $blockedTime = substr($proba, 2);
            if (time() < $blockedTime) {
                echo "<b><font color=\"red\">KONTO ZABLOKOWANE<br>Wpisano błędne hasło 3 razy!<br>Zostanie odblokowane: ", date("Y-m-d H:i:s ", $blockedTime), "</font></b>";
                sleep(10);
                echo "<script>location.href='wyloguj.php';</script>";
            } else {
                if ((!isset($_COOKIE['userid'])) || ($_COOKIE['userid'] != $idu)) {
                    setcookie("userid", $idu, mktime(23, 59, 59, date("m"), date("d"), date("Y")));
                    setcookie("userlogin", $login, mktime(23, 59, 59, date("m"), date("d"), date("Y")));
                }

                mysqli_query($polaczenie, "INSERT INTO logi VALUES (NULL,'$godzina','$ip','$idu')");
                mysqli_query($polaczenie, "UPDATE blednelogi SET proba='0' WHERE users_idu='$idu'");
                header('Location: index2.php');
            }
        } else {
            if ((!isset($_COOKIE['userid'])) || ($_COOKIE['userid'] != $idu)) {
                setcookie("userid", $idu, mktime(23, 59, 59, date("m"), date("d"), date("Y")));
                setcookie("userlogin", $login, mktime(23, 59, 59, date("m"), date("d"), date("Y")));
            }
            mysqli_query($polaczenie, "INSERT INTO logi VALUES (NULL,'$godzina','$ip','$idu')");
            mysqli_query($polaczenie, "UPDATE blednelogi SET proba='0' WHERE users_idu='$idu'");
            header("Location: index2.php");
        }
    } else {

        if ($proba == '2') {
            $proba = "b-" . strtotime("+1 minutes", time());
            mysqli_query($polaczenie, "UPDATE blednelogi SET proba='$proba',data='$godzina' WHERE users_idu='$idu'");
        }
        if (substr($proba, 0, 2) == "b-") {
            $blockedTime = substr($proba, 2);
            if (time() < $blockedTime) {
                echo "<font color=\"red\">KONTO ZABLOKOWANE<br>Wpisano błędne hasło 3 razy!<br>Zostanie odblokowane: ", date("Y-m-d H:i:s ", $blockedTime), "</font>";
            } else {
                mysqli_query($polaczenie, "UPDATE blednelogi SET proba='1',datagodzina='$godzina' WHERE users_idu='$idu'");
                echo "<b>Niepoprawne hasło!<br><br></b>";
                echo "Za chwile wrócisz do strony logowania";
                sleep(10);
                echo "<script>location.href='wyloguj.php';</script>";
            }
        } else {
            if (isset($proba)) {
                $proba = $proba + 1;
                mysqli_query($polaczenie, "UPDATE blednelogi SET proba='$proba',datagodzina='$godzina' WHERE users_idu='$idu'");
                echo "<b>Niepoprawne hasło!<br><br></b>";
                echo "Za chwile wrócisz do strony logowania";
                sleep(10);
                echo "<script>location.href='wyloguj.php';</script>";
            } else {
                $proba = $proba + 1;
                mysqli_query($polaczenie, "INSERT INTO blednelogi VALUES (NULL,'$godzina','$ip','$proba','$idu')");
                echo "<b>Niepoprawne hasło!<br><br></b>";
                sleep(10);
                echo "<script>location.href='wyloguj.php';</script>";


                echo "$login";
            }
        }
        mysqli_close($polaczenie);
        echo "<a href=\"index.php\">Powrót</a>";
    }
}
