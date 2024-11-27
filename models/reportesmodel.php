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

    function getReportesPorNivelAcademico()
    {
        try {
            $query = $this->query("
                SELECT 
                na.nomb_academ AS nivel_academico,
                    m.nomb_modalidad AS modalidad,
                    p.nomb_programa AS nombre_programa,
					s.nomb_munic AS nombre_municipio,
					d.nomb_depto AS nombre_departamento
                FROM programas p
                LEFT JOIN nivel_academico na ON p.cod_academ = na.cod_academ
                LEFT JOIN modalidades m ON p.cod_modalidad = m.cod_modalidad
                LEFT JOIN municipios s ON p.cod_munic = s.cod_munic
				LEFT JOIN departamentos d ON s.cod_depto = d.cod_depto
                WHERE na.nomb_academ IN ('Pregrado', 'Posgrado')
                ORDER BY na.nomb_academ, m.nomb_modalidad, p.nomb_programa
            ");
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            error_log('ReportesModel::getReportesPorNivelAcademico -> Datos: ' . json_encode($result));
            return $result;
        } catch (PDOException $e) {
            error_log('ReportesModel::getReportesPorNivelAcademico -> Error: ' . $e->getMessage());
            return [];
        }
    }

    function getReportesPorFormacion()
    {
        try {
            $query = $this->query("
                SELECT 
                    nf.nomb_nivel_formacion AS nivel_formacion,
                    m.nomb_modalidad AS modalidad,
                    p.nomb_programa AS nombre_programa,
                    s.nomb_munic AS nombre_municipio,
					d.nomb_depto AS nombre_departamento
                FROM programas p
                LEFT JOIN niveles_formacion nf ON p.cod_nivel_formacion = nf.cod_nivel_formacion
                LEFT JOIN modalidades m ON p.cod_modalidad = m.cod_modalidad
                 LEFT JOIN municipios s ON p.cod_munic = s.cod_munic
				LEFT JOIN departamentos d ON s.cod_depto = d.cod_depto
                WHERE nf.nomb_nivel_formacion IN ('Universitario', 'Maestría', 'Tecnológico', 'Especialización Universitaria')
                ORDER BY nf.nomb_nivel_formacion, m.nomb_modalidad, p.nomb_programa
            ");
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            error_log('ReportesModel::getReportesPorFormacion -> Datos: ' . json_encode($result));
            return $result;
        } catch (PDOException $e) {
            error_log('ReportesModel::getReportesPorFormacion -> Error: ' . $e->getMessage());
            return [];
        }
    }

    function getReportesPorModalidad()
    {
        try {
            $query = $this->query("
                SELECT 
                    m.nomb_modalidad AS modalidad,
                    p.nomb_programa AS nombre_programa,
                    s.nomb_munic AS nombre_municipio,
					d.nomb_depto AS nombre_departamento
                FROM programas p
                LEFT JOIN modalidades m ON p.cod_modalidad = m.cod_modalidad
                LEFT JOIN municipios s ON p.cod_munic = s.cod_munic
				LEFT JOIN departamentos d ON s.cod_depto = d.cod_depto
                WHERE m.nomb_modalidad IN ('Presencial', 'Virtual', 'A Distancia')
                ORDER BY m.nomb_modalidad, p.nomb_programa
            ");
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            error_log('ReportesModel::getReportesPorModalidad -> Datos: ' . json_encode($result));
            return $result;
        } catch (PDOException $e) {
            error_log('ReportesModel::getReportesPorModalidad -> Error: ' . $e->getMessage());
            return [];
        }
    }
}
