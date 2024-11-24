<?php

class Programas extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->loadModel('programas');
        error_log('Programas::construct -> Controlador de Programas creado.');
    }


    function render() {
        error_log('programas::render->View de programas');
        $this->view->render('programas/index');
    }
    function index()
    {
        try {
            $programas = $this->model->getAllPrograms();

            $this->view->render('programas/index', ['programas' => $programas]);
        } catch (Exception $e) {
            error_log('Programas::index -> Error al obtener programas: ' . $e->getMessage());
            $this->view->render('errores/error', ['error' => 'Error al cargar los programas.']);
        }
    }

    function carga()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel'])) {
                $file = $_FILES['excel']['tmp_name'];

                if (!$this->isValidExcelFile($_FILES['excel'])) {
                    throw new Exception('El archivo no es un Excel válido.');
                }

                $result = $this->model->loadFromExcel($file);

                if ($result) {
                    $this->view->render('programas/carga', ['success' => 'Archivo cargado correctamente.']);
                } else {
                    throw new Exception('Error al procesar el archivo Excel.');
                }
            } else {
                $this->view->render('programas/carga');
            }
        } catch (Exception $e) {
            error_log('Programas::carga -> Error: ' . $e->getMessage());
            $this->view->render('programas/carga', ['error' => $e->getMessage()]);
        }
    }

    private function isValidExcelFile($file)
    {
        $allowedMimeTypes = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
            'application/vnd.ms-excel', 
        ];
        return in_array($file['type'], $allowedMimeTypes);
    }
}
