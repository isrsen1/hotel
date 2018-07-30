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

if (isset($_POST['vrsteOglasaPadajuci'])) {
    $vrstaOglasa = $_POST['vrsteOglasaPadajuci'];
    $nazivOglasa = $_POST['nazivOglasa'];
    $opis = $_POST['opis'];
    $url = $_POST['url'];
    $trajanje;

    $upit = "select trajanje from vrsta_oglasa where id_vrsta_oglasa = '$vrstaOglasa'";
    $rezultatUpita = $baza->selectDB($upit);
    if(mysqli_num_rows($rezultatUpita) > 0){
        $row = mysqli_fetch_assoc($rezultatUpita);
        $trajanje = $row['trajanje'];
    }

    
    $slika = $_FILES['userfile']['tmp_name'];
    $slikaIme = $_FILES['userfile']['name'];

    $target_dir = "oglasi/";
    $target_file = $target_dir . basename($slikaIme);
    $aktivanOd = $_POST['aktivanOd'];
   
    
    $datetime = new DateTime($aktivanOd);
    $datum1 = $datetime->format('Y-m-d H:i:s');
    $datum2 = $datetime->modify('+' . $trajanje . ' hour');
    $datum2 = $datum2->format('Y-m-d H:i:s');
    

    if($vrstaOglasa != "" && $nazivOglasa != "" && $opis != "" && $url != "" && $datum1 != ""){
         if (move_uploaded_file($slika, $target_file)) {
             $upit = "INSERT INTO `WebDiP2017x134`.`oglas` (`id_oglas`, `id_status`, `id_vrsta_oglasa`, `naziv`, `url`, `opis`, `pocetak`, `broj_klikova`, `id_korisnik`, `kraj`, `slika`) "
                     . "VALUES (NULL, '2', '$vrstaOglasa', '$nazivOglasa', '$url', '$opis', '$datum1', '0', '$idKorisnik', '$datum2', '$target_file');";
         
             $baza->updateDB($upit);
         }
    }
}
else{

if (isset($_GET['status']) && $_GET['status'] == "prihvacen") {
    $upit = "SELECT id_status FROM status WHERE status.naziv = 'odobren'";
    $rezultatUpita = $baza->selectDB($upit);
    $row = mysqli_fetch_assoc($rezultatUpita);
    $idStatus = $row['id_status'];
    $idOglas = $_GET['oglas'];
    $upit = "UPDATE `oglas` SET `id_status` = '$idStatus' WHERE `oglas`.`id_oglas` = '$idOglas';";
    $baza->updateDB($upit);
} else if (isset($_GET['status']) && $_GET['status'] == "odbijen") {
    $upit = "SELECT id_status FROM status WHERE status.naziv = 'odbijen'";
    $rezultatUpita = $baza->selectDB($upit);
    $row = mysqli_fetch_assoc($rezultatUpita);
    $idStatus = $row['id_status'];
    $idOglas = $_GET['oglas'];
    $upit = "UPDATE `oglas` SET `id_status` = '$idStatus' WHERE `oglas`.`id_oglas` = '$idOglas';";
    $baza->updateDB($upit);
} else if (isset($_GET['primjedbe']) && $_GET['primjedbe'] == "prihvacena") {
    $upit = "SELECT id_status FROM status WHERE status.naziv = 'blokiran'";
    $rezultatUpita = $baza->selectDB($upit);
    $row = mysqli_fetch_assoc($rezultatUpita);
    $idStatus = $row['id_status'];

    $idOglas = $_GET['oglas'];
    $upit = "UPDATE `primjedba` SET `status` = 'prihvacen' WHERE `primjedba`.`id_oglas` = '$idOglas';";
    $baza->updateDB($upit);
    $upit = "UPDATE `oglas` SET `id_status` = '$idStatus' WHERE `oglas`.`id_oglas` = '$idOglas';";
    $baza->updateDB($upit);
} else if (isset($_GET['primjedbe']) && $_GET['primjedbe'] == "odbacena") {
    $idOglas = $_GET['oglas'];
    $upit = "UPDATE `primjedba` SET `status` = 'odbijen' WHERE `primjedba`.`id_oglas` = '$idOglas';";
    $baza->updateDB($upit);
}

}

if(isset($_POST['razlogPrimjedbe'])){
    echo $idKorisnik;
    $idOglas = $_POST['nazivOglasPrimjedba'];
    $razlog = $_POST['razlogPrimjedbe'];
    $vrijeme = time();
    echo $idOglas;
    $datetime = new DateTime();
    $datetime->setTimestamp($vrijeme);
    $datum1 = $datetime->format('Y-m-d H:i:s');
    
    $upit="INSERT INTO `WebDiP2017x134`.`primjedba` (`id_korisnik`, `id_oglas`, `razlog`, `datum_primjedbe`, `status`) "
            . "VALUES ('$idKorisnik', '$idOglas', '$razlog', '$datum1', 'zahtjev');";
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
        <script  src = "javascript/oglasi.js"></script>
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
            <input type="hidden" id="korimeId" name="korimeId" value="<?php echo $idKorisnik ?>">
            <input type="hidden" id="tipKorisnika" name="tipKorisnika" value="<?php echo $tipKorisnika ?>">
            <?php
        }
        ?>
            <?php if (isset($tipKorisnika) && ($tipKorisnika == "administrator" || $tipKorisnika == "moderator")) { ?>
        <h1> Poslani zahtjevi - moderator </h1>
        <table id="zahtjevi">

        </table>

        <h1> Zahtjevi za blokiranje - moderator </h1>
        <table id="primjedba">

        </table>
            <?php }  ?>
<?php if(isset($tipKorisnika)) { ?>        
        <h1> Posalji zahtjev za blokiranjem oglasa </h1>
        <form action="oglasi.php" method="post">
            <table>
                <tr>
                    <td>
                        <label for="nazivOglasPrimjedba">Oglas:</label>
                    </td>
                    <td>
                        <select id="nazivOglasPrimjedba" name="nazivOglasPrimjedba">
                            
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="razlogPrimjedbe"> Razlog: </label>
                    </td>
                    <td>
                        <input type="text" name="razlogPrimjedbe" id="razlogPrimjedbe">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="Posalji primjedbu">
                    </td>
                </tr>
            </table>
        </form>
        <h1> Pregled vrsta i kreiranje oglasa - registriran </h1>
        <table id="vrsteOglasa">

        </table>

        <br>
        <br>
        <form method="post" action="oglasi.php" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>
                        <label for="vrsteOglasaPadajuci"> Vrsta oglasa: </label>
                    </td>
                    <td>
                        <select id="vrsteOglasaPadajuci" name="vrsteOglasaPadajuci">

                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="nazivOglasa">Naziv oglasa:</label> 
                    </td>
                    <td>
                        <input type="text" id="nazivOglasa" name="nazivOglasa">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="opis">Opis:</label> 
                    </td>
                    <td>
                        <textarea id="opis" name="opis" rows="5" cols="20"> </textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="url">Url:</label> 
                    </td>
                    <td>
                        <input type="text" id="url" name="url">
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <label for="aktivanOd">Aktivan od:</label> 
                    </td>
                    <td>
                        <input type="datetime-local" id="aktivanOd" name="aktivanOd">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="dokument"> Slika: </label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                    </td>
                    <td>
                        <input name="userfile" type="file" id="slikaOglasa">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" name="napraviOglas">
                    </td>
                </tr>

            </table>
        </form>
        
        <h1> Moji oglasi </h1>
        <div id="popisOglasa">
            
        </div>
        <?php } ?>
    </body>

</html>

