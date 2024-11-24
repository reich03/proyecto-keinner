<?php

class Error404 extends Controller {
    function __construct() {
        parent::__construct();
        error_log('Error 404::__Construct -> Vista de errores');
    }

    function render() {
        error_log('Errores::render->View de erroress');
        $this->view->render('error/index');
    }
}

