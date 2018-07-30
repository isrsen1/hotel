$(document).ready(function () {
    $.ajax({
            type: "post",
            url: "dohvatiPodatke.php",
            data: {
                tipPodatka: 'hoteli',
                postBrojacHotela: 100
            },
            dataType:"JSON",
            
            success: function (response) {
                for(var brojac in response){
                    $('#hotel').append("<option value=" + response[brojac].id_hotel + ">" + response[brojac].naziv + "</option>"); 
                }
            }
        });
    
});

