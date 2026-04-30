document.addEventListener('DOMContentLoaded', () => {
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  document.documentElement.classList.add('pcstudio-ready');

  if (!reducedMotion && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });

    document.querySelectorAll('.reveal-up').forEach((item) => observer.observe(item));
  } else {
    document.querySelectorAll('.reveal-up').forEach((item) => item.classList.add('is-visible'));
  }

  const scanner = document.querySelector('[data-scanner]');
  if (scanner) {
    const title = scanner.querySelector('[data-scanner-title]');
    const text = scanner.querySelector('[data-scanner-text]');

    scanner.querySelectorAll('.scanner-point').forEach((point) => {
      point.addEventListener('click', () => {
        scanner.querySelectorAll('.scanner-point').forEach((item) => item.classList.remove('is-active'));
        point.classList.add('is-active');

        if (title) {
          title.textContent = point.dataset.title || '';
        }

        if (text) {
          text.textContent = point.dataset.text || '';
        }
      });
    });
  }

  const configurator = document.querySelector('[data-configurator]');
  if (configurator) {
    const imageBase = window.pcpLanding?.assetImgBase || './assets/img/';
    const imageUrl = (file) => `${imageBase}${file}`;
    const results = {
      'Warm Mineral': {
        image: imageUrl('kitchen-warm-ceramique.jpg'),
        colors: ['#FBF8F2', '#D8C7AD', '#6B4E35'],
        text: 'Pierre claire, bois noyer et lumière chaude pour un espace doux, premium et accueillant.',
      },
      'Pure Marble': {
        image: imageUrl('kitchen-white-ceramique.jpg'),
        colors: ['#FFFFFF', '#E7DFD2', '#C9A76A'],
        text: 'Blanc veiné, lignes lumineuses et élégance intemporelle pour un projet très clair.',
      },
      'Sage Architecture': {
        image: imageUrl('kitchen-sage-ceramique.jpg'),
        colors: ['#A7B3A0', '#F4EFE6', '#B8785F'],
        text: 'Vert sauge, pierre claire et détails champagne pour une ambiance contemporaine apaisante.',
      },
      'Natural Stone': {
        image: imageUrl('texture-sand-stone.jpg'),
        colors: ['#E7DFD2', '#B7A58B', '#9B7A4A'],
        text: 'Une direction minérale équilibrée, naturelle et facile à intégrer.',
      },
      'Soft Concrete': {
        image: imageUrl('texture-concrete-light.jpg'),
        colors: ['#F4EFE6', '#E7DFD2', '#6F6A60'],
        text: 'Un esprit béton clair, architectural, doux et très contemporain.',
      },
    };
    const state = {
      project: 'Cuisine',
      style: 'Clair',
      mood: 'Premium',
    };
    const resultTitle = configurator.querySelector('[data-config-title]');
    const resultText = configurator.querySelector('[data-config-text]');
    const resultImage = configurator.querySelector('[data-config-image]');
    const resultSwatches = configurator.querySelector('[data-config-swatches]');

    const getResult = () => {
      if (state.style === 'Marbre' || state.style === 'Clair') {
        return 'Pure Marble';
      }

      if (state.style === 'Chaleureux' || state.mood === 'Familiale') {
        return 'Warm Mineral';
      }

      if (state.mood === 'Architecturale' || state.project === 'Salle de bain') {
        return 'Sage Architecture';
      }

      if (state.style === 'Béton') {
        return 'Soft Concrete';
      }

      return 'Natural Stone';
    };

    const render = () => {
      const result = getResult();
      const data = results[result] || results['Natural Stone'];

      if (resultTitle) {
        resultTitle.textContent = result;
      }

      if (resultText) {
        resultText.textContent = `${data.text} Recommandé pour un projet ${state.project.toLowerCase()} au style ${state.style.toLowerCase()}.`;
      }

      if (resultImage) {
        resultImage.src = data.image;
        resultImage.alt = result;
      }

      if (resultSwatches) {
        resultSwatches.innerHTML = data.colors.map((color) => `<span style="--swatch:${color}"></span>`).join('');
      }
    };

    configurator.querySelectorAll('button[data-group]').forEach((button) => {
      button.addEventListener('click', () => {
        const group = button.dataset.group;

        if (!group) {
          return;
        }

        state[group] = button.dataset.value || '';
        configurator.querySelectorAll(`button[data-group="${group}"]`).forEach((item) => item.classList.remove('is-active'));
        button.classList.add('is-active');
        render();
      });
    });
  }

  const wizard = document.querySelector('[data-quote-wizard]');
  if (wizard) {
    let current = 1;
    const total = 4;
    const steps = wizard.querySelectorAll('[data-step]');
    const prev = wizard.querySelector('[data-wizard-prev]');
    const next = wizard.querySelector('[data-wizard-next]');
    const progress = wizard.querySelector('[data-wizard-progress]');
    const status = wizard.querySelector('[data-wizard-status]');
    const summary = wizard.querySelector('[data-wizard-summary]');
    const form = wizard.querySelector('form');
    const materialField = wizard.querySelector('[data-wizard-material]');
    const dimensionsField = wizard.querySelector('[data-wizard-dimensions]');
    const dimensionsDisplay = wizard.querySelector('[data-wizard-dimensions-display]');
    const messageField = wizard.querySelector('[data-wizard-message]');
    const messageDisplay = wizard.querySelector('[data-wizard-message-display]');

    const selectedValue = (name) => form?.querySelector(`[name="${name}"]:checked`)?.value || 'À définir';

    const renderSummary = () => {
      if (!summary || !form) {
        return;
      }

      summary.innerHTML = [
        ['Projet', selectedValue('project_type')],
        ['Style', selectedValue('style')],
        ['Budget', selectedValue('budget')],
      ].map(([label, value]) => `<span><strong>${label}</strong>${value}</span>`).join('');
    };

    const render = () => {
      steps.forEach((step) => step.classList.toggle('is-active', Number(step.dataset.step) === current));

      if (prev) {
        prev.hidden = current === 1;
      }

      if (next) {
        next.textContent = current === total ? 'Envoyer ma demande' : 'Continuer';
      }

      if (progress) {
        progress.style.width = `${(current / total) * 100}%`;
      }

      if (status && current !== total) {
        status.textContent = '';
      }

      if (current === total) {
        renderSummary();
      }
    };

    const prepareHiddenFields = () => {
      if (materialField) {
        materialField.value = selectedValue('style');
      }

      if (dimensionsField) {
        dimensionsField.value = dimensionsDisplay?.value || '';
      }

      if (messageField) {
        const userMessage = messageDisplay?.value || '';
        messageField.value = [
          `Projet : ${selectedValue('project_type')}`,
          `Style souhaité : ${selectedValue('style')}`,
          `Budget approximatif : ${selectedValue('budget')}`,
          '',
          'Message client :',
          userMessage || 'Non renseigné',
        ].join('\n');
      }
    };

    prev?.addEventListener('click', () => {
      current = Math.max(1, current - 1);
      render();
    });

    next?.addEventListener('click', () => {
      if (current < total) {
        current += 1;
        render();
        return;
      }

      const required = form?.querySelectorAll('[required]') || [];
      const invalid = Array.from(required).find((field) => !field.value.trim());

      if (invalid) {
        invalid.focus();
        if (status) {
          status.textContent = 'Merci de renseigner les champs essentiels avant de préparer la demande.';
        }
        return;
      }

      if (status) {
        status.textContent = '';
      }

      prepareHiddenFields();
      form?.requestSubmit();
    });

    render();
  }

  document.querySelectorAll('[data-filter-scope]').forEach((scope) => {
    const buttons = scope.querySelectorAll('[data-filter]');
    const items = scope.querySelectorAll('[data-filter-item]');

    buttons.forEach((button) => {
      button.addEventListener('click', () => {
        const filter = button.dataset.filter || 'Tous';
        buttons.forEach((item) => item.classList.toggle('is-active', item === button));

        items.forEach((item) => {
          const shouldShow = filter === 'Tous' || item.dataset.filterValue === filter;
          item.classList.toggle('is-hidden', !shouldShow);
        });
      });
    });
  });

  const lightbox = document.querySelector('[data-lightbox]');
  if (lightbox) {
    const image = lightbox.querySelector('[data-lightbox-image]');
    const close = lightbox.querySelector('[data-lightbox-close]');
    const focusableSelector = 'a[href], button:not([disabled]), input:not([disabled]), textarea:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])';
    let previousFocus = null;

    const closeLightbox = () => {
      lightbox.hidden = true;
      lightbox.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('lightbox-open');
      previousFocus?.focus?.();
    };

    document.querySelectorAll('[data-lightbox-src]').forEach((button) => {
      button.addEventListener('click', () => {
        previousFocus = document.activeElement;
        if (image) {
          image.src = button.dataset.lightboxSrc || '';
          image.alt = button.dataset.lightboxAlt || '';
        }
        lightbox.hidden = false;
        lightbox.setAttribute('aria-hidden', 'false');
        document.body.classList.add('lightbox-open');
        close?.focus();
      });
    });

    close?.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', (event) => {
      if (event.target === lightbox) {
        closeLightbox();
      }
    });
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && !lightbox.hidden) {
        closeLightbox();
      }

      if (event.key !== 'Tab' || lightbox.hidden) {
        return;
      }

      const focusable = Array.from(lightbox.querySelectorAll(focusableSelector));

      if (!focusable.length) {
        return;
      }

      const first = focusable[0];
      const last = focusable[focusable.length - 1];

      if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
      } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
      }
    });
  }

  document.querySelectorAll('[data-before-after]').forEach((slider) => {
    const before = slider.querySelector('.pcstudio-before-after__before');
    const range = slider.querySelector('[data-before-after-range]');
    const sync = () => {
      if (before && range) {
        before.style.setProperty('--before-width', `${range.value}%`);
      }
    };
    range?.addEventListener('input', sync);
    sync();
  });

  const story = document.querySelector('[data-sticky-story]');
  if (story) {
    const steps = story.querySelectorAll('[data-story-step]');
    const image = story.querySelector('img[data-story-image]');

    if ('IntersectionObserver' in window) {
      const storyObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) {
            return;
          }
          steps.forEach((step) => step.classList.toggle('is-active', step === entry.target));
          if (image && entry.target.dataset.storyImage) {
            image.src = entry.target.dataset.storyImage;
          }
        });
      }, { threshold: 0.62 });

      steps.forEach((step) => storyObserver.observe(step));
    }
  }

  const floatingCta = document.querySelector('[data-floating-cta]');
  const navLinks = document.querySelectorAll('.site-nav__menu a[href*="#"]');
  const sections = Array.from(navLinks).map((link) => {
    const id = link.hash;
    return id ? { link, section: document.querySelector(id) } : null;
  }).filter((item) => item?.section);

  const syncScrollUi = () => {
    floatingCta?.classList.toggle('is-visible', window.scrollY > 540);

    let active = null;
    sections.forEach((item) => {
      const rect = item.section.getBoundingClientRect();
      if (rect.top < 160 && rect.bottom > 160) {
        active = item.link;
      }
    });

    navLinks.forEach((link) => link.classList.toggle('is-active', link === active));
  };

  syncScrollUi();
  window.addEventListener('scroll', syncScrollUi, { passive: true });
});
