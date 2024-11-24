<?php 


class Main Extends Controller{

    function __construct()
    {
        parent::__construct();
        //Manera de definir Los errores, Pasamos la clase el metodo y la alerta respectiva
        error_log('Login::construct->Inicio de main o vista principal');
    }

    function render()
    {
        error_log('Login::render->Index de main');
        $this->view->render('main/index');
    }
}