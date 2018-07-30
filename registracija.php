<?php
    require_once 'pomocneSkripte/aplikacijskiOkvir.php';
    require_once 'pomocneSkripte/recaptcha.php';
    Sesija::kreirajSesiju();
    $korisnik = Sesija::dajKorisnika();
    
    if($korisnik){
        header('Location:index.php');
    }
    if(isset($_POST['korime'])){
    
    $secret = "6LcLNF0UAAAAAJg-_QOghUTScOOcL5Af4uRDQ6Bu";
    $response = null;
    $recaptcha = new ReCaptcha($secret);
    if ($_POST["g-recaptcha-response"]) {
    $response = $recaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
}

if ($response != null && $response->success) {

        global $baza;
        $skripta = "index.php";

        $ime = $_POST["ime"];
        $prezime = $_POST["prezime"];
        $korime = $_POST["korime"];
        $email = $_POST["email"];
        $lozinka = $_POST["lozinka"];
        $ponoviLozinku = $_POST["ponoviLozinku"];
        $matches = preg_match('/^[a-z]{1,}\.{0,1}[A-Za-z0-9]{1,}@{1,1}[A-Za-z]{1,}\.{1,1}[A-Za-z]{1,}$/', $email);
        
        if($korime == "" || $lozinka == "" || $ponoviLozinku == "" || $email == ""){
            exit();
        }
        else if(strlen($email) > 30){
            echo "Email predug";
            exit();
        }
        else if(strlen($lozinka) > 12){
            echo "Lozinka preduga";
            exit();
        }
        else if(strlen($korime) > 12){
            echo "Korisnicko ime predugo";
            exit();
        }
        else if(!$matches){
            echo "Email nije dobar";
            exit();
        }
        else if(strlen($ime)> 20){
            echo "Ime predugo";
            exit();
        }
        else if(strlen($prezime) > 20){
            echo "Prezime predugo";
            exit();
        }
        
               
        $sol = sha1(time());
        $kriptirana = sha1($sol . "." . $lozinka);
        
        $registracijskiKod = rand();
        
        $upit = "INSERT INTO `WebDiP2017x134`.`korisnik` (`id_korisnik`, `id_tip_korisnika`, `ime`, `prezime`, `korIme`, `email`, `lozinka`, `sol`, `kriptirana`, `neuspjesne_lozinke`, `zakljucan`, `aktiviran`, `reg_kod`) 
            VALUES (NULL, '3', '$ime', '$prezime', '$korime', '$email', '$lozinka', '$sol', '$kriptirana', '0', '0', '0', '$registracijskiKod');";
        
        $mail_from = "From: noreply";
        $mail_subject = "Aktivacija";
        $mail_body = "http://webdip.barka.foi.hr/2017_projekti/WebDiP2017x134/aktivacija.php?korime=$korime&aktivacijskiKod=$registracijskiKod";

        if (mail($email, $mail_subject, $mail_body, $mail_from)) {
            echo("Poslana poruka za: '$email'!");
        } 
        
        $baza->updateDB($upit, $skripta);
        $baza->zatvoriDB(); 
    }
    else{
        echo "Oznacite polje kojim potvrdujete da niste robot";
    }
    }
    
    
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Početna </title>
        
        <meta charset = "UTF-8">
        <meta charset = "UTF-8">
        <meta name = "author" content="Ivan Srsen">
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"> </script> 
     <!--   <script src = "http://code.jquery.com/ui/1.12.1/jquery-ui.js"> </script> -->
        <script  src = "javascript/registracija.js"></script>
    </head>
    <body>
        <form id = "registracija" method="post" name = "registracija" action="registracija.php">
            <table>
                <tr>
                    <td>
                        <label for="ime">Ime: </label>
                    </td>                    
                    <td>
                        <input type="text" id="ime" name="ime" size="20" maxlength="30" placeholder="Ime">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="prezime">Prezime:</label>
                    </td>                    
                    <td>
                        <input type="text" id="prezime" name="prezime" size="20" maxlength="50" placeholder="Prezime">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for= "korime">Korisničko ime: </label>
                    </td>
                    <td>
                        <input type= "text" id = "korime" name = "korime" size = "15" maxlength = "20" placeholder = "Korisničko ime">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for = "email">E-mail: </label>
                    </td>  
                    <td>
                        <input id = "email" type = "email" name = "email"  placeholder = "E-mail">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="lozinka">Lozinka:</label>
                    </td>
                    <td>
                        <input type = "password" id = "lozinka" name = "lozinka" size = "15" maxlength = "15" placeholder = "Lozinka" >
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for= "ponoviLozinku">Ponovi lozinku: </label>
                    </td>
                    <td>
                        <input type= "password" id= "ponoviLozinku" name= "ponoviLozinku" size= "15" maxlength= "15" placeholder = "Ponovi lozinku" >
                    </td>
                </tr>
                <tr>
                    <td> <div class="g-recaptcha" data-sitekey="6LcLNF0UAAAAAPar9_l0o3TLEyhR6L3N9BGz0hGA"></div></td>
                    <td>
                        <input type= "submit" id = "btnRegistracija" value=" Registriraj se" name="btnRegistracija">
                    </td>
                </tr>
            </table>
           

        </form>
        <div id="errorContainer">
            
        </div>
        
        <script src='https://www.google.com/recaptcha/api.js'></script>

        
    </body>
</html>
