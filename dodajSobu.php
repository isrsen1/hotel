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
global $baza;
if(isset($_POST['brojLezaja'])){
        $slika = $_FILES['userfile']['tmp_name'];
        $slikaIme = $_FILES['userfile']['name'];
        
        $target_dir = "sobe/";
        $target_file = $target_dir . basename($slikaIme);
        
        $brojSobe = $_POST['brojSobe'];
        $brojLezaja = $_POST['brojLezaja'];
        $opisSobe = $_POST['opisSobe'];
        $hotel = $_POST['hotel'];
        
        
        if($brojSobe !="" && $brojLezaja!=""){
           if (move_uploaded_file($slika, $target_file)) {
        
        
        if($_POST['opisSobe'] == ""){
        $upit = "INSERT INTO `WebDiP2017x134`.`soba` (`id_soba`, `id_hotel`, `broj_sobe`, `broj_lezajeva`, `slika`) "
                . "VALUES (NULL, '$hotel', '$brojSobe', '$brojLezaja', '$slikaIme');";
        }
        else{
            $upit = "INSERT INTO `WebDiP2017x134`.`soba` (`id_soba`, `id_hotel`, `broj_sobe`, `broj_lezajeva`, `slika`, `opis`) "
                . "VALUES (NULL, '$hotel', '$brojSobe', '$brojLezaja', '$slikaIme', '$opisSobe');";
        }
        $baza->selectDB($upit);
        }
        else{
            echo "NEsto nije u redu";
        }
        
}
}
$baza->zatvoriDB();

?>

<html>
    <head>
        <meta charset="UTF-8">
        <title> Dodaj sobu </title>
        <meta charset="UTF-8">
        <meta name="author" content="Ivan Srsen">
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"> </script>
        <script src = "http://code.jquery.com/ui/1.12.1/jquery-ui.js"> </script>
        <script  src = "javascript/dodajSobu.js"></script>
    </head>

    <body>
        <ul>
            <?php
            if (!$korisnik) {
                ?>
                <li><a href="registracija.php">Registracija</a></li>    <!-- Link koji vodi na registraciju -->
                <li><a href="prijava.php">Prijava</a></li>
                <?php
            } else {
                ?>
                <li><a href="odjava.php">Odjava</a></li>
                <?php
            }
            ?>
            <li><a href="dodajHotel.php"> Dodaj hotel</a></li>
        </ul>
<?php if(isset($tipKorisnika) && ($tipKorisnika == "administrator" || $tipKorisnika == "moderator")){ ?>
        <form enctype="multipart/form-data" id="dodajSobu" name="dodajSobu" method="post" action="dodajSobu.php">
            <table>
                <tr> 
                    <td> <label for = "brojSobe"> Broj sobe: </label> </td>
                    <td> <input type = "text" id = "brojSobe" name = "brojSobe"> </td> 
                </tr>
                <tr>
                    <td> <label for = "brojLezaja"> Broj ležaja: </label> </td>
                    <td> <input type = "text" id = "brojLezaja" name = "brojLezaja"> </td>
                </tr>
                <tr> 
                    <td> <label for = "opis"> Opis: </label> </td>
                    <td> <textarea id="opis" name="opisSobe" rows="3" cols="40" placeholder="Opis sobe" ></textarea> </td> 
                </tr>
                <tr>
                    <td>
                        <label for="dokument"> Slika: </label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                    </td>
                    <td>
                        <input name="userfile" type="file" id="dokument">
                    </td>
                </tr>
                <tr>
                    <td>
                <select name="hotel" id="hotel">
                     
                </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type= "submit" id = "submitDodajSobu" value="Dodaj sobu" name="submitDodajSobu">
                    </td>
                </tr>
            </table>
        </form>
<?php } ?>
    </body>
</html>

