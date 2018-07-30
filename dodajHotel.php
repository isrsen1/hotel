<?php
require_once 'pomocneSkripte/aplikacijskiOkvir.php';
if (isset($_POST['naziv'])) {
    global $baza;
    $naziv = $_POST['naziv'];
    $email = $_POST['email'];
    $kategorija = $_POST['kategorija'];
    $telefon = $_POST['telefon'];
    $adresa = $_POST['adresa'];
    $upit = "INSERT INTO `hotel` (`id_hotel`, `naziv`, `email`, `kategorija`, `telefon`, `adresa`) VALUES (NULL, '$naziv', '$email', '$kategorija', '$telefon', '$adresa')";
    $baza->updateDB($upit);
    $baza->zatvoriDB();
}

if(isset($_POST['submitModeratorHotel'])){
    global $baza;
    
    $idModerator = $_POST['moderator'];
    $idHotel = $_POST['hotel'];
    $upit = "INSERT INTO `korisnik_moderira_hotel` (`id_korisnik`, `id_hotel`) VALUES ('$idModerator', '$idHotel');";
    $baza->updateDB($upit);
    $baza->zatvoriDB();
}
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title> Dodaj hotel</title>
        <meta charset="UTF-8">
        <meta name="author" content="Ivan Srsen">
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"> </script>
        <script src = "http://code.jquery.com/ui/1.12.1/jquery-ui.js"> </script>
        <script  src = "javascript/dodajHotel.js"></script>
    </head>
    <body>
        <ul>
            <li><a href="dodajHotel.php">Dodaj Hotel</a>    <!-- Link koji vodi na registraciju -->
        </ul>

        <form id = "dodajHotel" method = "post" name = "dodajHotel" action = "dodajHotel.php">
            <table>
                <tr> 
                    <td> <label for = "naziv"> Naziv: </label> </td>
                    <td> <input type = "text" id = "naziv" name = "naziv"> </td> 
                </tr>
                <tr>
                    <td> <label for = "email"> Email: </label> </td>
                    <td> <input type = "text" id = "email" name = "email"> </td>
                </tr>
                <tr> 
                    <td> <label for = "kategorija"> Kategorija: </label> </td>
                    <td> <input type = "text" id = "kategorija" name = "kategorija"> </td> 
                </tr>
                <tr> 
                    <td> <label for = "telefon"> Telefon: </label> </td>
                    <td> <input type = "text" id = "telefon" name = "telefon"> </td> 
                </tr>
                <tr> 
                    <td> <label for = "adresa"> Adresa: </label> </td>
                    <td> <input type = "text" id = "adresa" name = "adresa"> </td> 
                </tr>
                
                <tr>
                    <td>
                        <input type= "submit" id = "btnDodajHotel" value="Dodaj hotel" name="btnDodajHotel">
                    </td>
                </tr>
            </table>
        </form>
        
        <form id = "dodijeliModeratora" method = "post" name = "dodijeliModeratora" action = "dodajHotel.php">
            <table>
                <tr> 
                    <td>
                        <span> Moderator: </span>
                    </td>
                    <td>
                <select name="moderator" id="moderator">
                   
                </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span> Hotel: </span>
                    </td>
                    <td>
                <select name="hotel" id="hotel">
                     
                </select>
                    </td>
                </tr>
                <tr>
                    <td>
                    <input type="submit" name="submitModeratorHotel" value="Dodijeli moderatora hotelu">
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>

