<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $empresa?->nombre ?? 'Salón de Belleza' }} - Servicios de estética, peluquería y bienestar.">
    <title>{{ $empresa?->nombre ?? 'Salón de Belleza' }}</title>

    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cdefs%3E%3ClinearGradient id='g' x1='0' y1='0' x2='1' y2='1'%3E%3Cstop offset='0' stop-color='%23ec4899'/%3E%3Cstop offset='1' stop-color='%237c3aed'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect rx='20' fill='url(%23g)' width='100' height='100'/%3E%3Ctext x='50' y='68' font-family='Arial' font-size='52' font-weight='bold' fill='white' text-anchor='middle'%3E%3C/text%3E%3C/svg%3E">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --rosa: #ec4899;
            --violeta: #7c3aed;
            --oscuro: #1e1b2e;
            --claro: #faf7ff;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #333;
            background: var(--claro);
        }
        html { scroll-behavior: smooth; }
        section[id], header[id] { scroll-margin-top: 80px; }
        h1, h2, h3, .serif { font-family: 'Playfair Display', serif; }
        .navbar-web {
            background: rgba(30, 27, 46, .92);
            backdrop-filter: blur(10px);
            padding: .9rem 0;
        }
        .navbar-web .navbar-brand {
            color: #fff;
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            letter-spacing: .5px;
        }
        .navbar-web .btn-login {
            color: #fff;
            border: 1px solid rgba(255,255,255,.35);
            border-radius: 50px;
            font-weight: 600;
        }
        .navbar-web .btn-login:hover { background: rgba(255,255,255,.15); color: #fff; }
        .link-nav { color: #fff !important; font-weight: 600; opacity: .9; }
        .link-nav:hover { opacity: 1; }
        .navbar-toggler:focus { box-shadow: none; }
        @media (max-width: 991.98px) {
            .navbar-web .navbar-collapse {
                background: rgba(30, 27, 46, .97);
                border-radius: 16px;
                padding: 1rem 1.25rem;
                margin-top: .75rem;
                box-shadow: 0 12px 30px rgba(0,0,0,.3);
            }
        }

        .hero {
            position: relative;
            background: linear-gradient(135deg, #1e1b2e 0%, #3b2a5f 55%, #7c3aed 100%);
            color: #fff;
            overflow: hidden;
            height: 100vh;
            min-height: 560px;
        }
        .hero-carousel { position: absolute; inset: 0; }
        .hero-carousel .carousel-inner, .hero-carousel .carousel-item { height: 100%; }
        .hero-slide {
            position: absolute; inset: 0;
            background-size: cover; background-position: center;
        }
        .hero-slide .overlay {
            position: absolute; inset: 0;
            background: linear-gradient(100deg, rgba(30,27,46,.92) 0%, rgba(59,42,95,.72) 45%, rgba(124,58,237,.35) 100%);
        }
        .hero-content {
            position: relative; z-index: 3;
            height: 100%;
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }
        .hero-carousel .carousel-indicators { z-index: 4; }
        .hero-carousel .carousel-indicators [data-bs-target] {
            width: 34px; height: 4px; border-radius: 4px; border: 0; background: rgba(255,255,255,.6);
        }
        .hero-carousel .carousel-indicators .active { background: #fff; }
        @media (max-width: 991.98px) {
            .hero-content {
                min-height: 85vh;
                display: flex;
                align-items: center;
                padding: 2rem 0;
            }
            .hero-content .row { width: 100%; }
            .hero-content .col-lg-7 { text-align: center; }
            .hero-content .d-flex { justify-content: center; }
            .hero-content .hero-eta { font-size: .72rem; }
        }
        .hero::before { display: none; }
        .hero .btn-cita {
            background: linear-gradient(90deg, var(--rosa), var(--violeta));
            color: #fff; border: none; border-radius: 50px;
            font-weight: 700; padding: .9rem 2rem;
            box-shadow: 0 10px 25px rgba(236,72,153,.4);
        }
        .hero .btn-cita:hover { transform: translateY(-2px); box-shadow: 0 15px 30px rgba(124,58,237,.45); }
        .hero-etiq { letter-spacing: 4px; text-transform: uppercase; font-size: .8rem; opacity: .8; }
        .hero h1 { font-size: clamp(2.4rem, 5vw, 4rem); font-weight: 800; }
        .stat-num { font-family: 'Playfair Display', serif; font-size: 2.2rem; font-weight: 700; }

        section { padding: 5rem 0; }
        .section-tag { color: var(--rosa); font-weight: 700; letter-spacing: 3px; text-transform: uppercase; font-size: .8rem; }

        .card-serv {
            border: none; border-radius: 20px; overflow: hidden;
            background: #fff; box-shadow: 0 8px 25px rgba(124,58,237,.08);
            transition: .25s; height: 100%;
        }
        .card-serv:hover { transform: translateY(-6px); box-shadow: 0 15px 35px rgba(124,58,237,.18); }
        .icon-wrap {
            width: 52px; height: 52px; border-radius: 14px;
            display: grid; place-items: center; font-size: 1.4rem; color: #fff;
            background: linear-gradient(135deg, var(--rosa), var(--violeta));
        }
        .card-serv .precio { color: var(--violeta); font-weight: 800; font-size: 1.2rem; }
        .card-serv .duracion { font-size: .82rem; color: #888; }

        .sobre {
            background: #fff;
        }
        .sobre-lista i { color: var(--rosa); }

        .cta-band {
            background: linear-gradient(120deg, var(--rosa), var(--violeta));
            color: #fff; border-radius: 24px; padding: 3.5rem 2rem;
        }
        .cta-band .btn { background: #fff; color: var(--violeta); border-radius: 50px; font-weight: 700; }

        .contacto-card { border-radius: 18px; background: #fff; box-shadow: 0 8px 25px rgba(124,58,237,.08); }
        .contacto-card i { color: var(--rosa); }

        .web-footer {
            background: var(--oscuro); color: #ccc; padding: 2.5rem 0 1.5rem;
        }
        .web-footer a { color: #fff; text-decoration: none; font-weight: 600; }

        .whatsapp-float {
            position: fixed;
            right: 22px;
            bottom: 22px;
            z-index: 999;
            width: 58px; height: 58px;
            border-radius: 50%;
            background: #25d366;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            box-shadow: 0 8px 20px rgba(37, 211, 102, .45);
            transition: transform .2s, box-shadow .2s;
        }
        .whatsapp-float:hover { transform: scale(1.08); box-shadow: 0 12px 26px rgba(37, 211, 102, .55); color: #fff; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top navbar-web">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="bi bi-scissors me-2"></i>{{ $empresa?->nombre ?? 'Salón de Belleza' }}
        </a>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('login') }}" class="btn btn-login d-none d-lg-inline-flex px-4">
                <i class="bi bi-person-circle me-1"></i>Acceso
            </a>
            <button class="navbar-toggler navbar-toggler-custom border-0" id="navToggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#menuWeb" aria-controls="menuWeb" aria-expanded="false" aria-label="Abrir menú">
                <i class="bi bi-list fs-2 text-white"></i>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="menuWeb">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3 mt-3 mt-lg-0">
                <li class="nav-item"><a class="nav-link link-nav" href="#servicios">Servicios</a></li>
                <li class="nav-item"><a class="nav-link link-nav" href="#sobre">Nosotros</a></li>
                <li class="nav-item"><a class="nav-link link-nav" href="#reservar">Reservar</a></li>
                <li class="nav-item"><a class="nav-link link-nav" href="#contacto">Contacto</a></li>
                <li class="nav-item d-lg-none">
                    <a href="{{ route('login') }}" class="btn btn-login px-4 d-inline-block"><i class="bi bi-person-circle me-1"></i>Acceso</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- HERO -->
<header class="hero" id="inicio">
    <div id="heroSlider" class="carousel slide carousel-fade hero-carousel" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Diapositiva 1"></button>
            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="1" aria-label="Diapositiva 2"></button>
            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="2" aria-label="Diapositiva 3"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="hero-slide" style="background-image:url('https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=1920&q=80')"><div class="overlay"></div></div>
                <div class="container hero-content">
                    <div class="row">
                        <div class="col-lg-7">
                            <p class="hero-eta mb-3">Belleza · Estética · Bienestar</p>
                            <h1 class="mb-4">Realza tu belleza con los mejores profesionales</h1>
                            <p class="fs-5 mb-4" style="color:#e6dcff">Cortes, peinados y tratamientos a tu medida.</p>
                            @if($empresa?->ciudad)<small class="d-block mb-4" style="color:#e6dcff"><i class="bi bi-geo-alt me-1"></i>{{ $empresa->ciudad }}</small>@endif
                            <div class="d-flex flex-wrap gap-3">
                                <a href="#servicios" class="btn btn-cita btn-lg px-4">Ver servicios</a>
                                <a href="#reservar" class="btn btn-outline-light btn-lg px-4 rounded-pill"><i class="bi bi-calendar2-heart me-1"></i>Reserva tu cita</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="carousel-item">
                <div class="hero-slide" style="background-image:url('https://images.unsplash.com/photo-1522337660859-02fbefca4702?auto=format&fit=crop&w=1920&q=80')"><div class="overlay"></div></div>
                <div class="container hero-content">
                    <div class="row">
                        <div class="col-lg-7">
                            <p class="hero-eta mb-3">Color · Mechas · Tratamientos</p>
                            <h1 class="mb-4">Transformación que se nota</h1>
                            <p class="fs-5 mb-4" style="color:#e6dcff">Color, mechas y tratamientos con productos de primeras marcas.</p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="#servicios" class="btn btn-cita btn-lg px-4">Ver servicios</a>
                                <a href="#reservar" class="btn btn-outline-light btn-lg px-4 rounded-pill"><i class="bi bi-calendar2-heart me-1"></i>Reserva tu cita</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="carousel-item">
                <div class="hero-slide" style="background-image:url('https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1920&q=80')"><div class="overlay"></div></div>
                <div class="container hero-content">
                    <div class="row">
                        <div class="col-lg-7">
                            <p class="hero-eta mb-3">Manos · Pies · Bienestar</p>
                            <h1 class="mb-4">Reserva tu cita y déjate mimar</h1>
                            <p class="fs-5 mb-4" style="color:#e6dcff">Manicura, pedicura y tratamientos de relax pensados para ti.</p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="#reservar" class="btn btn-cita btn-lg px-4"><i class="bi bi-calendar2-heart me-1"></i>Reservar</a>
                                <a href="#servicios" class="btn btn-outline-light btn-lg px-4 rounded-pill">Ver servicios</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
</header>

<!-- SERVICIOS -->
<section id="servicios">
    <div class="container">
        <div class="text-center mb-5">
            <p class="section-tag">Nuestros servicios</p>
            <h2 class="display-6 fw-bold">Cuidamos de ti y de tu belleza</h2>
            <p class="text-muted">Tarifas orientativas. El presupuesto final se confirma en el salón.</p>
        </div>
        @forelse($categorias as $categoria)
            <div class="mb-5">
                <h3 class="h4 mb-3 d-flex align-items-center gap-2">
                    <i class="bi {{ $categoria->icono ?? 'bi-stars' }}" style="color:{{ $categoria->color ?? '#ec4899' }}"></i>
                    {{ $categoria->nombre }}
                </h3>
                <div class="row g-3">
                    @foreach($categoria->servicios as $servicio)
                        <div class="col-sm-6 col-lg-4">
                            <div class="card card-serv p-4">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="icon-wrap"><i class="bi {{ $categoria->icono ?? 'bi-stars' }}"></i></div>
                                    <h5 class="mb-0 flex-grow-1">{{ $servicio->nombre }}</h5>
                                </div>
                                @if($servicio->descripcion)
                                    <p class="text-muted mb-3 small">{{ $servicio->descripcion }}</p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="precio">{{ $empresa?->simbolo_moneda ?? 'S/.' }} {{ number_format($servicio->precio, 2) }}</span>
                                    <span class="duracion"><i class="bi bi-clock me-1"></i>{{ $servicio->duracion_formateada }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-center text-muted">Próximamente publicaremos nuestro catálogo de servicios.</p>
        @endforelse
    </div>
</section>

<!-- SOBRE NOSOTROS -->
<section class="sobre" id="sobre">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <p class="section-tag">Sobre nosotros</p>
                <h2 class="fw-bold mb-4">Donde la profesionalidad se encuentra con la pasión</h2>
                <p class="text-muted">
                    En {{ $empresa?->nombre ?? 'nuestro salón' }} combinamos técnica, los mejores productos y un trato cercano
                    para ofrecerte una experiencia única de cuidado personal.
                </p>
                <ul class="list-unstyled mt-4 fs-5">
                    <li class="mb-3"><i class="bi bi-check-circle-fill me-2"></i>Profesionales certificados y en formación continua</li>
                    <li class="mb-3"><i class="bi bi-check-circle-fill me-2"></i>Productos de primeras marcas</li>
                    <li class="mb-3"><i class="bi bi-check-circle-fill me-2"></i>Atención personalizada y asesoría</li>
                    <li class="mb-3"><i class="bi bi-check-circle-fill me-2"></i>Ambiente acogedor y seguro</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="row g-3 text-center">
                    <div class="col-6"><div class="contacto-card p-4"><div class="icon-wrap mx-auto mb-2"><i class="bi bi-trophy"></i></div><h5 class="mb-1">Calidad</h5><p class="text-muted mb-0 small">Los mejores tratamientos</p></div></div>
                    <div class="col-6"><div class="contacto-card p-4"><div class="icon-wrap mx-auto mb-2"><i class="bi bi-emoji-smile"></i></div><h5 class="mb-1">Cercanía</h5><p class="text-muted mb-0 small">Trato personalizado</p></div></div>
                    <div class="col-6"><div class="contacto-card p-4"><div class="icon-wrap mx-auto mb-2"><i class="bi bi-gem"></i></div><h5 class="mb-1">Productos</h5><p class="text-muted mb-0 small">Primeras marcas</p></div></div>
                    <div class="col-6"><div class="contacto-card p-4"><div class="icon-wrap mx-auto mb-2"><i class="bi bi-shield-check"></i></div><h5 class="mb-1">Higiene</h5><p class="text-muted mb-0 small">Protocolo garantizado</p></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section id="reservar">
    <div class="container">
        <div class="cta-band text-center">
            <h2 class="mb-3">¿Lista para transformarte?</h2>
            <p class="fs-5 mb-4">Reserva tu cita hoy y déjate mimar.</p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="tel:+51916377263" class="btn btn-lg px-4"><i class="bi bi-telephone me-1"></i>+51 916 377 263</a>
                <a href="{{ route('login') }}" class="btn btn-lg btn-cita px-4"><i class="bi bi-calendar2-heart me-1"></i>Reservar</a>
            </div>
        </div>
    </div>
</section>

<!-- CONTACTO -->
<section id="contacto" class="pt-0">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="contacto-card p-4 h-100">
                    <i class="bi bi-geo-alt fs-3 mb-2 d-block"></i>
                    <h5 class="fw-bold">Dirección</h5>
                    <p class="text-muted mb-0">{{ $empresa?->direccion }}<br>{{ $empresa?->ciudad }}, {{ $empresa?->provincia }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contacto-card p-4 h-100">
                    <i class="bi bi-telephone fs-3 mb-2 d-block"></i>
                    <h5 class="fw-bold">Contacto</h5>
                    <p class="text-muted mb-0">+51 916 377 263<br>{{ $empresa?->email }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contacto-card p-4 h-100">
                    <i class="bi bi-clock fs-3 mb-2 d-block"></i>
                    <h5 class="fw-bold">Horario</h5>
                    <p class="text-muted mb-0">{{ $empresa?->hora_apertura ? $empresa->hora_apertura . ' - ' . $empresa->hora_cierre : 'Consultar en el salón' }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="web-footer">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <i class="bi bi-scissors me-2"></i><span class="fw-bold text-white">{{ $empresa?->nombre ?? 'Salón de Belleza' }}</span>
                <p class="mb-0 mt-2 small">Sistema de gestión profesional para tu salón.</p>
            </div>
            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                <a href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i>Acceder al sistema</a>
            </div>
        </div>
        <hr class="border-secondary mt-4 mb-3">
        <p class="mb-0 text-center small opacity-75">© {{ date('Y') }} {{ $empresa?->nombre ?? 'Salón de Belleza' }} · Todos los derechos reservados.</p>
    </div>
</footer>

@include('partials.whatsapp-float')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    var carousel = document.getElementById('heroSlider');
    if (carousel && window.bootstrap) {
        new bootstrap.Carousel(carousel, { interval: 4000, ride: 'carousel', wrap: true, touch: true, pause: false });
    }
})();
</script>
<script>
(function () {
    var menu = document.getElementById('menuWeb');
    var nav = document.querySelector('.navbar-web');
    if (!menu || !window.bootstrap) return;
    var collapse = bootstrap.Collapse.getOrCreateInstance(menu, { toggle: false });

    menu.querySelectorAll('.link-nav').forEach(function (link) {
        link.addEventListener('click', function () {
            if (menu.classList.contains('show')) collapse.hide();
        });
    });

    document.addEventListener('click', function (e) {
        if (menu.classList.contains('show') && !nav.contains(e.target)) collapse.hide();
    });
})();
</script>
</body>
</html>