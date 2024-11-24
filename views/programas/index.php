<?php
require_once "./views/components/head.php";
?>


<div class="container mx-auto py-10">
    <h1 class="text-3xl font-bold text-center mb-6">Lista de Programas Académicos</h1>

    <table class="table-auto w-full bg-white shadow-md rounded">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="px-4 py-2">Código SNIES</th>
                <th class="px-4 py-2">Nombre</th>
                <th class="px-4 py-2">Estado</th>
                <th class="px-4 py-2">Créditos</th>
                <th class="px-4 py-2">Semestres</th>
                <th class="px-4 py-2">Periodicidad</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->d['programas'] as $programa): ?>
                <tr class="border-b">
                    <td class="px-4 py-2"><?php echo $programa['snies']; ?></td>
                    <td class="px-4 py-2"><?php echo $programa['nomb_programa']; ?></td>
                    <td class="px-4 py-2"><?php echo $programa['estado_programa']; ?></td>
                    <td class="px-4 py-2"><?php echo $programa['creditos']; ?></td>
                    <td class="px-4 py-2"><?php echo $programa['semestres']; ?></td>
                    <td class="px-4 py-2"><?php echo $programa['periodicidad']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
require_once "./views/components/footer.php";
?>