<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta Programas SNIES - Villavicencio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="/ConsultSnies/assets/images/favicon-32x32.png" sizes="32x32">
    <link rel="stylesheet" href="/ConsultSnies/assets/css/root.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="flex flex-col min-h-screen bg-gray-100 text-gray-800 animate-fade-in">

    <header class="bg-gradient-to-r from-blue-600 to-blue-800 text-white shadow-lg">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <h1 class="text-2xl font-bold hover:text-blue-300 transition-all duration-300">
                <a href="/ConsultSnies">Sistema SNIES</a>
            </h1>
            <nav class="hidden md:block">
                <ul class="flex space-x-6">
                    <li><a href="/ConsultSnies" class="hover:text-blue-300 transition-all duration-300">Inicio</a></li>
                    <li><a href="/ConsultSnies/programas" class="hover:text-blue-300 transition-all duration-300">Programas</a></li>
                    <li><a href="/ConsultSnies/programas/carga" class="hover:text-blue-300 transition-all duration-300">Cargar Programas</a></li>
                    <li><a href="/ConsultSnies/reportes" class="hover:text-blue-300 transition-all duration-300">Reportes</a></li>
                    <li><a href="/ConsultSnies/ayuda" class="hover:text-blue-300 transition-all duration-300">Ayuda</a></li>
                </ul>
            </nav>

            <div class="md:hidden">
                <button id="menu-btn" class="focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden bg-blue-700 md:hidden">
            <ul class="flex flex-col space-y-4 p-4">
                <li><a href="/ConsultSnies" class="hover:text-blue-300 transition-all duration-300">Inicio</a></li>
                <li><a href="<?php echo constant('URL'); ?>programas" class="hover:text-blue-300 transition-all duration-300">Programas</a></li>
                <li><a href="/programas/carga" class="hover:text-blue-300 transition-all duration-300">Cargar Programas<< /a>
                </li>
                <li><a href="/reportes" class="hover:text-blue-300 transition-all duration-300">Reportes</a></li>
                <li><a href="/ayuda" class="hover:text-blue-300 transition-all duration-300">Ayuda</a></li>
            </ul>
        </div>
    </header>

    <script>
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>