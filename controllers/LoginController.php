<?php 


namespace Controllers ;
use Classes\Email ; 
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router) {

         $alertas = [] ;
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = new Usuario($_POST); 

            $alertas = $usuario->validarLogin();

            if(empty($alertas)) {
                //Verificar que el usuario exista 
                $usuario = Usuario::where('email', $usuario->email) ;
                   
                if(!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado') ;
               
                } else {
                    //El usuario existe y esta confirmado
                    if(password_verify($_POST['password'] , $usuario->password)) {
                      //Iniciar la session 
                      session_start(); 
                      $_SESSION['id'] = $usuario->id ;
                      $_SESSION['nombre'] = $usuario->nombre ;
                      $_SESSION['email'] = $usuario->email ;
                      $_SESSION['login'] = true ; 

                      //Redireccionar 
                      header('Location: /dashboard') ; 
                      debuguear($_SESSION); 
                    } else {
                        Usuario::setAlerta('error', 'Password incorrecto') ;
                    }
                }
            }
        }
        $alertas = Usuario::getAlertas() ;
        //Render a la vista 
        $router->render('auth/login', [
            'titulo' => "iniciar sesión",
            'alertas' => $alertas,

        ]) ; 
    }

    public static function logout() {
        echo "Desde login" ; 

    }


    public static function crear(Router $router) { 
        $alertas = [] ; 
        $usuario = new Usuario ; 

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
              $usuario->sincronizar($_POST) ; 
              
              $alertas = $usuario->validarNuevaCuenta(); 

             if(empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email) ; 
              
                if($existeUsuario){
                    Usuario::setAlerta('error', 'el usuario ya esta registrado') ; 
                    $alertas = Usuario::getAlertas() ; 
                } else {
                    //Hashear el password 
                    $usuario->hashPassword();
                    
                    //Eliminar password2 
                    unset($usuario->password2) ; 

                    //Generar token 
                    $usuario->crearToken() ; 

                    //crear un nuevo usuario 
                $resultado = $usuario->guardar() ;
                
                //Enviar Email 
                $email = new Email($usuario->email,$usuario->nombre,$usuario->token); 
                $email->enviarConfirmacion(); 
                

                
                if($resultado) {
                  header('Location: /mensaje') ; 
                }

                }
             }
        }

        
        //Render a la vista 
        $router->render('auth/crear', [
            'titulo' => "Crear cuenta",
            'usuario' => $usuario ,
            'alertas' => $alertas, 
            
        ]) ;
    }

    public static function olvide(Router $router) {
       $alertas = [] ; 
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
           $usuario = new Usuario($_POST); 
           $alertas = $usuario->validarEmail(); 

           if(empty($alertas)) {
            //Buscar el usuario 
            $usuario = Usuario::where('email', $usuario->email) ;
            if($usuario && $usuario->confirmado) {
                //encontre al usuario

              //Generar un nuevo toke
               $usuario->crearToken() ; 
               unset($usuario->password2) ; 

              //Actualizar el usuario 
               $usuario->guardar() ;

              //Enviar el email 
                $email = new Email($usuario->email,$usuario->nombre,$usuario->token) ; 
                $email->enviarInstrucciones(); 
              //Imprimir la alerta
                Usuario::setAlerta('exito','Hemos enviado las instrucciones a tu email') ; 
              
            } else {
               Usuario::setAlerta('error', 'El Email no se encuentra registrado') ;
    
             }

           }
        }
        $alertas = Usuario::getAlertas() ; 
        //Muestra la vista 
        $router->render('auth/olvide', [
            'titulo' => 'Olvide Mi Password',
            'alertas' => $alertas, 
        ]) ; 
    }


    
    public static function reestablecer(Router $router) {
          $token = s($_GET['token']);
          $mostrar = true ; 

          if(!$token) { 
            header('Location: /');
        }
        //Identificar el usuario con el token 
        $usuario = Usuario::where('token', $token) ;
          
        if(empty($usuario)) { 
            Usuario::setAlerta('error','Usuario no encontrado'); 
            $mostrar = false ;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            //Añadir el nuevo password 
            $usuario->sincronizar($_POST);
          //Validar el password 
       $alertas =  $usuario->validarPassword();
           
       if(empty($alertas)) {
         //Hashear el nuevo password 
        $usuario->hashPassword();

         //Eliminar el token 
         $usuario->token = null ; 
         //Guardar el usario 
        $resultado =  $usuario->guardar() ;

         //Redireccionar
         if($resultado) {
             header('Location: /') ;
         } 
       }

        }

        $alertas = Usuario::getAlertas();
        //Muestra la vista 
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password',
            'alertas' => $alertas,
            'mostrar' => $mostrar, 

        ]) ; 
    }

    
    public static function mensaje(Router $router) {
      $router->render('auth/mensaje', [
        "titutlo" => "Cuenta Creada Exitosamente"
      ]) ; 
    }

    public static function confirmar(Router $router) {
        $token = s($_GET['token']);

       if(!$token) {
        header('Location: /');
        }

        //Encontrar al usuario con el token 
        $usuario = Usuario::where('token', $token) ;

        if(empty($usuario)) {
            //No se encontro un usuario con ese token 
            Usuario::setAlerta('error', 'Token no valido') ; 
        } else {
            //Confirmar la cuenta 
            $usuario->confirmado = 1 ; 
            $usuario->token = null ;
            unset($usuario->password2) ; 
          
            //Guardar en la base de datos 
            $usuario->guardar() ;

            Usuario::setAlerta('exito', 'Cuenta confirmada correctamente') ;
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar', [
            "titulo" => 'Confirma tu cuenta UpTask',
            "alertas" => $alertas , 
        ]) ; 
    }
}