<?php
require_once 'aplikacijskiOkvir.php';
Sesija::kreirajSesiju();
    if(isset($_POST['korisnickoIme'])){
        global $baza;
        $korisnickoIme = $_POST['korisnickoIme'];
        $upit = "SELECT korime FROM korisnik WHERE korime = '$korisnickoIme'";
        $rezultatUpita = $baza->selectDB($upit);

    if (mysqli_num_rows($rezultatUpita) == 1) {
        echo '1';
        exit();
    }
    else{
        echo '0';
        exit();
    }
    $baza->zatvoriDB();
    }
?>

