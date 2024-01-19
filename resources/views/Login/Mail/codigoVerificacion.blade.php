<head>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.11.1/css/all.css">
    <link href="https://fonts.googleapis.com/css?family=Baloo+Da+2&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    Hola {{ $user }}, <i>Este es tu codigo de verificación</i>
    <div class="card">
        <div class="card-body">
            <h2>{{ $codeVer }}</h2>
        </div>
    </div>
    <br>

    Comparta solo si el inicio de sesión es real,
    <br/>
    <p><i>No responder este correo, no recibirá respuesta.</i></p>
</body>