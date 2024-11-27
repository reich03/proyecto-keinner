<?php
require_once "./views/components/head.php";

$programasPorPagina = 20;
$paginaActual = isset($_GET['page']) ? intval($_GET['page']) : 1;
$totalProgramas = count($this->d['programas']);
$totalPaginas = ceil($totalProgramas / $programasPorPagina);

$inicio = ($paginaActual - 1) * $programasPorPagina;
$programasPagina = array_slice($this->d['programas'], $inicio, $programasPorPagina);

$rango = 2;
$inicioRango = max(1, $paginaActual - $rango);
$finRango = min($totalPaginas, $paginaActual + $rango);
?>

<div class="pt-[2rem] pb-[2rem] bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen">
    <div class="container mx-auto max-w-8xl bg-white shadow-xl rounded-lg p-8 border border-gray-200">
        <h1 class="text-4xl font-extrabold text-blue-600 text-center mb-6">Lista de Programas Académicos</h1>

        <div class="overflow-x-auto mb-6">
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
                        <th class="px-4 py-2">Acciones</th>
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
                                <td class="px-4 py-2">
                                    <button class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700"
                                        onclick="editarPrograma('<?php echo $programa['snies']; ?>')">Editar</button> |
                                    <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700"
                                        onclick="confirmarEliminacion('<?php echo $programa['snies']; ?>')">Eliminar</button>
                                </td>
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
        <div class="flex justify-center items-center space-x-2 flex-wrap bg-white shadow-md rounded-lg p-4">
            <?php if ($totalPaginas > 1): ?>
                <?php if ($paginaActual > 1): ?>
                    <a href="?page=<?php echo $paginaActual - 1; ?>"
                        class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Anterior
                    </a>
                <?php endif; ?>

                <?php if ($inicioRango > 1): ?>
                    <a href="?page=1" class="px-3 py-2 border rounded bg-white text-blue-600 hover:bg-blue-600 hover:text-white">
                        1
                    </a>
                    <?php if ($inicioRango > 2): ?>
                        <span class="px-3 py-2">...</span>
                    <?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $inicioRango; $i <= $finRango; $i++): ?>
                    <a href="?page=<?php echo $i; ?>"
                        class="px-3 py-2 border rounded <?php echo $i == $paginaActual ? 'bg-blue-600 text-white' : 'bg-white text-blue-600'; ?> hover:bg-blue-600 hover:text-white">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($finRango < $totalPaginas): ?>
                    <?php if ($finRango < $totalPaginas - 1): ?>
                        <span class="px-3 py-2">...</span>
                    <?php endif; ?>
                    <a href="?page=<?php echo $totalPaginas; ?>" class="px-3 py-2 border rounded bg-white text-blue-600 hover:bg-blue-600 hover:text-white">
                        <?php echo $totalPaginas; ?>
                    </a>
                <?php endif; ?>

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

<script>
    function confirmarEliminacion(snies) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esta acción!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarPrograma(snies);
            }
        });
    }

    function eliminarPrograma(snies) {
        fetch(`http://localhost/ConsultSnies/programas/eliminar/${snies}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Eliminado!',
                        'El programa ha sido eliminado correctamente.',
                        'success'
                    ).then(() => {
                        document.querySelector(`tr[data-snies='${snies}']`).remove();
                    });
                } else {
                    Swal.fire(
                        'Error!',
                        data.error || 'Hubo un problema al eliminar el programa.',
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Error al eliminar el programa:', error);
                Swal.fire(
                    'Error!',
                    'Hubo un error al intentar eliminar el programa.',
                    'error'
                );
            });
    }


    function editarPrograma(snies) {
        fetch(`http://localhost/ConsultSnies/programas/editar/${snies}`)
            .then(response => response.json())
            .then(programa => {
                if (!programa) {
                    throw new Error('No se pudo cargar el programa');
                }
                console.log(programa)
                Swal.fire({
                    title: 'Editar Programa',
                    html: `
                    <input id="nomb_programa" class="swal2-input" value="${programa.nomb_programa}" placeholder="Nombre del programa">
                    <input id="creditos" class="swal2-input" value="${programa.creditos}" placeholder="Créditos">
                    <input id="semestres" class="swal2-input" value="${programa.semestres}" placeholder="Semestres">
                `,
                    preConfirm: () => {
                        return {
                            nomb_programa: document.getElementById('nomb_programa').value,
                            creditos: document.getElementById('creditos').value,
                            semestres: document.getElementById('semestres').value,
                            snies: snies
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const data = result.value;
                        console.log(data);

                        fetch('http://localhost/ConsultSnies/programas/editar', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify(data)
                            })
                            .then(response => response.json())
                            .then(response => {
                                if (response.success) {
                                    Swal.fire('Éxito', 'Programa actualizado correctamente', 'success');
                                } else {
                                    Swal.fire('Error', 'Hubo un problema al actualizar el programa', 'error');
                                }
                            });
                    }
                });
            })
            .catch(error => {
                console.error('Error al cargar el programa:', error);
                Swal.fire('Error', 'No se pudo cargar el programa para editarlo', 'error');
            });
    }
</script>
<?php
require_once "./views/components/footer.php";
?>