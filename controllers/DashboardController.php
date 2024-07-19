<?php

namespace Controllers;

use Model\Proyecto;
use MVC\Router;

class DashboardController
{
    public static function index(Router $router)
    {
        session_start();

        isAuth();

        $router->render('dashboard/index', [
            'titulo' => "Proyectos",
        ]);
    }

    public static function crear_proyecto(Router $router)
    {
        session_start();
        isAuth();
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST);

            //Validacion 
            $alertas = $proyecto->validarProyecto();

            if (empty($alertas)) {
                //Generar una url unica 
                $proyecto->url = md5(uniqid());
                //Almacenar el creador del proyecto 
                $proyecto->propietarioid = $_SESSION['id'];
                //Guardar el proyecto
                $proyecto->guardar();

                //Redireccionar 
                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }

        $router->render('dashboard/crear-proyecto', [
            'alertas' => $alertas,
            'titulo' => "Crear Proyecto",
        ]);
    }

    public static function proyecto(Router $router) {
         
        session_start();
        isAuth(); 
       $token = $_GET['id']; 

       if(!$token) header('Location: /dashboard');
      //Revisar que la persona que visita el proyecto es quien lo creo 
         $proyecto = Proyecto::where('url', $token);
        
         if($proyecto->propietarioid !== $_SESSION['id']) {
            header('Location: /dashboard');
         }

        $router->render('/dashboard/proyecto', [
            'titulo' => $proyecto->proyecto, 
        ]) ; 
    }

    public static function perfil(Router $router)
    {
        session_start();
        $router->render('dashboard/perfil', [
            'titulo' => "perfil",
        ]);
    }
}
