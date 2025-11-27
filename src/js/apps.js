// src/js/apps.js
document.addEventListener("DOMContentLoaded", () => {
  console.log("✅ apps.js cargado correctamente");
  const FUNIFELT_ID = 1;

  // --- 0. LÓGICA DE IDIOMA (Igual que en confirmDelete.js) ---
  const langCode = navigator.language.startsWith("es") ? "es" : "en";

  // Objeto con todos los textos y datos dependientes del idioma
  const translations = {
    es: {
      ui: {
        primaryTitle: "Aplicaciones de",
        primarySubtitle: "Desarrolladas por el equipo de",
        defaultCompany: "FUNIFELT",
        viewMore: "Ver más",
        knowMore: "Conoce más",
        loadingError: "Error al cargar el contenido.",
        noApps: "No se encontraron aplicaciones para esta empresa.",
        noAllied: "No se encontraron aplicaciones de aliados.",
        promoTitle: "Apoya la Misión Social de FUNIFELT",
        promoText: "Cada compra financia programas educativos para comunidades vulnerables. ¡Tu descarga hace la diferencia!",
      },
      banners: [
        {
          id: "static1",
          appName: "Aplicaciones Móviles",
          description: "FUNIFELT ha desarrollado cuatro aplicaciones diferentes para mejorar la competencia comunicativa en inglés, español, italiano y holandés. Se centran en los estudiantes autónomos. Aprenderán vocabulario nuevo y podrán escuchar cómo se pronuncia; esas aplicaciones tienen más de 78 niveles y juegos.",
          backgroundImageUrl: "/build/img/banners/banner-aplicaciones-moviles.png",
          linkUrl: "https://www.funifelt.com/languageapps",
          contentBackgroundColor: "#2c3e50",
          titleColor: "#ffffff",
          textColor: "#ecf0f1",
          buttonBgColor: "#3498db",
          buttonTextColor: "#ffffff",
          imageBackgroundColor: "#2a2a2a",
        },
        {
          id: "static2",
          appName: "Escuelas & Colegios",
          description: "Contamos con programas escolares que ayudan a los estudiantes basados en programas curriculares nacionales e internacionales, generando un ambiente cálido de respeto y tolerancia con otras culturas enfocadas en el plurilingüismo. ¡Sé parte de nuestras escuelas!",
          backgroundImageUrl: "/build/img/banners/banner-escuelas-colegios.png",
          linkUrl: "https://www.funifelt.com/schools",
          contentBackgroundColor: "#8e44ad",
          titleColor: "#ffffff",
          textColor: "#ecf0f1",
          buttonBgColor: "#9b59b6",
          buttonTextColor: "#ffffff",
          imageBackgroundColor: "#2a2a2a",
        },
        {
          id: "static3",
          appName: "Español para Extranjeros",
          description: "Ofrecemos lecciones individuales desde Principiantes hasta Avanzados a precios adecuados y apoyamos el proceso educativo y turístico de nuestros estudiantes que quieran estudiar y viajar a Latinoamérica o España. Nuestros alumnos cuentan con diversos materiales para las pruebas DELE y SIELE.",
          backgroundImageUrl: "/build/img/banners/banner-espanol-extranjeros.png",
          linkUrl: "https://www.funifelt.com/spanish-4-foreigners",
          contentBackgroundColor: "#c0392b",
          titleColor: "#ffffff",
          textColor: "#ecf0f1",
          buttonBgColor: "#e74c3c",
          buttonTextColor: "#ffffff",
          imageBackgroundColor: "#2a2a2a",
        },
        {
          id: "static4",
          appName: "Donación",
          description: "Nuestra Fundación recibió libros donados por niños de EE.UU. Estos libros son cuentos infantiles como Mi pequeño pony, Frozen, etc. Esta donación ayuda a nuestros niños a disfrutar de la lectura y escritura con historias que conocen de la TV. ¡Sé parte de nuestro programa!",
          backgroundImageUrl: "/build/img/banners/banner-donacion.png",
          linkUrl: "https://www.facebook.com/revistaedu.co/photos/a.908086225882781.1073741828.906933325998071/1754255121265883/?type=3&theater",
          contentBackgroundColor: "#16a085",
          titleColor: "#ffffff",
          textColor: "#ecf0f1",
          buttonBgColor: "#1abc9c",
          buttonTextColor: "#ffffff",
          imageBackgroundColor: "#2a2a2a",
        },
        {
          id: "static5",
          appName: "PNDE- 2016-2026",
          description: "FUNIFELT y el Ministerio de Educación Nacional presentan el Plan Decenal de Educación. Somos parte del Comité de Gestión y participamos en la presentación del Plan 2016-2026. Nos sentimos comprometidos con impulsar el camino hacia la calidad y la equidad.",
          backgroundImageUrl: "/build/img/banners/banner-pnde-colombia.png",
          linkUrl: "https://www.mineducacion.gov.co/portal/salaprensa/Noticias/363197:Colombia-ya-tiene-su-Plan-Nacional-Decenal-de-Educacion",
          contentBackgroundColor: "#2980b9",
          titleColor: "#ffffff",
          textColor: "#ecf0f1",
          buttonBgColor: "#3498db",
          buttonTextColor: "#ffffff",
          imageBackgroundColor: "#2a2a2a",
        },
      ],
    },
    en: {
      ui: {
        primaryTitle: "Apps by",
        primarySubtitle: "Developed by the team at",
        defaultCompany: "FUNIFELT",
        viewMore: "Read more",
        knowMore: "Learn more",
        loadingError: "Error loading content.",
        noApps: "No apps found for this company.",
        noAllied: "No allied apps found.",
        promoTitle: "Support FUNIFELT's Social Mission",
        promoText: "Every purchase funds educational programs for vulnerable communities. Your download makes a difference!",
      },
      banners: [
        {
          id: "static1",
          appName: "Mobile Applications",
          description: "FUNIFELT has developed four different applications to improve communicative competence in English, Spanish, Italian, and Dutch. Focused on autonomous learners, they teach new vocabulary and pronunciation with over 78 levels and games at the end of each level.",
          backgroundImageUrl: "/build/img/banners/banner-aplicaciones-moviles.png",
          linkUrl: "https://www.funifelt.com/languageapps",
          contentBackgroundColor: "#2c3e50",
          titleColor: "#ffffff",
          textColor: "#ecf0f1",
          buttonBgColor: "#3498db",
          buttonTextColor: "#ffffff",
          imageBackgroundColor: "#2a2a2a",
        },
        {
          id: "static2",
          appName: "Schools & Colleges",
          description: "We have school programs that support students based on national and international curricular standards, creating a warm environment of respect and tolerance for other cultures focused on multilingualism. Be part of our schools!",
          backgroundImageUrl: "/build/img/banners/banner-escuelas-colegios.png",
          linkUrl: "https://www.funifelt.com/schools",
          contentBackgroundColor: "#8e44ad",
          titleColor: "#ffffff",
          textColor: "#ecf0f1",
          buttonBgColor: "#9b59b6",
          buttonTextColor: "#ffffff",
          imageBackgroundColor: "#2a2a2a",
        },
        {
          id: "static3",
          appName: "Spanish for Foreigners",
          description: "We offer individual lessons from Beginners to Advanced at affordable prices, supporting the educational and tourism process of students wishing to study and travel in Latin America or Spain. Our students have access to various materials for DELE and SIELE exams.",
          backgroundImageUrl: "/build/img/banners/banner-espanol-extranjeros.png",
          linkUrl: "https://www.funifelt.com/spanish-4-foreigners",
          contentBackgroundColor: "#c0392b",
          titleColor: "#ffffff",
          textColor: "#ecf0f1",
          buttonBgColor: "#e74c3c",
          buttonTextColor: "#ffffff",
          imageBackgroundColor: "#2a2a2a",
        },
        {
          id: "static4",
          appName: "Donation",
          description: "Our Foundation received books donated by children from the USA. These are children's stories like My Little Pony, Frozen, etc. This donation helps our children enjoy reading and writing through stories they recognize from TV. Be part of our program!",
          backgroundImageUrl: "/build/img/banners/banner-donacion.png",
          linkUrl: "https://www.facebook.com/revistaedu.co/photos/a.908086225882781.1073741828.906933325998071/1754255121265883/?type=3&theater",
          contentBackgroundColor: "#16a085",
          titleColor: "#ffffff",
          textColor: "#ecf0f1",
          buttonBgColor: "#1abc9c",
          buttonTextColor: "#ffffff",
          imageBackgroundColor: "#2a2a2a",
        },
        {
          id: "static5",
          appName: "PNDE- 2016-2026",
          description: "FUNIFELT and the Ministry of National Education present the Decennial Education Plan. We are part of the Management Committee participating in the 2016-2026 plan presentation. We are committed to promoting 'the path towards quality and equity'.",
          backgroundImageUrl: "/build/img/banners/banner-pnde-colombia.png",
          linkUrl: "https://www.mineducacion.gov.co/portal/salaprensa/Noticias/363197:Colombia-ya-tiene-su-Plan-Nacional-Decenal-de-Educacion",
          contentBackgroundColor: "#2980b9",
          titleColor: "#ffffff",
          textColor: "#ecf0f1",
          buttonBgColor: "#3498db",
          buttonTextColor: "#ffffff",
          imageBackgroundColor: "#2a2a2a",
        },
      ],
    },
  };

  // Seleccionamos los textos según el idioma
  const t = translations[langCode];

  // --- Helper function to create safe folder names ---
  function safeFolderName(name) {
    if (!name) return "undefined";
    let safeName = name.toLowerCase();
    safeName = safeName.replace(/\s/g, "_");
    safeName = safeName.replace(/[^a-z0-9_-]/g, "");
    return safeName || "undefined";
  }

  // --- 1. Seleccionar todos los elementos del DOM ---
  const elements = {
    primaryAppsContainer: document.getElementById("funifeltApps"),
    alliedAppsContainer: document.getElementById("newApps"),
    sliderContainer: document.getElementById("appSlider"),
    promoContainer: document.getElementById("promoBanner"),
    primaryTitle: document.getElementById("primary-section-title"),
    primarySubtitle: document.getElementById("primary-section-subtitle"),
    alliedCompaniesLink: document.getElementById("allied-companies-link"),
    alliedCompaniesDropdown: document.getElementById("allied-companies-dropdown"),
    prevBtn: document.getElementById("prevBtn"),
    nextBtn: document.getElementById("nextBtn"),
  };

  // --- 2. Función para obtener todos los datos necesarios al inicio ---
  async function fetchInitialData() {
    try {
      const structuredAppsResponse = await fetch("/api/apps");
      if (!structuredAppsResponse.ok) {
        throw new Error(t.ui.loadingError);
      }
      const structuredData = await structuredAppsResponse.json();

      const apps = structuredData.flatMap((company) =>
        (company.applications || []).map((app) => ({
          ...app,
          company_id: company.company_id,
          company_name: company.company_name,
        }))
      );

      const companyMap = new Map();
      structuredData.forEach((company) => {
        if (!company.company_id) return;
        companyMap.set(String(company.company_id), {
          id: String(company.company_id),
          name: company.company_name || `Empresa ${company.company_id}`,
        });
      });

      if (!companyMap.has(String(FUNIFELT_ID))) {
        companyMap.set(String(FUNIFELT_ID), {
          id: String(FUNIFELT_ID),
          name: "Funifelt",
        });
      }

      const allCompanies = Array.from(companyMap.values());
      return { apps, allCompanies };
    } catch (error) {
      console.error("Error crítico al obtener datos:", error);
      if (elements.primaryAppsContainer) {
        elements.primaryAppsContainer.innerHTML = `<p class="no-results">${t.ui.loadingError}</p>`;
      }
      return null;
    }
  }

  // --- 3. Funciones de renderizado (MODIFICADAS PARA IDIOMA) ---
  
  function renderTitles(companyId, allCompanies) {
    const company = allCompanies.find((c) => String(c.id) === String(companyId));
    const companyName = company ? company.name : t.ui.defaultCompany;

    if (elements.primaryTitle) {
      // Usamos t.ui.primaryTitle ("Aplicaciones de" o "Apps by")
      elements.primaryTitle.textContent = `${t.ui.primaryTitle} ${companyName}`;
    }
    if (elements.primarySubtitle) {
      // Usamos t.ui.primarySubtitle
      elements.primarySubtitle.textContent = `${t.ui.primarySubtitle} ${companyName}.`;
    }
  }

  function renderApps(apps, primaryCompanyId) {
    const primaryId = Number(primaryCompanyId);
    const primaryApps = apps.filter((a) => Number(a.company_id) === primaryId);
    const alliedApps = apps.filter((a) => Number(a.company_id) !== primaryId);

    const cardHTML = (app) => {
      const companyFolderName = safeFolderName(app.company_name);
      const imgSrc = app.image
        ? `/build/img/apps/${companyFolderName}/${app.image}`
        : "";
      const placeholder = `https://placehold.co/300x300/2c3e50/ffffff?text=${encodeURIComponent(
        app.app_name || "App"
      )}`;

      return `
          <div class="app-card" data-id="${app.id || app.app_id}">
            <img src="${imgSrc}" alt="${app.name || app.app_name}" class="app-icon" onerror="this.onerror=null; this.src='${placeholder}';">
            <h4 class="app-name">${app.name || app.app_name}</h4>
          </div>`;
    };

    if (elements.primaryAppsContainer) {
      elements.primaryAppsContainer.innerHTML = primaryApps.length
        ? primaryApps.map(cardHTML).join("")
        : `<p class="no-results">${t.ui.noApps}</p>`;
    }
    if (elements.alliedAppsContainer) {
      elements.alliedAppsContainer.innerHTML = alliedApps.length
        ? alliedApps.map(cardHTML).join("")
        : `<p class="no-results">${t.ui.noAllied}</p>`;
    }
  }

  function renderStaticSlider() {
    if (elements.sliderContainer) {
      // AQUÍ: Usamos t.banners en lugar de la variable estática anterior
      elements.sliderContainer.innerHTML = t.banners
        .map((banner, index) => {
          const isEven = (index + 1) % 2 === 0;

          const imageStyle = `style="background-color: ${banner.imageBackgroundColor || "#2a2a2a"};"`;
          const imageHtml = `
                <div class="slider-item__image" ${imageStyle}>
                    <img src="${banner.backgroundImageUrl}" alt="Banner de ${banner.appName}">
                </div>`;

          const contentStyle = `style="background-color: ${banner.contentBackgroundColor || "#2a2a2a"};"`;
          const titleStyle = `style="color: ${banner.titleColor || "#ffffff"};"`;
          const textStyle = `style="color: ${banner.textColor || "#dddddd"};"`;
          const buttonStyle = `style="background-color: ${banner.buttonBgColor || "#26488C"}; color: ${banner.buttonTextColor || "#FFFFFF"};"`;

          const contentHtml = `
                <div class="slider-item__content" ${contentStyle}>
                    <h3 ${titleStyle}>${banner.appName}</h3>
                    <p ${textStyle}>${banner.description}</p>
                    <a href="${banner.linkUrl}" class="btn-promo" ${buttonStyle}>${t.ui.viewMore}</a>
                </div>`;

          return `
            <div class="slider-item" data-id="${banner.id}">
                ${isEven ? contentHtml + imageHtml : imageHtml + contentHtml}
            </div>
        `;
        })
        .join("");
    }
  }

  function renderPromoBanner() {
    if (elements.promoContainer) {
      // AQUÍ: Usamos las traducciones de t.ui.promoTitle, t.ui.promoText y t.ui.knowMore
      elements.promoContainer.innerHTML = `
            <div class="promo-content">
                <h3>${t.ui.promoTitle}</h3>
                <p>${t.ui.promoText}</p>
                <a href="https://www.funifelt.com/" class="btn-promo-intercalado">${t.ui.knowMore}</a>
            </div>
            <div class="promo-image">
              <img src="/build/img/banners/mision-social.png" alt="Misión Social Funifelt">
            </div>
        `;
    }
  }

  function setupSlider() {
    if (!elements.sliderContainer || !elements.prevBtn || !elements.nextBtn)
      return;

    const items = elements.sliderContainer.querySelectorAll(".slider-item");
    const totalItems = items.length;
    if (totalItems <= 1) {
      elements.prevBtn.style.display = "none";
      elements.nextBtn.style.display = "none";
      return;
    }

    let currentIndex = 0;
    elements.sliderContainer.style.scrollBehavior = "smooth";

    const advanceSlider = (direction) => {
      if (direction === "next") {
        currentIndex = (currentIndex + 1) % totalItems;
      } else {
        currentIndex = (currentIndex - 1 + totalItems) % totalItems;
      }
      updateSlider();
    };

    const updateSlider = () => {
      const scrollAmount = items[currentIndex].offsetLeft;
      elements.sliderContainer.scrollTo({ left: scrollAmount });
    };

    let autoSlideInterval = setInterval(() => advanceSlider("next"), 5000);

    const resetAutoSlide = () => {
      clearInterval(autoSlideInterval);
      autoSlideInterval = setInterval(() => advanceSlider("next"), 5000);
    };

    elements.nextBtn.addEventListener("click", () => {
      advanceSlider("next");
      resetAutoSlide();
    });

    elements.prevBtn.addEventListener("click", () => {
      advanceSlider("prev");
      resetAutoSlide();
    });
  }

  // --- 4. Función principal de inicialización ---
  async function initialize() {
    renderStaticSlider();
    renderPromoBanner();
    setupSlider();

    const data = await fetchInitialData();
    if (!data) return;

    const { apps, allCompanies } = data;

    const params = new URLSearchParams(window.location.search);
    const companyIdFromUrl = params.get("company_id") || FUNIFELT_ID;

    renderApps(apps, companyIdFromUrl);
    renderTitles(companyIdFromUrl, allCompanies);

    document.querySelectorAll(".app-gallery").forEach((gallery) => {
      gallery.addEventListener("click", (e) => {
        const card = e.target.closest(".app-card");
        if (card && card.dataset.id) {
          window.location.href = `/app_detail?id=${card.dataset.id}`;
        }
      });
    });

    console.log("✨ Página de apps inicializada correctamente.");
  }

  // --- 5. Ejecutar la inicialización ---
  initialize();
});