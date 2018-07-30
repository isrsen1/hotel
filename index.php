<?php
require_once 'pomocneSkripte/aplikacijskiOkvir.php';

Sesija::kreirajSesiju();
$korisnik = Sesija::dajKorisnika();

$cookie_name = "korisnik";
$cookie_value = $_SERVER['REMOTE_ADDR'];
if(!isset($_COOKIE[$cookie_name])){
    setcookie($cookie_name, $cookie_value, time() + (86400 * 2), "/"); 
}

if ($korisnik) {
    $tipKorisnika = Korisnik::tipKorisnika($korisnik);
    $upit = "SELECT id_korisnik FROM korisnik WHERE korIme =  '$korisnik'";
    $rezultatUpita = $baza->selectDB($upit);
    $row = mysqli_fetch_assoc($rezultatUpita);
    $idKorisnik = $row['id_korisnik'];
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Početna </title>
        <meta charset="UTF-8">
        <meta name="author" content="Ivan Srsen">
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"> </script>
        <script src = "http://code.jquery.com/ui/1.12.1/jquery-ui.js"> </script>
        <script  src = "javascript/index.js"></script>
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
        <select name="hotel" id="hotel">
       
        </select>
        
        
        <input type="text" id="velicinaSobe" name="velicinaSobe" placeholder="Velicina sobe">
        <input type="datetime-local" id="pretraziOd" name="pretraziOd">
        <input type="datetime-local" id="pretraziDo" name="pretraziDo">
        <button id="pretraziSobe"> Pretrazi sobe</button>
        <table id="sobeHotela">
            
        </table>
        
        <table id="slobodneSobe">
            
        </table>
        
        
    </body>
</html>

