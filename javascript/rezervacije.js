$(document).ready(function () {
    $.ajax({
            type: "post",
            url: "dohvatiPodatke.php",
            data: {
                tipPodatka: 'registrirani',
                
            },
            dataType:"JSON",
            
            success: function (response) {
                for(var brojac in response){
                   $('#regKorisnici').append("<option value=" + response[brojac].id_korisnik + ">" + response[brojac].korIme + "</option>"); 
                }
            }
        });
    
      $.ajax({
            type: "post",
            url: "dohvatiPodatke.php",
            data: {
                tipPodatka: 'hotel',
            },
            dataType:"JSON",
            
            success: function (response) {
                soba(response[0].id_hotel);
                for(var brojac in response){
                   $('#hotel').append("<option value=" + response[brojac].id_hotel + ">" + response[brojac].naziv + "</option>"); 
                }
            }
            
        });
      
       function soba(idHotel){
           $('#soba').children().remove();
         $.ajax({
            type: "post",
            url: "dohvatiPodatke.php",
            data: {
                tipPodatka: 'sobe',
                hotel: idHotel
            },
            dataType:"JSON",
            
            success: function (response) {
                for(var brojac in response){
                   $('#soba').append("<option value=" + response[brojac].id_soba + ">" + response[brojac].broj_sobe + "</option>"); 
                }
            }
        }); 
    }
    
    $('#hotel').on('change', function() {
        var idHotel = $('#hotel').val();
        soba(idHotel);
})
    
});