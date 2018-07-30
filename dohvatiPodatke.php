<?php

require_once 'pomocneSkripte/aplikacijskiOkvir.php';
Sesija::kreirajSesiju();
global $baza;

if (isset($_POST['tipPodatka']) && $_POST['tipPodatka'] == "hoteli") {
    $rows = array();
    $upit = "SELECT naziv, id_hotel FROM hotel";
    $hoteli = $baza->selectDB($upit);
    if (mysqli_num_rows($hoteli) > 0) {
        while ($row = mysqli_fetch_assoc($hoteli)) {
            $rows[] = $row;
        }
    }
    header("Content-Type: application/json");
    echo json_encode($rows);
} else if (isset($_POST['tipPodatka']) && $_POST['tipPodatka'] == "dohvatiModeratore") {
    $rows = array();
    $upit = "SELECT korIme, id_korisnik FROM `korisnik` WHERE `id_tip_korisnika` = '2'";
    $popisModeratora = $baza->selectDB($upit);
    if (mysqli_num_rows($popisModeratora) > 0) {
        while ($row = mysqli_fetch_assoc($popisModeratora)) {
            $rows[] = $row;
        }
    }
    header("Content-Type: application/json");
    echo json_encode($rows);
} else if (isset($_POST['tipPodatka']) && $_POST['tipPodatka'] == "korisnici" && $_POST['orderBy'] == "none") {
    $rows = array();
    $upit = "SELECT ime, prezime, korime, zakljucan FROM korisnik;";
    $popisKorisnika = $baza->selectDB($upit);
    if (mysqli_num_rows($popisKorisnika) > 0) {
        while ($row = mysqli_fetch_assoc($popisKorisnika)) {
            $rows[] = $row;
        }
    }
    header("Content-Type: application/json");
    echo json_encode($rows);
} else if ((isset($_POST['tipPodatka']) && $_POST['tipPodatka'] == "korisnici" && $_POST['orderBy'] != "none")) {
    $orderBy = $_POST['orderBy'];
    $rows = array();
    $upit = "SELECT ime, prezime, korime, zakljucan FROM korisnik ORDER BY $orderBy;";
    $popisKorisnika = $baza->selectDB($upit);
    if (mysqli_num_rows($popisKorisnika) > 0) {
        while ($row = mysqli_fetch_assoc($popisKorisnika)) {
            $rows[] = $row;
        }
    }
    header("Content-Type: application/json");
    echo json_encode($rows);
} else if (isset($_POST['tipPodatka']) && $_POST['tipPodatka'] == "stranice") {
    $rows = array();
    $upit = "SELECT id_stranica, naziv FROM stranica";
    $stranice = $baza->selectDB($upit);
    if (mysqli_num_rows($stranice) > 0) {
        while ($row = mysqli_fetch_assoc($stranice)) {
            $rows[] = $row;
        }
    }
    header("Content-Type: application/json");
    echo json_encode($rows);
} else if (isset($_POST['tipPodatka']) && $_POST['tipPodatka'] == "statistika") {
    $rows = array();
    $korime = $_POST['korime'];
    if ($korime == "none") {
        $upit = "SELECT `oglas`.`naziv`, `oglas`.`broj_klikova`, `korisnik`.`korIme` FROM oglas INNER JOIN korisnik ON `oglas`.`id_korisnik` = `korisnik`.`id_korisnik` WHERE oglas.id_status = '1'";
    } else {
        $upit = "SELECT `oglas`.`naziv`, `oglas`.`broj_klikova`, `korisnik`.`korIme` FROM oglas INNER JOIN korisnik ON `oglas`.`id_korisnik` = `korisnik`.`id_korisnik` WHERE `korisnik`.`korIme` = '$korime' AND oglas.id_status = '1'";
    }
    
   $datum1=$_POST['datum1'];
    $datum2=$_POST['datum2'];
if($datum1!="none" && $datum1!="" && $datum2 != "none" && $datum2!=""){
    $datetime1 = new DateTime($datum1);
    $datetime2 = new DateTime($datum2);
    $datum1 = $datetime1->format('Y-m-d H:i:s');
    $datum2 = $datetime2->format('Y-m-d H:i:s');
    $upit = $upit." AND oglas.pocetak BETWEEN '$datum1' AND '$datum2'";
}
    
    
    
    
    $stranice = $baza->selectDB($upit);
    if (mysqli_num_rows($stranice) > 0) {
        while ($row = mysqli_fetch_assoc($stranice)) {
            $rows[] = $row;
        }
    }
    header("Content-Type: application/json");
    echo json_encode($rows);
} else if (isset($_POST['tipPodatka']) && $_POST['tipPodatka'] == "pozicija") {
    $rows = array();
    $korimeId = $_POST['korimeId'];
    $tipKorisnika = $_POST['tipKorisnika'];
    if ($tipKorisnika == "administrator") {
        $upit = "SELECT * FROM pozicija JOIN stranica ON  `pozicija`.`id_stranica` =  `stranica`.`id_stranica`";
    } else {
        $upit = "SELECT `stranica`.`naziv`, `pozicija`.`id_lokacija`, `pozicija`.`id_stranica`, `korisnik`.`korIme`, `pozicija`.`id_pozicija` FROM pozicija INNER JOIN zaduzen ON pozicija.id_pozicija = zaduzen.id_pozicija "
                . "INNER JOIN korisnik ON zaduzen.id_korisnik = korisnik.id_korisnik "
                . "INNER JOIN stranica ON stranica.id_stranica = pozicija.id_stranica WHERE korisnik.id_korisnik = '$korimeId'";
    }
    $pozicije = $baza->selectDB($upit);
    if (mysqli_num_rows($pozicije) > 0) {
        while ($row = mysqli_fetch_assoc($pozicije)) {
            $rows[] = $row;
        }
    }
    header("Content-Type: application/json");
    echo json_encode($rows);
} else if (isset($_POST['tipPodatka']) && $_POST['tipPodatka'] == "zahtjev") {
    $rows = array();
    $korimeId = $_POST['korimeId'];
    $tipKorisnika = $_POST['tipKorisnika'];
    if ($tipKorisnika == "administrator") {
        $upit = "select oglas.id_oglas, oglas.naziv as naziv_oglasa, status.naziv as naziv_statusa, vrsta_oglasa.id_vrsta_oglasa, pozicija.id_pozicija "
                . "from oglas "
                . "inner join status on status.id_status = oglas.id_status "
                . "inner join vrsta_oglasa on vrsta_oglasa.id_vrsta_oglasa = oglas.id_vrsta_oglasa "
                . "inner join pozicija on pozicija.id_pozicija = vrsta_oglasa.id_pozicija "
                . "where status.naziv = 'zahtjev'";
    } else if ($tipKorisnika == "moderator") {
        $upit = "select pozicija.id_pozicija "
                . "from korisnik "
                . "inner join zaduzen on korisnik.id_korisnik = zaduzen.id_korisnik "
                . "inner join pozicija on pozicija.id_pozicija = zaduzen.id_pozicija "
                . "where korisnik.id_korisnik = '$korimeId'";
        $rezultatUpita = $baza->selectDB($upit);
        if (mysqli_num_rows($rezultatUpita) > 0) {
            while ($row = mysqli_fetch_assoc($rezultatUpita)) {
                $pozicija = $row['id_pozicija'];
                $upit = "select oglas.id_oglas, oglas.naziv as naziv_oglasa, status.naziv as naziv_statusa, vrsta_oglasa.id_vrsta_oglasa, pozicija.id_pozicija "
                        . "from oglas "
                        . "inner join status on status.id_status = oglas.id_status "
                        . "inner join vrsta_oglasa on vrsta_oglasa.id_vrsta_oglasa = oglas.id_vrsta_oglasa "
                        . "inner join pozicija on pozicija.id_pozicija = vrsta_oglasa.id_pozicija "
                        . "where pozicija.id_pozicija = '$pozicija' AND status.naziv = 'zahtjev'";

                $rezultatUpita1 = $baza->selectDB($upit);
                if (mysqli_num_rows($rezultatUpita1)) {
                    while ($row1 = mysqli_fetch_assoc($rezultatUpita1)) {
                        $rows[] = $row1;
                    }
                }
            }
        }
    }

    if ($tipKorisnika == "administrator") {
        $pozicije = $baza->selectDB($upit);
        if (mysqli_num_rows($pozicije) > 0) {
            while ($row = mysqli_fetch_assoc($pozicije)) {
                $rows[] = $row;
            }
        }
    }
    header("Content-Type: application/json");
    echo json_encode($rows);
} else if (isset($_POST['tipPodatka']) && $_POST['tipPodatka'] == "primjedba") {
    $upit = "SELECT oglas.naziv, oglas.id_oglas, korisnik.korIme, primjedba.razlog, primjedba.datum_primjedbe, primjedba.status FROM oglas INNER JOIN primjedba ON oglas.id_oglas = primjedba.id_oglas INNER JOIN korisnik ON korisnik.id_korisnik = primjedba.id_korisnik INNER JOIN status ON status.id_status = oglas.id_status WHERE status.naziv = 'aktivan' AND primjedba.status = 'zahtjev' ORDER BY primjedba.datum_primjedbe";
    $rows = array();
    $primjedbe = $baza->selectDB($upit);
    if (mysqli_num_rows($primjedbe) > 0) {
        while ($row = mysqli_fetch_assoc($primjedbe)) {
            $rows[] = $row;
        }
    }
    header("Content-Type: application/json");
    echo json_encode($rows);
    
}

else if(isset($_POST['tipPodatka']) && $_POST['tipPodatka'] == "registrirani"){
    $upit = "select korisnik.id_korisnik, korisnik.korIme "
            . "from korisnik "
            . "inner join tip_korisnika "
            . "on korisnik.id_tip_korisnika = tip_korisnika.id_tip_korisnika "
            . "where naziv = 'registriran'";
    $rows = array();
    $regKorisnici = $baza->selectDB($upit);
    if (mysqli_num_rows($regKorisnici) > 0) {
        while ($row = mysqli_fetch_assoc($regKorisnici)) {
            $rows[] = $row;
        }
    }
    header("Content-Type: application/json");
    echo json_encode($rows);
}

else if(isset($_POST['tipPodatka']) && $_POST['tipPodatka'] == "hotel"){
    $upit = "select id_hotel, naziv from hotel";
    $rows = array();
    $hoteliSobe = $baza->selectDB($upit);
    if (mysqli_num_rows($hoteliSobe) > 0) {
        while ($row = mysqli_fetch_assoc($hoteliSobe)) {
            $rows[] = $row;
        }
    }
    header("Content-Type: application/json");
    echo json_encode($rows);
}

else if(isset($_POST['tipPodatka']) && $_POST['tipPodatka'] == "soba"){
    $idHotel = $_POST['hotel'];
    $velicina = $_POST['velicina'];
    
    $datum1=$_POST['datum1'];
    $datum2=$_POST['datum2'];
if($datum1!="none" && $datum1!="" && $datum2 != "none" && $datum2!=""){
    $datetime1 = new DateTime($datum1);
    $datetime2 = new DateTime($datum2);
    $datum1 = $datetime1->format('Y-m-d H:i:s');
    $datum2 = $datetime2->format('Y-m-d H:i:s');
}

    $upit = "select soba.id_soba, soba.broj_sobe, soba.broj_lezajeva, soba.slika, soba.opis, soba.id_hotel from soba where id_hotel = '$idHotel'";
    /*
    $upit = "select distinct soba.id_soba, soba.broj_sobe, soba.broj_lezajeva, soba.slika, soba.opis, soba.id_hotel from soba "
            . "inner join rezervacija on rezervacija.id_soba = soba.id_soba where id_hotel = '$idHotel'";
   */
    if($velicina != "none"){
        $upit = $upit." AND soba.broj_lezajeva = '$velicina'";
    }
     
    
    
    if($datum1!="none" && $datum1!="" && $datum2 != "none" && $datum2!=""){
        $upit = $upit." AND soba.id_soba not in ("
                . "SELECT soba.id_soba FROM soba "
                . "JOIN rezervacija ON soba.id_soba = rezervacija.id_soba "
                . "WHERE ('$datum1' > rezervacija.datum_dolaska AND  '$datum1' < rezervacija.datum_odlaska) "
                . "OR ('$datum2' > rezervacija.datum_dolaska "
                . "AND  '$datum2' < rezervacija.datum_odlaska))";
    }
     
    
    $rows = array();
    $hoteliSobe = $baza->selectDB($upit);
   if (mysqli_num_rows($hoteliSobe) > 0) {
        while ($row = mysqli_fetch_assoc($hoteliSobe)) {
            $rows[] = $row;
        }
    }
    header("Content-Type: application/json");
    echo json_encode($rows);
}

else if(isset($_POST['tipPodatka']) && $_POST['tipPodatka'] == "vrste"){
    $upit = "select * from vrsta_oglasa";
    $rows = array();
    $vrsteOglasa = $baza->selectDB($upit);
    if (mysqli_num_rows($vrsteOglasa) > 0) {
        while ($row = mysqli_fetch_assoc($vrsteOglasa)) {
            $rows[] = $row;
        }
    }
    header("Content-Type: application/json");
    echo json_encode($rows);
} else if(isset($_POST['tipPodatka']) && $_POST['tipPodatka'] == "mojiOglasi"){
    $idKorisnik = $_POST['korisnik'];
    $upit = "select oglas.id_oglas, oglas.naziv, oglas.url, oglas.broj_klikova, oglas.slika, status.naziv as statusNaziv "
            . "from oglas "
            . "inner join status "
            . "on status.id_status = oglas.id_status where oglas.id_korisnik = '$idKorisnik'";
    $rows = array();
    $oglas = $baza->selectDB($upit);
    if (mysqli_num_rows($oglas) > 0) {
        while ($row = mysqli_fetch_assoc($oglas)) {
            $rows[] = $row;
        }
    }
    header("Content-Type: application/json");
    echo json_encode($rows);
}
else if(isset($_POST['tipPodatka']) && $_POST['tipPodatka'] == "aktivni"){
    $upit = "select oglas.naziv, oglas.id_oglas from oglas inner join status on oglas.id_status = status.id_status where status.naziv = 'aktivan'";
    $rows = array();
    $oglas = $baza->selectDB($upit);
    if (mysqli_num_rows($oglas) > 0) {
        while ($row = mysqli_fetch_assoc($oglas)) {
            $rows[] = $row;
        }
    }
    header("Content-Type: application/json");
    echo json_encode($rows);
}

else if(isset($_POST['tipPodatka']) && $_POST['tipPodatka'] == "oglasiIzmjena"){
    $lokacija = $_POST['lokacija'];
    $stranica = $_POST['stranica'];
    $upit = "SELECT oglas.naziv, oglas.slika, status.naziv, oglas.id_vrsta_oglasa, vrsta_oglasa.id_pozicija, vrsta_oglasa.brzina_izmjene, pozicija.id_stranica, pozicija.id_lokacija "
            . "FROM oglas INNER JOIN status ON status.id_status = oglas.id_status "
            . "INNER JOIN vrsta_oglasa ON vrsta_oglasa.id_vrsta_oglasa = oglas.id_vrsta_oglasa "
            . "INNER JOIN pozicija ON pozicija.id_pozicija = vrsta_oglasa.id_pozicija "
            . "WHERE status.naziv =  'aktivan' AND pozicija.id_stranica = '$stranica' AND pozicija.id_lokacija = '$lokacija'";
    $rows = array();
    $oglas = $baza->selectDB($upit);
    if (mysqli_num_rows($oglas) > 0) {
        while ($row = mysqli_fetch_assoc($oglas)) {
            $rows[] = $row;
        }
    }
    header("Content-Type: application/json");
    echo json_encode($rows);
}
else if(isset($_POST['tipPodatka']) && $_POST['tipPodatka'] == "slobodneSobe"){
    $upit = "select hotel.naziv, soba.broj_sobe, soba.broj_lezajeva, soba.opis from hotel inner join soba on soba.id_hotel = hotel.id_hotel where '1' = '1'";
    $rows = array();
    $oglas = $baza->selectDB($upit);
    if (mysqli_num_rows($oglas) > 0) {
        while ($row = mysqli_fetch_assoc($oglas)) {
            $rows[] = $row;
        }
    }
    header("Content-Type: application/json");
    echo json_encode($rows);
}
else if(isset($_POST['tipPodatka']) && $_POST['tipPodatka'] == "statistikaReg"){
    $korime = $_POST['korime'];
    $sort = $_POST['sortiranje'];
    $upit = "SELECT `oglas`.`naziv`, `oglas`.`broj_klikova`, `korisnik`.`korIme`, oglas.id_vrsta_oglasa FROM oglas "
            . "INNER JOIN korisnik ON `oglas`.`id_korisnik` = `korisnik`.`id_korisnik` inner join status on oglas.id_status = status.id_status "
            . "WHERE `korisnik`.`korIme` = '$korime' AND status.naziv = 'aktivan' OR status.naziv = 'istekao' OR status.naziv = 'blokiran' ";
    if($sort == "d"){
        $upit = $upit."order by oglas.broj_klikova asc";
    }
    else{
         $upit = $upit."order by oglas.broj_klikova desc";

    }
    $statistikaReg = $baza->selectDB($upit);
    if (mysqli_num_rows($statistikaReg) > 0) {
        while ($row = mysqli_fetch_assoc($statistikaReg)) {
            $rows[] = $row;
        }
    }
    header("Content-Type: application/json");
    echo json_encode($rows);
}
else if(isset($_POST['tipPodatka']) && $_POST['tipPodatka'] == "sobe"){
    $idHotel = $_POST['hotel'];
    $upit = "select id_soba, broj_sobe, broj_lezajeva, slika, opis from soba where id_hotel = '$idHotel'";
    $rows = array();
    $hoteliSobe = $baza->selectDB($upit);
    if (mysqli_num_rows($hoteliSobe) > 0) {
        while ($row = mysqli_fetch_assoc($hoteliSobe)) {
            $rows[] = $row;
        }
    }
    header("Content-Type: application/json");
    echo json_encode($rows);
}
$baza->zatvoriDB();
?>

