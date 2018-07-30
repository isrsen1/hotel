$(document).ready(function () {
    
    var korimeId = $('#korimeId').val();
    var tipKorisnika = $('#tipKorisnika').val();
    
    $.ajax({
            type: "post",
            url: "dohvatiPodatke.php",
            data: {
                tipPodatka: 'mojiOglasi',
                korisnik: korimeId
            },
            dataType:"JSON",
            
            success: function (response) {
                for(var brojac in response){
                   $('#popisOglasa').append("<figure><img width='150' height='150' src='" + response[brojac].slika + "'>");
                   if(response[brojac].statusNaziv == "zahtjev"){
                   $('#popisOglasa').append("<figcaption>" + response[brojac].statusNaziv + "<a href='azurirajOglas.php?idOglas="+ response[brojac].id_oglas+"'> Azuriraj </a>" +  "</figcaption></figure>")
               }
               else{
                   $('#popisOglasa').append("<figcaption>" + response[brojac].statusNaziv +  "</figcaption></figure>")
               }
                        $('#popisOglasa').append("<br>");
                }
            }
        });
    
    $.ajax({
            type: "post",
            url: "dohvatiPodatke.php",
            data: {
                tipPodatka: 'vrste',
            },
            dataType:"JSON",
            
            success: function (response) {
                
                $('#vrsteOglasa').append("<tr>");
                $('#vrsteOglasa').append("<th> Vrsta oglasa </th>");
                $('#vrsteOglasa').append("<th> Pozicija </th>");
                $('#vrsteOglasa').append("<th> Cijena </th>");
                $('#vrsteOglasa').append("<th> Brzina izmjene </th>");
                $('#vrsteOglasa').append("<th> Trajanje </th>");
                    $('#vrsteOglasa').append("</tr>"); 
                
                for(var brojac in response){
                   $('#vrsteOglasa').append("<tr>");
                $('#vrsteOglasa').append("<td>" + response[brojac].id_vrsta_oglasa + "</td>");
                $('#vrsteOglasa').append("<td>" + response[brojac].id_pozicija + "</td>");
                $('#vrsteOglasa').append("<td>" + response[brojac].cijena + "</td>");
                $('#vrsteOglasa').append("<td>" + response[brojac].brzina_izmjene + "</td>");
                $('#vrsteOglasa').append("<td>" + response[brojac].trajanje + "</td>");
                    $('#vrsteOglasa').append("</tr>"); 
                }
            }
        });
    
    $.ajax({
            type: "post",
            url: "dohvatiPodatke.php",
            data: {
                tipPodatka: 'zahtjev',
                korimeId: korimeId,
                tipKorisnika: tipKorisnika
            },
            dataType:"JSON",
            
            success: function (response) {
                for(var brojac in response){
                   $('#zahtjevi').append("<tr>");
                $('#zahtjevi').append("<td>" + response[brojac].naziv_oglasa + "</td>");
                $('#zahtjevi').append("<td>" + response[brojac].id_pozicija + "</td>");
                $('#zahtjevi').append("<td>" + response[brojac].id_vrsta_oglasa + "</td>");
                $('#zahtjevi').append("<td><a href=oglasi.php?status=prihvacen&oglas=" + response[brojac].id_oglas +">Prihvati</a></td>");
                $('#zahtjevi').append("<td><a href=oglasi.php?status=odbijen&oglas=" + response[brojac].id_oglas +">Odbij</a></td>");
                $('#zahtjevi').append("</tr>"); 
                }
            }
        });

    $.ajax({
            type: "post",
            url: "dohvatiPodatke.php",
            data: {
                tipPodatka: 'primjedba',
            },
            dataType:"JSON",
            
            success: function (response) {
                for(var brojac in response){
                   $('#primjedba').append("<tr>");
                $('#primjedba').append("<td>" + response[brojac].naziv + "</td>");
                $('#primjedba').append("<td>" + response[brojac].korIme + "</td>");
                $('#primjedba').append("<td>" + response[brojac].razlog + "</td>");
                $('#primjedba').append("<td>" + response[brojac].datum_primjedbe + "</td>");                
                    $('#primjedba').append("<td><a href=oglasi.php?primjedbe=prihvacena&oglas=" + response[brojac].id_oglas +">Prihvati</a></td>"); 
                $('#primjedba').append("<td><a href=oglasi.php?primjedbe=odbacena&oglas=" + response[brojac].id_oglas +">Odbaci</a></td>"); 
                    $('#primjedba').append("</tr>"); 
                }
            }
        });
        
       $.ajax({
            type: "post",
            url: "dohvatiPodatke.php",
            data: {
                tipPodatka: 'vrste',
            },
            dataType:"JSON",
            
            success: function (response) {
                for(var brojac in response){
                   $('#vrsteOglasaPadajuci').append("<option value=" + response[brojac].id_vrsta_oglasa + ">" + response[brojac].id_vrsta_oglasa + "</option>"); 
                }
            }
            
        });
        
        $.ajax({
            type: "post",
            url: "dohvatiPodatke.php",
            data: {
                tipPodatka: 'aktivni',
            },
            dataType:"JSON",
            
            success: function (response) {
                for(var brojac in response){
                   $('#nazivOglasPrimjedba').append("<option value=" + response[brojac].id_oglas + ">" + response[brojac].naziv + "</option>"); 
                }
            }
            
        });

});

