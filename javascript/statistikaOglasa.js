$(document).ready(function () {
    var sort = "d";

    $.ajax({
        type: "post",
        url: "dohvatiPodatke.php",
        data: {
            tipPodatka: 'vrste'
        },
        dataType: "JSON",
        success: function (response) {
            for (var brojac in response) {
              $('#vrsteOglasa').append("<option value=" + response[brojac].id_vrsta_oglasa + ">" + response[brojac].id_vrsta_oglasa + "</option>");
            }
        }
    });

    $.ajax({
        type: "post",
        url: "dohvatiPodatke.php",
        data: {
            tipPodatka: 'statistika',
            korime: 'none',
            datum1: 'none',
            datum2: 'none'
        },
        dataType: "JSON",
        success: function (response) {
            for (var brojac in response) {
                $('#statistikaOglasa').append("<tr>");
                $('#statistikaOglasa').append("<td>" + response[brojac].naziv + "</td>");
                $('#statistikaOglasa').append("<td>" + response[brojac].korIme + "</td>");
                $('#statistikaOglasa').append("<td>" + response[brojac].broj_klikova + "</td>");

                $('#statistikaOglasa').append("</tr>");
            }
        }
    });

    $("#filter").click(function () {
        if ($('#korime').val() != "" || ($('#datumOd').val() != "" && $('#datumDo').val() != "")) {
            var korime = $('#korime').val();
            if(datumOd == "" || datumDo == ""){
                datumOd = "none";
                datumDo = "none";
            }
            else{
            var datumOd = $('#datumOd').val();
            var datumDo = $('#datumDo').val();
        }
                $('#statistikaOglasa').children().remove();
            $.ajax({
                type: "post",
                url: "dohvatiPodatke.php",
                data: {
                    tipPodatka: 'statistika',
                    korime: korime,
                    datum1: datumOd,
                    datum2: datumDo
                },
                dataType: "JSON",
                success: function (response) {
                    for (var brojac in response) {
                        $('#statistikaOglasa').append("<tr>");
                        $('#statistikaOglasa').append("<td>" + response[brojac].naziv + "</td>");
                        $('#statistikaOglasa').append("<td>" + response[brojac].korIme + "</td>");
                        $('#statistikaOglasa').append("<td>" + response[brojac].broj_klikova + "</td>");
                        $('#statistikaOglasa').append("</tr>");
                    }
                }
            });
        }
    });

    reg(sort);

    $('#sortKlik').on('click', function () {
        if (sort == "d") {
            sort = "e";
        } else {
            sort = "d";
        }
        $('#statistikaRegistrirani').children().remove();
        reg(sort);
    })

    function reg(sort) {
        var sortiraj = sort;
        if ($('#korimeId').val()) {
            var korime = $('#korimeId').val();
            $.ajax({
                type: "post",
                url: "dohvatiPodatke.php",
                data: {
                    tipPodatka: 'statistikaReg',
                    korime: korime,
                    sortiranje: sortiraj
                },
                dataType: "JSON",
                success: function (response) {
                    for (var brojac in response) {
                        $('#statistikaRegistrirani').append("<tr>");
                        $('#statistikaRegistrirani').append("<td>" + response[brojac].id_vrsta_oglasa + "</td>");
                        $('#statistikaRegistrirani').append("<td>" + response[brojac].naziv + "</td>");
                        $('#statistikaRegistrirani').append("<td>" + response[brojac].broj_klikova + "</td>");


                        $('#statistikaRegistrirani').append("</tr>");
                    }
                }
            });
        }
    }
});




