<?php

require_once 'pomocneSkripte/aplikacijskiOkvir.php';

if(isset($_GET['hotel'])&& !isset($_GET['soba'])){
    global $baza;
$hotel = $_GET['hotel'];
$upit = "SELECT `hotel`.`naziv`, `soba`.`broj_sobe`, count(`rezervacija`.`id_soba`) as total FROM hotel INNER JOIN soba ON `hotel`.`id_hotel` = `soba`.`id_hotel` INNER JOIN rezervacija ON `soba`.`id_soba` = `rezervacija`.`id_soba` WHERE `hotel`.`naziv` = '$hotel' GROUP BY `rezervacija`.`id_soba` ";

$sobe = $baza->selectDB($upit);
echo $hotel;
echo "<br>";
if (mysqli_num_rows($sobe) > 0) {
    while ($row = mysqli_fetch_assoc($sobe)) {
        $hotel = $row['naziv'];
        $soba = $row['broj_sobe'];
        $brojRezervacija = $row['total'];
        echo "<a href='hotelDetalji.php?hotel=$hotel&soba=$soba'>" . $soba . "</a>";
        echo "--";
        echo "--";
        echo $brojRezervacija;
        
    }
}
$baza->zatvoriDB();
}
else{
    global $baza;
    $hotel = $_GET['hotel'];
    $soba = $_GET['soba'];
    $upit = "SELECT `broj_sobe`, `slika`, `opis` FROM `soba` INNER JOIN `hotel` ON `hotel`.`id_hotel` = `soba`.`id_hotel` WHERE `hotel`.`naziv` = '$hotel' AND `soba`.`broj_sobe` = '$soba'";
    $detalji = $baza->selectDB($upit);
    if (mysqli_num_rows($detalji) > 0) {
        while ($row = mysqli_fetch_assoc($detalji)) {
            $opis = $row['opis'];
            $slika = $row['slika'];
            echo $opis;
            echo $slika;
    }
}
$baza->zatvoriDB();
}
?>

