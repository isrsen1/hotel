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

if (isset($_POST['btnDodajPoziciju'])) {
    $stranica = $_POST['stranica'];
    $lokacija = $_POST['lokacija'];
    $sirina = $_POST['sirina'];
    $visina = $_POST['visina'];
    $moderator = $_POST['moderator'];

    $upit = "SELECT * FROM `WebDiP2017x134`.`pozicija` WHERE `id_stranica` = '$stranica' AND `id_lokacija` = '$lokacija'";
    $rezultatUpita = $baza->selectDB($upit);
    if (mysqli_num_rows($rezultatUpita) > 0) {
        echo "Vec postoji";
    } else {
        $upit = "INSERT INTO `WebDiP2017x134`.`pozicija` (`id_pozicija`, `id_stranica`, `id_lokacija`, `visina`, `sirina`, `opis`) VALUES (NULL, '$stranica', '$lokacija', '$sirina', '$visina', NULL);";
        $rezultat = $baza->updateDB($upit);

        $upit = "SELECT id_pozicija FROM `WebDiP2017x134`.`pozicija` WHERE `id_stranica` = '$stranica' AND `id_lokacija` = '$lokacija'";
        $rezultatUpita = $baza->selectDB($upit);
        $row = mysqli_fetch_assoc($rezultatUpita);
        $idPozicije = $row['id_pozicija'];

        $upit = "INSERT INTO `WebDiP2017x134`.`zaduzen` (`id_pozicija`, `id_korisnik`) VALUES ('$idPozicije', '$moderator');";
        $baza->updateDB($upit);
    }
} else if (isset($_POST['btnDodajVrstuOglasa'])) {
    $trajanje = $_POST['trajanje'];
    $brzinaIzmjene = $_POST['brzinaIzmjene'];
    $cijena = $_POST['cijena'];
    $pozicija = $_POST['pozicija'];
    
    $upit ="INSERT INTO `WebDiP2017x134`.`vrsta_oglasa` (`id_vrsta_oglasa`, `id_pozicija`, `cijena`, `brzina_izmjene`, `trajanje`) VALUES (NULL, '$pozicija', '$cijena', '$brzinaIzmjene', '$trajanje');";
    $baza->updateDB($upit);
}

$baza->zatvoriDB();
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Postavke oglasa </title>
        <meta charset="UTF-8">
        <meta name="author" content="Ivan Srsen">
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src = "http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script  src = "javascript/oglasiPostavke.js"></script>
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
        <h1> Definiranje pozicija oglasa </h1>

        <form id = "dodajPoziciju" method = "post" name = "dodajPoziciju" action = "oglasiPostavke.php">
            <table>
                <tr> 
                    <td> <label for = "stranica"> Stranica: </label> </td>
                    <td> <select name="stranica" id="stranica">

                        </select> 
                    </td> 
                </tr>
                <tr>
                    <td> <label for = "lokacija"> Lokacija: </label> </td>
                    <td> <input type = "text" id = "lokacija" name = "lokacija" placeholder="Lokacija oglasa"> </td>
                </tr>
                <tr> 
                    <td> <label for = "sirina"> Širina: </label> </td>
                    <td> <input type = "text" id = "sirina" name = "sirina" placeholder="Sirina oglasa"> </td> 
                </tr>
                <tr> 
                    <td> <label for = "visina"> Visina: </label> </td>
                    <td> <input type = "text" id = "visina" name = "visina" placeholder="Visina oglasa"> </td> 
                </tr>
                <tr> 
                    <td> <label for = "moderator"> Moderator: </label> </td>
                    <td> <select name="moderator" id="moderator">

                        </select> 
                    </td> 
                </tr>

                <tr>
                    <td>
                        <input type= "submit" id = "btnDodajPoziciju" value="Dodaj poziciju" name="btnDodajPoziciju">
                    </td>
                </tr>
            </table>
        </form>
        <?php 
        }
        ?>
<?php 
if($korisnik){
    ?>
        <h1> Definiranje vrste oglasa</h1>

        <input type="hidden" id="korimeId" name="korimeId" value="<?php echo $idKorisnik ?>">
        <input type="hidden" id="tipKorisnika" name="tipKorisnika" value="<?php echo $tipKorisnika ?>">


        <form id = "dodajVrstuOglasa" method = "post" name = "dodajVrstuOglasa" action = "oglasiPostavke.php">
            <table>
                <tr> 
                    <td> <label for = "trajanje"> Trajanje: </label> </td>
                    <td> <input type="text" id="trajanje" placeholder="Trajanje oglasa" name="trajanje">
                    </td> 
                </tr>
                <tr>
                    <td> <label for = "brzinaIzmjene"> Brzina izmjene: </label> </td>
                    <td> <input type = "text" id = "brzinaIzmjene" name = "brzinaIzmjene" placeholder="Sekunde"> </td>
                </tr>
                <tr> 
                    <td> <label for = "cijena"> Cijena: </label> </td>
                    <td> <input type = "text" id = "cijena" name = "cijena" placeholder="Cijena vrste oglasa"> </td> 
                </tr>
                <tr> 
                    <td> <label for = "pozicija"> Pozicija: </label> </td>
                    <td> <select id="pozicija" name="pozicija">

                        </select> </td> 
                </tr> 
                <tr>
                    <td>
                        <input type= "submit" id = "btnDodajVrstuOglasa" value="Dodaj vrstu oglasa" name="btnDodajVrstuOglasa">
                    </td>
                </tr>
            </table>
        </form>
        <?php
}
        ?>
    </body>

</html>

