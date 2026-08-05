<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        (function () {
            try {
                var t = localStorage.getItem('theme');
                if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    document.documentElement.setAttribute('data-bs-theme', 'dark');
                }
            } catch (e) {}
        })();
    </script>
    <title>@yield('title', 'TPV Peluquería') · {{ $empresaConfig->nombre ?? 'TPV Peluquería' }}</title>

    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cdefs%3E%3ClinearGradient id='g' x1='0' y1='0' x2='1' y2='1'%3E%3Cstop offset='0' stop-color='%23ec4899'/%3E%3Cstop offset='1' stop-color='%237c3aed'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect rx='20' fill='url(%23g)' width='100' height='100'/%3E%3Ctext x='50' y='68' font-family='Arial' font-size='52' font-weight='bold' fill='white' text-anchor='middle'%3ET%3C/text%3E%3C/svg%3E">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        @media (max-width: 991px) {
            .app-sidebar .brand-text { display: inline-flex !important; }
            .app-sidebar .nav-section-title { display: inline-block !important; }
            .app-sidebar .nav-link span { display: inline !important; }
            .app-sidebar .user-info { display: flex !important; }
            .app-sidebar .user-logout { display: inline-flex !important; }
        }
    </style>

    @stack('styles')
</head>
<body>
<div class="app-wrapper">

    <!-- Sidebar -->
    <aside class="app-sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo">
                @if($empresaConfig?->logo)
                    <img src="{{ asset('storage/' . $empresaConfig->logo) }}" alt="Logo">
                @else
                    <i class="bi bi-scissors"></i>
                @endif
            </div>
            <div class="brand-text">
                <span class="brand-title">{{ $empresaConfig->nombre ?? 'TPV Peluquería' }}</span>
                <span class="brand-subtitle">Sistema de Gestión</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <span class="nav-section-title">Principal</span>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('agenda.index') }}" class="nav-link {{ request()->routeIs('agenda.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar3"></i><span>Agenda</span>
                </a>
                <a href="{{ route('tpv.index') }}" class="nav-link {{ request()->routeIs('tpv.*') ? 'active' : '' }}">
                    <i class="bi bi-cash-coin"></i><span>TPV / Cobrar</span>
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-section-title">Gestión</span>
                <a href="{{ route('clientes.index') }}" class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i><span>Clientes</span>
                </a>
                <a href="{{ route('bonos.index') }}" class="nav-link {{ request()->routeIs('bonos.*') || request()->routeIs('tipos-bonos.*') ? 'active' : '' }}">
                    <i class="bi bi-ticket-perforated-fill"></i><span>Bonos</span>
                </a>
                <a href="{{ route('servicios.index') }}" class="nav-link {{ request()->routeIs('servicios.*') || request()->routeIs('categorias-servicios.*') ? 'active' : '' }}">
                    <i class="bi bi-stars"></i><span>Servicios</span>
                </a>
                <a href="{{ route('productos.index') }}" class="nav-link {{ request()->routeIs('productos.*') || request()->routeIs('categorias-productos.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam-fill"></i><span>Productos</span>
                </a>
                <a href="{{ route('empleados.index') }}" class="nav-link {{ request()->routeIs('empleados.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge-fill"></i><span>Equipo</span>
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-section-title">Caja & Ventas</span>
                <a href="{{ route('caja.index') }}" class="nav-link {{ request()->routeIs('caja.*') ? 'active' : '' }}">
                    <i class="bi bi-safe2-fill"></i><span>Caja</span>
                </a>
                <a href="{{ route('ventas.index') }}" class="nav-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt-cutoff"></i><span>Ventas</span>
                </a>
                <a href="{{ route('informes.index') }}" class="nav-link {{ request()->routeIs('informes.*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow"></i><span>Informes</span>
                </a>
            </div>

            @if(auth()->user()?->isAdmin())
            <div class="nav-section">
                <span class="nav-section-title">Sistema</span>
                <a href="{{ route('configuracion.index') }}" class="nav-link {{ request()->routeIs('configuracion.*') ? 'active' : '' }}">
                    <i class="bi bi-gear-fill"></i><span>Configuración</span>
                </a>
                <a href="{{ route('usuarios.index') }}" class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-lock-fill"></i><span>Usuarios</span>
                </a>
                <a href="{{ route('sistema.backup.index') }}" class="nav-link {{ request()->routeIs('sistema.*') ? 'active' : '' }}">
                    <i class="bi bi-cloud-arrow-down-fill"></i><span>Backup & Reset</span>
                </a>
            </div>
            @endif
        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
                <div class="user-info">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <span class="user-role">{{ ucfirst(auth()->user()->rol) }}</span>
                </div>
                <button type="button" id="temaBtn" class="theme-toggle" title="Cambiar tema">
                    <i class="bi bi-moon-stars-fill" id="temaIcon"></i>
                </button>
                <form action="{{ route('logout') }}" method="POST" class="user-logout">
                    @csrf
                    <button type="submit" title="Cerrar sesión"><i class="bi bi-box-arrow-right"></i></button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main -->
    <div class="app-main">
        <header class="app-topbar">
            <button class="btn-toggle-sidebar" id="btnToggleSidebar" aria-label="Menú">
                <i class="bi bi-list"></i>
            </button>
            <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            <div class="topbar-actions">
                <div class="search-bar">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Buscar cliente, producto...">
                </div>
                <button class="topbar-btn" title="Notificaciones">
                    <i class="bi bi-bell-fill"></i>
                    <span class="badge-dot"></span>
                </button>
                <a href="{{ route('agenda.index') }}" class="topbar-btn" title="Agenda">
                    <i class="bi bi-calendar-event"></i>
                </a>
            </div>
        </header>

        <main class="app-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-x-circle-fill me-2"></i><strong>Revisa los datos:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
@include('partials.whatsapp-float')
@stack('scripts')
<script>
    (function () {
        var root = document.documentElement;
        var btn = document.getElementById('temaBtn');
        var icon = document.getElementById('temaIcon');
        if (!btn || !icon) return;
        function actualizarIcono() {
            icon.className = root.getAttribute('data-theme') === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
        }
        btn.addEventListener('click', function () {
            var oscuro = root.getAttribute('data-theme') === 'dark';
            if (oscuro) {
                root.removeAttribute('data-theme');
                root.removeAttribute('data-bs-theme');
                localStorage.setItem('theme', 'light');
            } else {
                root.setAttribute('data-theme', 'dark');
                root.setAttribute('data-bs-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            }
            actualizarIcono();
        });
        actualizarIcono();
    })();
</script>
<script>
        (function () {
            var sidebar = document.getElementById('sidebar');
            var btn = document.getElementById('btnToggleSidebar');
            var appMain = document.querySelector('.app-main');
            if (!sidebar || !btn) return;
            var overlay = document.createElement('div');
            overlay.id = 'sidebarOverlay';
            overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:999;display:none;';
            document.body.appendChild(overlay);
            var esMovil = function () { return window.innerWidth <= 991; };
            function abMenu(abierto) {
                if (abierto) {
                    sidebar.style.transform = 'translateX(0)';
                    sidebar.style.boxShadow = '0 0 40px rgba(0,0,0,.35)';
                    sidebar.style.width = 'var(--sidebar-width, 260px)';
                    sidebar.style.transition = 'transform .25s ease, box-shadow .25s ease';
                } else {
                    sidebar.style.transform = 'translateX(-100%)';
                    sidebar.style.boxShadow = 'none';
                    sidebar.style.transition = 'transform .25s ease, box-shadow .25s ease';
                }
            }
            btn.addEventListener('click', function () {
                if (esMovil()) {
                    var abierto = sidebar.style.transform === 'translateX(0px)' || sidebar.classList.contains('mobile-open');
                    sidebar.classList.toggle('mobile-open', !abierto);
                    abierto = !abierto;
                    overlay.style.display = abierto ? 'block' : 'none';
                    abMenu(abierto);
                } else {
                    sidebar.classList.toggle('collapsed');
                }
            });
            overlay.addEventListener('click', function () {
                cerrar();
            });
            function cerrar() {
                sidebar.classList.remove('mobile-open');
                overlay.style.display = 'none';
                abMenu(false);
            }
            window.addEventListener('resize', function () {
                if (!esMovil()) {
                    sidebar.classList.remove('mobile-open');
                    sidebar.style.transform = '';
                    sidebar.style.boxShadow = '';
                    sidebar.style.transition = '';
                    sidebar.style.width = '';
                    overlay.style.display = 'none';
                    if (appMain) appMain.style.marginLeft = '';
                } else {
                    if (appMain) appMain.style.marginLeft = '0';
                }
            });
            if (esMovil()) { abMenu(false); if (appMain) appMain.style.marginLeft = '0'; }
        })();
    </script>
</body>
</html>
