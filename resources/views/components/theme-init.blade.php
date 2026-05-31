@props(['sidebar' => false])

@auth
<meta name="user-theme" content="{{ auth()->user()->theme_color ?? 'blue' }}">
@endauth
<script>
    (function() {
        @auth
        window.__isAuthenticated = true;
        window.__userTheme = @json(auth()->user()->theme_color ?? 'blue');
        localStorage.setItem('gestorpro-theme', window.__userTheme);
        @else
        window.__isAuthenticated = false;
        @endauth

        const darkMode = localStorage.getItem('gestorpro-dark-mode');
        const theme = window.__isAuthenticated
            ? window.__userTheme
            : (localStorage.getItem('gestorpro-theme') || 'blue');

        @if($sidebar)
        const sidebarCollapsed = localStorage.getItem('sidebar-collapsed');
        document.documentElement.setAttribute('data-sidebar-collapsed', sidebarCollapsed === 'true' ? 'true' : 'false');
        @endif

        if (darkMode === 'true' || (darkMode === null && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }

        if (theme && theme !== 'blue') {
            document.documentElement.setAttribute('data-theme', theme);
        } else {
            document.documentElement.removeAttribute('data-theme');
        }
    })();
</script>
