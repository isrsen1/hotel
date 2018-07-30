$(document).ready(function () {
    $.ajax({
        type: "post",
        url: "dohvatiPodatke.php",
        data: {
            tipPodatka: 'korisnici',
            orderBy: 'none'
        },
        dataType: "JSON",
        success: function (response) {
            for (var brojac in response) {
                $('#korisnici').append("<tr>");
                $('#korisnici').append("<td>" + response[brojac].ime + "</td>");
                $('#korisnici').append("<td>" + response[brojac].prezime + "</td>");
                $('#korisnici').append("<td>" + response[brojac].korime + "</td>");
                if (response[brojac].zakljucan == 1) {
                    $('#korisnici').append("<td><a href=korisnici.php?korime=" + response[brojac].korime + "&status=1>Odblokiraj</a></td>");
                } else {
                    $('#korisnici').append("<td> <a href=korisnici.php?korime=" + response[brojac].korime + "&status=0>Blokiraj</a></td>");
                }
                $('#korisnici').append("</tr>");
            }
        }
    });

    $("#sortIme").click(function () {
        $('#korisnici').children().remove();
        $.ajax({
        type: "post",
        url: "dohvatiPodatke.php",
        data: {
            tipPodatka: 'korisnici',
            orderBy: 'ime'
        },
        dataType: "JSON",
        success: function (response) {
            for (var brojac in response) {
                $('#korisnici').append("<tr>");
                $('#korisnici').append("<td>" + response[brojac].ime + "</td>");
                $('#korisnici').append("<td>" + response[brojac].prezime + "</td>");
                $('#korisnici').append("<td>" + response[brojac].korime + "</td>");
                if (response[brojac].zakljucan == 1) {
                    $('#korisnici').append("<td><a href=korisnici.php?korime=" + response[brojac].korime + "&status=1>Odblokiraj</a></td>");
                } else {
                    $('#korisnici').append("<td> <a href=korisnici.php?korime=" + response[brojac].korime + "&status=0>Blokiraj</a></td>");
                }
                $('#korisnici').append("</tr>");
            }
        }
    });
    });
    
    $("#sortPrezime").click(function () {
        $('#korisnici').children().remove();
        $.ajax({
        type: "post",
        url: "dohvatiPodatke.php",
        data: {
            tipPodatka: 'korisnici',
            orderBy: 'prezime'
        },
        dataType: "JSON",
        success: function (response) {
            for (var brojac in response) {
                $('#korisnici').append("<tr>");
                $('#korisnici').append("<td>" + response[brojac].ime + "</td>");
                $('#korisnici').append("<td>" + response[brojac].prezime + "</td>");
                $('#korisnici').append("<td>" + response[brojac].korime + "</td>");
                if (response[brojac].zakljucan == 1) {
                    $('#korisnici').append("<td><a href=korisnici.php?korime=" + response[brojac].korime + "&status=1>Odblokiraj</a></td>");
                } else {
                    $('#korisnici').append("<td> <a href=korisnici.php?korime=" + response[brojac].korime + "&status=0>Blokiraj</a></td>");
                }
                $('#korisnici').append("</tr>");
            }
        }
    });
    });

});

