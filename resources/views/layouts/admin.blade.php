<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>
(function(){
  const stored = localStorage.getItem('theme');
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  const theme = stored || (prefersDark ? 'dark' : 'light');
  document.documentElement.setAttribute('data-bs-theme', theme);
})();
</script>
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
            --topbar-height: 70px;
            --transition-speed: 0.3s;
            
            /* Light Theme Variables */
            --bg-body: #f8fafc;
            --bg-sidebar: #ffffff;
            --bg-topbar: #ffffff;
            --bg-card: #ffffff;
            --text-main: #334155;
            --text-muted: #64748b;
            --border-color: #edf2f7;
            --primary-color: #667eea;
            --primary-hover: #764ba2;
            --sidebar-link-hover: rgba(102, 126, 234, 0.05);
        }

        [data-bs-theme="dark"] {
            --bg-body: #0f172a;
            --bg-sidebar: #1e293b;
            --bg-topbar: #1e293b;
            --bg-card: #1e293b;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --primary-color: #818cf8;
            --primary-hover: #a5b4fc;
            --sidebar-link-hover: rgba(129, 140, 248, 0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            overflow-x: hidden;
            transition: background-color var(--transition-speed), color var(--transition-speed);
        }

        .wrapper {
        /* Theme-aware header backgrounds */
        .card-header {
            background-color: var(--bg-card);
            color: var(--text-main);
        }
        .modal-header {
            background-color: var(--bg-card);
            color: var(--text-main);
        }
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        /* Sidebar Styles */
        .sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: var(--bg-sidebar);
            color: var(--text-main);
            transition: all var(--transition-speed) ease-in-out;
            height: 100vh;
            position: fixed;
            z-index: 1000;
            box-shadow: 4px 0 10px rgba(0,0,0,0.02);
            border-right: 1px solid var(--border-color);
            overflow-x: hidden;
        }

        .sidebar.collapsed {
            min-width: var(--sidebar-collapsed-width);
            max-width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 10px 20px;
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar.collapsed .sidebar-header {
            justify-content: center;
            padding: 10px 0;
        }

        .sidebar.collapsed .sidebar-header h4 {
            display: none !important;
        }

        .sidebar.collapsed .sidebar-header .sidebar-toggle {
            margin: 0;
            display: flex !important;
            justify-content: center;
            width: 100%;
        }

        .sidebar ul.components {
            padding: 20px 0;
        }

        .sidebar ul li a {
            padding: 12px 25px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            color: var(--text-muted);
            text-decoration: none;
            transition: 0.3s;
            font-weight: 500;
            white-space: nowrap;
        }

        .sidebar.collapsed ul li a {
            padding: 12px 0;
            justify-content: center;
        }

        .sidebar ul li a i {
            font-size: 1.2rem;
            min-width: 30px;
            text-align: center;
        }

        .sidebar.collapsed ul li a span,
        .sidebar.collapsed .sidebar-label {
            display: none;
        }

        .sidebar ul li a:hover, .sidebar ul li.active > a {
            color: var(--primary-color);
            background: var(--sidebar-link-hover);
            border-right: 3px solid var(--primary-color);
        }

        .sidebar-label {
            white-space: nowrap;
            overflow: hidden;
            color: var(--text-muted);
        }

        /* Content Styles */
        #content {
            width: 100%;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all var(--transition-speed) ease-in-out;
        }

        .sidebar.collapsed + #content {
            margin-left: var(--sidebar-collapsed-width);
        }

        .navbar {
            height: var(--topbar-height);
            background: var(--bg-topbar);
            border-bottom: 1px solid var(--border-color) !important;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
        }

        .main-content {
            padding: 30px;
        }

        .card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
            color: var(--text-main);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        /* Mobile Adjustments */
        .sidebar-overlay {
            display: none;
            position: fixed;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.4);
            z-index: 999;
            opacity: 0;
            transition: all 0.3s ease-in-out;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            .sidebar.active {
                margin-left: 0;
                box-shadow: 0 0 20px rgba(0,0,0,0.2);
            }
            #content {
                width: 100%;
                margin-left: 0;
            }
            .sidebar.collapsed + #content {
                margin-left: 0;
            }
        }
    </style>
    @stack('css')
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="wrapper">
        @include('layouts.partials.sidebar')

        <div id="content">
            @include('layouts.partials.topbar')

            <div class="main-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggles = document.querySelectorAll('.sidebar-toggle');
            const overlay = document.getElementById('sidebarOverlay');
            const themeToggle = document.getElementById('themeToggle');
            const html = document.documentElement;
            
            // Theme Management
            const getStoredTheme = () => localStorage.getItem('theme');
            const setStoredTheme = theme => localStorage.setItem('theme', theme);
            const getPreferredTheme = () => {
                const storedTheme = getStoredTheme();
                if (storedTheme) return storedTheme;
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            };

            const setTheme = theme => {
                html.setAttribute('data-bs-theme', theme);
                setStoredTheme(theme);
                updateThemeIcon(theme);
            };

            const updateThemeIcon = theme => {
                if (!themeToggle) return;
                const icon = themeToggle.querySelector('i');
                if (theme === 'dark') {
                    icon.classList.replace('bi-moon-stars', 'bi-sun');
                } else {
                    icon.classList.replace('bi-sun', 'bi-moon-stars');
                }
            };

            setTheme(getPreferredTheme());

            if (themeToggle) {
                themeToggle.addEventListener('click', () => {
                    const theme = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
                    setTheme(theme);
                });
            }

            // Sidebar Management
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed && window.innerWidth >= 992) {
                sidebar.classList.add('collapsed');
            }

            const toggleSidebar = () => {
                if (window.innerWidth >= 992) {
                    sidebar.classList.toggle('collapsed');
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
                } else {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                    sidebar.classList.remove('collapsed');
                }
            };

            toggles.forEach(btn => {
                btn.addEventListener('click', toggleSidebar);
            });

            if (overlay) {
                overlay.addEventListener('click', toggleSidebar);
            }

            // Handle tooltips
            const initTooltips = () => {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    const oldTooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
                    if (oldTooltip) oldTooltip.dispose();
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            };

            initTooltips();

            document.addEventListener('shown.bs.modal', function() {
                initTooltips();
            });
        });
    </script>
    @stack('js')
</body>
</html>
