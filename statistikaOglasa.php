<?php
require_once 'pomocneSkripte/aplikacijskiOkvir.php';

Sesija::kreirajSesiju();
$korisnik = Sesija::dajKorisnika();

$cookie_name = "korisnik";
$cookie_value = $_SERVER['REMOTE_ADDR'];
if (!isset($_COOKIE[$cookie_name])) {
    setcookie($cookie_name, $cookie_value, time() + (86400 * 2), "/");
}

if ($korisnik) {
    $tipKorisnika = Korisnik::tipKorisnika($korisnik);
    $upit = "SELECT id_korisnik, korIme FROM korisnik WHERE korIme =  '$korisnik'";
    $rezultatUpita = $baza->selectDB($upit);
    $row = mysqli_fetch_assoc($rezultatUpita);
    $idKorisnik = $row['id_korisnik'];
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Statistika </title>
        <meta charset="UTF-8">
        <meta name="author" content="Ivan Srsen">
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src = "http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script  src = "javascript/statistikaOglasa.js"></script>
    </head>

    <body>
        <ul>
            <?php
            if(!$korisnik){
            ?>
            <li><a href="registracija.php">Registracija</a></li>    <!-- Link koji vodi na registraciju -->
            <li><a href="prijava.php">Prijava</a></li>
            <?php
            }
            else{
            ?>
            <li><a href="pomocneSkripte/odjava.php">Odjava</a></li>
            <?php
            }
            ?>
            
            <?php if(isset($tipKorisnika) && $tipKorisnika == "administrator"){ ?>
            <li><a href="dodajHotel.php"> Dodaj hotel</a></li>
            <?php }
            if(isset($tipKorisnika) && ($tipKorisnika == "moderator" || $tipKorisnika == "administrator")){
            ?>
            <li><a href="dodajSobu.php"> Dodaj sobu</a></li>
            <?php  
           }
            ?>
            <?php 
            if(isset($tipKorisnika) && $tipKorisnika == "administrator"){
            ?>
            <li><a href="korisnici.php"> Korisnici</a></li>
            <?php } ?>
             <?php 
            if(isset($tipKorisnika) && ($tipKorisnika == "moderator" || $tipKorisnika == "administrator")){
            ?>
            <li><a href="oglasiPostavke.php"> Postavke oglasa</a></li>
            <li><a href="rezervacije.php">Rezervacije</a></li>
            <?php } ?>
            <?php if(isset($tipKorisnika)){ ?>
            <li><a href="oglasi.php">Oglasi</a></li>
            <?php } ?>
        </ul>
        <?php
        if ($korisnik) {
            ?>
            <input type="hidden" id="korimeId" name="korimeId" value="<?php echo $korisnik ?>">
            <input type="hidden" id="tipKorisnika" name="tipKorisnika" value="<?php echo $tipKorisnika ?>">
            <?php
        }
        ?>

        <table id="statistikaOglasa">
        </table>
        <input type="text" id="korime" name="korime">
        <input type="datetime-local" id="datumOd" name="datumOd" placeholder="od">
        <input type="datetime-local" id="datumDo" name="datumDo" placeholder="do">
        <button name="filter" id="filter">Filtriraj</button>



        <h1> Statistika oglasa - registrirani</h1>
        <table id="statistikaRegistrirani">

        </table>

        <button id="sortKlik"> Sort klik </button>
        <br>
        <select id="vrsteOglasa">

        </select>
        <br>
        <button id="filterReg"> Filtriraj </button>
    </body>
</html>
