<div id="userModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-[#1C508D] px-4 pt-5 pb-4 sm:p-6 sm:pb-4 rounded-t-lg">
                <h3 class="text-lg leading-6 font-semibold text-white text-center" id="modalTitle">Agregar/Editar Usuario</h3>
            </div>
            <div class="p-4">
                <form id="userForm">
                    <div class="mb-4">
                        <label for="userName" class="block text-sm text-gray-600">Nombre <span class="text-red-700">*</span></label>
                        <input type="text" id="userName" name="first_name" class="w-full px-3 py-2 bg-[#E8F0FE] rounded-[0.65rem] bg-blue-100">
                    </div>
                    <div class="mb-4">
                        <label for="userLastName" class="block text-sm text-gray-600">Apellido <span class="text-red-700">*</span></label>
                        <input type="text" id="userLastName" name="last_name" class="w-full px-3 py-2 bg-[#E8F0FE] rounded-[0.65rem] bg-blue-100">
                    </div>
                    <div class="mb-4">
                        <label for="userEmail" class="block text-sm text-gray-600">Correo <span class="text-red-700">*</span></label>
                        <input type="email" id="userEmail" name="email" class="w-full px-3 py-2 bg-[#E8F0FE] rounded-[0.65rem] bg-blue-100">
                    </div>
                    <div class="mb-4">
                        <label for="userPhone" class="block text-sm text-gray-600">Teléfono</label>
                        <input type="text" id="userPhone" name="phone" class="w-full px-3 py-2 bg-[#E8F0FE] rounded-[0.65rem] bg-blue-100">
                    </div>
                    <div class="mb-4">
                        <label for="userPassword" class="block text-sm text-gray-600">Contraseña <span class="text-red-700">*</span></label>
                        <input type="password" id="userPassword" name="password" class="w-full px-3 py-2 bg-[#E8F0FE] rounded-[0.65rem] bg-blue-100">
                    </div>
                    <div class="mb-4">
                        <label for="userRole" class="block text-sm text-gray-600">Rol <span class="text-red-700">*</span></label>
                        <select id="userRole" name="role" class="w-full px-3 py-2 bg-[#E8F0FE] rounded-[0.65rem] bg-blue-100">
                            <option value="1">Cliente</option>
                            <option value="2">Trabajador</option>
                        </select>
                    </div>
                    <input type="hidden" id="userId" name="userId">
                    <div class="flex mt-4">
                        <button type="submit" class="!bg-[#1C508D] text-white w-full px-4 py-2 !rounded-2xl">Guardar</button>
                        <button type="button" id="closeModal" class="ml-4 !bg-red-500 text-white w-full px-4 py-2 !rounded-2xl ">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>