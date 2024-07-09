<?php 


namespace Controllers ;

use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router) {


        if($_SERVER['REQUEST_METHOD'] == 'POST') {

        }

        //Render a la vista 
        $router->render('auth/login', [
            'titulo' => "iniciar sesión"

        ]) ; 
    }

    public static function logout() {
        echo "Desde login" ; 

    }


    public static function crear(Router $router) { 
           $usuario = new Usuario ; 

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
              $usuario->sincronizar($_POST) ; 
              
              $alertas = $usuario->validarNuevaCuenta(); 

              debuguear($alertas) ; 
        }

        
        //Render a la vista 
        $router->render('auth/crear', [
            'titulo' => "Crear cuenta",
            'usuario' => $usuario ,
            
        ]) ;
    }

    public static function olvide(Router $router) {
       
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

        }
        //Muestra la vista 
        $router->render('auth/olvide', [
            'titulo' => 'Olvide Mi Password'
        ]) ; 
    }


    
    public static function reestablecer(Router $router) {

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

        }
        //Muestra la vista 
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password'
        ]) ; 
    }

    
    public static function mensaje(Router $router) {
      $router->render('auth/mensaje', [
        "titutlo" => "Cuenta Creada Exitosamente"
      ]) ; 
    }

    public static function confirmar(Router $router) {
        $router->render('auth/confirmar', [
            "titulo" => 'Confirma tu cuenta UpTask'
        ]) ; 
    }
}