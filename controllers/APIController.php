<?php
namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController{
    public static function index(){
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar() {
        // Almacena la cita y regresa el id del cliente
        // instanciamos el modelo de Cita
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
        $id = $resultado['id'];

        // Almacena las citas y servicios
        // con explode estamos separando tod el string que nos trae $_POST['servicios'] para trabajarlo como un arreglo
        $idServicios = explode(",", $_POST['servicios']);
        
        // Va a ir iterando cada uno de los servicios para ir guardándo cada referencia de la cita 
        foreach($idServicios as $idservicio) {
            // Almacena los servicios con el ID de la cita
            // Este arreglo asociativo viene siendo un objeto en JS
            $args = [
                'citaId' => $id,
                'servicioId' => $idservicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }
        // // Retornamos una respuesta
        // $respuesta = [
        //     'resultado' => $resultado
        // ];
        // Así que lo podremos pasar en un json_encode para obtener ese objeto
        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            
            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location: '.$_SERVER['HTTP_REFERER']);
        }
    }
}