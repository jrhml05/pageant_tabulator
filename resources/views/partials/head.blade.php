<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ isset($title) && $title ? $title . ' · ' : '' }}Mr. & Ms. LCUAA 2026 Tabulation</title>
<script>
    (() => {
        let theme = null;
        try { theme = localStorage.getItem('theme'); } catch {}
        if (!theme) theme = matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        document.documentElement.classList.toggle('dark', theme === 'dark');
    })();
</script>
@vite(['resources/css/app.css', 'resources/js/app.js'])
