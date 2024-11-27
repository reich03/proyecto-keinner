<?php 
require_once "./views/components/head.php";
?>
<div class="pt-[2rem] pb-[2rem] bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen flex items-center">
    <div id="reporteContainer" class="container mx-auto max-w-5xl bg-white shadow-xl rounded-lg p-8 border border-gray-200">
        <div class="text-center mb-6">
            <h2 class="text-5xl font-extrabold text-blue-600 mb-4">Reportes de Programas Académicos</h2>
            <p class="text-lg text-gray-700">
                Selecciona el tipo de reporte y visualiza los datos en gráficos y tablas para analizar la información de manera interactiva.
            </p>
        </div>

        <div class="mb-6 text-center">
            <label for="reporteSelector" class="font-bold mr-2 text-lg">Seleccionar Reporte:</label>
            <select id="reporteSelector" class="px-4 py-2 border rounded mb-[10px]">
                <option value="nivelAcademico">Programas por Nivel Académico</option>
                <option value="nivelFormacion">Programas con Formación Universitaria, Maestría, Tecnológico y Especialización Universitaria</option>
                <option value="modalidad">Programas por Modalidad</option>
            </select>
            <label for="tipoGrafico" class="font-bold ml-4 mr-2 text-lg">Tipo de Gráfico:</label>
            <select id="tipoGrafico" class="px-4 py-2 border rounded">
                <option value="bar">Barras</option>
                <option value="pie">Pastel</option>
            </select>
        </div>

        <div class="flex justify-center mb-6">
            <canvas id="reporteChart" class="w-full max-w-4xl h-auto"></canvas>
        </div>

        <div class="overflow-x-auto shadow rounded-lg">
            <table id="reporteTable" class="table-auto w-full text-left border-collapse border border-gray-200 hidden">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 px-6 py-3 font-medium text-gray-700">Categoría</th>
                        <th class="border border-gray-300 px-6 py-3 font-medium text-gray-700 text-center">Total Programas</th>
                    </tr>
                </thead>
                <tbody id="reporteTableBody" class="bg-white"></tbody>
            </table>
        </div>

        <div class="text-center mt-8">
            <button onclick="exportPDF()" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg text-lg font-medium">
                Exportar Reporte como PDF
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    const reportes = <?php echo json_encode($this->reportes); ?>;
    console.log("Reportes recibidos:", reportes);

    const reporteSelector = document.getElementById("reporteSelector");
    const tipoGraficoSelector = document.getElementById("tipoGrafico");
    const reporteTable = document.getElementById("reporteTable");
    const reporteTableBody = document.getElementById("reporteTableBody");
    const reporteChart = document.getElementById("reporteChart").getContext("2d");

    let chartInstance = null;

    function actualizarReporte() {
        const reporteSeleccionado = reporteSelector.value;
        const tipoGrafico = tipoGraficoSelector.value;
        let categorias = [];
        let valores = [];

        if (reporteSeleccionado === "nivelAcademico") {
            categorias = [...new Set(reportes.map(r => r.nivel_academico))];
            valores = categorias.map(categoria => {
                return reportes
                    .filter(r => r.nivel_academico === categoria)
                    .reduce((total, r) => total + parseInt(r.total_programas), 0);
            });
        } else if (reporteSeleccionado === "nivelFormacion") {
            const formaciones = ["Universitario", "Maestría", "Tecnológico", "Especialización Universitaria"];
            categorias = formaciones;
            valores = formaciones.map(formacion => {
                return reportes
                    .filter(r => r.nivel_formacion === formacion)
                    .reduce((total, r) => total + parseInt(r.total_programas), 0);
            });
        } else if (reporteSeleccionado === "modalidad") {
            categorias = [...new Set(reportes.map(r => r.modalidad))];
            valores = categorias.map(categoria => {
                return reportes
                    .filter(r => r.modalidad === categoria)
                    .reduce((total, r) => total + parseInt(r.total_programas), 0);
            });
        }

        actualizarTabla(categorias, valores);
        actualizarGrafico(categorias, valores, tipoGrafico);
    }

    function actualizarTabla(categorias, valores) {
        reporteTableBody.innerHTML = "";
        categorias.forEach((categoria, index) => {
            const row = document.createElement("tr");
            row.classList.add("hover:bg-gray-50");
            row.innerHTML = `
                <td class="border border-gray-300 px-6 py-3">${categoria}</td>
                <td class="border border-gray-300 px-6 py-3 text-center">${valores[index]}</td>
            `;
            reporteTableBody.appendChild(row);
        });
        reporteTable.classList.remove("hidden");
    }

    function actualizarGrafico(categorias, valores, tipo) {
        if (chartInstance) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(reporteChart, {
            type: tipo,
            data: {
                labels: categorias,
                datasets: [{
                    label: "Total Programas",
                    data: valores,
                    backgroundColor: tipo === "pie" ? 
                        ["rgba(54, 162, 235, 0.6)", "rgba(255, 99, 132, 0.6)", "rgba(255, 206, 86, 0.6)", "rgba(75, 192, 192, 0.6)"] :
                        "rgba(54, 162, 235, 0.6)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: tipo === "pie" ? "top" : "bottom" }
                },
                scales: tipo === "bar" ? { y: { beginAtZero: true } } : {}
            }
        });
    }

    async function exportPDF() {
        const container = document.getElementById("reporteContainer");

        const canvas = await html2canvas(container, { scale: 2 });
        const imgData = canvas.toDataURL("image/png");

        const pdf = new jspdf.jsPDF("p", "mm", "a4");
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

        pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);
        pdf.save("Reporte.pdf");
    }

    reporteSelector.addEventListener("change", actualizarReporte);
    tipoGraficoSelector.addEventListener("change", actualizarReporte);

    actualizarReporte();
</script>
<?php
require_once "./views/components/footer.php";
?>
