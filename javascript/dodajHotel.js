$(document).ready(function () {
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
    
    $.ajax({
            type: "post",
            url: "dohvatiPodatke.php",
            data: {
                tipPodatka: 'hoteli'
            },
            dataType:"JSON",
            
            success: function (response) {
                for(var brojac in response){
                    $('#hotel').append("<option value=" + response[brojac].id_hotel + ">" + response[brojac].naziv + "</option>"); 
                }
            }
        });
    
});
