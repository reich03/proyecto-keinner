<?php
require_once "./views/components/head.php";

$programasPorPagina = 20; 
$paginaActual = isset($_GET['page']) ? intval($_GET['page']) : 1;
$totalProgramas = count($this->d['programas']);
$totalPaginas = ceil($totalProgramas / $programasPorPagina);

$inicio = ($paginaActual - 1) * $programasPorPagina;
$programasPagina = array_slice($this->d['programas'], $inicio, $programasPorPagina);

// Limitar el número de botones de paginación visibles
$rango = 2; // Número de páginas a mostrar a cada lado de la actual
$inicioRango = max(1, $paginaActual - $rango);
$finRango = min($totalPaginas, $paginaActual + $rango);
?>

<div class="container mx-auto py-10 px-4">
    <div class="bg-gray-100 shadow-lg rounded-lg p-6">
        <h1 class="text-3xl font-bold text-center mb-6">Lista de Programas Académicos</h1>

        <div class="overflow-x-auto">
            <table class="table-auto w-full bg-white shadow-md rounded overflow-hidden">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-2">Código SNIES</th>
                        <th class="px-4 py-2">Nombre</th>
                        <th class="px-4 py-2">Estado</th>
                        <th class="px-4 py-2">Créditos</th>
                        <th class="px-4 py-2">Semestres</th>
                        <th class="px-4 py-2">Periodicidad</th>
                        <th class="px-4 py-2">Nivel Académico</th>
                        <th class="px-4 py-2">Modalidad</th>
                        <th class="px-4 py-2">Departamento</th>
                        <th class="px-4 py-2">Municipio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($programasPagina)): ?>
                        <?php foreach ($programasPagina as $programa): ?>
                            <tr class="border-b hover:bg-gray-100">
                                <td class="px-4 py-2"><?php echo $programa['snies']; ?></td>
                                <td class="px-4 py-2"><?php echo $programa['nomb_programa']; ?></td>
                                <td class="px-4 py-2"><?php echo $programa['estado']; ?></td>
                                <td class="px-4 py-2"><?php echo $programa['creditos']; ?></td>
                                <td class="px-4 py-2"><?php echo $programa['semestres']; ?></td>
                                <td class="px-4 py-2"><?php echo $programa['periodicidad']; ?></td>
                                <td class="px-4 py-2"><?php echo $programa['nivel_academico']; ?></td>
                                <td class="px-4 py-2"><?php echo $programa['modalidad']; ?></td>
                                <td class="px-4 py-2"><?php echo $programa['departamento']; ?></td>
                                <td class="px-4 py-2"><?php echo $programa['municipio']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center px-4 py-2">No hay programas disponibles.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-6 flex justify-center items-center space-x-2 flex-wrap bg-white shadow-md rounded-lg p-4">
            <?php if ($totalPaginas > 1): ?>
                <!-- Botón anterior -->
                <?php if ($paginaActual > 1): ?>
                    <a href="?page=<?php echo $paginaActual - 1; ?>"
                       class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Anterior
                    </a>
                <?php endif; ?>

                <!-- Mostrar botón para la primera página si no está en el rango visible -->
                <?php if ($inicioRango > 1): ?>
                    <a href="?page=1" class="px-3 py-2 border rounded bg-white text-blue-600 hover:bg-blue-600 hover:text-white">
                        1
                    </a>
                    <?php if ($inicioRango > 2): ?>
                        <span class="px-3 py-2">...</span>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Botones dentro del rango -->
                <?php for ($i = $inicioRango; $i <= $finRango; $i++): ?>
                    <a href="?page=<?php echo $i; ?>"
                       class="px-3 py-2 border rounded <?php echo $i == $paginaActual ? 'bg-blue-600 text-white' : 'bg-white text-blue-600'; ?> hover:bg-blue-600 hover:text-white">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <!-- Mostrar botón para la última página si no está en el rango visible -->
                <?php if ($finRango < $totalPaginas): ?>
                    <?php if ($finRango < $totalPaginas - 1): ?>
                        <span class="px-3 py-2">...</span>
                    <?php endif; ?>
                    <a href="?page=<?php echo $totalPaginas; ?>" class="px-3 py-2 border rounded bg-white text-blue-600 hover:bg-blue-600 hover:text-white">
                        <?php echo $totalPaginas; ?>
                    </a>
                <?php endif; ?>

                <!-- Botón siguiente -->
                <?php if ($paginaActual < $totalPaginas): ?>
                    <a href="?page=<?php echo $paginaActual + 1; ?>"
                       class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Siguiente
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once "./views/components/footer.php";
?>
