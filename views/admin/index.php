<h1 class="nombre-pagina">Panel de Administración</h1>
<?php

use Model\Cita;

 include_once __DIR__.'/../templates/barra.php'; ?>

<h2>Buscar Citas</h2>
<div class="busqueda">
    <form class="formulario" action="">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input 
                type="date" 
                name="fecha" 
                id="fecha"
                value="<?php echo $fecha; ?>">
        </div>
    </form>
</div>
<?php
    if(count($citas) === 0){
        echo "<h2>No hay citas para la fecha seleccionada</h2>";
    }
?>
<div id="citas-admin">
    <ul class="citas">
        <?php
            $idCita=0;
            foreach ($citas as $key => $cita){
                if ($idCita !== $cita->id){
                    $total = 0;
        ?>
        <li>
            <p>ID: <span><?php echo $cita->id; ?></span></p>
            <p>Hora: <span><?php echo $cita->hora; ?></span></p>
            <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
            <p>Email: <span><?php echo $cita->email; ?></span></p>
            <p>Telefono: <span><?php echo $cita->telefono; ?></span></p>
            <h3>Servicios</h3>
            <?php 
                    $idCita = $cita->id;
                }// Fin if 
                // Suma el precio de todos los servicios
                $total += $cita->precio;
            ?>
            <p class="servicio">Servicio: <?php echo $cita->servicio . " $" . $cita->precio; ?></p>
                <?php
                $actual = $cita->id; // trae el id actual de la cita
                $proximo = $citas[$key + 1]->id ?? 0; // trae el índice en el arreglo de la DB y le suma 1
                if (esUltimo($actual, $proximo)){
                    ?>
                    <p class="total">Total: <span>$<?php echo $total; ?></span></p>
                    <form action="/api/eliminar" method="POST">
                        <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                        <input type="submit" class="boton-eliminar" value="Eliminar" id="eliminar">
                    </form>
                    <?php
                }
            } //Fin foreach 
        ?>
        <!-- </li> -->
    </ul>
</div>

<?php 
    $script = "<script src='build/js/buscador.js'></script>";
?>