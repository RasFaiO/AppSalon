<h1 class="nombre-pagina">Recuperar Contraseña</h1>
<p class="descripcion-pagina">Coloca tu nueva contraseña a continuación</p>
<?php
    require_once __DIR__ . '../../templates/alertas.php';
    if (!$error){
?>
    <form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Contraseña</label>
        <input 
        type="password"
        id="password"
        name="password"
        placeholder="Tu Nueva Contraseña">
    </div>
    <div class="campo">
        <label for="password_r">Contraseña</label>
        <input 
        type="password"
        id="password_r"
        name="password_r"
        placeholder="Confirma Tu Nueva Contraseña">
    </div>
    <input type="submit" class="boton" value="Guardar Nueva Contraseña">
</form>
<?php
    }
?>

<div class="acciones">
    <a href="/">¿Ya Tienes Cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿No Tienes Cuenta? Regístrate</a>
</div>