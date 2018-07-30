<?php
    require_once 'pomocneSkripte/aplikacijskiOkvir.php';
    if(isset($_GET["korime"]) && isset($_GET["aktivacijskiKod"])){
        global $baza;
        $korime = $_GET["korime"];
        $aktivacijskiKod = $_GET["aktivacijskiKod"];
        
        $upit = "SELECT reg_kod FROM korisnik WHERE korime = '$korime'";
        $rezultatUpita = $baza->selectDB($upit);
        
        if (mysqli_num_rows($rezultatUpita) == 1) {
        $row = mysqli_fetch_assoc($rezultatUpita);
        $skripta = "index.php";
        $regKod = $row['reg_kod'];
        if($regKod == $aktivacijskiKod){
            $upit = "UPDATE `korisnik` SET `aktiviran` = '1' WHERE `korisnik`.`korIme` = '$korime';";
            $baza->updateDB($upit, $skripta);
        }
        }
        $baza->zatvoriDB();
    }
?>

