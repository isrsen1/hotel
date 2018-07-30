$(document).ready(function () {
var velicina = "none";
var datum1 = "none";
var datum2 = "none";
    $.ajax({
        type: "post",
        url: "dohvatiPodatke.php",
        data: {
            tipPodatka: 'hoteli'
        },
        dataType: "JSON",

        success: function (response) {
            soba(response[0].id_hotel, velicina, datum1, datum2);
            for (var brojac in response) {
                $('#hotel').append("<option value=" + response[brojac].id_hotel + ">" + response[brojac].naziv + "</option>");
            }
        }
    });

    function soba(idHotel, velicina, datum1, datum2) {
        $('#sobeHotela').children().remove();
        $.ajax({
            type: "post",
            url: "dohvatiPodatke.php",
            data: {
                tipPodatka: 'soba',
                hotel: idHotel,
                velicina: velicina,
                datum1: datum1,
                datum2: datum2
            },
            dataType: "JSON",

            success: function (response) {
                $('#sobeHotela').append("<tr>");
                $('#sobeHotela').append("<th> Broj sobe </th>");
                $('#sobeHotela').append("<th> Broj lezaja </th>");
                $('#sobeHotela').append("<th> opis </th>");
                $('#sobeHotela').append("<th> Slika </th>");
                $('#sobeHotela').append("</tr>");
                for (var brojac in response) {
                    $('#sobeHotela').append("<tr>");
                    $('#sobeHotela').append("<td style='text-align:center; vertical-align: middle;'><a href=rezervacije.php?idSobe="+ response[brojac].id_soba +"&idHotel=" + response[brojac].id_hotel + ">" + response[brojac].broj_sobe + "</a></td>");
                    $('#sobeHotela').append("<td style='text-align:center; vertical-align: middle;'>" + response[brojac].broj_lezajeva + "</td>");
                    $('#sobeHotela').append("<td style='text-align:center; vertical-align: middle;'>" + response[brojac].opis + "</td>");
                    $('#sobeHotela').append("<td  style='text-align:center; vertical-align: middle;'><a href='sobe/" + response[brojac].slika + "'> <img width='100' height='100' src='sobe/" + response[brojac].slika + "'></a></td>");
                    
                    $('#sobeHotela').append("</tr>");
                }
            }
        });
    }

    $('#hotel').on('change', function () {
        var idHotel = $('#hotel').val();
        velicina = "none";
        datum1 = "none";
        datum2 = "none";
        soba(idHotel, velicina, datum1, datum2);
    })

$('#pretraziSobe').on('click',function(){
    datum1 = $('#pretraziOd').val();
    datum2 = $('#pretraziDo').val();
    velicina = $('#velicinaSobe').val();
    if(velicina == ""){
        velicina = "none";
    }
    var idHotel = $('#hotel').val();
    soba(idHotel, velicina, datum1, datum2);
})

});



