<?php
require_once 'pomocneSkripte/aplikacijskiOkvir.php';
global $baza;
Sesija::kreirajSesiju();
$korisnik = Sesija::dajKorisnika();

if ($korisnik) {
    $tipKorisnika = Korisnik::tipKorisnika($korisnik);
    $upit = "SELECT id_korisnik FROM korisnik WHERE korIme =  '$korisnik'";
    $rezultatUpita = $baza->selectDB($upit);
    $row = mysqli_fetch_assoc($rezultatUpita);
    $idKorisnik = $row['id_korisnik'];
}

$cookie_name = "korisnik";
$cookie_value = $_SERVER['REMOTE_ADDR'];
if (!isset($_COOKIE[$cookie_name])) {
    setcookie($cookie_name, $cookie_value, time() + (86400 * 2), "/");
}

if(isset($_POST['soba'])){
    $vrijemeDolaska = $_POST['vrijemeDolaska'];
    $trajanjeBoravka = $_POST['trajanje'];
    $regKorisnik = $_POST['regKorisnici'];
    $idHotel = $_POST['hotel'];
    $idSoba = $_POST['soba'];
    
    if($vrijemeDolaska != "" && $trajanjeBoravka != ""){
       $upit = "INSERT INTO `WebDiP2017x134`.`rezervacija` (`id_korisnik`, `id_soba`, `datum_dolaska`, `trajanje`) VALUES ('$regKorisnik', '$idSoba', '$vrijemeDolaska', '$trajanjeBoravka');";
       $baza->updateDB($upit);
    }
    else{
       
    }
}

if(isset($_GET['idSobe'])){
    $idSobe = $_GET['idSobe'];
    $idHotel = $_GET['idHotel'];
    $upit = "select * from soba where id_soba = '$idSobe'";
    $rezultat = $baza->selectDB($upit);
    if(mysqli_num_rows($rezultat)>0){
        $row = mysqli_fetch_assoc($rezultat);
        $opis = $row['opis'];
        $brojSobe = $row['broj_sobe'];
        $brojLezaja = $row['broj_lezajeva'];
        $slika = $row['slika'];
    }
    $mailovi = array();
    $upit = "select korisnik.korIme, korisnik.id_korisnik, korisnik.email, hotel.id_hotel, hotel.naziv from korisnik "
            . "inner join korisnik_moderira_hotel on korisnik.id_korisnik = korisnik_moderira_hotel.id_korisnik "
            . "inner join hotel on hotel.id_hotel = korisnik_moderira_hotel.id_hotel where hotel.id_hotel = '$idHotel'";
    $rezultat = $baza->selectDB($upit);
    if(mysqli_num_rows($rezultat) > 0){
        while ($row = mysqli_fetch_assoc($rezultat)) {
            $mailovi[] = $row['email'];
           $nazivHotela = $row['naziv'];
        }
        
        $mail_from = "From: noreply";
        $mail_subject = "Rezervacija";
        $mail_body = "Rezervacija sobe " + $brojSobe + " u hotelu " + $nazivHotela;

       
            foreach($mailovi as $value){
                
            }
        
    }
    
}

$baza->zatvoriDB();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Rezervacije </title>
        <meta charset="UTF-8">
        <meta name="author" content="Ivan Srsen">
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src = "http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script  src = "javascript/rezervacije.js"></script>
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
            if(isset($tipKorisnika) && ($tipKorisnika == "moderator" || $tipKorisnika == "administrator")){
            ?>
        <form action="rezervacije.php" method="post" name="rezervacije" id="rezervacije">
        <table>
            <tr>
                <td>
                    <label for="regKorisnici"> Korisnici: </label>
                </td>
                <td>
                    <select id="regKorisnici" name="regKorisnici">

                    </select>
                </td>
            </tr>

            <tr>
                <td>
                    <label for="hotel"> Hotel: </label>
                </td>
                <td>
                    <select id="hotel" name="hotel">

                    </select>
                </td>
            </tr>

            <tr>
                <td>
                    <label for="soba"> Soba: </label>
                </td>
                <td>
                    <select id="soba" name="soba">

                    </select>
                </td>
            </tr>

            <tr>
                <td>
                    <label for="vrijemeDolaska"> Vrijeme dolaska: </label>
                </td>
                <td>
                    <input type="datetime-local" name="vrijemeDolaska" id="vrijemeDolaska">
                </td>
            </tr>
       
        <tr>
            <td>
                <label for="trajanje"> Trajanje boravka: </label>
            </td>
            <td>
                <input type="text" name="trajanje" id="trajanje">
            </td>
        </tr>
        <tr>
            <td><input type="submit" value="rezerviraj"></td>
        </tr>

</table>
            </form>
        
        <?php 
            }
        if(isset($_GET['idSobe'])){
            echo "<br>";echo "<br>";echo "<br>";
            echo "Broj sobe: " . $brojSobe;
            echo "<br>";
            echo "Broj lezaja: ". $brojLezaja;
            echo "<br>";
            echo "Opis: ". $opis;
            echo "<br>";
            echo "<img src='sobe/" .$slika . "'>";
            echo "<br>";
            echo "<form method='post' action='rezervacije.php?idSobe=". $idSobe. +"&idHotel=".$idHotel ."'>";
            echo "<textarea rows='6' cols='30'></textarea>";
            echo "<input type='submit' value='Posalji mail'>";
            echo "</form>";
        }
        ?>
</body>
</html>



