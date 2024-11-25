<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

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
            $query = $this->query("
                SELECT 
                    p.snies,
                    p.nomb_programa,
                    e.nomb_estado AS estado,
                    na.nomb_academ AS nivel_academico,
                    nf.nomb_nivel_formacion AS nivel_formacion,
                    m.nomb_modalidad AS modalidad,
                    i.nomb_int AS institucion,
                    d.nomb_depto AS departamento,
                    mu.nomb_munic AS municipio,
                    p.creditos,
                    p.semestres,
                    p.periodicidad,
                    p.codigo_anterior_icfes,
                    p.vigente,
                    r.fecha_resolucion,
                    r.fecha_ejecutoria,
                    r.vigencia AS vigencia_acreditacion,
                    tr.nomb_tipo AS tipo_reconocimiento
                FROM programas p
                LEFT JOIN estados e ON p.cod_estado = e.cod_estado
                LEFT JOIN nivel_academico na ON p.cod_academ = na.cod_academ
                LEFT JOIN niveles_formacion nf ON p.cod_nivel_formacion = nf.cod_nivel_formacion
                LEFT JOIN modalidades m ON p.cod_modalidad = m.cod_modalidad
                LEFT JOIN instituciones i ON p.cod_inst = i.cod_inst
                LEFT JOIN municipios mu ON p.cod_munic = mu.cod_munic
                LEFT JOIN departamentos d ON mu.cod_depto = d.cod_depto
                LEFT JOIN reconocimientos r ON p.snies = r.snies
                LEFT JOIN tipos_reconocimientos tr ON r.cod_tipo = tr.cod_tipo
            ");
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('ProgramasModel::getAllPrograms -> Error: ' . $e->getMessage());
            return [];
        }
    }

    private function getOrInsert($table, $keyColumn, $valueColumn, $value, $db)
    {
        if (!$value) return null;

        $stmt = $db->prepare("SELECT $keyColumn FROM $table WHERE $valueColumn = ?");
        $stmt->execute([$value]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result[$keyColumn];
        } else {
            $stmt = $db->prepare("INSERT INTO $table ($valueColumn) VALUES (?) RETURNING $keyColumn");
            $stmt->execute([$value]);
            return $stmt->fetchColumn();
        }
    }


    private function getOrInsertDepartamento($departamento, $db)
    {
        if (!$departamento) return null;

        $stmt = $db->prepare("SELECT cod_depto FROM departamentos WHERE nomb_depto = ?");
        $stmt->execute([$departamento]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['cod_depto'];
        } else {
            $stmt = $db->prepare("INSERT INTO departamentos (nomb_depto) VALUES (?) RETURNING cod_depto");
            $stmt->execute([$departamento]);
            return $stmt->fetchColumn();
        }
    }

    private function getOrInsertMunicipio($municipio, $departamento, $db)
    {
        if (!$municipio || !$departamento) {
            return null;
        }

        $cod_depto = $this->getOrInsert('departamentos', 'cod_depto', 'nomb_depto', $departamento, $db);

        $stmt = $db->prepare("SELECT cod_munic FROM municipios WHERE nomb_munic = ? AND cod_depto = ?");
        $stmt->execute([$municipio, $cod_depto]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['cod_munic'];
        } else {
            $stmt = $db->prepare("INSERT INTO municipios (nomb_munic, cod_depto) VALUES (?, ?) RETURNING cod_munic");
            $stmt->execute([$municipio, $cod_depto]);
            return $stmt->fetchColumn();
        }
    }

    private function getOrInsertModalidad($modalidad, $db)
    {
        if (!$modalidad) return null;

        $stmt = $db->prepare("SELECT cod_modalidad FROM modalidades WHERE nomb_modalidad = ?");
        $stmt->execute([$modalidad]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['cod_modalidad'];
        } else {
            $stmt = $db->prepare("INSERT INTO modalidades (nomb_modalidad) VALUES (?) RETURNING cod_modalidad");
            $stmt->execute([$modalidad]);
            return $stmt->fetchColumn();
        }
    }

    private function getOrInsertNivelAcademico($nivelAcademico, $db)
    {
        if (!$nivelAcademico) return null;

        $stmt = $db->prepare("SELECT cod_academ FROM nivel_academico WHERE nomb_academ = ?");
        $stmt->execute([$nivelAcademico]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['cod_academ'];
        } else {
            $stmt = $db->prepare("INSERT INTO nivel_academico (nomb_academ) VALUES (?) RETURNING cod_academ");
            $stmt->execute([$nivelAcademico]);
            return $stmt->fetchColumn();
        }
    }

    public function loadFromExcel($filePath)
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();

            $db = $this->db->connect();
            if (!$db) {
                throw new Exception('No se pudo establecer la conexión a la base de datos.');
            }

            $db->beginTransaction();

            foreach ($worksheet->getRowIterator() as $rowIndex => $row) {
                if ($rowIndex === 1) continue; // Salta la fila de encabezados

                $data = [];
                foreach ($row->getCellIterator() as $cell) {
                    $value = $cell->getValue();
                    $data[] = $value instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText ? $value->getPlainText() : $value;
                }

                // Ajusta los índices según tu archivo Excel
                $snies = $data[7] ?? null;
                $nomb_programa = $data[9] ?? null;
                $estado_programa = $data[11] ?? null;
                $modalidad = $data[27] ?? 'Presencial'; // Valor predeterminado si está vacío
                $nivel_academico = $data[25] ?? null;
                $institucion = $data[1] ?? null;
                $departamento = $data[34] ?? null;
                $municipio = $data[35] ?? null;
                $creditos = is_numeric($data[28]) ? intval($data[28]) : 0;
                $semestres = is_numeric($data[29]) ? intval($data[29]) : 1;
                $periodicidad = $this->mapPeriodicidad($data[30]) ?? 'Semestral';
                $codigo_anterior_icfes = substr((string) ($data[8] ?? ''), 0, 50);

                // Validaciones básicas
                if (!$snies || !$nomb_programa) {
                    error_log("Fila $rowIndex: Datos incompletos: SNIES=$snies, Programa=$nomb_programa.");
                    continue; // Salta esta fila
                }

                // Inserciones y mapeos
                $cod_estado = $this->getOrInsertEstado($estado_programa, $db);
                $cod_inst = $this->getOrInsert('instituciones', 'cod_inst', 'nomb_int', $institucion, $db);
                $cod_depto = $this->getOrInsert('departamentos', 'cod_depto', 'nomb_depto', $departamento, $db);

                // Manejo de municipio
                $cod_munic = $cod_depto ? $this->getOrInsertMunicipio($municipio, $departamento, $db) : null;
                if (!$cod_munic) {
                    error_log("Fila $rowIndex: Municipio o Departamento vacío. Municipio='$municipio', Departamento='$departamento'");
                    continue;
                }

                // Manejo de modalidad
                $cod_modalidad = $this->getOrInsertModalidad($modalidad, $db);
                if (!$cod_modalidad) {
                    error_log("Fila $rowIndex: Modalidad no válida. Modalidad='$modalidad'");
                    continue;
                }

                // Manejo de nivel académico
                $cod_academ = $this->getOrInsertNivelAcademico($nivel_academico, $db);

                // Inserción en la tabla `programas`
                $stmt = $db->prepare("
                    INSERT INTO programas (
                        snies, nomb_programa, cod_estado, cod_inst, cod_munic, cod_modalidad, 
                        cod_academ, creditos, semestres, periodicidad, codigo_anterior_icfes
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ON CONFLICT (snies) DO UPDATE SET nomb_programa = EXCLUDED.nomb_programa
                ");
                $stmt->execute([
                    $snies,
                    $nomb_programa,
                    $cod_estado,
                    $cod_inst,
                    $cod_munic,
                    $cod_modalidad,
                    $cod_academ,
                    $creditos,
                    $semestres,
                    $periodicidad,
                    $codigo_anterior_icfes
                ]);
            }

            $db->commit();
            return true;
        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            error_log('ProgramasModel::loadFromExcel -> Error: ' . $e->getMessage());
            return false;
        }
    }


    private function getOrInsertNivelFormacion($nivelFormacion, $db)
    {
        return $this->getOrInsert('niveles_formacion', 'cod_nivel_formacion', 'nomb_nivel_formacion', $nivelFormacion, $db);
    }

    private function getOrInsertTipoReconocimiento($tipoReconocimiento, $db)
    {
        return $this->getOrInsert('tipos_reconocimientos', 'cod_tipo', 'nomb_tipo', $tipoReconocimiento, $db);
    }

    private function getOrInsertEstado($estado, $db)
    {
        return $this->getOrInsert('estados', 'cod_estado', 'nomb_estado', $estado, $db);
    }
    private function getOrInsertTitulo($titulo, $db)
    {
        if (!$titulo) {
            error_log("Título no proporcionado.");
            return null;
        }

        $stmt = $db->prepare("SELECT cod_titulo FROM titulos WHERE nomb_titulo = ?");
        $stmt->execute([$titulo]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['cod_titulo'];
        } else {
            $stmt = $db->prepare("INSERT INTO titulos (nomb_titulo) VALUES (?) RETURNING cod_titulo");
            $stmt->execute([$titulo]);
            return $stmt->fetchColumn();
        }
    }

    private function getOrInsertCampoDetallado($detallado, $db)
    {
        if (!$detallado || trim($detallado) === '.') {
            error_log("getOrInsertCampoDetallado: Valor 'detallado' vacío o inválido.");
            return null;
        }

        $stmt = $db->prepare("SELECT cod_detallado FROM campo_detallado WHERE nomb_detallado = ?");
        $stmt->execute([$detallado]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['cod_detallado'];
        } else {
            $stmt = $db->prepare("INSERT INTO campo_detallado (nomb_detallado) VALUES (?) RETURNING cod_detallado");
            $stmt->execute([$detallado]);
            return $stmt->fetchColumn();
        }
    }


    private function getOrInsertInstitucion($institucion, $db)
    {
        if (!$institucion) return null;

        $stmt = $db->prepare("SELECT cod_inst FROM instituciones WHERE nomb_int = ?");
        $stmt->execute([$institucion]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['cod_inst'];
        } else {
            $stmt = $db->prepare("INSERT INTO instituciones (nomb_int) VALUES (?) RETURNING cod_inst");
            $stmt->execute([$institucion]);
            return $stmt->fetchColumn();
        }
    }

    private function mapPeriodicidad($periodicidad)
    {
        $map = [
            'Semestral' => 'Semestral',
            'Anual' => 'Anual',
        ];
        return $map[$periodicidad] ?? null;
    }
}
