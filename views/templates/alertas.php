<?php
    foreach ($alertas as $key => $mensajes):
        foreach($mensajes as $mensaje):
?>
    <p class="alerta <?php echo $key; ?>">
        <?php
            echo $mensaje; 
        ?>
    </p>
<?php

        endforeach;
    endforeach;
?>