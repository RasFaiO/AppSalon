<h1 class="nombre-pagina">¿Olvidaste Tu Contraseña?</h1>
<p class="descripcion-pagina">Agrega tu correo para enviar los datos de recuperación</p>
<?php
    require_once __DIR__ . '../../templates/alertas.php';
?>
<form class="formulario" action="/olvide" method="POST">
    <div class="campo">
        <label for="email">E-mail</label>
        <input type="email"
        id="email"
        name="email"
        placeholder="Tu E-mail">
    </div>
    <input type="submit" value="Recuperar Contraseña" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya Tienes Cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿No Tienes Cuenta? Regístrate</a>
</div>