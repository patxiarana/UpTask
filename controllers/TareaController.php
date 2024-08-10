<?php 

namespace Controllers;

class TareaController {
    public static function index() {
        // Código para index
    }

    public static function crear() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Código para crear

            $respuesta = [
                'proyectoid' => $_POST['proyectoid']
            ] ; 

            echo json_encode($respuesta); 
        }
    }

    public static function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Código para actualizar
        }
    }

    public static function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Código para eliminar
        }
    }
}