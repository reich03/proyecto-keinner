<?php
require_once "./views/components/head.php";
?>
<div class="container mx-auto py-10">
    <h1 class="text-3xl font-bold text-center mb-6">Cargar Programas desde Excel</h1>

    <?php if (isset($this->d['success'])): ?>
        <div class="bg-green-200 text-green-800 p-4 rounded mb-4">
            <?php echo $this->d['success']; ?>
        </div>
    <?php elseif (isset($this->d['error'])): ?>
        <div class="bg-red-200 text-red-800 p-4 rounded mb-4">
            <?php echo $this->d['error']; ?>
        </div>
    <?php endif; ?>

    <form action="/programas/carga" method="POST" enctype="multipart/form-data" class="bg-white p-6 shadow rounded">
        <label class="block mb-2 font-bold">Subir Archivo Excel</label>
        <input type="file" name="excel" accept=".xls,.xlsx" class="border p-2 w-full mb-4" required>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Cargar</button>
    </form>
</div>

<?php
require_once "./views/components/footer.php";
?>
