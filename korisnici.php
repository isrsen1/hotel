<?php

require_once 'pomocneSkripte/aplikacijskiOkvir.php';

Sesija::kreirajSesiju();
$korisnik = Sesija::dajKorisnika();
if ($korisnik) {
    $tipKorisnika = Korisnik::tipKorisnika($korisnik);
    $upit = "SELECT id_korisnik FROM korisnik WHERE korIme =  '$korisnik'";
    $rezultatUpita = $baza->selectDB($upit);
    $row = mysqli_fetch_assoc($rezultatUpita);
    $idKorisnik = $row['id_korisnik'];
}

if(isset($_GET['status']) && $_GET['status'] == 1){
    global $baza;
    $korisnickoIme = $_GET['korime'];
    $upit = "UPDATE `korisnik` SET `zakljucan` = '0' WHERE `korisnik`.`korIme` = '$korisnickoIme';";
    $baza->updateDB($upit);
    $upit = "UPDATE `korisnik` SET `neuspjesne_lozinke` = '0' WHERE `korisnik`.`korIme` = '$korisnickoIme';";
    $baza->updateDB($upit);
                $baza->zatvoriDB();
}
else if(isset($_GET['status']) && $_GET['status'] == 0){
    global $baza;
    $korisnickoIme = $_GET['korime'];
    $upit = "UPDATE `korisnik` SET `zakljucan` = '1' WHERE `korisnik`.`korIme` = '$korisnickoIme';";
    $baza->updateDB($upit);
                $baza->zatvoriDB();
}


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Korisnici </title>
        <meta charset="UTF-8">
        <meta name="author" content="Ivan Srsen">
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"> </script>
        <script src = "http://code.jquery.com/ui/1.12.1/jquery-ui.js"> </script>
        <script  src = "javascript/dohvatiKorisnike.js"></script>
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
        <?php if(isset($tipKorisnika) && $tipKorisnika == "administrator"){ ?>
        <table id="korisnici">
            <button id="sortIme"> Ime </button>
            <button id="sortPrezime"> Prezime </button>
        </table>
        <?php } ?>
    </body>
</html>

