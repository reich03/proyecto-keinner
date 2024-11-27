<?php


class ReportesModel extends Model
{
    function __construct()
    {
        parent::__construct();
        error_log('ProgramasModel::construct -> Modelo de reportes creado.');
    }

    function getAllReportes()
    {
        try {
            $query = $this->query("
        SELECT 
            na.nomb_academ AS nivel_academico,
            nf.nomb_nivel_formacion AS nivel_formacion,
            m.nomb_modalidad AS modalidad,
            COUNT(*) AS total_programas
        FROM programas p
        LEFT JOIN nivel_academico na ON p.cod_academ = na.cod_academ
        LEFT JOIN niveles_formacion nf ON p.cod_nivel_formacion = nf.cod_nivel_formacion
        LEFT JOIN modalidades m ON p.cod_modalidad = m.cod_modalidad
        GROUP BY na.nomb_academ, nf.nomb_nivel_formacion, m.nomb_modalidad
        ORDER BY na.nomb_academ, nf.nomb_nivel_formacion, m.nomb_modalidad
    ");
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            error_log('ReportesModel::getAllReportes -> Datos: ' . json_encode($result));
            return $result;
        } catch (PDOException $e) {
            error_log('ReportesModel::getAllReportes -> Error: ' . $e->getMessage());
            return [];
        }
    }
}
