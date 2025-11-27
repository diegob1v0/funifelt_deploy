// src/js/dashboard/dashboard.js

Chart.register(ChartDataLabels);

let chartInstanceDia = null;
let chartInstanceTop = null;
let chartInstanceEmp = null;
let chartInstanceMes = null;
let chartInstanceCat = null;
let showLabels = false;

const PALETA_COLORES = [
  "#198754",
  "#0d6efd",
  "#dc3545",
  "#ffc107",
  "#6f42c1",
  "#0dcaf0",
  "#fd7e14",
  "#20c997",
];

document.addEventListener("DOMContentLoaded", async () => {
  const dashboardGrid = document.querySelector(".dashboard__grid");
  if (!dashboardGrid) return;

  await cargarFiltroEmpresas();
  cargarDashboard();

  // Event Listeners
  document
    .getElementById("companyFilter")
    ?.addEventListener("change", () => cargarDashboard());

  document.getElementById("toggleLabels")?.addEventListener("change", (e) => {
    showLabels = e.target.checked;
    cargarDashboard();
  });

  document
    .getElementById("btnFilterTable")
    ?.addEventListener("click", () => cargarDashboard());

  // --- LOGICA INTELIGENTE DE FILTROS (UX) ---
  const dateStart = document.getElementById("dateStart");
  const dateEnd = document.getElementById("dateEnd");
  const limitFilter = document.getElementById("limitFilter");

  // Si toco fechas -> Borro el límite (pongo 'all' o un valor neutro si prefieres, pero 'all' tiene sentido)
  const clearLimit = () => {
    // Opcional: limitFilter.value = 'all';
    // O simplemente dejamos que el usuario decida, pero tu pediste borrar.
    // Mejor UX: Si hay fechas, ignoramos visualmente el limite, pero dejemoslo funcional.
    // Tu pedido especifico: "ese filtro se borre".
    limitFilter.value = "all";
  };

  // Si toco límite -> Borro las fechas
  const clearDates = () => {
    dateStart.value = "";
    dateEnd.value = "";
  };

  dateStart?.addEventListener("change", clearLimit);
  dateEnd?.addEventListener("change", clearLimit);
  limitFilter?.addEventListener("change", clearDates);
});

// ... (cargarFiltroEmpresas y formatMoney igual que antes) ...
async function cargarFiltroEmpresas() {
  try {
    const resp = await fetch("/api/empresas/listar");
    const empresas = await resp.json();
    const select = document.getElementById("companyFilter");
    if (select && empresas.length > 0) {
      empresas.forEach((emp) => {
        const option = document.createElement("option");
        option.value = emp.id;
        option.textContent = emp.name;
        select.appendChild(option);
      });
    }
  } catch (e) {}
}

async function cargarDashboard() {
  const companyId = document.getElementById("companyFilter")
    ? document.getElementById("companyFilter").value
    : "";
  const limit = document.getElementById("limitFilter").value;
  const dateStart = document.getElementById("dateStart").value;
  const dateEnd = document.getElementById("dateEnd").value;

  // --- LÓGICA DE EXPANSIÓN DE TABLA ---
  const tableContainer = document.querySelector(".table-responsive");
  if (tableContainer) {
    // Si pide 'all' o más de 20, le ponemos la clase para expandir
    if (limit === "all" || parseInt(limit) > 20) {
      tableContainer.classList.add("expanded");
    } else {
      tableContainer.classList.remove("expanded");
    }
  }
  // ------------------------------------

  let url = `/api/dashboard?limit=${limit}`;
  if (companyId) url += `&company_id=${companyId}`;
  if (dateStart) url += `&date_start=${dateStart}`;
  if (dateEnd) url += `&date_end=${dateEnd}`;

  try {
    const respuesta = await fetch(url);
    const resultado = await respuesta.json();
    if (resultado.status === "success") {
      const filterContainer = document.getElementById("filterContainer");
      if (filterContainer && resultado.role_detected == 3)
        filterContainer.style.display = "block";

      renderizarDashboard(resultado.data, resultado.view_mode);
      renderizarTabla(resultado.data.latest_transactions);
    }
  } catch (error) {
    console.error("Error:", error);
  }
}

const formatMoney = (value) =>
  new Intl.NumberFormat("es-CO", {
    style: "currency",
    currency: "COP",
    minimumFractionDigits: 0,
  }).format(value);

// Helper de Colores
const obtenerMapaColores = (listaEmpresas) => {
  let mapa = {};
  listaEmpresas.forEach((nombre, index) => {
    mapa[nombre] = PALETA_COLORES[index % PALETA_COLORES.length];
  });
  return mapa;
};

function renderizarDashboard(data, mode) {
  // KPIs... (Igual que antes)
  if (document.getElementById("kpi-users"))
    document.getElementById("kpi-users").textContent = data.kpis.total_users;
  if (document.getElementById("kpi-apps"))
    document.getElementById("kpi-apps").textContent = data.kpis.total_apps;
  if (document.getElementById("kpi-revenue"))
    document.getElementById("kpi-revenue").textContent = formatMoney(
      data.kpis.total_revenue_historic
    );

  // Destroy charts
  if (chartInstanceDia) chartInstanceDia.destroy();
  if (chartInstanceTop) chartInstanceTop.destroy();
  if (chartInstanceEmp) chartInstanceEmp.destroy();
  if (chartInstanceMes) chartInstanceMes.destroy();
  if (chartInstanceCat) chartInstanceCat.destroy();

  // Mapa Global de colores (para consistencia)
  // Obtenemos lista unica de empresas desde los datos disponibles
  let empresasUnicas = [];
  if (data.sales_by_company)
    empresasUnicas = data.sales_by_company.map((d) => d.company_name);
  const mapaColores = obtenerMapaColores(empresasUnicas);

  // -------------------------------------------------------
  // B. GRÁFICA DIARIA (MULTILÍNEA)
  // -------------------------------------------------------
  const ctxDia = document.getElementById("chartVentasDia");
  if (ctxDia) {
    const fechas = data.sales_daily.map((d) => d.report_date);
    const datasetsDia = [];

    // Fondo General
    datasetsDia.push({
      label: "Total General",
      data: data.sales_daily.map((d) => d.total_revenue),
      borderColor: "#adb5bd",
      backgroundColor: "rgba(173, 181, 189, 0.1)",
      fill: true,
      tension: 0.4,
      order: 99,
      datalabels: {
        display: showLabels, // Obedecer al switch
        align: "top", // Poner el número encima del punto
        anchor: "end",
        color: "#666", // Color gris oscuro para el texto
        font: { weight: "bold" },
        formatter: (v) => (v > 0 ? formatMoney(v) : ""), // Formato moneda
      },
    });

    // Líneas por Empresa
    if (data.sales_daily_breakdown && mode === "global") {
      const agrupado = {};
      data.sales_daily_breakdown.forEach((item) => {
        if (!agrupado[item.company_name]) agrupado[item.company_name] = {};
        agrupado[item.company_name][item.report_date] = parseFloat(
          item.total_revenue
        );
      });
      // Modificamos el forEach para usar el índice (index)
      Object.keys(agrupado).forEach((nombre, index) => {
        const dataEmp = fechas.map((f) => agrupado[nombre][f] || 0);
        const color = mapaColores[nombre] || "#333";

        // LÓGICA DE ESCALERA:
        // Calculamos un empuje hacia abajo diferente para cada empresa.
        // Empresa 0: baja 4px
        // Empresa 1: baja 20px (4 + 16)
        // Empresa 2: baja 36px ...
        const empujeHaciaAbajo = 4 + index * 16;

        datasetsDia.push({
          label: nombre,
          data: dataEmp,
          borderColor: color,
          backgroundColor: color,
          borderWidth: 2,
          tension: 0.3,
          order: 1,
          datalabels: {
            display: showLabels,
            align: "start", // Hacia abajo
            anchor: "start", // Desde el punto inferior
            offset: empujeHaciaAbajo, // <--- AQUÍ ESTÁ EL TRUCO
            color: color,
            font: { weight: "bold", size: 10 },
            backgroundColor: "rgba(255, 255, 255, 0.9)", // Fondo un poco más sólido
            borderRadius: 3,
            padding: 1,
            formatter: (v) => (v > 0 ? formatMoney(v) : ""),
          },
        });
      });
    }
    chartInstanceDia = new Chart(ctxDia, {
      type: "line",
      data: { labels: fechas, datasets: datasetsDia },
      options: {
        layout: {
          padding: { top: 20 }, // Un poco de aire extra arriba por seguridad
        },
        responsive: true,
        plugins: {
          legend: { position: "top" },
          tooltip: { mode: "index", intersect: false },
        },
        scales: {
          y: {
            grace: "10%", // <--- ESTA ES LA CLAVE: Agrega 10% de espacio extra arriba
            ticks: {
              callback: function (value) {
                return "$" + value;
              },
            },
          },
        },
      },
    });
  }

  // -------------------------------------------------------
  // C. TOP APPS (CON PRECIO)
  // -------------------------------------------------------
  const ctxTop = document.getElementById("chartTopApps");
  if (ctxTop) {
    // Modificamos etiquetas: "App ($ Precio)"
    const labels = data.top_apps.map((d) => {
      const precio =
        parseFloat(d.price) > 0 ? formatMoney(d.price) : "(Gratis)";
      return `${d.app_name} ${precio}`;
    });

    chartInstanceTop = new Chart(ctxTop, {
      type: "bar",
      data: {
        labels: labels,
        datasets: [
          {
            label: "Ventas",
            data: data.top_apps.map((d) => d.total_sold),
            backgroundColor: PALETA_COLORES.slice(0, 5),
            borderRadius: 4,
            datalabels: {
              display: showLabels,
              color: "#fff",
              anchor: "center",
            },
          },
        ],
      },
      options: {
        plugins: { legend: { display: false } },
        scales: { y: { ticks: { stepSize: 1 } } },
      },
    });
  }

  // -------------------------------------------------------
  // D. EMPRESAS (HORIZONTAL CORREGIDO)
  // -------------------------------------------------------
  const containerEmp = document.getElementById("containerEmpresas");
  const ctxEmp = document.getElementById("chartEmpresas");
  if (containerEmp && ctxEmp) {
    if (mode === "global" && data.sales_by_company) {
      containerEmp.style.display = "block";
      const noms = data.sales_by_company.map((d) => d.company_name);

      chartInstanceEmp = new Chart(ctxEmp, {
        type: "bar",
        data: {
          labels: noms, // Y-Axis: Nombres
          datasets: [
            {
              label: "Ingresos",
              data: data.sales_by_company.map((d) => d.total_revenue), // X-Axis: Dinero
              backgroundColor: noms.map((n) => mapaColores[n] || "#ccc"),
              borderRadius: 4,
              // Configuración de etiquetas para horizontal
              datalabels: {
                display: showLabels,
                anchor: "end",
                align: "right", // Forzamos a la derecha
                formatter: (val) => formatMoney(val),
              },
            },
          ],
        },
        options: {
          layout: {
            padding: { right: 30 }, // Aire extra a la derecha para que quepa el texto largo
          },
          indexAxis: "y", // <--- AQUÍ ES DONDE DEBE IR (Nivel raíz de options)
          responsive: true,
          plugins: { legend: { display: false } },
          scales: {
            x: {
              // Eje X es el dinero en horizontal
              grace: "10%", // <--- CLAVE: Espacio extra a la derecha
              ticks: { callback: (v) => "$" + v },
            },
          },
        },
      });
    } else {
      containerEmp.style.display = "none";
    }
  }

  // -------------------------------------------------------
  // E. MENSUAL (APILADO + TOTAL)
  // -------------------------------------------------------
  const ctxMes = document.getElementById("chartMensual");
  if (ctxMes) {
    // 1. Obtener meses únicos y ordenados
    let mesesUnicos = [];
    if (data.sales_monthly_breakdown && mode === "global") {
      mesesUnicos = [
        ...new Set(data.sales_monthly_breakdown.map((item) => item.periodo)),
      ].sort();
    } else {
      const md = data.sales_monthly.slice().reverse();
      mesesUnicos = md.map((d) => d.periodo);
    }

    const datasetsMes = [];

    if (data.sales_monthly_breakdown && mode === "global") {
      // A. Calcular Totales por Mes para la Línea General
      const totalesPorMes = {};
      mesesUnicos.forEach((m) => (totalesPorMes[m] = 0));

      data.sales_monthly_breakdown.forEach((item) => {
        totalesPorMes[item.periodo] += parseFloat(item.total_revenue);
      });

      // B. Agregar Línea de Total General
      datasetsMes.push({
        type: "line", // <--- GRÁFICA MIXTA
        label: "Total General",
        data: mesesUnicos.map((m) => totalesPorMes[m]),
        borderColor: "#333",
        borderWidth: 2,
        pointBackgroundColor: "#333",
        fill: false,
        tension: 0.1,
        order: 0, // Poner al frente
        datalabels: {
          display: showLabels,
          align: "top",
          anchor: "end",
          color: "#333",
          font: { weight: "bold" },
          formatter: (v) => formatMoney(v),
        },
      });

      // C. Agregar Barras Apiladas por Empresa
      const agrupadoMes = {};
      data.sales_monthly_breakdown.forEach((item) => {
        if (!agrupadoMes[item.company_name])
          agrupadoMes[item.company_name] = {};
        agrupadoMes[item.company_name][item.periodo] = parseFloat(
          item.total_revenue
        );
      });

      Object.keys(agrupadoMes).forEach((empresa) => {
        const dataEmpresa = mesesUnicos.map(
          (mes) => agrupadoMes[empresa][mes] || 0
        );
        const color = mapaColores[empresa] || "#ccc";

        datasetsMes.push({
          type: "bar",
          label: empresa,
          data: dataEmpresa,
          backgroundColor: color,
          stack: "Stack 0",
          order: 1,
          datalabels: {
            display: showLabels,
            color: "white",
            formatter: (v) => (v > 0 ? formatMoney(v) : ""),
          },
        });
      });
    } else {
      // Vista Empresa única (Simple)
      const md = data.sales_monthly.slice().reverse();
      mesesUnicos = md.map((d) => d.periodo);
      datasetsMes.push({
        type: "bar",
        label: "Ingresos",
        data: md.map((d) => d.total_revenue),
        backgroundColor: "#fd7e14",
        datalabels: {
          display: showLabels,
          anchor: "end",
          align: "top",
          formatter: (v) => formatMoney(v),
        },
      });
    }

    chartInstanceMes = new Chart(ctxMes, {
      data: {
        labels: mesesUnicos,
        datasets: datasetsMes,
      },
      options: {
        responsive: true,
        plugins: { legend: { display: mode === "global" } },
        scales: {
          x: { stacked: true },
          y: {
            stacked: true, // Las barras se apilan
            beginAtZero: true,
            ticks: { callback: (v) => "$" + v },
          },
        },
      },
    });
  }

  // F. CATEGORIAS (Igual que antes)
  const ctxCat = document.getElementById("chartCategorias");
  if (ctxCat && data.sales_category) {
    chartInstanceCat = new Chart(ctxCat, {
      type: "doughnut",
      data: {
        labels: data.sales_category.map((d) => d.category_name),
        datasets: [
          {
            data: data.sales_category.map((d) => d.total_revenue),
            backgroundColor: PALETA_COLORES,
            datalabels: {
              display: showLabels,
              color: "white",
              formatter: (val, ctx) => {
                let sum = 0;
                ctx.chart.data.datasets[0].data.map((d) => (sum += Number(d)));
                return ((val * 100) / sum).toFixed(0) + "%";
              },
            },
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: "right" } },
      },
    });
  }
}

function renderizarTabla(transacciones) {
  const tbody = document.getElementById("tableBodyTransactions");
  if (!tbody) return;
  tbody.innerHTML = "";
  if (!transacciones || transacciones.length === 0) {
    tbody.innerHTML =
      '<tr><td colspan="7" style="text-align:center; padding: 2rem;">No hay datos para mostrar con estos filtros</td></tr>';
    return;
  }
  transacciones.forEach((t) => {
    const tr = document.createElement("tr");
    const fecha = new Date(t.created_at).toLocaleDateString("es-CO", {
      day: "2-digit",
      month: "short",
      year: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    });
    const empresa = t.company_name || "-";
    tr.innerHTML = `<td>#${t.id}</td><td>${
      t.user_email
    }</td><td style="font-weight:500">${
      t.app_name
    }</td><td>${empresa}</td><td>${fecha}</td><td class="amount-col">${formatMoney(
      t.amount
    )}</td><td><span class="status-badge">Completado</span></td>`;
    tbody.appendChild(tr);
  });
}
