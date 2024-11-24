<div class="absolute right-4 w-0 h-0 border-l-8 border-l-transparent border-r-8 border-r-transparent border-b-8 border-b-[#1C508D] -top-2"></div>
<div class="p-4 bg-[#1C508D] rounded-t-lg">
    <h2 class="text-white text-center text-lg font-semibold">Iniciar Sesión</h2>
    <p class="text-blue-200 text-center text-[12px]">Entra con tu usuario registrado</p>
</div>
<div class="p-4">
    <form id="loginForm" method="post">
        <div class="mb-4">
            <label for="mail" class="block text-sm text-gray-600">Usuario / Correo <span class="text-red-700">*</span></label>
            <input type="text" id="mail" name="mail" class="w-full px-3 py-2 login bg-[#E8F0FE] rounded-[0.65rem] bg-blue-100" placeholder="ejemplo@correo.com">
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm text-gray-600">Contraseña <span class="text-red-700">*</span></label>
            <input type="password" id="password" name="password" class="w-full px-3 py-2 login rounded-[0.65rem] bg-blue-100" placeholder="********">
        </div>
        <div class="grid place-content-center">
            <button type="submit" class="!bg-[#1C508D] text-white w-full px-4 py-2 !rounded-2xl">Ingresar</button>
        </div>
    </form>
    <div class="mt-4 text-sm">
        <a href="#" class="text-[#1c508D] hover:underline">¿Olvidaste tu contraseña? Recupérala aquí</a>
    </div>
</div>
<div class="px-2 py-4 text-sm bg-[#1C508D] flex items-center justify-center rounded-b-md">
    <a href="/Cine-Colombia/register" class="text-white text-center hover:underline">¿No estás registrado? Regístrate aquí</a>
</div>

<script>
    $(document).ready(function() {
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '/Cine-Colombia/login/authenticate',
                data: $(this).serialize(),
                success: function(response) {
                    $('body').append(response);
                }
            });
        });
    });
</script>
