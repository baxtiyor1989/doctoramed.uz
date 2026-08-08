const menuBtn = document.getElementById("menuBtn");
const nav = document.getElementById("nav");

menuBtn?.addEventListener("click", () => {
  nav.classList.toggle("open");
});

document.querySelectorAll(".nav a").forEach((link) => {
  link.addEventListener("click", () => nav.classList.remove("open"));
});

document.querySelectorAll(".nav-submenu-toggle").forEach((button) => {
  button.addEventListener("click", (event) => {
    event.preventDefault();
    event.stopPropagation();
    button.closest(".has-submenu")?.classList.toggle("submenu-open");
  });
});

const serviceFilterKey = "doctoramed-service-menu-filter";
const servicesGrid = document.querySelector("[data-services-grid]");
const servicesEmpty = document.querySelector("[data-services-empty]");
const servicesToggle = document.querySelector("[data-services-toggle]");
const servicesToggleWrap = document.querySelector("[data-services-toggle-wrap]");

const refreshServicesToggle = () => {
  const hiddenCards = servicesGrid?.querySelectorAll(".service-collapsed") || [];
  if (servicesToggleWrap) servicesToggleWrap.hidden = hiddenCards.length === 0;
  if (servicesToggle) servicesToggle.textContent = servicesToggle.dataset.defaultText || servicesToggle.textContent;
  servicesGrid?.classList.remove("services-expanded");
};

const loadServicesByMenu = async (menuId) => {
  if (!servicesGrid?.dataset.servicesFilterUrl) return;

  servicesGrid.classList.add("loading");
  try {
    const url = new URL(servicesGrid.dataset.servicesFilterUrl, window.location.origin);
    if (menuId) url.searchParams.set("menu_id", menuId);
    const response = await fetch(url.toString(), {
      headers: { "X-Requested-With": "XMLHttpRequest" },
    });
    if (!response.ok) return;
    const data = await response.json();
    servicesGrid.innerHTML = data.html || "";
    if (servicesEmpty) servicesEmpty.hidden = Number(data.count || 0) > 0;
    bindServiceModalOpeners();
    refreshServicesToggle();
  } finally {
    servicesGrid.classList.remove("loading");
  }
};

document.querySelectorAll(".nav a[data-menu-id]").forEach((link) => {
  link.addEventListener("click", (event) => {
    const canFilterServices = link.dataset.serviceFilterable === "1";
    sessionStorage.setItem(serviceFilterKey, canFilterServices ? (link.dataset.menuId || "") : "");
    if (canFilterServices) {
      event.preventDefault();
      const menuId = link.dataset.menuId || "";
      const targetUrl = new URL(link.href, window.location.origin);
      targetUrl.searchParams.set("service_menu", menuId);
      targetUrl.hash = "services";

      if (!servicesGrid) {
        window.location.href = targetUrl.toString();
        return;
      }

      document.getElementById("services")?.scrollIntoView({ behavior: "smooth" });
      nav?.classList.remove("open");
      loadServicesByMenu(menuId);
      return;
    }

    if (link.href.includes("#services")) {
      event.preventDefault();
      document.getElementById("services")?.scrollIntoView({ behavior: "smooth" });
      loadServicesByMenu("");
    }
  });
});

const serviceMenuFromUrl = new URLSearchParams(window.location.search).get("service_menu");
if (serviceMenuFromUrl) {
  sessionStorage.setItem(serviceFilterKey, serviceMenuFromUrl);
}

if (window.location.hash === "#services") {
  loadServicesByMenu(serviceMenuFromUrl || sessionStorage.getItem(serviceFilterKey));
}

refreshServicesToggle();

const languageDropdown = document.querySelector("[data-language-dropdown]");
const languageButton = languageDropdown?.querySelector(".language-current");

languageButton?.addEventListener("click", (event) => {
  event.stopPropagation();
  const isOpen = languageDropdown.classList.toggle("open");
  languageButton.setAttribute("aria-expanded", String(isOpen));
});

document.addEventListener("click", () => {
  languageDropdown?.classList.remove("open");
  languageButton?.setAttribute("aria-expanded", "false");
});

const accessibilityWidget = document.querySelector("[data-accessibility-widget]");
const accessibilityToggle = accessibilityWidget?.querySelector(".accessibility-toggle");
const accessibilityStateKey = "doctoramed-accessibility";
const accessibilityDefaultState = {
  grayscale: false,
  hideImages: false,
  fontLevel: 0,
};
const accessibilityFontScales = {
  "-3": 0.82,
  "-2": 0.88,
  "-1": 0.94,
  0: 1,
  1: 1.08,
  2: 1.16,
  3: 1.24,
};

const getAccessibilityState = () => {
  try {
    return {
      ...accessibilityDefaultState,
      ...JSON.parse(localStorage.getItem(accessibilityStateKey) || "{}"),
    };
  } catch {
    return { ...accessibilityDefaultState };
  }
};

let accessibilityState = getAccessibilityState();

const saveAccessibilityState = () => {
  localStorage.setItem(accessibilityStateKey, JSON.stringify(accessibilityState));
};

const applyAccessibilityState = () => {
  document.body.classList.toggle("accessibility-gray", accessibilityState.grayscale);
  document.body.classList.toggle("accessibility-hide-images", accessibilityState.hideImages);
  document.body.classList.toggle("accessibility-font-scale", accessibilityState.fontLevel !== 0);
  document.body.style.setProperty("--accessibility-font-scale", accessibilityFontScales[accessibilityState.fontLevel] || 1);

  document.querySelectorAll("[data-accessibility-toggle='grayscale']").forEach((button) => {
    button.classList.toggle("active", accessibilityState.grayscale);
  });
  document.querySelectorAll("[data-accessibility-toggle='hide-images']").forEach((button) => {
    button.classList.toggle("active", accessibilityState.hideImages);
  });
  document.querySelectorAll("[data-accessibility-font]").forEach((button) => {
    const mode = button.dataset.accessibilityFont;
    button.classList.toggle(
      "active",
      (mode === "normal" && accessibilityState.fontLevel === 0)
        || (mode === "down" && accessibilityState.fontLevel < 0)
        || (mode === "up" && accessibilityState.fontLevel > 0)
    );
  });
};

applyAccessibilityState();

accessibilityToggle?.addEventListener("click", (event) => {
  event.stopPropagation();
  const isOpen = accessibilityWidget.classList.toggle("open");
  accessibilityToggle.setAttribute("aria-expanded", String(isOpen));
});

accessibilityWidget?.addEventListener("click", (event) => {
  event.stopPropagation();
});

document.querySelectorAll("[data-accessibility-toggle]").forEach((button) => {
  button.addEventListener("click", () => {
    const mode = button.dataset.accessibilityToggle;
    if (mode === "grayscale") {
      accessibilityState.grayscale = !accessibilityState.grayscale;
    }
    if (mode === "hide-images") {
      accessibilityState.hideImages = !accessibilityState.hideImages;
    }
    saveAccessibilityState();
    applyAccessibilityState();
  });
});

document.querySelectorAll("[data-accessibility-font]").forEach((button) => {
  button.addEventListener("click", () => {
    if (button.dataset.accessibilityFont === "normal") {
      accessibilityState.fontLevel = 0;
    } else if (button.dataset.accessibilityFont === "up") {
      accessibilityState.fontLevel = Math.min(3, Number(accessibilityState.fontLevel || 0) + 1);
    } else {
      accessibilityState.fontLevel = Math.max(-3, Number(accessibilityState.fontLevel || 0) - 1);
    }
    saveAccessibilityState();
    applyAccessibilityState();
  });
});

document.querySelector("[data-accessibility-reset]")?.addEventListener("click", () => {
  accessibilityState = { ...accessibilityDefaultState };
  saveAccessibilityState();
  applyAccessibilityState();
});

document.addEventListener("click", () => {
  accessibilityWidget?.classList.remove("open");
  accessibilityToggle?.setAttribute("aria-expanded", "false");
});

const videoModal = document.getElementById("videoModal");
const videoFrame = document.getElementById("heroVideoFrame");
const videoTitle = document.getElementById("heroVideoTitle");
const videoOpen = document.querySelector("[data-video-open]");
const videoCloseItems = document.querySelectorAll("[data-video-close]");
const videoItems = document.querySelectorAll("[data-video-src]");
let currentVideoSrc = videoFrame?.getAttribute("src") || "";

videoOpen?.addEventListener("click", () => {
  if (videoFrame && !videoFrame.src) {
    videoFrame.src = currentVideoSrc;
  }
  videoModal?.classList.add("open");
  videoModal?.setAttribute("aria-hidden", "false");
  document.body.style.overflow = "hidden";
});

videoCloseItems.forEach((item) => {
  item.addEventListener("click", () => {
    videoModal?.classList.remove("open");
    videoModal?.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
    if (videoFrame) {
      currentVideoSrc = videoFrame.src || currentVideoSrc;
      videoFrame.src = "";
    }
  });
});

videoItems.forEach((item) => {
  item.addEventListener("click", () => {
    videoItems.forEach((button) => button.classList.remove("active"));
    item.classList.add("active");
    if (videoFrame) {
      const src = item.dataset.videoSrc || "";
      const separator = src.includes("?") ? "&" : "?";
      currentVideoSrc = `${src}${separator}autoplay=1`;
      videoFrame.src = currentVideoSrc;
      videoFrame.title = item.dataset.videoTitle || "Video";
      if (videoTitle) videoTitle.textContent = videoFrame.title;
    }
  });
});

servicesToggle?.addEventListener("click", () => {
  const isExpanded = !servicesGrid?.classList.contains("services-expanded");
  servicesGrid?.classList.toggle("services-expanded", isExpanded);
  servicesToggle.textContent = isExpanded
    ? servicesToggle.dataset.closeText || "Close"
    : servicesToggle.dataset.defaultText || servicesToggle.textContent;
});

const serviceModal = document.getElementById("serviceModal");
const serviceModalTitle = document.getElementById("serviceModalTitle");
const serviceModalText = document.getElementById("serviceModalText");
const serviceCloseItems = document.querySelectorAll("[data-service-close]");
const serviceAppointmentButton = document.querySelector("[data-service-appointment]");
let selectedServiceForAppointment = "";

const bindServiceModalOpeners = () => {
  document.querySelectorAll("[data-service-open]").forEach((item) => {
    if (item.dataset.serviceBound === "1") return;
    item.dataset.serviceBound = "1";
    item.addEventListener("click", () => {
      if (serviceModalTitle) serviceModalTitle.textContent = item.dataset.serviceTitle || "";
      if (serviceModalText) {
        const description = item.closest("[data-service-card]")?.querySelector("[data-service-full-description]");
        serviceModalText.textContent = item.dataset.serviceDescription || description?.textContent || "";
      }
      selectedServiceForAppointment = item.dataset.serviceTitle || "";
      serviceModal?.classList.add("open");
      serviceModal?.setAttribute("aria-hidden", "false");
      document.body.style.overflow = "hidden";
    });
  });
};

bindServiceModalOpeners();

serviceCloseItems.forEach((item) => {
  item.addEventListener("click", () => {
    serviceModal?.classList.remove("open");
    serviceModal?.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
  });
});

const resumeModal = document.getElementById("resumeModal");
const resumeOpen = document.querySelector("[data-resume-open]");
const resumeCloseItems = document.querySelectorAll("[data-resume-close]");
const appointmentModal = document.getElementById("appointmentModal");
const appointmentOpenItems = document.querySelectorAll("[data-appointment-open]");
const appointmentCloseItems = document.querySelectorAll("[data-appointment-close]");

const openAppointmentModal = () => {
  appointmentModal?.classList.add("open");
  appointmentModal?.setAttribute("aria-hidden", "false");
  document.body.style.overflow = "hidden";
};

const appointmentTypeSelect = document.querySelector("[data-appointment-type-select]");
const appointmentTypeChoices = appointmentTypeSelect && window.Choices
  ? new Choices(appointmentTypeSelect, {
      removeItemButton: true,
      shouldSort: false,
      position: "top",
      searchEnabled: true,
      searchPlaceholderValue: appointmentTypeSelect.dataset.placeholder || "Qidirish...",
      placeholder: true,
      placeholderValue: appointmentTypeSelect.dataset.placeholder || "Tanlang",
      itemSelectText: "",
      noResultsText: "Natija topilmadi",
      noChoicesText: "Boshqa variant yo‘q",
    })
  : null;

const setAppointmentType = (value) => {
  const select = document.querySelector("[data-appointment-type-select]");
  if (!select || !value) return;

  if (appointmentTypeChoices) {
    const hasChoice = Array.from(select.options).some((option) => option.value === value);
    if (hasChoice) {
      appointmentTypeChoices.setChoiceByValue(value);
    } else {
      appointmentTypeChoices.setChoices([{ value, label: value, selected: true }], "value", "label", false);
    }
    return;
  }

  const hasOption = Array.from(select.options).some((option) => option.value === value);
  if (!hasOption) {
    const option = document.createElement("option");
    option.value = value;
    option.textContent = value;
    select.appendChild(option);
  }

  if (select.multiple) {
    Array.from(select.options).forEach((option) => {
      if (option.value === value) option.selected = true;
    });
  } else {
    select.value = value;
  }
  select.dispatchEvent(new Event("change", { bubbles: true }));
};

document.querySelectorAll("[data-region-select]").forEach((regionSelect) => {
  const form = regionSelect.closest("form");
  const districtField = form?.querySelector("[data-district-field]");
  const districtSelect = form?.querySelector("[data-district-select]");
  if (!districtField || !districtSelect) return;

  const updateDistricts = (resetValue = false) => {
    const regionId = regionSelect.value;
    const options = Array.from(districtSelect.options).slice(1);
    let districtCount = 0;

    options.forEach((option) => {
      const matches = regionId !== "" && option.dataset.regionId === regionId;
      option.hidden = !matches;
      option.disabled = !matches;
      if (matches) districtCount += 1;
    });

    if (resetValue) districtSelect.value = "";
    const hasDistricts = districtCount > 0;
    districtField.hidden = !hasDistricts;
    districtSelect.disabled = !hasDistricts;
    districtSelect.required = hasDistricts;
  };

  regionSelect.addEventListener("change", () => updateDistricts(true));
  updateDistricts(false);
});

document.querySelectorAll("[data-resume-branch-select]").forEach((branchSelect) => {
  const form = branchSelect.closest("form");
  const vacancyField = form?.querySelector("[data-resume-vacancy-field]");
  const vacancySelect = form?.querySelector("[data-resume-vacancy-select]");
  if (!vacancyField || !vacancySelect) return;

  const updateVacancies = (resetValue = false) => {
    const branchId = branchSelect.value;
    const options = Array.from(vacancySelect.options).slice(1);
    let vacancyCount = 0;

    options.forEach((option) => {
      const matches = branchId !== "" && option.dataset.branchId === branchId;
      option.hidden = !matches;
      option.disabled = !matches;
      if (matches) vacancyCount += 1;
    });

    if (resetValue) vacancySelect.value = "";
    vacancyField.hidden = vacancyCount === 0;
    vacancySelect.disabled = vacancyCount === 0;
    vacancySelect.required = vacancyCount > 0;
  };

  branchSelect.addEventListener("change", () => updateVacancies(true));
  updateVacancies(false);
});

appointmentOpenItems.forEach((item) => {
  item.addEventListener("click", () => {
    setAppointmentType(item.dataset.appointmentType || "");
    openAppointmentModal();
  });
});

serviceAppointmentButton?.addEventListener("click", () => {
  setAppointmentType(selectedServiceForAppointment);
  serviceModal?.classList.remove("open");
  serviceModal?.setAttribute("aria-hidden", "true");
  openAppointmentModal();
});

appointmentCloseItems.forEach((item) => {
  item.addEventListener("click", () => {
    appointmentModal?.classList.remove("open");
    appointmentModal?.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
  });
});

const openResumeModal = () => {
  resumeModal?.classList.add("open");
  resumeModal?.setAttribute("aria-hidden", "false");
  document.body.style.overflow = "hidden";
};

resumeOpen?.addEventListener("click", openResumeModal);

resumeCloseItems.forEach((item) => {
  item.addEventListener("click", () => {
    resumeModal?.classList.remove("open");
    resumeModal?.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
  });
});

if (document.querySelector("#appointmentModal .resume-success, #appointmentModal .resume-form small")) {
  openAppointmentModal();
} else if (document.querySelector("#resumeModal .resume-success, #resumeModal .resume-form small")) {
  openResumeModal();
}

document.querySelectorAll(".resume-select-wrap select:not([multiple])").forEach((select) => {
  const wrapper = select.closest(".resume-select-wrap");
  const custom = document.createElement("div");
  const trigger = document.createElement("button");
  const menu = document.createElement("div");
  const placeholder = document.createComment("resume select menu");

  custom.className = "resume-custom-select";
  trigger.className = "resume-select-trigger";
  trigger.type = "button";
  menu.className = "resume-select-menu";

  const setTriggerText = () => {
    if (!select.multiple) {
      trigger.textContent = select.options[select.selectedIndex]?.text || select.options[0]?.text || "";
      return;
    }

    const selectedOptions = Array.from(select.selectedOptions).filter((option) => option.value !== "");
    trigger.replaceChildren();
    if (!selectedOptions.length) {
      trigger.textContent = select.dataset.placeholder || "Tanlang";
      return;
    }

    selectedOptions.forEach((option) => {
      const chip = document.createElement("span");
      chip.className = "resume-select-chip";
      chip.textContent = option.text;
      trigger.appendChild(chip);
    });
  };

  select.addEventListener("change", setTriggerText);

  const positionMenu = () => {
    const rect = trigger.getBoundingClientRect();
    menu.style.left = `${rect.left}px`;
    menu.style.top = `${rect.bottom + 8}px`;
    menu.style.width = `${rect.width}px`;
  };

  const closeSelect = () => {
    custom.classList.remove("open");
    menu.classList.remove("fixed");
    menu.removeAttribute("style");
    if (placeholder.parentNode) {
      placeholder.replaceWith(menu);
    }
  };

  const openSelect = () => {
    document.querySelectorAll(".resume-custom-select.open").forEach((item) => {
      if (item !== custom) item.dispatchEvent(new CustomEvent("resume-select-close"));
    });

    custom.classList.add("open");
    menu.replaceWith(placeholder);
    document.body.appendChild(menu);
    menu.classList.add("fixed");
    positionMenu();
  };

  Array.from(select.options).forEach((option) => {
    const item = document.createElement("button");
    item.type = "button";
    item.className = "resume-select-option";
    item.textContent = option.text;
    item.dataset.value = option.value;

    if (option.selected) {
      item.classList.add("active");
    }

    item.addEventListener("click", () => {
      if (select.multiple) {
        option.selected = !option.selected;
        item.classList.toggle("active", option.selected);
      } else {
        select.value = option.value;
      }
      select.dispatchEvent(new Event("change", { bubbles: true }));
      if (!select.multiple) {
        menu.querySelectorAll(".resume-select-option").forEach((button) => button.classList.remove("active"));
        item.classList.add("active");
      }
      setTriggerText();
      if (!select.multiple) closeSelect();
    });

    menu.appendChild(item);
  });

  trigger.addEventListener("click", (event) => {
    event.stopPropagation();
    custom.classList.contains("open") ? closeSelect() : openSelect();
  });

  menu.addEventListener("click", (event) => {
    event.stopPropagation();
  });

  custom.addEventListener("resume-select-close", closeSelect);
  window.addEventListener("resize", () => {
    if (custom.classList.contains("open")) positionMenu();
  });
  document.querySelectorAll(".resume-modal-dialog").forEach((dialog) => {
    dialog.addEventListener("scroll", () => {
      if (custom.classList.contains("open")) positionMenu();
    });
  });

  setTriggerText();
  custom.append(trigger, menu);
  wrapper?.appendChild(custom);
});

document.addEventListener("click", () => {
  document.querySelectorAll(".resume-custom-select.open").forEach((item) => {
    item.dispatchEvent(new CustomEvent("resume-select-close"));
  });
});

document.querySelectorAll("[data-phone-mask]").forEach((phoneMaskInput) => {
  phoneMaskInput.addEventListener("input", () => {
    let digits = phoneMaskInput.value.replace(/\D/g, "");

    if (digits.startsWith("998")) {
      digits = digits.slice(3);
    }

    digits = digits.slice(0, 9);

    const part1 = digits.slice(0, 2);
    const part2 = digits.slice(2, 5);
    const part3 = digits.slice(5, 7);
    const part4 = digits.slice(7, 9);

    phoneMaskInput.value = ["+998", part1, part2, part3, part4]
      .filter(Boolean)
      .join(" ");
  });

  phoneMaskInput.addEventListener("focus", () => {
    if (!phoneMaskInput.value) {
      phoneMaskInput.value = "+998 ";
    }
  });
});

document.querySelectorAll("[data-appointment-captcha-refresh]").forEach((button) => {
  button.addEventListener("click", () => {
    const field = button.closest(".appointment-captcha-field");
    const image = field?.querySelector("[data-appointment-captcha-image]");
    const input = field?.querySelector('input[name$="_captcha"]');
    if (image) image.src = `${image.src.split("?")[0]}?t=${Date.now()}`;
    if (input) {
      input.value = "";
      input.focus();
    }
  });
});

document.querySelectorAll("[data-uppercase-input]").forEach((input) => {
  input.addEventListener("input", () => {
    const cursorPosition = input.selectionStart;
    input.value = input.value.toLocaleUpperCase("uz-UZ");
    input.setSelectionRange(cursorPosition, cursorPosition);
  });
});

const doctorsSlider = document.querySelector("[data-doctors-slider]");
const doctorsPrev = document.querySelector("[data-doctors-prev]");
const doctorsNext = document.querySelector("[data-doctors-next]");

const scrollDoctors = (direction) => {
  if (!doctorsSlider) return;

  const firstCard = doctorsSlider.querySelector(".doctor-card");
  const gap = parseFloat(getComputedStyle(doctorsSlider).columnGap || "24");
  const distance = firstCard ? firstCard.getBoundingClientRect().width + gap : 284;

  doctorsSlider.scrollBy({
    left: direction * distance,
    behavior: "smooth",
  });
};

doctorsPrev?.addEventListener("click", () => scrollDoctors(-1));
doctorsNext?.addEventListener("click", () => scrollDoctors(1));

const aboutSlider = document.querySelector("[data-about-slider]");
const aboutSlides = aboutSlider?.querySelectorAll(".about-slide") || [];
const aboutDots = aboutSlider?.querySelector("[data-about-dots]");
let aboutSlideIndex = 0;
let aboutSlideTimer;

const showAboutSlide = (index) => {
  if (!aboutSlides.length) return;

  aboutSlideIndex = (index + aboutSlides.length) % aboutSlides.length;
  aboutSlides.forEach((slide, slideIndex) => {
    slide.classList.toggle("active", slideIndex === aboutSlideIndex);
  });
  aboutDots?.querySelectorAll("button").forEach((dot, dotIndex) => {
    dot.classList.toggle("active", dotIndex === aboutSlideIndex);
  });
};

const startAboutSlider = () => {
  window.clearInterval(aboutSlideTimer);
  if (aboutSlides.length > 1) {
    aboutSlideTimer = window.setInterval(() => showAboutSlide(aboutSlideIndex + 1), 4500);
  }
};

aboutSlides.forEach((_, index) => {
  const dot = document.createElement("button");
  dot.type = "button";
  dot.classList.toggle("active", index === 0);
  dot.addEventListener("click", () => {
    showAboutSlide(index);
    startAboutSlider();
  });
  aboutDots?.appendChild(dot);
});

startAboutSlider();

const revealElements = document.querySelectorAll(".reveal");

const revealObserver = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
      }
    });
  },
  {
    threshold: 0.13,
  }
);

revealElements.forEach((el) => revealObserver.observe(el));

const bindLogoScatter = (logo, maxDistance = 260, baseSpread = 34) => {
  const dots = logo?.querySelectorAll("span") || [];

  logo?.addEventListener("mousemove", (event) => {
    const logoRect = logo.getBoundingClientRect();
    const mouseX = event.clientX - logoRect.left;
    const mouseY = event.clientY - logoRect.top;

    dots.forEach((dot, index) => {
      const dotRect = dot.getBoundingClientRect();
      const dotX = dotRect.left - logoRect.left + dotRect.width / 2;
      const dotY = dotRect.top - logoRect.top + dotRect.height / 2;
      const deltaX = dotX - mouseX;
      const deltaY = dotY - mouseY;
      const distance = Math.hypot(deltaX, deltaY) || 1;
      const strength = Math.max(0, 1 - distance / maxDistance);
      const spread = baseSpread + (index % 5) * 4;
      const moveX = (deltaX / distance) * strength * spread;
      const moveY = (deltaY / distance) * strength * spread;

      dot.style.transform = `translate(${moveX}px, ${moveY}px)`;
    });
  });

  logo?.addEventListener("mouseleave", () => {
    dots.forEach((dot) => {
      dot.style.transform = "";
    });
  });
};

bindLogoScatter(document.querySelector(".hero-css-logo"));
document.querySelectorAll(".testimonial-css-logo").forEach((logo) => {
  bindLogoScatter(logo, 220, 24);
});

const lines = document.querySelectorAll(".line");

document.addEventListener("mousemove", (event) => {
  const x = event.clientX / window.innerWidth - 0.5;
  const y = event.clientY / window.innerHeight - 0.5;

  lines.forEach((line, index) => {
    const speed = (index + 1) * 12;
    line.style.translate = `${x * speed}px ${y * speed}px`;
  });
});

const sections = document.querySelectorAll("main section[id], footer[id]");
const navLinks = document.querySelectorAll(".nav a");

window.addEventListener("scroll", () => {
  let current = "";

  sections.forEach((section) => {
    const sectionTop = section.offsetTop - 120;
    if (window.scrollY >= sectionTop) {
      current = section.getAttribute("id");
    }
  });

  navLinks.forEach((link) => {
    link.classList.remove("active");
    if (link.getAttribute("href") === `#${current}`) {
      link.classList.add("active");
    }
  });
});
// Clinic service rating widget
document.querySelectorAll('[data-rating-widget]').forEach((widget) => {
  const panel = widget.querySelector('.clinic-rating-panel');
  const trigger = widget.querySelector('[data-rating-open]');
  const options = widget.querySelector('[data-rating-options]');
  const results = widget.querySelector('[data-rating-results]');
  const message = widget.querySelector('[data-rating-message]');
  const copy = JSON.parse(widget.dataset.copy || '{}');
  let state = null;

  const showResults = (data) => {
    state = data;
    options.hidden = true;
    results.hidden = false;
    results.innerHTML = `<div class="clinic-rating-result-head"><span class="clinic-rating-average">${data.average}</span><span>/ 5 · ${data.total} ${copy.votes}</span></div>${data.results.map(row => `<div class="clinic-rating-result-row"><span>${row.score} ★</span><span class="clinic-rating-track"><span class="clinic-rating-fill" style="width:${row.percent}%"></span></span><strong>${row.percent}%</strong></div>`).join('')}`;
  };
  const load = async () => {
    try { const response = await fetch(widget.dataset.statusUrl, {headers:{Accept:'application/json'}}); state = await response.json(); if (state.voted) showResults(state); } catch (_) {}
  };
  trigger.addEventListener('click', () => { panel.hidden = !panel.hidden; trigger.setAttribute('aria-expanded', String(!panel.hidden)); if (!state) load(); });
  widget.querySelector('[data-rating-close]').addEventListener('click', () => { panel.hidden = true; trigger.setAttribute('aria-expanded','false'); trigger.focus(); });
  options.addEventListener('click', async (event) => {
    const button = event.target.closest('[data-rating-score]'); if (!button) return;
    options.querySelectorAll('button').forEach(item => item.disabled = true); message.textContent = '';
    try {
      const response = await fetch(widget.dataset.submitUrl, {method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':widget.dataset.csrf},body:JSON.stringify({score:Number(button.dataset.ratingScore),locale:widget.dataset.locale})});
      const data = await response.json(); if (!response.ok && response.status !== 409) throw new Error(); showResults(data); message.textContent = data.message || copy.thanks;
    } catch (_) { message.textContent = 'Xatolik yuz berdi. Qayta urinib ko‘ring.'; options.querySelectorAll('button').forEach(item => item.disabled = false); }
  });
  document.addEventListener('keydown', event => { if (event.key === 'Escape' && !panel.hidden) { panel.hidden = true; trigger.setAttribute('aria-expanded','false'); } });
  load();
});
