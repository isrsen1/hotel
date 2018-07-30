<?php
require_once 'pomocneSkripte/aplikacijskiOkvir.php';

if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

Sesija::kreirajSesiju();
$korisnik = Sesija::dajKorisnika();
global $baza;
if ($korisnik) {
    header('Location: index.php');
}

if (isset($_COOKIE['zapamtiMe'])) {
    $value = $_COOKIE['zapamtiMe'];
}
if (isset($_POST) && isset($_POST["btnPrijava"])) {
    $korisnik = Sesija::dajKorisnika();

    if ($korisnik) {
        echo "Vec ste ulogirani";
    } else {
        $korime = $_POST["korime"];
        $lozinka = $_POST["lozinka"];

        if (Korisnik::autentikacija($korime, $lozinka) == -1) {
            
        } else {
            Sesija::kreirajKorisnika($korime);
            if (isset($_POST['zapamtiMe']) && $_POST['zapamtiMe'] == 1) {
                $cookie_name = "zapamtiMe";
                $cookie_value = $_POST["korime"];
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
            }
            header('Location: index.php');
        }
    }
} else if (isset($_POST["btnZaboravljenaLozinka"]) && isset($_POST["korime"])) {
    $korisnickoIme = $_POST["korime"];
    $upit = "SELECT email FROM korisnik WHERE korime = '$korisnickoIme'";
    $rezultatUpita = $baza->selectDB($upit);
    if (mysqli_num_rows($rezultatUpita) == 1) {
        $row = mysqli_fetch_assoc($rezultatUpita);
        $email = $row['email'];
        echo $email;
    }
        $skripta = "index.php";
        $novaLozinka = rand();
        $sol = sha1(time());
        $kriptirana = sha1($sol . "." . $novaLozinka);
        
        $mail_from = "From: noreply";
        $mail_subject = "Zaboravljena lozinka";
        $mail_body = "Nova lozinka: $novaLozinka";

        if (mail($email, $mail_subject, $mail_body, $mail_from)) {
            $upit = "UPDATE  `WebDiP2017x134`.`korisnik` SET  `lozinka` =  '$novaLozinka',
`sol` =  '$sol',
`kriptirana` =  '$kriptirana' WHERE  `korisnik`.`korIme` ='$korisnickoIme';";
            $baza->updateDB($upit, $skripta);
            $baza->zatvoriDB();
        }
    }
    ?>
    <!DOCTYPE html>

    <html>
        <head>
            <meta charset="UTF-8">
            <title> Prijava </title>
            <meta charset="UTF-8">
            <meta name="author" content="Ivan Srsen">
            <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src = "http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
            <script  src = "javascript/prijava.js"></script>
        </head>
        <body>
            <ul>
                <li><a href="registracija.php">Registracija</a>    <!-- Link koji vodi na registraciju -->
            </ul>

            <form id = "prijava" method = "post" name = "prijava" action = "prijava.php">
                <table>
                    <tr> 
                        <td> <label for = "korime"> Korisničko ime: </label> </td>
                        <td> <input type = "text" id = "korime" name = "korime" <?php
    if (isset($value)) {
        echo "value = '$value'";
    }
    ?> > </td> 
                </tr>
                <tr>
                    <td> <label for = "lozinka"> Lozinka: </label> </td>
                    <td> <input type = "text" id = "lozinka" name = "lozinka"> </td>
                </tr>
                <tr>
                    <td>
                        <input type= "submit" id = "btnPrijava" value="Prijavi se" name="btnPrijava">
                    </td>
                    <td>
                        <input type="checkbox" name="zapamtiMe" value="1"> 
                        <span> Zapamti me </span>
                    </td>

                </tr>
                <tr>
                    <td>
                        <input type="submit" id="btnZaboravljenaLozinka" value="Zaboravljena lozinka" name="btnZaboravljenaLozinka">
                    </td>
                </tr>
                <div id = "notValid">
                    <tr>

                    </tr>
                </div>
            </table>
        </form>
    </body>
</html>


