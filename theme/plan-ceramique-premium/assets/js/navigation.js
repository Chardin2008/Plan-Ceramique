document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.nav-toggle');
  const shell = document.querySelector('.site-header__nav-shell');
  const header = document.querySelector('[data-site-header]');

  const closeMenu = () => {
    if (!toggle || !shell) {
      return;
    }

    toggle.setAttribute('aria-expanded', 'false');
    shell.classList.remove('is-open');
    document.body.classList.remove('menu-open');
  };

  if (toggle && shell) {
    toggle.addEventListener('click', () => {
      const isOpen = toggle.getAttribute('aria-expanded') === 'true';

      toggle.setAttribute('aria-expanded', String(!isOpen));
      shell.classList.toggle('is-open', !isOpen);
      document.body.classList.toggle('menu-open', !isOpen);
    });

    shell.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', closeMenu);
    });

    document.addEventListener('click', (event) => {
      if (!shell.classList.contains('is-open')) {
        return;
      }

      if (!shell.contains(event.target) && !toggle.contains(event.target)) {
        closeMenu();
      }
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        closeMenu();
        toggle.focus();
      }
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth >= 1180) {
        closeMenu();
      }
    });
  }

  const syncHeader = () => {
    if (!header) {
      return;
    }

    header.classList.toggle('is-scrolled', window.scrollY > 12);
  };

  syncHeader();
  window.addEventListener('scroll', syncHeader, { passive: true });
});
