$(document).ready(function () {
    var korimeId = $('#korimeId').val();
    var tipKorisnika = $('#tipKorisnika').val();
    $.ajax({
            type: "post",
            url: "dohvatiPodatke.php",
            data: {
                tipPodatka: 'stranice'
            },
            dataType:"JSON",
            
            success: function (response) {
                for(var brojac in response){
                    $('#stranica').append("<option value=" + response[brojac].id_stranica + ">" + response[brojac].naziv + "</option>"); 
                }
            }
        });
        
        $.ajax({
            type: "post",
            url: "dohvatiPodatke.php",
            data: {
                tipPodatka: 'pozicija',
                korimeId: korimeId,
                tipKorisnika: tipKorisnika
            },
            dataType:"JSON",
            
            success: function (response) {
                for(var brojac in response){
                    $('#pozicija').append("<option value=" + response[brojac].id_pozicija + ">" + response[brojac].naziv  + "-" + response[brojac].id_lokacija + "</option>"); 
                }
            }
        });
        
    $.ajax({
            type: "post",
            url: "dohvatiPodatke.php",
            data: {
                tipPodatka: 'dohvatiModeratore'
            },
            dataType:"JSON",
            
            success: function (response) {
                for(var brojac in response){
                   $('#moderator').append("<option value=" + response[brojac].id_korisnik + ">" + response[brojac].korIme + "</option>");   
            }
            }
        });
});


