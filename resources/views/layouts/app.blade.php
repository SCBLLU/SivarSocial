<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="theme-color" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @stack('styles')
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <title>SivarSocial</title>
    @vite(['resources/css/app.css', 'resources/css/menu-mobile.css', 'resources/js/app.js'])

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @livewireStyles()
    <script src="https://kit.fontawesome.com/6305bb531f.js" crossorigin="anonymous"></script>

    <!-- Estilos para prevenir flash de contenido Alpine.js -->
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Prevenir flash de contenido Alpine.js */
        [x-show]:not([style*="display: none"]) {
            visibility: visible !important;
        }

        [x-show][style*="display: none"] {
            display: none !important;
            visibility: hidden !important;
        }

        /* Asegurar que los menús desplegables estén ocultos inicialmente */
        .dropdown-menu[x-show] {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }

        /* Ocultar específicamente el dropdown cuando tiene x-show y no está activo */
        .dropdown-menu[x-show][style*="display: none"] {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }

        /* Forzar estado inicial para cualquier elemento con Alpine.js */
        [x-data] .dropdown-menu {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }
    </style>
</head>

<body style="background-color: #0f02a4; color: white;">

    {{-- olas animadas --}}
    <div class="wave-background">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            style="margin: auto; background: rgba(255, 255, 255,0); display: block; shape-rendering: auto;"
            class="wave-svg h-[180px] md:h-[320px] lg:h-[400px] w-full" preserveAspectRatio="xMidYMid slice"
            viewBox="0 0 1980 600">
            <g transform="">
                <linearGradient id="lg-0.8589700868456098" x1="0" x2="1" y1="0" y2="0">
                    <stop stop-color="#e3f1ff" offset="0"></stop>
                    <stop stop-color="#4b00fd" offset="0"></stop>
                    <stop stop-color="#1b75be" offset="1"></stop>
                </linearGradient>
                <path
                    d="M 0 0 L 0 483.83 Q 165 499.45 330 431.346 T 660 416.36 T 990 398.11 T 1320 417.3 T 1650 460.606 T 1980 422.928 L 1980 0 Z"
                    fill="url(#lg-0.8589700868456098)" opacity="0.4">
                    <animate attributeName="d" dur="14.285714285714285s" repeatCount="indefinite"
                        keyTimes="0;0.333;0.667;1" calcmod="spline" keySplines="0.2 0 0.2 1;0.2 0 0.2 1;0.2 0 0.2 1"
                        begin="0s"
                        values="M0 0L 0 243.84212793573954Q 165 244.198481323735  330 214.97043919697492T 660 203.02580743298734T 990 199.82321083516976T 1320 201.96793159645011T 1650 230.24393184683908T 1980 207.67099842439933L 1980 0 Z;M0 0L 0 235.0618328876254Q 165 269.3721943745703  330 218.17211425063454T 660 226.50305342938853T 990 196.3260923575501T 1320 232.4077939064989T 1650 230.51147502836807T 1980 224.94661631212443L 1980 0 Z;M0 0L 0 205.27226479494266Q 165 240.5155728470433  330 212.79915181526675T 660 213.58512293669696T 990 212.32102514981105T 1320 204.89820182351832T 1650 246.2686309045961T 1980 215.49084241837852L 1980 0 Z;M0 0L 0 243.84212793573954Q 165 244.198481323735  330 214.97043919697492T 660 203.02580743298734T 990 199.82321083516976T 1320 201.96793159645011T 1650 230.24393184683908T 1980 207.67099842439933L 1980 0 Z">
                    </animate>
                </path>
                <path
                    d="M 0 0 L 0 454.064 Q 165 524.25 330 476.306 T 660 415.47 T 990 441.096 T 1320 456.284 T 1650 423.084 T 1980 472.882 L 1980 0 Z"
                    fill="url(#lg-0.8589700868456098)" opacity="0.4">
                    <animate attributeName="d" dur="14.285714285714285s" repeatCount="indefinite"
                        keyTimes="0;0.333;0.667;1" calcmod="spline" keySplines="0.2 0 0.2 1;0.2 0 0.2 1;0.2 0 0.2 1"
                        begin="-2.8571428571428568s"
                        values="M0 0L 0 198.91548309391138Q 165 261.3922476818655  330 234.30112068896972T 660 204.3221098374849T 990 197.40719560146192T 1320 210.24404882431836T 1650 221.90110529290098T 1980 229.98865514501918L 1980 0 Z;M0 0L 0 233.19830897370343Q 165 262.28508633762743  330 238.99758623407547T 660 208.4840242477092T 990 225.62292636527536T 1320 232.06758779859936T 1650 209.26990298551567T 1980 237.85581571111973L 1980 0 Z;M0 0L 0 214.6550175761586Q 165 255.77933395353938  330 205.58046331850787T 660 248.3458831575966T 990 222.1831187074748T 1320 230.19035948492177T 1650 228.0683781289646T 1980 201.17251667905413L 1980 0 Z;M0 0L 0 198.91548309391138Q 165 261.3922476818655  330 234.30112068896972T 660 204.3221098374849T 990 197.40719560146192T 1320 210.24404882431836T 1650 221.90110529290098T 1980 229.98865514501918L 1980 0 Z">
                    </animate>
                </path>
                <path
                    d="M 0 0 L 0 474.842 Q 165 468.48 330 413.282 T 660 417.634 T 990 425.592 T 1320 406.512 T 1650 415.276 T 1980 463.114 L 1980 0 Z"
                    fill="url(#lg-0.8589700868456098)" opacity="0.4">
                    <animate attributeName="d" dur="14.285714285714285s" repeatCount="indefinite"
                        keyTimes="0;0.333;0.667;1" calcmod="spline" keySplines="0.2 0 0.2 1;0.2 0 0.2 1;0.2 0 0.2 1"
                        begin="-5.7142857142857135s"
                        values="M0 0L 0 215.16905431144298Q 165 249.14013005538385  330 208.41012606073144T 660 219.40030814888186T 990 211.27085687418423T 1320 192.61204273811109T 1650 197.1885193400654T 1980 194.1286625359876L 1980 0 Z;M0 0L 0 229.8226321403231Q 165 236.19867929760932  330 203.52409441404166T 660 214.7170706299125T 990 198.3572922664371T 1320 205.71937345561807T 1650 212.78814530052776T 1980 239.82308226121222L 1980 0 Z;M0 0L 0 247.9361109368723Q 165 231.5303336771614  330 210.95445598569952T 660 200.65096582892625T 990 232.7786653161789T 1320 199.8474501390285T 1650 200.50986927760832T 1980 220.1168133404018L 1980 0 Z;M0 0L 0 215.16905431144298Q 165 249.14013005538385  330 208.41012606073144T 660 219.40030814888186T 990 211.27085687418423T 1320 192.61204273811109T 1650 197.1885193400654T 1980 194.1286625359876L 1980 0 Z">
                    </animate>
                </path>
                <path
                    d="M 0 0 L 0 473.1 Q 165 513.392 330 435.542 T 660 458.17 T 990 399.696 T 1320 468.146 T 1650 454.19 T 1980 474.174 L 1980 0 Z"
                    fill="url(#lg-0.8589700868456098)" opacity="0.4">
                    <animate attributeName="d" dur="14.285714285714285s" repeatCount="indefinite"
                        keyTimes="0;0.333;0.667;1" calcmod="spline" keySplines="0.2 0 0.2 1;0.2 0 0.2 1;0.2 0 0.2 1"
                        begin="-8.571428571428571s"
                        values="M0 0L 0 224.7037505439761Q 165 272.6930353316019  330 229.55156582258994T 660 236.61065365348014T 990 219.91504503660167T 1320 230.31466475967437T 1650 220.06173038098666T 1980 207.42481352241373L 1980 0 Z;M0 0L 0 208.16910156011605Q 165 259.1249630688156  330 226.47382965111322T 660 228.26470562084018T 990 214.50370306252307T 1320 233.37870660752753T 1650 248.78240193288315T 1980 229.8215346622372L 1980 0 Z;M0 0L 0 236.77128821460013Q 165 256.3974835288876  330 217.5513635828135T 660 228.94425335689596T 990 199.4737517303316T 1320 234.14322206572314T 1650 227.22631676701283T 1980 237.64106680051827L 1980 0 Z;M0 0L 0 224.7037505439761Q 165 272.6930353316019  330 229.55156582258994T 660 236.61065365348014T 990 219.91504503660167T 1320 230.31466475967437T 1650 220.06173038098666T 1980 207.42481352241373L 1980 0 Z">
                    </animate>
                </path>
                <path
                    d="M 0 0 L 0 417.03 Q 165 505.246 330 418.8 T 660 425.814 T 990 428.028 T 1320 421.424 T 1650 412.998 T 1980 465.304 L 1980 0 Z"
                    fill="url(#lg-0.8589700868456098)" opacity="0.4">
                    <animate attributeName="d" dur="14.285714285714285s" repeatCount="indefinite"
                        keyTimes="0;0.333;0.667;1" calcmod="spline" keySplines="0.2 0 0.2 1;0.2 0 0.2 1;0.2 0 0.2 1"
                        begin="-11.428571428571427s"
                        values="M0 0L 0 206.7391959060845Q 165 245.70594371304395  330 203.10592615464478T 660 200.39674383834097T 990 223.5013485428745T 1320 197.11555530025123T 1650 192.20908422829876T 1980 225.460814725169L 1980 0 Z;M0 0L 0 222.47847594882043Q 165 226.43274857890046  330 203.86035526418627T 660 198.8500946506723T 990 246.60167473644773T 1320 194.98517556687025T 1650 247.29227858709245T 1980 200.39555084376045L 1980 0 Z;M0 0L 0 211.39873916492658Q 165 263.85646813786497  330 219.62354308390505T 660 233.22692097790812T 990 198.60435835485188T 1320 232.7961046393257T 1650 229.7087015967473T 1980 244.3330217931673L 1980 0 Z;M0 0L 0 206.7391959060845Q 165 245.70594371304395  330 203.10592615464478T 660 200.39674383834097T 990 223.5013485428745T 1320 197.11555530025123T 1650 192.20908422829876T 1980 225.460814725169L 1980 0 Z">
                    </animate>
                </path>
            </g>
        </svg>
    </div>
    <style>
        @media (max-width: 768px) {
            .wave-svg {
                height: 240px !important;
                min-height: 180px !important;
                max-height: 300px !important;
            }
        }

        @media (max-width: 480px) {
            .wave-svg {
                height: 180px !important;
                min-height: 140px !important;
                max-height: 220px !important;
            }
        }

        @media (max-width: 370px) {
            .wave-svg {
                height: 140px !important;
                min-height: 100px !important;
                max-height: 180px !important;
            }
        }
    </style>
    {{-- termina olas animadas --}}

    {{-- Contenedor principal --}}
    <div class="content-wrapper">
        <header class="bg-white shadow-violet-700/100 rounded-b-xl">
            <div class="container flex items-center justify-between p-5 mx-auto">
                <a href="{{ route('home') }}" class="z-20 cursor-pointer">
                    <img srcset="https://res.cloudinary.com/dj848z4er/image/upload/v1748745136/tokhsr71m0thpsjaduyc.png 4x"
                        alt="LOGO" class="navbar-logo-responsive">
                </a>
                <!-- Menú hamburguesa SIEMPRE a la derecha dentro del navbar -->
                <div class="relative ml-auto">

                    <!-- Menú animado y responsivo -->
                    <nav
                        class="absolute right-0 z-50 flex-col items-center hidden w-56 gap-8 transition-all duration-300 ease-in-out bg-white rounded-lg navmax md:static top-12 md:top-0 md:bg-transparent md:rounded-none md:w-auto md:flex md:flex-row">
                        <div
                            class="flex flex-col w-full p-4 bg-white rounded-lg profile-account md:flex-row md:gap-8 md:items-center md:p-0 md:bg-transparent md:rounded-none md:w-auto">
                            @auth
                                @php
                                    // Detectar si estás en tu propio perfil
                                    $currentRoute = request()->route();
                                    $isProfile = false;

                                    if ($currentRoute && $currentRoute->getName() === 'posts.index') {
                                        $routeUser = $currentRoute->parameter('user');

                                        // Manejar tanto cuando $routeUser es un objeto como cuando es un string
                                        $routeUsername = is_object($routeUser) ? $routeUser->username : $routeUser;

                                        if ($routeUsername && $routeUsername === Auth::user()->username) {
                                            $isProfile = true;
                                        }
                                    }
                                @endphp

                                @if ($isProfile)
                                    {{-- Solo mostrar PUBLICACIONES cuando estás en tu propio perfil --}}
                                    <a href="{{ route('home') }}"
                                        class="flex items-center justify-center gap-2 my-2 text-base font-bold text-center text-blue-700 uppercase md:my-0 md:text-left hover:underline">
                                        PUBLICACIONES
                                    </a>
                                @else
                                    <x-profile-link :user="Auth::user()" />
                                @endif

                            @endauth
                            @guest
                                @if (request()->routeIs('login'))
                                    <a href="{{ route('home') }}"
                                        class="block my-2 text-base font-bold text-center text-blue-700 uppercase md:my-0 md:text-left hover:underline">
                                        PUBLICACIONES
                                    </a>
                                @elseif (request()->routeIs('register'))
                                    <a href="{{ route('home') }}"
                                        class="block my-2 text-base font-bold text-center text-blue-700 uppercase md:my-0 md:text-left hover:underline">
                                        PUBLICACIONES
                                    </a>
                                    <a href="{{ route('login') }}"
                                        class="block my-2 text-base font-bold text-center text-blue-700 uppercase md:my-0 md:text-left hover:underline">
                                        INICIAR SESIÓN
                                    </a>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="block my-2 text-base font-bold text-center text-blue-700 uppercase md:my-0 md:text-left hover:underline">
                                        INICIAR SESIÓN
                                    </a>
                                    <a href="{{ url('/register') }}"
                                        class="block my-2 text-base font-bold text-center text-blue-700 uppercase md:my-0 md:text-left hover:underline">
                                        CREAR CUENTA
                                    </a>
                                @endif
                            @endguest
                        </div>
                    </nav>
                </div>
            </div>
        </header>

        <!-- menu para mobile -->
        @include('layouts.menu-mobile')
        @yield('menu-mobile')
        <!-- fin menu para mobile -->

        <div class="contenido">
            @if(Route::is('recuperar', 'code.verific', 'restablecer'))
                @yield('contenido-recover')
            @else
                <main class="container p-5 mx-auto mt-10 mb-5">
                    <h2 class="mb-10 text-3xl font-bold text-center">
                        @yield('titulo')
                    </h2>

                    <div>
                        @yield('contenido')
                    </div>
                </main>
            @endif

            <footer class="p-5 font-bold text-center text-gray-300 uppercase">
                <small>SivarSocial &copy; {{ now()->year }}</small>
            </footer>
        </div>
        <!-- menu de perfil para mobile -->
        @yield('lista-perfiles-mobile')
        @yield('aviso-recuperacion-mobile')
        <!-- fin menu de perfil para mobile -->

    </div>

    @livewireScripts()

    <style>
        @media (max-width: 768px) {
            .navbar-logo-responsive {
                max-width: 110px !important;
                height: auto !important;
            }
        }
    </style>
    <script>
        function activarInput() {
            document.getElementById("buscar").focus();
            document.getElementById("buscar2").focus();
        }

        document.addEventListener('DOMContentLoaded', function () {
            const camposBusqueda = [{
                inputId: 'buscar',
                resultIds: ['resultados-busqueda']
            },
            {
                inputId: 'buscar2',
                resultIds: ['resultados-busqueda2']
            }
            ];

            camposBusqueda.forEach(campo => {
                const input = document.getElementById(campo.inputId);
                if (input) {
                    input.addEventListener('keyup', function () {
                        const query = this.value;

                        fetch(`/buscar-usuarios?buscar=${encodeURIComponent(query)}`)
                            .then(response => response.text())
                            .then(html => {
                                campo.resultIds.forEach(id => {
                                    const destino = document.getElementById(id);
                                    if (destino) {
                                        destino.innerHTML = html;
                                    }
                                });
                            });
                    });
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const modalConfigs = [
                { overlay: "overlay0", content: "modalmenu0", drag: "dragHandle0" },
                { overlay: "overlay1", content: "modalmenu1", drag: "dragHandle1" },
                // Agrega más aquí
            ];

            modalConfigs.forEach(cfg => {
                const modal2 = document.getElementById(cfg.overlay);
                const modalContent2 = document.getElementById(cfg.content);
                const dragHandle2 = document.getElementById(cfg.drag);

                let startY2 = 0;
                let currentY2 = 0;
                let isDragging2 = false;

                if (dragHandle2) {
                    dragHandle2.addEventListener("touchstart", (e) => {
                        startY2 = e.touches[0].clientY;
                        isDragging2 = true;
                        modalContent2.style.transition = "none";
                    });

                    dragHandle2.addEventListener("touchmove", (e) => {
                        if (!isDragging2) return;
                        currentY2 = e.touches[0].clientY;
                        let diff2 = currentY2 - startY2;

                        if (diff2 > 0) {
                            modalContent2.style.transform = `translateY(${diff2}px)`;
                        }
                    });

                    dragHandle2.addEventListener("touchend", () => {
                        isDragging2 = false;
                        modalContent2.style.transition = "transform 0.3s ease";

                        if (currentY2 - startY2 > 100) {
                            modalContent2.style.transform = `translateY(100%)`;
                            setTimeout(() => {
                                modal2.classList.add("hidden");
                                modalContent2.style.transform = "";
                                document.documentElement.style.overflow = "";
                            }, 300);
                        } else {
                            modalContent2.style.transform = "translateY(0)";
                        }
                    });
                }
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth > 768) {
                    closeModal(0);
                }
            });
        });

        function openModal(index) {
            const modal2 = document.getElementById(`overlay${index}`);
            const content = document.getElementById(`modalmenu${index}`);

            if (!modal2 || !content) return;

            content.classList.remove('like-mobile-close', 'like-desktop-close');
            void content.offsetWidth;

            if (window.innerWidth < 648) {
                content.classList.add('like-mobile-open');
            } else {
                content.classList.add('like-desktop-open');
            }

            modal2.classList.remove('hidden');
            modal2.classList.add('flex');
            document.body.style.overflow = 'hidden';
            document.documentElement.style.overflow = "hidden";
        }

        function closeModal(index) {
            const modal2 = document.getElementById(`overlay${index}`);
            const content = document.getElementById(`modalmenu${index}`);

            if (!modal2 || !content) return;

            content.classList.remove('like-mobile-open', 'like-desktop-open');
            void content.offsetWidth;

            if (window.innerWidth < 648) {
                content.classList.add('like-mobile-close');
                content.style.transform = "";
            } else {
                content.classList.add('like-desktop-close');
            }

            content.addEventListener('animationend', function handler() {
                content.removeEventListener('animationend', handler);
                modal2.classList.add('hidden');
                modal2.classList.remove('flex');
                document.body.style.overflow = '';
                document.documentElement.style.overflow = "";
            });
        }
    </script>
</body>

</html>