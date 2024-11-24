<?php
session_start();
$user = $_SESSION['user'];
$isEmployee = $user['user_type'] == 'empleado';
?>

<div class="absolute right-4 w-0 h-0 border-l-8 border-l-transparent border-r-8 border-r-transparent border-b-8 border-b-[#1C508D] -top-2"></div>
<div class="p-4 bg-[#1C508D] rounded-t-lg">
    <h2 class="text-white text-center text-lg font-semibold">Bienvenido, <?= $user['nombre'] ?></h2>
    <p class="text-blue-200 text-center text-[12px]">Correo: <?= $user['correo'] ?></p>
</div>
<div class="p-4">
    <div class="mb-4">
        <label class="block text-sm text-gray-600">Nombre: <?= $user['nombre'] ?> <?= $user['apellido'] ?></label>
    </div>
    <div class="mb-4">
        <label class="block text-sm text-gray-600">Teléfono: <?= $user['telefono'] ?></label>
    </div>
    <div class="grid place-content-center">
        <button id="logoutButton" class="!bg-[#1C508D] text-white w-full px-4 py-2 !rounded-2xl">Cerrar Sesión</button>
    </div>
</div>
<div class="px-2 py-4 text-sm bg-[#1C508D] flex items-center justify-center rounded-b-md">
    <?php if ($isEmployee) : ?>
        <a href="/Cine-Colombia/dashboard" class="text-white text-center hover:underline">Ir a Dashboard</a>
    <?php else : ?>
        <button id="viewPurchasesButton" class="text-white text-center hover:underline">Ver Mis Compras</button>
    <?php endif; ?>
</div>

<!-- Modal -->
<div id="purchasesModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-3/4 lg:w-1/2">
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 class="text-lg font-semibold">Mis Compras</h2>
            <button id="closeModalButton" class="text-black">&times;</button>
        </div>
        <div class="overflow-y-auto max-h-96">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Película</th>
                        <th class="py-2 px-4 border-b">Fecha</th>
                        <th class="py-2 px-4 border-b">Hora</th>
                        <th class="py-2 px-4 border-b">Sala</th>
                        <th class="py-2 px-4 border-b">Cantidad</th>
                        <th class="py-2 px-4 border-b">Total</th>
                    </tr>
                </thead>
                <tbody id="purchasesTableBody">
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.getElementById('logoutButton').addEventListener('click', function() {
        $.ajax({
            url: '/Cine-Colombia/login/logout',
            method: 'POST',
            success: function() {
                window.location.reload();
            }
        });
    });

    document.getElementById('viewPurchasesButton').addEventListener('click', function() {
        document.getElementById('purchasesModal').classList.remove('hidden');
        fetchPurchases();
    });

    document.getElementById('closeModalButton').addEventListener('click', function() {
        document.getElementById('purchasesModal').classList.add('hidden');
    });

    function fetchPurchases() {
        $.ajax({
            url: '/Cine-Colombia/movies/getSalesByUser',
            method: 'GET',
            success: function(response) {
                const purchases = JSON.parse(response);
                const tableBody = document.getElementById('purchasesTableBody');
                tableBody.innerHTML = '';

                purchases.forEach(purchase => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                    <td class="py-2 px-4 border-b">${purchase.titulo}</td>
                    <td class="py-2 px-4 border-b">${purchase.fecha}</td>
                    <td class="py-2 px-4 border-b">${purchase.hora}</td>
                    <td class="py-2 px-4 border-b">${purchase.sala}</td>
                    <td class="py-2 px-4 border-b">${purchase.cantidad}</td>
                    <td class="py-2 px-4 border-b">${purchase.total}</td>
                `;
                    tableBody.appendChild(row);
                });
            }
        });
    }
</script>