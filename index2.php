<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Licznerski</title>
</head>

<body>
    <?php

    error_reporting(0);

    ?>


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
    $dbhost = "serwer1976179.home.pl";
    $dbuser = "31577296_lab7";
    $dbpassword = "Okulary123/";
    $dbname = "31577296_lab7";
    $polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
    if (!$polaczenie) {
        echo "Błąd połączenia z MySQL." . PHP_EOL;
        echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    $idk = $_COOKIE['userid'];
    if (isset($usr)) {
        $query = "SELECT * FROM logi WHERE idu=$idk order by data desc limit 1";
        $result = mysqli_query($polaczenie, $query);
        $rekord1 = mysqli_fetch_array($result);
    }
    ?>

    <?php
    $usr = $_COOKIE['userlogin'];
    if (isset($usr)) {
        ?>

        

        <?php
            echo "Dzień dobry, <b>", $_COOKIE['userlogin'], "</b>";
            ?>
        <b>
            <font color="red">
                <?php
                    if (!empty($wiersz1)) {
                        echo "ostatnie niepoprawne logowanie: ", $wiersz1['datagodzina'], " <hr>";
                    }
                    ?>
            </font>
        </b>

        <p> Lista plików oraz katalogów</p>
        <?php

            $dir = "/lab7/users/$usr";
            $files = scandir($dir);
            $arrlength = count($files);
            for ($x = 2; $x < $arrlength; $x++) {

                if (is_file("/z7/users/$usr/$files[$x]")) {
                    echo "<a href='/lab7/users/$usr/$files[$x]' download='$files[$x]'>$files[$x]</a><br>";
                } else {
                    echo $files[$x], "<br>";
                    $dir2 = "/lab7/users/$usr/$files[$x]";
                    $files2 = scandir($dir2);
                    $arrlength2 = count($files2);
                    for ($y = 2; $y < $arrlength2; $y++) {

                        if (is_file("/lab7/users/$usr/$files[$x]/$files2[$y]")) {
                            echo "&#8594<a href='/lab7/users/$usr/$files[$x]/$files2[$y]' download='$files2[$y]'>$files2[$y]</a>";
                        } else {
                            echo "&#8594", $files2[$y];
                        }
                        echo "<br>";
                    }
                }
            }
            echo "<br>";

            ?>

        <p>Dodaj plik</p>
        <form action="odbieranie.php" method="POST" ENCTYPE="multipart/form-data">
            <?php
                if (is_dir($dir)) {
                    if ($dh = opendir($dir)) {
                        while (($file = readdir($dh)) !== false) {
                            if (is_dir("/lab7/users/$usr/$file") && $file != '.' && $file != '..') {
                                echo "<input type=\"radio\" name=\"folder\" value =$file>$file<br>";
                            }
                        }
                        closedir($dh);
                    }
                }
                ?>
            <input type="file" name="plik" />
            <input type="submit" value="Wyślij plik" />
        </form>

        <p>Stwórz katalog</p>
        <form method="POST" action="nowy.php">
            Nazwa:<input type="text" name="nazwa">
            <input type="submit" value="Stwórz folder" />
        </form>
        
        <a href="index.php">Wyloguj się</a>
        
    <?php
    } else {
        echo "Zaloguj się by móc dodawać pliki ";
    }
    ?>
</body>

</html>