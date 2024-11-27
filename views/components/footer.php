<footer class="bg-gray-900 text-gray-300 mt-auto">
    <div class="container mx-auto py-10 px-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center md:text-left">
            <h3 class="text-lg font-bold text-white mb-4">Sistema SNIES</h3>
            <p class="text-sm">
                Consulta información detallada sobre instituciones educativas y programas académicos en Colombia.
            </p>
        </div>

        <div class="text-center">
            <h3 class="text-lg font-bold text-white mb-4">Enlaces Rápidos</h3>
            <ul class="space-y-2">
                <li><a href="<?php echo constant('URL'); ?>/programas" class="hover:text-blue-400 transition">Consultar Programas</a></li>
                <li><a href="<?php echo constant('URL'); ?>/reportes" class="hover:text-blue-400 transition">Generar Reportes</a></li>
            </ul>
        </div>

        <div class="text-center md:text-right">
            <h3 class="text-lg font-bold text-white mb-4">Contáctanos</h3>
            <p class="text-sm">Villavicencio, Colombia</p>
            <p class="text-sm">Email: <a href="mailto:info@sistemassnies.edu.co" class="hover:text-blue-400">info@sistemassnies.edu.co</a></p>
            <p class="text-sm">Tel: +57 123 456 7890</p>
        </div>
    </div>
    <div class="bg-gray-800 py-4">
        <div class="container mx-auto text-center text-sm">
            &copy; <?php echo date('Y'); ?> Sistema SNIES. Todos los derechos reservados.
            <span class="block md:inline-block">
                <a href="#" class="hover:underline ml-4">Términos de Uso</a> | 
                <a href="#" class="hover:underline">Política de Privacidad</a>
            </span>
        </div>
    </div>
</footer>



</body>

</html>