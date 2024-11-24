<?php

// require_once './libs/phpspreadsheet/autoload.php'; 

// use PhpOffice\PhpSpreadsheet\IOFactory;

class ProgramasModel extends Model
{
    function __construct()
    {
        parent::__construct();
        error_log('ProgramasModel::construct -> Modelo de Programas creado.');
    }

    function getAllPrograms()
    {
        try {
            $query = $this->query("SELECT * FROM programas");
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('ProgramasModel::getAllPrograms -> Error: ' . $e->getMessage());
            return [];
        }
    }

    // function loadFromExcel($file)
    // {
    //     try {
    //         $spreadsheet = IOFactory::load($file);
    //         $data = $spreadsheet->getActiveSheet()->toArray();

    //         $this->db->connect()->beginTransaction();

    //         foreach ($data as $index => $row) {
    //             if ($index === 0) continue;

    //             $stmt = $this->prepare("INSERT INTO programas (nomb_programa, cod_inst, estado_programa, creditos, semestres, periodicidad) VALUES (?, ?, ?, ?, ?, ?)");
    //             $stmt->execute([
    //                 $row[9],   
    //                 $row[1],   
    //                 $row[12],  
    //                 $row[29], 
    //                 $row[30],  
    //                 $row[31]   
    //             ]);
    //         }

    //         $this->db->connect()->commit();
    //         return true;
    //     } catch (Exception $e) {
    //         $this->db->connect()->rollBack();
    //         error_log('ProgramasModel::loadFromExcel -> Error: ' . $e->getMessage());
    //         return false;
    //     }
    // }
}
