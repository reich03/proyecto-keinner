<?php
require_once "./views/components/head.php";
?>
<div class="bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen flex items-center">
    <div class="container mx-auto max-w-3xl bg-white shadow-xl rounded-lg p-8 border border-gray-200">
        <h1 class="text-4xl font-extrabold text-blue-600 text-center mb-6">Cargar Programas desde Excel</h1>

        <?php if (isset($this->d['success'])): ?>
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                <?php echo $this->d['success']; ?>
            </div>
        <?php elseif (isset($this->d['error'])): ?>
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                <?php echo $this->d['error']; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo constant('URL'); ?>/programas/carga" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md">
            <label for="excel" class="block text-lg font-bold mb-2">Subir Archivo Excel</label>
            <input type="file" id="excel" name="excel" accept=".xls,.xlsx" class="border p-3 w-full mb-4 rounded" required>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-lg">
                Cargar Archivo
            </button>
            </form>
    </div>
</div>
<?php
require_once "./views/components/footer.php";
?>