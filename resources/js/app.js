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

const REFRESH_MS = 5000;

class SignedOutError extends Error {
    constructor() {
        super('you are signed out, sign in again');
    }
}

async function request(url) {
    const response = await fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        cache: 'no-store',
    });
    // 401/419 or a redirect to the login page: the session expired.
    if (response.redirected || [401, 419].includes(response.status)) throw new SignedOutError();
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    return response;
}

function setLiveNote(text) {
    const note = document.querySelector('[data-live-note]');
    if (note) note.textContent = text;
}

// Report tables are server-rendered Blade, so a refresh re-requests this page and
// swaps in each [data-refresh-region] by position. Only regions whose markup
// changed are replaced, which keeps horizontal scroll and text selection intact.
let refreshSeq = 0;
let refreshTimer = null;

async function refreshTables() {
    const seq = ++refreshSeq;
    const html = await (await request(window.location.href)).text();
    // A later refresh already won.
    if (seq !== refreshSeq) return;

    const fresh = new DOMParser().parseFromString(html, 'text/html').querySelectorAll('[data-refresh-region]');
    const regions = document.querySelectorAll('[data-refresh-region]');
    // The page layout changed (e.g. the final's empty state became a table).
    if (fresh.length !== regions.length) {
        window.location.reload();
        return;
    }
    regions.forEach((region, i) => {
        if (region.innerHTML !== fresh[i].innerHTML) region.innerHTML = fresh[i].innerHTML;
    });

    const time = document.querySelector('[data-live-time]');
    if (time) time.textContent = `Updated ${new Date().toLocaleTimeString()}`;
}

let pollFailing = false;

async function pollTables() {
    clearTimeout(refreshTimer);
    if (document.hidden) return;
    try {
        await refreshTables();
        if (pollFailing) setLiveNote('');
        pollFailing = false;
    } catch (error) {
        pollFailing = true;
        if (error instanceof SignedOutError) {
            setLiveNote('Signed out. Sign in again to see new scores.');
            return;
        }
        setLiveNote("Can't reach the server. Scores shown may be out of date; retrying.");
    }
    refreshTimer = setTimeout(pollTables, REFRESH_MS);
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
});

// Forms that delete or close something ask first.
document.addEventListener('submit', (event) => {
    const message = event.target.dataset?.confirm;
    if (message && !window.confirm(message)) event.preventDefault();
});

document.addEventListener('keydown', (event) => {
    // On the score sheet, Enter (the tablet keyboard's "next" key) moves to the next score box.
    if (event.key === 'Enter' && event.target.matches?.('[data-score-input]')) {
        event.preventDefault();
        const inputs = [...document.querySelectorAll('[data-score-input]:not(:disabled)')];
        inputs[inputs.indexOf(event.target) + 1]?.focus();
        return;
    }

    if (event.key !== 'Escape') return;
    const drawer = document.getElementById('admin-drawer');
    if (drawer?.dataset.open === 'true') {
        setDrawer(false);
        document.querySelector('[data-drawer-toggle]')?.focus();
    }
});

applyTheme(currentTheme());

if (document.querySelector('[data-live-report]')) {
    const time = document.querySelector('[data-live-time]');
    if (time) time.textContent = `Updated ${new Date().toLocaleTimeString()}`;
    refreshTimer = setTimeout(pollTables, REFRESH_MS);
    // Background tabs stop polling; catch up as soon as the tab is shown again.
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) pollTables();
    });
}

// Judge tablets: notice when the tabulator opens or closes a segment. The waiting screen
// jumps straight to the new sheet; a sheet in progress shows a banner instead, so a judge
// is never pulled away mid-entry.
const JUDGE_POLL_MS = 4000;
const watch = document.body.dataset.judgeWatch;

async function pollSegments() {
    try {
        const { open } = await (await request(watch)).json();
        const shown = JSON.parse(document.body.dataset.open || '[]');
        const waiting = document.body.dataset.judgeWaiting;

        if (waiting && open.length) {
            window.location.assign(waiting);
            return;
        }

        const banner = document.querySelector('[data-segment-banner]');
        const text = document.querySelector('[data-segment-banner-text]');
        const opened = open.filter((name) => !shown.includes(name));
        const closed = shown.filter((name) => !open.includes(name));

        if (banner && text && !waiting && (opened.length || closed.length)) {
            text.textContent = opened.length
                ? `${opened.join(' and ')} ${opened.length > 1 ? 'are' : 'is'} now open.`
                : `The tabulator closed ${closed.join(' and ')}.`;
            banner.hidden = false;
        }
    } catch (error) {
        if (error instanceof SignedOutError) {
            window.location.reload();
            return;
        }
        // Network blips are shown by Livewire's offline notice; keep polling.
    }
    setTimeout(pollSegments, JUDGE_POLL_MS);
}

if (watch) setTimeout(pollSegments, JUDGE_POLL_MS);
