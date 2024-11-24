<?php

class Controller
{
    function __construct()
    {
        $this->view = new View();
    }


    function loadModel($model) {
        if (!is_string($model)) {
            error_log("Error: loadModel espera un string. Recibido: " . gettype($model));
            return; 
        }
        
        $url = 'models/' . $model . 'model.php';
        error_log("Error: loadModel el modelo es : " . ($url));

        if (file_exists($url)) {
            require_once $url;
            error_log("Error: loadModel info de la url: " .($url));
            $modelName = ucfirst($model) . 'Model';  
            error_log("Error: loadModel espera un string. Recibido: " .($modelName));
            if (class_exists($modelName)) {
                $this->model = new $modelName();
                error_log("Error: loadModel es xd: " .($modelName) ."xdd"  .($model));

            } else {
                error_log("model::loadModel->Error: Clase $modelName no encontrada");
            }
        } else {
            error_log("model::loadModel->Error: No se encontró el modelo $url");
        }
    }
    
    


    function existPost($params)
    {
        foreach ($params as $param) {
            if (!isset($_POST[$param])) {
                error_log('Controller::existsPosts-> No existe el parametro ' . $param);
                return false;
            }
        }
        return true;
    }

    function existGet($params)
    {
        foreach ($params as $param) {
            if (!isset($_GET[$param])) {
                error_log('Controller::existsGet-> No existe el parametro ' . $param);
                return false;
            }
        }
        return true;
    }

    function getGet($name)
    {
        return $_GET[$name];
    }

    function getPost($name)
    {
        return $_POST[$name];
    }

    function redirect($route, $messages)
    {
        $data = [];
        $params = '';
        foreach ($messages as $key => $message) {
            array_push($data, $key . '=' . $message);
        }
        $params = join('&', $data);
        if ($params !== '') {
            $params = '?' . $params;
        }
        header('Location: ' . constant('URL') . $route . $params);
    }
}
