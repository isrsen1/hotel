$(document).ready(function () {

var vrstaOglasaId = $('#vrstaOglasaId').val();
$.ajax({
            type: "post",
            url: "dohvatiPodatke.php",
            data: {
                tipPodatka: 'vrste',
            },
            dataType:"JSON",
            
            success: function (response) {
                for(var brojac in response){
                   if(response[brojac].id_vrsta_oglasa == vrstaOglasaId){
                       $('#vrsteOglasaPadajuci').append("<option selected value=" + response[brojac].id_vrsta_oglasa + ">" + response[brojac].id_vrsta_oglasa + "</option>");
                   }else{
                       $('#vrsteOglasaPadajuci').append("<option value=" + response[brojac].id_vrsta_oglasa + ">" + response[brojac].id_vrsta_oglasa + "</option>"); 
                   }
                       
                }
            }
            
        });
    });