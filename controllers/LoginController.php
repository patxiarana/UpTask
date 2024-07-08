<?php 


namespace Controllers ;

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


        if($_SERVER['REQUEST_METHOD'] == 'POST') {

        }

        
        //Render a la vista 
        $router->render('auth/crear', [
            'titulo' => "Crear cuenta"
            
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

    
    public static function mensaje() {
        echo "Desde mensaje" ; 
    }

    public static function confirmar() {
        echo "Desde confirmar" ; 
    }
}