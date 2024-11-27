<?php
require_once "./views/components/head.php";
?>
<div class="pt-[2rem] pb-[2rem] bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen flex items-center">
    <div id="reporteContainer" class="container mx-auto max-w-5xl bg-white shadow-xl rounded-lg p-8 border border-gray-200">
        <div class="text-center mb-6">
            <h2 class="text-5xl font-extrabold text-blue-600 mb-4">Programas Académicos por Nivel, Formación y Modalidad</h2>
            <p class="text-lg text-gray-700">
                Selecciona el tipo de reporte y visualiza los datos en tablas para analizar la información de manera interactiva.
            </p>
        </div>

        <div class="mb-6 text-center">
            <label for="reporteSelector" class="font-bold mr-2 text-lg">Seleccionar Reporte:</label>
            <select id="reporteSelector" class="px-4 py-2 border rounded mb-[10px]">
                <option value="nivelAcademico">Programas por Nivel Académico</option>
                <option value="nivelFormacion">Programas con Formación Universitaria, Maestría, Tecnológico y Especialización Universitaria</option>
                <option value="modalidad">Programas por Modalidad</option>
            </select>
        </div>

        <div class="overflow-x-auto shadow rounded-lg">
            <table id="reporteTable" class="table-auto w-full text-left border-collapse border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 px-6 py-3 font-medium text-gray-700 text-center">
                            Modalidad <input type="text" id="modalidadFilter" class="w-full px-2 py-1 mt-1" placeholder="Filtrar...">
                        </th>
                        <th class="border border-gray-300 px-6 py-3 font-medium text-gray-700">
                            Nivel Académico / Nivel de Formación <input type="text" id="nivelFilter" class="w-full px-2 py-1 mt-1" placeholder="Filtrar...">
                        </th>
                        <th class="border border-gray-300 px-6 py-3 font-medium text-gray-700">
                            Programas <input type="text" id="programaFilter" class="w-full px-2 py-1 mt-1" placeholder="Filtrar...">
                        </th>
                        <th class="border border-gray-300 px-6 py-3 font-medium text-gray-700">
                            Municipio <input type="text" id="municipioFilter" class="w-full px-2 py-1 mt-1" placeholder="Filtrar...">
                        </th>
                        <th class="border border-gray-300 px-6 py-3 font-medium text-gray-700">
                            Departamento <input type="text" id="departamentoFilter" class="w-full px-2 py-1 mt-1" placeholder="Filtrar...">
                        </th>
                    </tr>
                </thead>
                <tbody id="reporteTableBody" class="bg-white"></tbody>
            </table>
        </div>

        <div id="pagination" class="mt-4 text-center"></div>

        <div class="text-center mt-8">
            <button onclick="exportPDF()" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg text-lg font-medium">
                Exportar Reporte como PDF
            </button>
        </div>
    </div>
</div>

<!-- Incluir jsPDF desde la CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- Incluir jsPDF autoTable desde la CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.18/jspdf.plugin.autotable.min.js"></script>

<script>
    const { jsPDF } = window.jspdf;

    const reporteSelector = document.getElementById("reporteSelector");
    const reporteTableBody = document.getElementById("reporteTableBody");
    const modalidadFilter = document.getElementById("modalidadFilter");
    const nivelFilter = document.getElementById("nivelFilter");
    const programaFilter = document.getElementById("programaFilter");
    const municipioFilter = document.getElementById("municipioFilter");
    const departamentoFilter = document.getElementById("departamentoFilter");
    const pagination = document.getElementById("pagination");

    let programas = [];
    let filteredProgramas = [];
    const itemsPerPage = 10; 
    let currentPage = 1;

    function actualizarReporte() {
        const reporteSeleccionado = reporteSelector.value;
        fetchReportes(reporteSeleccionado);
    }

    function actualizarTabla(programas, page = 1) {
        reporteTableBody.innerHTML = "";
        const startIndex = (page - 1) * itemsPerPage;
        const paginatedPrograms = programas.slice(startIndex, startIndex + itemsPerPage);

        paginatedPrograms.forEach(programa => {
            const row = document.createElement("tr");
            row.classList.add("hover:bg-gray-50");

            row.innerHTML = `
                <td class="border border-gray-300 px-6 py-3">${programa.modalidad}</td>
                <td class="border border-gray-300 px-6 py-3">${programa.nivel}</td>
                <td class="border border-gray-300 px-6 py-3">${programa.nombre_programa}</td>
                <td class="border border-gray-300 px-6 py-3">${programa.nombre_municipio}</td>
                <td class="border border-gray-300 px-6 py-3">${programa.nombre_departamento}</td>
            `;
            reporteTableBody.appendChild(row);
        });

        updatePagination(programas.length, page);
    }

    function updatePagination(totalItems, currentPage) {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        pagination.innerHTML = '';

        const paginationHTML = `
            <button class="mx-1 px-4 py-2 bg-blue-500 text-white rounded" onclick="changePage(1)" ${currentPage === 1 ? 'disabled' : ''}>Primera</button>
            <button class="mx-1 px-4 py-2 bg-blue-500 text-white rounded" onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>Anterior</button>
            <span class="mx-1 py-2 text-lg text-gray-700">Página ${currentPage} de ${totalPages}</span>
            <button class="mx-1 px-4 py-2 bg-blue-500 text-white rounded" onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>Siguiente</button>
            <button class="mx-1 px-4 py-2 bg-blue-500 text-white rounded" onclick="changePage(${totalPages})" ${currentPage === totalPages ? 'disabled' : ''}>Última</button>
        `;

        pagination.innerHTML = paginationHTML;
    }

    function changePage(page) {
        const totalPages = Math.ceil(filteredProgramas.length / itemsPerPage);
        if (page < 1 || page > totalPages) return;

        currentPage = page;
        actualizarTabla(filteredProgramas, currentPage);
    }

    function fetchReportes(tipoReporte) {
        fetch(`http://localhost/ConsultSnies/reportes/obtenerReportes/${tipoReporte}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error al cargar los reportes:', data.error);
                } else {
                    programas = [];

                    if (tipoReporte === 'nivelAcademico') {
                        programas = data.map(p => ({
                            modalidad: p.modalidad,
                            nivel: p.nivel_academico,
                            nombre_programa: p.nombre_programa,
                            nombre_municipio: p.nombre_municipio,
                            nombre_departamento: p.nombre_departamento,
                        }));
                    } else if (tipoReporte === 'nivelFormacion') {
                        programas = data.map(p => ({
                            modalidad: p.modalidad,
                            nivel: p.nivel_formacion,
                            nombre_programa: p.nombre_programa,
                            nombre_municipio: p.nombre_municipio,
                            nombre_departamento: p.nombre_departamento,
                        }));
                    } else if (tipoReporte === 'modalidad') {
                        programas = data.map(p => ({
                            modalidad: p.modalidad,
                            nivel: '-', 
                            nombre_programa: p.nombre_programa,
                            nombre_municipio: p.nombre_municipio,
                            nombre_departamento: p.nombre_departamento,
                        }));
                    }

                    filteredProgramas = programas;
                    actualizarTabla(filteredProgramas);
                }
            })
            .catch(error => {
                console.error('Error al obtener los reportes:', error);
            });
    }

    function applyFilters() {
        const modalidad = modalidadFilter.value.toLowerCase();
        const nivel = nivelFilter.value.toLowerCase();
        const programa = programaFilter.value.toLowerCase();
        const municipio = municipioFilter.value.toLowerCase();
        const departamento = departamentoFilter.value.toLowerCase();

        filteredProgramas = programas.filter(p => 
            p.modalidad.toLowerCase().includes(modalidad) &&
            p.nivel.toLowerCase().includes(nivel) &&
            p.nombre_programa.toLowerCase().includes(programa) &&
            p.nombre_municipio.toLowerCase().includes(municipio) &&
            p.nombre_departamento.toLowerCase().includes(departamento)
        );

        currentPage = 1; 
        actualizarTabla(filteredProgramas);
    }

    modalidadFilter.addEventListener("input", applyFilters);
    nivelFilter.addEventListener("input", applyFilters);
    programaFilter.addEventListener("input", applyFilters);
    municipioFilter.addEventListener("input", applyFilters);
    departamentoFilter.addEventListener("input", applyFilters);

    reporteSelector.addEventListener("change", actualizarReporte);
    actualizarReporte();

    // Función para exportar el PDF usando autoTable
    function exportPDF() {
        const doc = new jsPDF();

        const table = document.getElementById("reporteTable");

        doc.autoTable({ 
            html: '#reporteTable',
            startY: 20, 
            theme: 'grid', 
            headStyles: { fillColor: [35, 48, 63], textColor: 255 }, 
            bodyStyles: { textColor: [0, 0, 0] }
        });

        doc.save("Reporte.pdf");
    }

    function exportPDFAlternativo() {
        const doc = new jsPDF();
        const table = document.getElementById("reporteTable");

        let tableData = [];
        table.querySelectorAll("tr").forEach((row, index) => {
            if (index > 0) { 
                let rowData = [];
                row.querySelectorAll("td").forEach(td => {
                    rowData.push(td.textContent);
                });
                tableData.push(rowData);
            }
        });

        doc.autoTable({
            head: [['Modalidad', 'Nivel Académico / Nivel de Formación', 'Programas', 'Municipio', 'Departamento']],
            body: tableData,
            startY: 20,
            theme: 'grid',
            headStyles: { fillColor: [35, 48, 63], textColor: 255 },
            bodyStyles: { textColor: [0, 0, 0] }
        });

        doc.save("Reporte_Alternativo.pdf");
    }
</script>

<?php
require_once "./views/components/footer.php";
?>
