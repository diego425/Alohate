$("#ocultarCuentasBancarias").click(function(e) {
    $(".tablaCuentasBancarias").hide();
});

async function clickBtn(input) {
    const foto = $(input).attr("foto");
    $("#input" + foto).click();
}

async function revisarImagen(input) {
    var id_preview = $(input).attr("foto");
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onloadend = function(e) {
            var id_preview_text = "#" + id_preview;
            var base64image = e.target.result;
            $("body").append(
                "<canvas id='tempCanvas' width='800' height='800' style='display:none'></canvas>"
            );
            var canvas = document.getElementById("tempCanvas");
            var ctx = canvas.getContext("2d");
            var cw = canvas.width;
            var ch = canvas.height;
            var maxW = 1000;
            var maxH = 1000;
            var img = new Image;
            img.src = this.result;
            img.onload = function() {
                var iw = img.width;
                var ih = img.height;
                var scale = Math.min((maxW / iw), (maxH / ih));
                var iwScaled = iw * scale;
                var ihScaled = ih * scale;
                canvas.width = iwScaled;
                canvas.height = ihScaled;
                ctx.drawImage(img, 0, 0, iwScaled, ihScaled);
                base64image = canvas.toDataURL("image/jpeg");
                $(id_preview_text).attr('src', base64image);
                $(id_preview_text).attr('xoriginal', base64image);
                $("#tempCanvas").remove();
                $('#val' + id_preview).val(base64image);
                $("#btn" + id_preview).hide();
                $("#quitar" + id_preview).show();
            }
        };
        reader.readAsDataURL(input.files[0]);
        $('#imagen_preview').show();
    }
}

async function quitarFoto(input) {
    const foto = $(input).attr("foto");
    $("#" + foto).attr("src", "");
    $("#" + foto).attr("xoriginal", "");
    $("#val" + foto).val("");
    $("#quitar" + foto).hide();
    $("#btn" + foto).show();
}

function copiar(text, span) {
    // Get the text field
    var copyText = document.getElementById("" + span);

    // Select the text field
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices

    navigator.clipboard
        .writeText(copyText.value)
        .then(() => {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Copiado al portapapeles.",
                showConfirmButton: false,
                timer: 1500
            });
        })
        .catch(() => {
            alert("something went wrong");
        });
}

function verificarTelefono(telefono, idCobro, ruta) {
    $.ajax({
        type: "POST",
        url: ruta,
        data: {
            "telefono": telefono,
            "idCobro": idCobro,
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: "JSON",
        success: function (response) {
            if (response[0].Title[0].Numero_celular == telefono) {
                Swal.close();
                $("#mainPrinc").show();
            }else{
                Swal.fire({
                    icon: "error",
                    text: "El teléfono ingresado no coincide",
                }).then((result) => {
                    location.reload();
                });
            }
        },error: function (error) {
            Swal.fire({
                icon: "error",
                text: "El teléfono ingresado no coincide",
            }).then((result) => {
                location.reload();
            });
        }
    });
}