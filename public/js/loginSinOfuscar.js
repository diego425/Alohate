$(document).ready(function(){
    //ver si las credenciales son las correctas
    ls.config.encrypt = true;
    if (ls.get('user') !== null && ls.get('pass') !== null) {
        $.ajax({
            type: "post",
            url: $('meta[name="csrf-url"]').attr('content'),
            data: {
                'user':ls.get('user'),
                'password':ls.get('pass')
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: "JSON",
            success: function (response) {
                if (response.valido === true) {
                    $('#user').val(ls.get('user'));
                    $('#password').val(ls.get('pass'));
                    $("#btnsu").click();
                }
            },error: function (error) {
            }
        });
    }
});

$('.login-reg-panel input[type="radio"]').on('change', function() {
    if($('#log-login-show').is(':checked')) {
        $('.register-info-box').fadeOut();
        $('.login-info-box').fadeIn();

        $('.white-panel').addClass('right-log');
        $('.register-show').addClass('show-log-panel');
        $('.login-show').removeClass('show-log-panel');

    }
    else if($('#log-reg-show').is(':checked')) {
        $('.register-info-box').fadeIn();
        $('.login-info-box').fadeOut();

        $('.white-panel').removeClass('right-log');

        $('.login-show').addClass('show-log-panel');
        $('.register-show').removeClass('show-log-panel');
    }
});

$("#submit").one("click",function (e) {
    form().then((result) => {
        $("#btnsu").click();
    }).catch((err) => {
        $("#btnsu").click();
    });
    
});

/* $('#formulario').submit(function (event) {
    let form = async ()=>{
        await form();
    };
}); */

function form() {
    return new Promise((resolve, reject) => {
        ls.config.encrypt = true;
        ls.set('user',$('#user').val());
        ls.set('pass',$('#password').val());
        resolve("ok");
    })
}

function verCode(code) {
    /* ls.config.encrypt = true;
    Swal.fire({
        title: 'Ingrese el código',
        text: 'Hemos enviado un correo con un código de activación a el Administrador. Por favor, ponlo abajo.',
        input: 'number',
        showCancelButton: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showLoaderOnConfirm: true,
        confirmButtonText: 'Verificar',
        inputAttributes: {
            maxlength: 6
        },
        preConfirm: (code2) => {
            if (code != code2) {
                Swal.showValidationMessage(`Tu codigo no es el mismo`);
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            ls.set('user',$('#Usuario').val());
            ls.set('pass',$('#Contrasenia').val());
            $('#formulario').submit();
        }
    }) */
}