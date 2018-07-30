$(document).ready(function () {
    $('#registracija').on('submit', function (event) {
        event.preventDefault();
        var korIme = $('#korime').val();
        var lozinka = $('#lozinka').val();
        var ponoviLozinku = $('#ponoviLozinku').val();
        var email = $('#email').val();
        var reg = /^[a-z]{1,}\.{0,1}[A-Za-z0-9]{1,}@{1,1}[A-Za-z]{1,}\.{1,1}[A-Za-z]{1,}$/;
        
        if(korIme == "" || lozinka == "" || ponoviLozinku == "" || email == ""){
            $('#errorContainer').html("<p>Morate ispuniti sva polja</p><br>");
            event.preventDefault();
            exit();
        }
        else if(lozinka !== ponoviLozinku){
            event.preventDefault();
            $('#errorContainer').html("<p>Lozinke se ne podudaraju</p><br>");
            exit();
        }
        else if(lozinka.length < 6){
            event.preventDefault();
            $('#errorContainer').html("<p>Lozinka sadrzi manje od 6 znakova</p><br>");
            exit();
        }
        else if(korIme.length < 5){
            event.preventDefault();
            $('#errorContainer').html("<p>Korisnicko ime sadrzi manje od 5 znakova</p><br>");
            exit();
        }
        
        
        else if(!reg.test(email)) {
            event.preventDefault();
            $('#errorContainer').html("<p>Email nije u dobrom formatu</p><br>");
            exit();
        }
        
        $.ajax({
            type: "post",
            url: "pomocneSkripte/korImeProvjera.php",
            data: "korisnickoIme=" + korIme,
            success: function (data) {
                if (data == 0) {
                    event.currentTarget.submit();
                } else{
                    alert("Korisnicko ime vec postoji");
                }
            }
        });
        
    })
})


