const THEME_KEY = 'theme';

function applyTheme(theme) {
    document.documentElement.classList.toggle('dark', theme === 'dark');
    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        const dark = theme === 'dark';
        button.setAttribute('aria-pressed', String(dark));
        button.setAttribute('aria-label', dark ? 'Switch to light theme' : 'Switch to dark theme');
    });
}

function currentTheme() {
    return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
}

function setDrawer(open) {
    const drawer = document.getElementById('admin-drawer');
    if (!drawer) return;
    drawer.dataset.open = String(open);
    document.querySelectorAll('[data-drawer-toggle]').forEach((button) => {
        button.setAttribute('aria-expanded', String(open));
    });
    if (open) drawer.querySelector('aside a')?.focus();
}

// Ranking and seeding endpoints are plain GETs that rewrite tables server-side,
// so the page reloads to show the new ranks once the request succeeds.
async function runAction(button) {
    const status = document.getElementById(button.dataset.statusTarget ?? 'action-status');
    if (button.dataset.confirm && !window.confirm(button.dataset.confirm)) return;

    const label = button.querySelector('[data-label]');
    const idleText = label?.textContent;
    button.disabled = true;
    button.setAttribute('aria-busy', 'true');
    if (label) label.textContent = button.dataset.busyLabel ?? 'Working…';
    if (status) status.textContent = '';

    try {
        const response = await fetch(button.dataset.actionUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        // 401/419 or a redirect to the login page: the session expired.
        if (response.redirected || [401, 419].includes(response.status)) throw new Error('you are signed out, sign in again');
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        window.location.reload();
    } catch (error) {
        button.disabled = false;
        button.removeAttribute('aria-busy');
        if (label) label.textContent = idleText;
        if (status) status.textContent = `${button.dataset.errorLabel ?? 'That action'} failed (${error.message}). Nothing was changed on this page; try again.`;
    }
}

document.addEventListener('click', (event) => {
    const themeButton = event.target.closest('[data-theme-toggle]');
    if (themeButton) {
        const next = currentTheme() === 'dark' ? 'light' : 'dark';
        try {
            localStorage.setItem(THEME_KEY, next);
        } catch {}
        applyTheme(next);
        return;
    }

    const drawerToggle = event.target.closest('[data-drawer-toggle]');
    if (drawerToggle) {
        setDrawer(drawerToggle.getAttribute('aria-expanded') !== 'true');
        return;
    }

    if (event.target.closest('[data-drawer-close]')) {
        setDrawer(false);
        return;
    }

    const dismiss = event.target.closest('[data-dismiss]');
    if (dismiss) {
        dismiss.closest('[data-dismissible]')?.remove();
        return;
    }

    const action = event.target.closest('[data-action-url]');
    if (action && !action.disabled) {
        event.preventDefault();
        runAction(action);
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;
    const drawer = document.getElementById('admin-drawer');
    if (drawer?.dataset.open === 'true') {
        setDrawer(false);
        document.querySelector('[data-drawer-toggle]')?.focus();
    }
});

applyTheme(currentTheme());
