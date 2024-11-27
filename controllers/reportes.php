<?php

class Reportes extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->loadModel('reportes');
        error_log('reportes::construct -> Controlador de reportes creado.');
    }

    function render()
    {
        try {
            $reportes = $this->model->getAllReportes();
            error_log('Reportes::render -> Datos obtenidos: ' . json_encode($reportes));

            $this->view->reportes = $reportes;
            $this->view->render('reportes/index');
            error_log('Reportes::render->View de reportes cargada correctamente.');
        } catch (Exception $e) {
            error_log('Reportes::render -> Error al obtener reportes: ' . $e->getMessage());
            $this->view->render('errores/error', ['error' => 'Error al cargar los reportes.']);
        }
    }
}
