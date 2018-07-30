<?php
require_once 'pomocneSkripte/aplikacijskiOkvir.php';
global $baza;
Sesija::kreirajSesiju();
$korisnik = Sesija::dajKorisnika();
if($korisnik){
echo Korisnik::tipKorisnika($korisnik);
}
$cookie_name = "korisnik";
$cookie_value = $_SERVER['REMOTE_ADDR'];
if(!isset($_COOKIE[$cookie_name])){
    setcookie($cookie_name, $cookie_value, time() + (86400 * 2), "/"); 
}

if(isset($_GET['idOglas'])){
    $idOglas = $_GET['idOglas'];
    $upit = "select id_vrsta_oglasa, naziv, opis,  url, pocetak from oglas where id_oglas = '$idOglas'";
    $rezultat = $baza->selectDB($upit);
    if(mysqli_num_rows($rezultat) > 0){
        $row = mysqli_fetch_assoc($rezultat);
        $vrstaOglasa = $row['id_vrsta_oglasa'];
        $naziv = $row['naziv'];
        $opis = $row['opis'];
        $url = $row['url'];
        $pocetak = $row['pocetak'];
        $pocetak = date('Y-m-d\TH:i:s', strtotime($pocetak));
    }
}


if(isset($_POST['vrsteOglasaPadajuci'])){
    $vrstaOglasa = $_POST['vrsteOglasaPadajuci'];
    $naziv = $_POST['nazivOglasa'];
    $opis = $_POST['opis'];
    $url = $_POST['url'];
    $idOglas = $_POST['oglasId'];
    $aktivanOd = $_POST['aktivanOd'];
    
    $slika = $_FILES['userfile']['tmp_name'];
    $slikaIme = $_FILES['userfile']['name'];

    $target_dir = "oglasi/";
    $target_file = $target_dir . basename($slikaIme);
    
    $upit = "select slika from oglas where id_oglas = '$idOglas'";
    $rezultat = $baza->selectDB($upit);
    if(mysqli_num_rows($rezultat) > 0){
        $row = mysqli_fetch_assoc($rezultat);
        $slikaBaza = $row['slika'];
    }
    
    $upit = "select trajanje from vrsta_oglasa where id_vrsta_oglasa = '$vrstaOglasa'";
    $rezultat = $baza->selectDB($upit);
    if(mysqli_num_rows($rezultat) > 0){
        $row = mysqli_fetch_assoc($rezultat);
        $trajanje = $row['trajanje'];
    }
    
    $datetime = new DateTime($aktivanOd);
    $datum1 = $datetime->format('Y-m-d H:i:s');
    $datum2 = $datetime->modify('+' . $trajanje . ' hour');
    $datum2 = $datum2->format('Y-m-d H:i:s');
    
    if($vrstaOglasa != "" && $naziv != "" && $opis != "" && $url != "" && $datum1 != ""){
        if($slikaIme != $slikaBaza && $slikaIme != ""){
             if (move_uploaded_file($slika, $target_file)) {
                 $upit = "UPDATE `WebDiP2017x134`.`oglas` "
                         . "SET `id_vrsta_oglasa` =  '$vrstaOglasa', "
                         . "`naziv` =  '$naziv', `url` =  '$url', `opis` =  '$opis', `pocetak` =  '$datum1', "
                         . "`slika` =  '$target_file' "
                         . "WHERE  `oglas`.`id_oglas` = '$idOglas';";
                 $baza->updateDB($upit);
             }
        }
        else{
            $upit = "UPDATE `WebDiP2017x134`.`oglas` "
                         . "SET `id_vrsta_oglasa` =  '$vrstaOglasa', "
                         . "`naziv` =  '$naziv', `url` =  '$url', `opis` =  '$opis', `pocetak` =  '$datum1' "                         
                         . "WHERE  `oglas`.`id_oglas` = '$idOglas';";
                 $baza->updateDB($upit);
        }
        Header('Location: oglasi.php');
    }
}
$baza->zatvoriDB();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Ažuriraj oglas </title>
        <meta charset="UTF-8">
        <meta name="author" content="Ivan Srsen">
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"> </script>
        <script src = "http://code.jquery.com/ui/1.12.1/jquery-ui.js"> </script>
        <script  src = "javascript/azurirajOglas.js"></script>
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
            <li><a href="dodajHotel.php"> Dodaj hotel</a></li>
            <li><a href="dodajSobu.php"> Dodaj sobu</a></li>
            <li><a href="korisnici.php"> Korisnici</a></li>
            <li><a href="oglasiPostavke.php"> Postavke oglasa</a></li>
            <li><a href="oglasi.php">Oglasi</a></li>
            <li><a href="rezervacije.php">Rezervacije</a></li>
        </ul>
        
         <?php
        if ($korisnik) {
            ?>
            <input type="hidden" id="vrstaOglasaId" name="vrstaOglasaId" value="<?php echo $vrstaOglasa ?>">
            <?php
        }
        ?>
        
        <form method="post" action="azurirajOglas.php" enctype="multipart/form-data">
            <input type="hidden" id="oglasId" name="oglasId" value="<?php if(isset($idOglas)){
                echo $idOglas; 
            }?>">
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
                        <input type="text" id="nazivOglasa" name="nazivOglasa" <?php if(isset($naziv)){
                            echo "value='$naziv'"; 
                        } ?> >
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="opis">Opis:</label> 
                    </td>
                    <td>
                        <textarea id="opis" name="opis" rows="5" cols="20"> <?php if(isset($opis)){
                            echo $opis;
                        } ?> </textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="url">Url:</label> 
                    </td>
                    <td>
                        <input type="text" id="url" name="url" <?php if(isset($url)){
                            echo "value='$url'"; 
                        } ?>>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <label for="aktivanOd">Aktivan od:</label> 
                    </td>
                    <td>
                        <input type="datetime-local" id="aktivanOd" name="aktivanOd" <?php if(isset($pocetak)){
                             echo "value = '$pocetak'";
                        } ?>>
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
                        <input type="submit" name="napraviOglas" value="Azuriraj">
                    </td>
                </tr>

            </table>
        </form>
        </body>
</html>



