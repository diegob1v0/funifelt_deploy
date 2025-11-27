// src/js/header.js
document.addEventListener("DOMContentLoaded", async function () {
  console.log("✅ header.js: DOMContentLoaded");

  // --- SELECCIÓN DE ELEMENTOS DEL DOM ---
  const header = document.querySelector(".apps-header");
  if (!header) {
    console.warn("header.js: no se encontró .apps-header, saliendo.");
    return;
  }

  const alliedCompaniesLink = document.getElementById("allied-companies-link");
  const alliedCompaniesDropdown = document.getElementById(
    "allied-companies-dropdown"
  );
  const searchIcon = document.getElementById("search-icon");
  const closeSearchBtn = document.getElementById("close-search-btn");
  const searchInput = document.getElementById("search-input");
  const mobileMenuBtn = document.getElementById("mobile-menu-btn");
  const headerMenu = document.getElementById("header-menu");
  const profileMenuToggle = document.getElementById("profile-menu-toggle");
  const profileMenu = document.getElementById("profile-menu");
  const searchContainer = header.querySelector(
    ".apps-header__search-container"
  );

  const TABLET_BREAKPOINT = 790;
  const FUNIFELT_ID = 1;
  let allApps = [];

  // --- FUNCIÓN AUXILIAR GLOBAL ---
  function safeFolderName(name) {
    if (!name) return "undefined";
    return (
      name
        .toLowerCase()
        .replace(/\s/g, "_")
        .replace(/[^a-z0-9_-]/g, "") || "undefined"
    );
  }

  // --- LÓGICA DEL MENÚ RESPONSIVE ---
  if (mobileMenuBtn && headerMenu) {
    mobileMenuBtn.addEventListener("click", function () {
      headerMenu.classList.toggle("show");
      // Cerrar otros menús cuando se abre el menú hamburguesa
      if (headerMenu.classList.contains("show")) {
        alliedCompaniesDropdown.classList.remove("show");
        profileMenu.classList.remove("show");
      }
    });
  }

  // --- CREACIÓN DEL CONTENEDOR DE SUGERENCIAS ---
  const searchSuggestionsContainer = document.createElement("div");
  searchSuggestionsContainer.id = "search-suggestions";
  searchSuggestionsContainer.classList.add("search-suggestions");

  if (searchContainer) {
    searchContainer.appendChild(searchSuggestionsContainer);
  }

  // --- LÓGICA DEL DROPDOWN DE EMPRESAS ALIADAS - CORREGIDO ---
  if (alliedCompaniesLink && alliedCompaniesDropdown) {
    alliedCompaniesLink.addEventListener("click", function (event) {
      console.log("🟥 Click en Empresas Aliadas");
      event.preventDefault();
      event.stopPropagation();

      if (window.innerWidth <= TABLET_BREAKPOINT) {
        // En móvil: el dropdown vive dentro del menú y debajo del link
        if (headerMenu && !headerMenu.contains(alliedCompaniesDropdown)) {
          const nav = headerMenu.querySelector(".apps-header__nav");
          if (nav) {
            nav.appendChild(alliedCompaniesDropdown); // lo movemos debajo del nav
          }
        }

        alliedCompaniesDropdown.style.position = "static";
        alliedCompaniesDropdown.style.width = "100%";
        alliedCompaniesDropdown.style.maxWidth = "none";
      } else {
        const linkRect = alliedCompaniesLink.getBoundingClientRect();
        alliedCompaniesDropdown.style.position = "absolute";
        alliedCompaniesDropdown.style.left = `${linkRect.left}px`;
        alliedCompaniesDropdown.style.top = "50px";
        alliedCompaniesDropdown.style.width = "auto";
      }

      alliedCompaniesDropdown.classList.toggle("show");
      // Cerrar otros menús
      profileMenu.classList.remove("show");
    });
  }

  // --- LÓGICA DE LA BARRA DE BÚSQUEDA ---
  if (searchIcon && closeSearchBtn && searchInput && searchContainer) {
    searchIcon.addEventListener("click", function (event) {
      event.stopPropagation();

      if (
        window.innerWidth <= TABLET_BREAKPOINT &&
        headerMenu &&
        !headerMenu.classList.contains("show")
      ) {
        headerMenu.classList.add("show");
      }

      header.classList.add("search-active");
      searchInput.focus();
    });

    closeSearchBtn.addEventListener("click", function () {
      header.classList.remove("search-active");
      searchInput.value = "";
      searchInput.dispatchEvent(new Event("input", { bubbles: true }));
      searchSuggestionsContainer.style.display = "none";
    });

    // --- FUNCIÓN PARA OBTENER TODAS LAS APPS ---
    async function fetchAllApps() {
      try {
        const response = await fetch("/api/apps");
        if (!response.ok) return [];
        const structuredData = await response.json();
        return structuredData.flatMap((company) =>
          (company.applications || []).map((app) => ({
            ...app,
            company_id: company.company_id,
            company_name: company.company_name,
          }))
        );
      } catch (error) {
        console.error("Error al obtener las apps para la búsqueda:", error);
        return [];
      }
    }

    // --- CARGAR LAS APPS AL INICIO ---
    allApps = await fetchAllApps();

    // --- ESCUCHAR EL INPUT DE BÚSQUEDA ---
    searchInput.addEventListener("input", function () {
      const searchTerm = this.value.toLowerCase().trim();

      if (searchTerm === "") {
        searchSuggestionsContainer.style.display = "none";
        return;
      }

      const filteredApps = allApps.filter((app) => {
        const name = (app.app_name || "").toLowerCase().trim();
        return name.includes(searchTerm);
      });

      showSuggestions(filteredApps);
    });
  }

  // --- MENÚ DESPLEGABLE DE PERFIL ---
  if (profileMenuToggle && profileMenu) {
    profileMenuToggle.addEventListener("click", function (event) {
      console.log("🟦 Click en avatar de usuario");
      event.stopPropagation();
      profileMenu.classList.toggle("show");

      const isOpen = profileMenu.classList.contains("show");
      profileMenuToggle.setAttribute(
        "aria-expanded",
        isOpen ? "true" : "false"
      );
      profileMenu.setAttribute("aria-hidden", isOpen ? "false" : "true");

      if (isOpen) {
        const rect = profileMenuToggle.getBoundingClientRect();

        if (window.innerWidth <= TABLET_BREAKPOINT) {
          // 📱 En móvil: justo debajo del icono
          profileMenu.style.position = "fixed";
          profileMenu.style.top = `${rect.bottom + 8}px`;
          profileMenu.style.left = `${rect.left}px`;
          profileMenu.style.right = "auto";
        } else {
          // 💻 En escritorio: usamos la esquina derecha como hasta ahora
          profileMenu.style.position = "absolute";
          profileMenu.style.top = "56px";
          profileMenu.style.right = "2rem";
          profileMenu.style.left = "auto";
        }
      }

      // Cerrar otros menús
      alliedCompaniesDropdown.classList.remove("show");
    });
  }

  // --- CERRAR MENÚS AL HACER CLIC FUERA ---
  document.addEventListener("click", function (event) {
    if (
      alliedCompaniesDropdown &&
      alliedCompaniesDropdown.classList.contains("show")
    ) {
      if (
        !alliedCompaniesLink.contains(event.target) &&
        !alliedCompaniesDropdown.contains(event.target)
      ) {
        alliedCompaniesDropdown.classList.remove("show");
      }
    }

    if (profileMenu && profileMenu.classList.contains("show")) {
      if (
        !profileMenuToggle.contains(event.target) &&
        !profileMenu.contains(event.target)
      ) {
        profileMenu.classList.remove("show");
        profileMenuToggle.setAttribute("aria-expanded", "false");
        profileMenu.setAttribute("aria-hidden", "true");
      }
    }

    if (
      header &&
      header.classList.contains("search-active") &&
      searchContainer &&
      searchIcon
    ) {
      const sc = searchContainer;
      if (!sc.contains(event.target) && !searchIcon.contains(event.target)) {
        header.classList.remove("search-active");
        searchSuggestionsContainer.style.display = "none";
      }
    }
  });

  // --- FUNCIÓN PARA MOSTRAR SUGERENCIAS ---
  function showSuggestions(apps) {
    if (!apps.length || !searchSuggestionsContainer) {
      searchSuggestionsContainer.style.display = "none";
      return;
    }

    searchSuggestionsContainer.innerHTML = apps
      .slice(0, 5)
      .map((app) => {
        const companyFolderName = safeFolderName(app.company_name);
        const imgSrc = app.image
          ? `/build/img/apps/${companyFolderName}/${app.image}`
          : `https://placehold.co/40x40/2c3e50/ffffff?text=${encodeURIComponent(
              (app.app_name || "").charAt(0) || "A"
            )}`;

        return `
                <a href="/app_detail?id=${app.app_id}" class="suggestion-item">
                    <img src="${imgSrc}" class="suggestion-icon" alt="${app.app_name}">
                    <span class="suggestion-name">${app.app_name}</span>
                </a>
            `;
      })
      .join("");

    searchSuggestionsContainer.style.display = "block";
  }

  // ==================================================
  //  CARGAR EMPRESAS ALIADAS EN EL DROPDOWN
  // ==================================================
  async function loadAlliedCompaniesDropdown() {
    if (!alliedCompaniesDropdown) return;

    try {
      const resp = await fetch("/api/empresas/listar");
      if (!resp.ok) {
        alliedCompaniesDropdown.innerHTML =
          '<p class="no-results">No se pudieron cargar las empresas aliadas.</p>';
        return;
      }

      const companies = await resp.json();
      const allies = companies.filter((c) => Number(c.id) !== FUNIFELT_ID);

      if (!allies.length) {
        alliedCompaniesDropdown.innerHTML =
          '<p class="no-results">No hay empresas aliadas.</p>';
        return;
      }

      alliedCompaniesDropdown.innerHTML = `
                <ul>
                    ${allies
                      .map(
                        (c) => `
                        <li>
                            <a href="/?company_id=${c.id}">
                                ${c.name}
                            </a>
                        </li>
                    `
                      )
                      .join("")}
                </ul>
            `;
    } catch (error) {
      console.error("Error al cargar empresas aliadas:", error);
      alliedCompaniesDropdown.innerHTML =
        '<p class="no-results">Error al cargar empresas aliadas.</p>';
    }
  }

  // Llamamos a la carga del dropdown
  loadAlliedCompaniesDropdown();
});
