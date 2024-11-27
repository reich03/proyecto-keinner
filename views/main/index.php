<?php 
require_once "./views/components/head.php";
?>
<div class="pt-[2rem] bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen flex items-center">
    <div class="container mx-auto max-w-4xl bg-white shadow-xl rounded-lg p-8 border border-gray-200">
        <div class="text-center">
            <h2 class="text-5xl font-extrabold text-blue-600 mb-6">¡Bienvenido a la Plataforma de Reportes Académicos!</h2>
            <p class="text-lg text-gray-700 mb-8">
                Aquí podrás explorar, visualizar y analizar datos relacionados con los programas académicos disponibles en diferentes niveles y modalidades. 
                Navega entre los reportes para obtener información detallada sobre:
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            
            <div>
                <ul class="list-disc list-inside text-left text-gray-700">
                    <li class="mb-4"><strong>Programas por Nivel Académico:</strong> Conoce los programas de pregrado y posgrado.</li>
                    <li class="mb-4"><strong>Programas por Nivel de Formación:</strong> Explora formaciones universitarias, maestrías, tecnológicas y más.</li>
                    <li class="mb-4"><strong>Modalidades de Estudio:</strong> Analiza los programas en modalidad presencial, virtual y a distancia.</li>
                </ul>
                <p class="text-lg text-gray-700 mt-6">
                    Utiliza los reportes interactivos para visualizar la información en gráficos y tablas, y exporta los datos para tus análisis.
                </p>
                <div class="text-center mt-6">
                    <a href="/ConsultSnies/reportes" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg text-lg font-medium inline-block">
                        Ver Reportes
                    </a>
                </div>
            </div>

            <div>
                <img src="/ConsultSnies/assets/images/images_bg.png"alt="Análisis de datos" class="w-full h-auto mx-auto rounded-md ">
            </div>
        </div>
    </div>
</div>
<?php
require_once "./views/components/footer.php";
?>
