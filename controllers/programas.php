<?php

class Programas extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->loadModel('programas');
        error_log('Programas::construct -> Controlador de Programas creado.');
    }


    function render()
    {
        try {
            $programas = $this->model->getAllPrograms();
            $this->view->render('programas/index', ['programas' => $programas]);
            error_log('programas::render->View de programas');
        } catch (Exception $e) {
            error_log('Programas::render -> Error al obtener programas: ' . $e->getMessage());
            $this->view->render('errores/error', ['error' => 'Error al cargar los programas.']);
        }
    }

    function index()
    {
        try {
            $programas = $this->model->getAllPrograms();
            error_log('Cantidad de programas obtenidos: ' . count($programas));
            $this->view->render('programas/index', ['programas' => $programas]);
        } catch (Exception $e) {
            error_log('Programas::index -> Error al obtener programas: ' . $e->getMessage());
            $this->view->render('errores/error', ['error' => 'Error al cargar los programas.']);
        }
    }


    function carga()
    {
        try {
            error_log('PHP upload_max_filesize: ' . ini_get('upload_max_filesize'));
            error_log('PHP post_max_size: ' . ini_get('post_max_size'));
            error_log('PHP memory_limit: ' . ini_get('memory_limit'));

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel'])) {
                $file = $_FILES['excel'];

                if ($file['error'] !== UPLOAD_ERR_OK) {
                    $uploadErrors = [
                        UPLOAD_ERR_INI_SIZE => 'El archivo excede el tamaño permitido por upload_max_filesize.',
                        UPLOAD_ERR_FORM_SIZE => 'El archivo excede el tamaño máximo especificado en el formulario.',
                        UPLOAD_ERR_PARTIAL => 'El archivo se subió parcialmente.',
                        UPLOAD_ERR_NO_FILE => 'No se subió ningún archivo.',
                        UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal.',
                        UPLOAD_ERR_CANT_WRITE => 'No se pudo escribir el archivo en el disco.',
                        UPLOAD_ERR_EXTENSION => 'Una extensión de PHP detuvo la carga del archivo.'
                    ];
                    $errorMsg = $uploadErrors[$file['error']] ?? 'Error desconocido.';
                    throw new Exception('Error al subir el archivo: ' . $errorMsg);
                }

                error_log('Archivo recibido: ' . $file['name'] . ' (' . $file['size'] . ' bytes)');
                if ($file['size'] > 20 * 1024 * 1024) {
                    throw new Exception('El archivo excede el tamaño máximo permitido de 20 MB.');
                }

                if (!$this->isValidExcelFile($file)) {
                    throw new Exception('El archivo no es un Excel válido.');
                }

                $filePath = $file['tmp_name'];
                error_log('Archivo temporal: ' . $filePath);

                $result = $this->model->loadFromExcel($filePath);
                if ($result) {
                    $this->view->render('programas/carga', ['success' => 'Archivo cargado correctamente.']);
                } else {
                    throw new Exception('Error al cargar los datos en la base de datos.');
                }
            } else {
                $this->view->render('programas/carga');
            }
        } catch (Exception $e) {
            error_log('Programas::carga -> Error: ' . $e->getMessage());
            $this->view->render('programas/carga', ['error' => $e->getMessage()]);
        }
    }

    public function editar()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            error_log('el valor de la data es:' . $data);
            if (!$data || !isset($data['snies'])) {
                throw new Exception('Datos inválidos.');
            }

            $result = $this->model->updateProgram($data[0]);
            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Error al actualizar el programa.');
            }
        } catch (Exception $e) {
            error_log('Programas::editar -> Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    public function eliminar($snies)
    {
        try {

            error_log('el valor desde el controlador' . var_dump($snies));
            $result = $this->model->deleteProgram($snies[0]);
            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Error al eliminar el programa.');
            }
        } catch (Exception $e) {
            error_log('Programas::eliminar -> Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }



    private function isValidExcelFile($file)
    {
        $allowedExtensions = ['xls', 'xlsx'];
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

        return in_array($fileExtension, $allowedExtensions);
    }
}
