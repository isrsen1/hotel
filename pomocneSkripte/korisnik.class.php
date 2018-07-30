<?php

class Korisnik{
    
    static function autentikacija($korisnickoIme, $lozinka) {
    global $baza;
    $upit = "SELECT korime, lozinka, sol, kriptirana, zakljucan, aktiviran FROM korisnik WHERE korime = '$korisnickoIme'";
    $rezultatUpita = $baza->selectDB($upit);

    if (mysqli_num_rows($rezultatUpita) == 1) {
        $row = mysqli_fetch_assoc($rezultatUpita);
        $skripta = "index.php";
        $korimeDohvacena = $row['korime'];
        $lozinkaDohvacena = $row['lozinka'];
        
        $sol = $row['sol'];
        $kriptirana = $row['kriptirana'];
        $zakljucan = $row['zakljucan'];
        $aktiviran = $row['aktiviran'];
        $rezultat = sha1($sol . "." . $lozinka);
        
        if(!isset($_SESSION[$korisnickoIme])){
            $_SESSION[$korisnickoIme] = 0;
        }
        
        if($zakljucan == 1){
            echo "Blokirani ste";
            $baza->zatvoriDB();
            return -1;
        }
        
        if($aktiviran == 0){
            echo "Aktivirajte korisnicki racun";
            $baza->zatvoriDB();
            return -1;
        }
        
        if ($rezultat == $kriptirana) {
            $upit = "UPDATE `korisnik` SET `neuspjesne_lozinke` = '0' WHERE `korisnik`.`korIme` = '$korisnickoIme';";
            $baza->updateDB($upit);
            $baza->zatvoriDB();
            return 1;
        }
        else if($korimeDohvacena == $korisnickoIme && $rezultat != $kriptirana){
            $_SESSION[$korisnickoIme]++;
            $upit = "UPDATE `korisnik` SET `neuspjesne_lozinke` = '$_SESSION[$korisnickoIme]' WHERE `korisnik`.`korIme` = '$korisnickoIme';";
            $baza->updateDB($upit);
            
            if($_SESSION[$korisnickoIme] == 3){
                $upit = "UPDATE `korisnik` SET `zakljucan` = '1' WHERE `korisnik`.`korIme` = '$korisnickoIme';";
                $_SESSION[$korisnickoIme] = 0;
                $baza->updateDB($upit, $skripta);
                $baza->zatvoriDB();
            }
            $baza->zatvoriDB();
            return -1;
        }
        else{
            return -1;
        }
    } else {
        return -1;
    }
}

static function tipKorisnika($korisnickoIme){
    global $baza;
    $upit = "select `tip_korisnika`.`naziv` as tip from `tip_korisnika` inner join korisnik on `tip_korisnika`.`id_tip_korisnika` = `korisnik`.`id_tip_korisnika` where `korisnik`.`korIme` = '$korisnickoIme'";
    $rezultatUpita = $baza->selectDB($upit);
    $row = mysqli_fetch_assoc($rezultatUpita);
    $tip = $row['tip'];
    return $tip;
}

}

