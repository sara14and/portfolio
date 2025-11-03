// assets/js/script.js
document.addEventListener('DOMContentLoaded', () => {
  // theme toggle
  const toggle = document.getElementById('theme-toggle');
  const storageKey = 'portfolio-theme';

  if (toggle) {
    const setAriaLabel = isDark => {
      const label = isDark ? i18n.theme_light_label : i18n.theme_dark_label;
      toggle.setAttribute('aria-label', label);
    };

    const applyTheme = theme => {
      const isDark = theme === 'dark';
      document.body.classList.toggle('dark', isDark);
      setAriaLabel(isDark);
    };

    const readStoredTheme = () => {
      try {
        return localStorage.getItem(storageKey);
      } catch (err) {
        return null;
      }
    };

    const writeStoredTheme = theme => {
      try {
        localStorage.setItem(storageKey, theme);
      } catch (err) {
        // ignore storage failures (private mode, etc.)
      }
    };

    const storedTheme = readStoredTheme();
    const initialTheme = storedTheme === 'dark' || storedTheme === 'light'
      ? storedTheme
      : 'light';
    applyTheme(initialTheme);

    toggle.addEventListener('click', () => {
      const nextTheme = document.body.classList.contains('dark')
        ? 'light'
        : 'dark';
      applyTheme(nextTheme);
      writeStoredTheme(nextTheme);
    });
  }


  // mobile menu toggle
  const menuToggle = document.getElementById('menu-toggle');
  const siteHeader = document.querySelector('.site-header');
  if (menuToggle && siteHeader) {
    menuToggle.addEventListener('click', () => {
      siteHeader.classList.toggle('nav-open');
    });
  }


  // =search functionality 
  const searchForm  = document.getElementById('searchForm');
  const searchInput = document.getElementById('globalSearch');
  const matchInfo   = document.getElementById('matchInfo');
  const resetBtn    = document.getElementById('resetSearch');
  let   matchIndex  = 0;
  let   matches     = [];

  function toggleResetButton() {
    resetBtn.hidden = searchInput.value.trim() === '';
  }

  toggleResetButton();

  function clearQueryParam() {
    const url = new URL(window.location);
    url.searchParams.delete('q');
    window.history.replaceState({}, '', url);
  }

  function clearHighlights() {
    document.querySelectorAll('.highlight').forEach(span => {
      const parent = span.parentNode;
      parent.replaceChild(
        document.createTextNode(span.textContent),
        span
      );
      parent.normalize();
    });
    matches = [];
    matchInfo.textContent = '';
  }

  function highlightMatches(term) {
    toggleResetButton();
    clearHighlights();
    if (!term) return;

    const regex = new RegExp(`(${term})`, 'gi');
    document.querySelectorAll('h2, h3, p, li')
      .forEach(el => {
        if (regex.test(el.textContent)) {
          el.innerHTML = el.textContent.replace(
            regex,
            '<span class="highlight">$1</span>'
          );
        }
      });
  
    matches = Array.from(document.querySelectorAll('.highlight'));
    if (matches.length) {
      matchIndex = 0;
      matches[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
  
      // singular / plural
      if (matches.length === 1) {
        matchInfo.textContent = i18n.match_one.replace("{count}", "1");
      } else {
        matchInfo.textContent = i18n.match_other.replace("{count}", matches.length);
      }
    } else {
      matchInfo.textContent = i18n.no_results;
    }
    clearQueryParam();
  }  
  

  const params = new URLSearchParams(window.location.search);
  const term   = params.get('q');
  if (term) {
    searchInput.value = term;
    highlightMatches(term);
  }

  searchInput.addEventListener('input', toggleResetButton);

  searchForm.addEventListener('submit', e => {
    e.preventDefault();
    const value = searchInput.value.trim();
  
    if (!value) {
      matchInfo.textContent = i18n.search_empty;
      matchInfo.classList.add('error-msg');
      searchInput.focus();
      return;
    }
  
    const url = new URL(window.location);
    url.searchParams.set('q', value);
    window.history.replaceState({}, '', url);
    highlightMatches(value);
  });
  

  // cycle to next match on enter
  searchInput.addEventListener('keydown', e => {
    if (e.key === 'Enter' && matches.length > 1) {
      e.preventDefault();
      matchIndex = (matchIndex + 1) % matches.length;
      matches[matchIndex].scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  });

  // reset search
  resetBtn.addEventListener('click', () => {
    searchInput.value = '';
    const url = new URL(window.location);
    url.searchParams.delete('q');
    window.history.replaceState({}, '', url);
    clearHighlights();
    toggleResetButton();
  });

  

  // AJAX projects
  document.querySelectorAll('.btn-view-desc').forEach(btn => {
    btn.addEventListener('click', async () => {
      const card = btn.closest('.card');
      const detail = card.querySelector('.detail');
      const id = card.dataset.id;
      if (detail.style.display === 'block') {
        detail.style.display = 'none';
        btn.textContent = i18n.view_desc;
        return;
      }
      btn.disabled = true;
      btn.textContent = i18n.loading;
      detail.innerHTML = `
        <div class="loading-indicator" role="status" aria-live="polite">
          <span class="spinner" aria-hidden="true"></span>
          <span class="loading-text">${i18n.loading}</span>
        </div>
      `;
      detail.style.display = 'block';
      try {
        const res = await fetch(`api/project_detail.php?id=${id}`);
        const json = await res.json();
        const { description, link, image, title } = json;
        detail.textContent = '';

        if (image) {
          const img = document.createElement('img');
          img.src = image;
          const fallbackTitle = title || '';
          const altTemplate = i18n.project_screenshot_alt || '';
          img.alt = altTemplate.replace('{title}', fallbackTitle);
          img.className = 'project-shot';
          detail.appendChild(img);
        }

        if (description) {
          const p = document.createElement('p');
          p.textContent = description;
          detail.appendChild(p);
        }

        if (link) {
          const anchor = document.createElement('a');
          anchor.href = link;
          anchor.target = '_blank';
          anchor.rel = 'noopener noreferrer';
          anchor.className = 'project-link';
          anchor.textContent = i18n.view_repo;
          detail.appendChild(anchor);
        }

        detail.style.display = 'block';
        btn.textContent = i18n.hide_desc;
      } catch {
        detail.textContent = 'Error loading.';
        detail.style.display = 'block';
        btn.textContent = i18n.hide_desc;
      } finally {
        btn.disabled = false;
      }
    });
  });

  // AJAX experience 
  document.querySelectorAll('.btn-view-desc-exp').forEach(btn => {
    btn.addEventListener('click', async () => {
      const card = btn.closest('.card');
      const detail = card.querySelector('.detail');
      const key = card.dataset.key;
      if (detail.style.display === 'block') {
        detail.style.display = 'none';
        btn.textContent = i18n.view_desc;
        return;
      }
      btn.disabled = true;
      btn.textContent = i18n.loading;
      detail.innerHTML = `
        <div class="loading-indicator" role="status" aria-live="polite">
          <span class="spinner" aria-hidden="true"></span>
          <span class="loading-text">${i18n.loading}</span>
        </div>
      `;
      detail.style.display = 'block';
      try {
        const res = await fetch(`api/experience_detail.php?key=${encodeURIComponent(key)}`);
        const json = await res.json();
        detail.innerHTML = '<ul>' + json.points.map(pt => `<li>${pt}</li>`).join('') + '</ul>';
        detail.style.display = 'block';
        btn.textContent = i18n.hide_desc;
      } catch {
        detail.textContent = 'Error loading.';
        detail.style.display = 'block';
        btn.textContent = i18n.hide_desc;
      } finally {
        btn.disabled = false;
      }
    });
  });

  // JS form validation (contact) 
const form = document.getElementById('contactForm');
if (form) {
  form.addEventListener('submit', e => {
        e.preventDefault();
    let valid = true;

    // clear previous errors
    form.querySelectorAll('input, textarea').forEach(el => {
      el.classList.remove('error');
    });
    form.querySelectorAll('.error-msg').forEach(span => {
      span.textContent = '';
    });

    function showError(el, msg) {
      el.classList.add('error');
      el.parentNode.querySelector('.error-msg').textContent = msg;
      valid = false;
    }

    const nameEl    = form.elements['name'];
    const emailEl   = form.elements['email'];
    const messageEl = form.elements['message'];

    // validate name
    if (!nameEl.value.trim()) {
      showError(nameEl, i18n.name_req);
    }

    // validate email
    const emailVal = emailEl.value.trim();
    const emailRx  = /^\S+@\S+\.\S+$/;
    if (!emailVal || !emailRx.test(emailVal)) {
      showError(emailEl, i18n.email_req);
    }

    // validate message
    if (!messageEl.value.trim()) {
      showError(messageEl, i18n.message_req);
    }

    if (valid) {
      form.submit();
    } else {
      form.querySelector('.error').focus();
    }
  });
}

});
