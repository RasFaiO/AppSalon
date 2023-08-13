<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Salón</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="/build/css/app.css">
    <link rel="shortcut icon" href="/build/img/logo.ico" type="image/x-icon">
</head>
<body>
    <div class="contenedor-app">
        <div class="imagen"></div>
        <div class="app">
            <?php echo $contenido; ?>
        </div>
    </div>
           
    <?php
    // Inprimimos la $script solamente en las páginas que necesitemos, en caso de que no exista la $script en la página imprimirrá un texto vacío
        echo $script ?? '';
    ?>
</body>
</html>