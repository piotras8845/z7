<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Licznerski</title>
</head>

<body>

    <a href="index.php">Strona główna</a></p>


    <form method="POST">
       Wprowadź dane do rejestracji<br><br>
        Login:<br>
        <input type="text" name="nick" maxlength="25" size="25"><br><br>
        Hasło:<br>
        <input type="password" name="haslo" maxlength="25" size="25"><br><br>
        Powtórz hasło:<br>
        <input type="password" name="haslo1" maxlength="25" size="25"><br><br>

        <input type="submit" value="Rejestruj" />
    </form>

    <?php
    $login = $_POST['nick'];
    $pass = $_POST['haslo'];
    $pass1 = $_POST['haslo1'];
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
    if (isset($login)) {
        if ($pass == $pass1) {

            $dodaj = "INSERT INTO users VALUES (NULL,'$login', '$pass')";
            mysqli_query($polaczenie, $dodaj);
            mysqli_close($polaczenie);
            mkdir("users/$login", 0777);
            echo "<script>alert('Konto zostało utworzone')</script>";
        } else {
            echo "<script>alert('Hasła muszą byś takie same!')</script>";
        }
    }
    ?>

</body>

</html>

</html>


