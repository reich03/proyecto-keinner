<?php


class App
{
    function __construct()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $url = explode('/', $url);

        require_once('controllers/errores.php');

        if (empty($url[0])) {
            error_log('APP::construct->No hay controlador Especificado');
            $archivoController = "controllers/main.php";
            require_once $archivoController;
            $controller = new Main();
            $controller->LoadModel('main');
            $controller->render();
            return;
        }

        $archivoController = 'controllers/' . $url[0] . '.php';
        if (file_exists($archivoController)) {
            require_once $archivoController;
            $controller = new $url[0];
            $controller->LoadModel($url[0]);

            if (isset($url[1])) {
                if (method_exists($controller, $url[1])) {
                    if (isset($url[2])) {
                        $nparam = count($url) - 2;
                        $params = [];
                        for ($i = 0; $i < $nparam; $i++) {
                            array_push($params, $url[$i + 2]);
                        }
                        $controller->{$url[1]}($params);
                    } else {
                        $controller->{$url[1]}();
                    }
                } else {
                    error_log('APP::construct->No existe el metodo Especificado');
                    $controller = new Error404();
                    $controller->render();
                }
            } else {
                $controller->render();
            }
        } else {
            error_log('APP::construct->No existe el controlador');
            $controller = new Error404();
            $controller->render();
        }
    }
}
