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
            error_log('Reportes::render->Vista de reportes cargada correctamente.');
        } catch (Exception $e) {
            error_log('Reportes::render -> Error al obtener reportes: ' . $e->getMessage());
            $this->view->render('errores/error', ['error' => 'Error al cargar los reportes.']);
        }
    }

    function reportesPorNivelAcademico()
    {
        try {
            $reportes = $this->model->getReportesPorNivelAcademico();
            error_log('Reportes::reportesPorNivelAcademico -> Datos obtenidos: ' . json_encode($reportes));

            $this->view->reportes = $reportes;
            $this->view->render('reportes/nivelAcademico');
        } catch (Exception $e) {
            error_log('Reportes::reportesPorNivelAcademico -> Error al obtener reportes: ' . $e->getMessage());
            $this->view->render('errores/error', ['error' => 'Error al cargar los reportes por nivel académico.']);
        }
    }

    function reportesPorFormacion()
    {
        try {
            $reportes = $this->model->getReportesPorFormacion();
            error_log('Reportes::reportesPorFormacion -> Datos obtenidos: ' . json_encode($reportes));

            $this->view->reportes = $reportes;
            $this->view->render('reportes/formacion');
        } catch (Exception $e) {
            error_log('Reportes::reportesPorFormacion -> Error al obtener reportes: ' . $e->getMessage());
            $this->view->render('errores/error', ['error' => 'Error al cargar los reportes por formación.']);
        }
    }

    function reportesPorModalidad()
    {
        try {
            $reportes = $this->model->getReportesPorModalidad();
            error_log('Reportes::reportesPorModalidad -> Datos obtenidos: ' . json_encode($reportes));

            $this->view->reportes = $reportes;
            $this->view->render('reportes/modalidad');
        } catch (Exception $e) {
            error_log('Reportes::reportesPorModalidad -> Error al obtener reportes: ' . $e->getMessage());
            $this->view->render('errores/error', ['error' => 'Error al cargar los reportes por modalidad.']);
        }
    }

    function obtenerReportes($tipoReporte)
    {
        error_log('entro al metodo' . $tipoReporte);
        try {
            switch ($tipoReporte[0]) {
                case 'nivelAcademico':
                    $reportes = $this->model->getReportesPorNivelAcademico();
                    error_log('entro aca');
                    break;
                case 'nivelFormacion':
                    $reportes = $this->model->getReportesPorFormacion();
                    error_log('o entro aca');

                    break;
                case 'modalidad':
                    $reportes = $this->model->getReportesPorModalidad();
                    error_log('o aqui');
                    break;
                default:
                    $reportes = [];
            }
            echo json_encode($reportes);
        } catch (Exception $e) {
            error_log('Reportes::obtenerReportes -> Error al obtener reportes: ' . $e->getMessage());
            echo json_encode(['error' => 'Error al cargar los reportes.']);
        }
    }
}
