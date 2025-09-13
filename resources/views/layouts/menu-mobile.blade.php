@section('menu-mobile')
  <!-- Header Start -->
  <div id="header" class="header2">

    <header class="header__navground header d-flex justify-content-between">

    <!-- Navigation Menu Start -->
    <div class="header__navigation">
      <nav id="menu" class="menu">
      <ul class="menu__list d-flex justify-content-start">

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

        <li class="menu__item">
        <a class="menu__link btn" href="{{ url('/') }}#home">
        <i class='bx bx-home-smile'></i>
        </a>
        </li>
        <li class="menu__item">
        <a class="menu__link btn" onclick="openModal(0)">
        <i class='bx bx-group'></i>
        </a>
        </li>
        <li class="menu__item">
        <a class="menu__link btn" href="{{ route('posts.create') }}">
        <i class='bx bx-plus'></i>
        </a>
        </li>
        <!-- Notificaciones -->
        <li class="menu__item">
        <div class="menu__link btn notification-btn-mobile">
        @livewire('notification-button')
        </div>
        </li>
        <!--                 <li class="menu__item">
      <a class="menu__link btn" href="#contact">
      <i class='bx bx-heart' ></i>
      </a>
      </li> -->

        <!-- Fin Notificaciones -->
        <!-- Mensajes -->

        <!--                 <li class="menu__item">
      <a class="menu__link btn" href="#contact">
      <i class='bx bx-chat'></i>
      </a>
      </li> -->

        <!-- Fin Mensajes -->

      @elseif(Route::is('perfil.index'))
      <li class="menu__item">
      <a class="menu__link btn" href="{{ url('/') }}#home">
        <i class='bx bx-home-smile'></i>
      </a>
      </li>
      <li class="menu__item">
      <a class="menu__link btn" href="{{ route('posts.create') }}">
        <i class='bx bx-plus'></i>
      </a>
      </li>
      <!-- Notificaciones -->
      <li class="menu__item">
      <div class="menu__link btn notification-btn-mobile">
      @livewire('notification-button')
      </div>
      </li>

      @elseif(Route::is('posts.create'))
      <li class="menu__item">
      <a class="menu__link btn" href="{{ url('/') }}#home">
        <i class='bx bx-home-smile'></i>
      </a>
      </li>
      <li class="menu__item">
      <a class="menu__link btn" onclick="openModal(0)">
        <i class='bx bx-group'></i>
      </a>
      </li>
      <!-- Notificaciones -->
      <li class="menu__item">
      <div class="menu__link btn notification-btn-mobile">
      @livewire('notification-button')
      </div>
      </li>

      @elseif(Route::is('users.followers', 'users.following', 'colaboradores.index'))
      <li class="menu__item">
      <a class="menu__link btn" href="{{ url('/') }}#home">
      <i class='bx bx-home-smile'></i>
      </a>
      </li>
      <li class="menu__item">
      <a class="menu__link btn" href="{{ route('posts.create') }}">
      <i class='bx bx-plus'></i>
      </a>
      </li>
      <!-- Notificaciones -->
      <li class="menu__item">
      <div class="menu__link btn notification-btn-mobile">
      @livewire('notification-button')
      </div>
      </li>

      @else

        <li class="menu__item">
        <a class="menu__link btn" href="{{ url('/') }}#home">
        <i class='bx bx-home-smile'></i>
        </a>
        </li>
        <li class="menu__item">
        <a class="menu__link btn" onclick="openModal(0)">
        <i class='bx bx-group'></i>
        </a>
        </li>
        <li class="menu__item">
        <a class="menu__link btn" href="{{ route('posts.create') }}">
        <i class='bx bx-plus'></i>
        </a>
        </li>
        <!-- Notificaciones -->
        <li class="menu__item">
        <div class="menu__link btn notification-btn-mobile">
        @livewire('notification-button')
        </div>
        </li>
        <!--                 <li class="menu__item">
      <a class="menu__link btn" href="#contact">
      <i class='bx bx-heart' ></i>
      </a>
      </li> -->

        <!-- Fin Notificaciones -->
        <!-- Mensajes -->

        <!--                 <li class="menu__item">
      <a class="menu__link btn" href="#contact">
      <i class='bx bx-chat'></i>
      </a>
      </li> -->

        <!-- Fin Mensajes -->

      @endif
      @endauth

        @guest
        @if (request()->routeIs('login'))

      <li class="menu__item">
      <a class="menu__link btn" href="{{ url('/') }}#home">
        <i class='bx bx-home-smile'></i>
      </a>
      </li>

      @elseif (request()->routeIs('register'))

      <li class="menu__item">
      <a class="menu__link btn" href="{{ url('/') }}#home">
        <i class='bx bx-home-smile'></i>
      </a>
      </li>

      @elseif (request()->routeIs('recuperar'))

      <li class="menu__item">
      <a class="menu__link btn" href="{{ url('/') }}#home">
        <i class='bx bx-home-smile'></i>
      </a>
      </li>
      <li class="menu__item">
      <a class="menu__link btn" href="{{ url('/register') }}">
        <i class='bx bxs-user-detail'></i>
      </a>
      </li>

      @elseif (request()->routeIs('code.verific'))

      <li class="menu__item">
      <a class="menu__link btn" onclick="openModal(1)">
        <i class="fa-regular fa-circle-question"></i>
      </a>
      </li>

      @elseif (request()->routeIs('restablecer'))

      <li class="menu__item">
      <a class="menu__link btn" onclick="openModal(1)">
        <i class="fa-regular fa-circle-question"></i>
      </a>
      </li>

      @else

      <li class="menu__item">
      <a class="menu__link btn" href="{{ route('login') }}">
        <i class='bx bx-user'></i>
      </a>
      </li>
      <li class="menu__item">
      <a class="menu__link btn" href="{{ url('/register') }}">
        <i class='bx bxs-user-detail'></i>
      </a>
      </li>

      @endif
      @endguest
      </ul>
      </nav>
    </div>
    <!-- Navigation Menu End -->

    <!-- Header Controls Start -->

    @auth
    <div class="header__controls d-flex justify-content-end">


      @if ($isProfile)

    @else
      <div class="profile-img">
      <a id="notify-trigger" class="header__trigger btn" href="{{ route('posts.index', auth()->user()) }}">

      <x-profile-link :user="Auth::user()" />

      </a>
      </div>
    @endif
    @endauth

      @guest
      @if (request()->routeIs('login'))


    @elseif (request()->routeIs('register'))

      <div class="profile-img">
      <a id="notify-trigger" class="header__trigger btn" href="{{ route('login') }}">
      <i class='bx bx-user'></i>
      </a>
      </div>
    @else


    @endif

    </div>
    @endguest
    <!-- Header Controls End -->
    </header>
  </div>
@endsection

@section("lista-perfiles-mobile")

  <!-- Modal - Estilo Instagram hoja deslizante para móvil -->
  <div id="overlay0" class="fixed inset-0 items-end justify-center hidden sm:items-center"
    style="background-color: rgba(0, 0, 0, 0.6); z-index: 1100;">
    <!-- Backdrop para cerrar modal -->
    <div class="absolute inset-0 cursor-pointer" onclick="closeModal(0)"></div>

    <!-- Contenedor  panel de perfiles -->
    <div id="modalmenu0"
    class="fixed bottom-0 left-0 right-0 bg-white text-black rounded-t-2xl shadow-lg z-50 flex flex-col max-h-[80vh] w-full mx-auto sm:relative sm:w-96 sm:h-96 sm:rounded-xl overflow-hidden">
    <!-- Drag handle -->
    <div id="dragHandle0"
      class="p-4 text-lg font-semibold text-center border-b border-gray-200 cursor-grab touch-none sm:hidden">
      <div class="w-12 h-1 mx-auto mb-2 bg-gray-300 rounded-full"></div>
      <div class="flex items-center justify-between px-2">
      <span class="text-lg font-bold text-purple-700" style="display: flex; align-items: center;">Perfiles <i
        class="ml-1 fa-solid fa-user-group" aria-hidden="true" style="font-size: 12px;"></i></span>
      <button onclick="closeModal(0)" class="p-1 transition-colors rounded-full hover:bg-gray-100">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      </div>
    </div>
    <!-- Header solo en desktop -->
    <div class="sticky top-0 z-10 flex-none hidden bg-white border-b border-gray-200 sm:block sm:rounded-t-xl">
      <div class="flex items-center justify-between px-4 py-3">
      <h3 class="text-lg font-semibold text-purple-700" style="display: flex; align-items: center;">Perfiles <i
        class="ml-1 fa-solid fa-user-group" aria-hidden="true" style="font-size: 12px;"></i></h3>
      <button onclick="closeModal(0)" class="p-1 transition-colors rounded-full hover:bg-gray-100">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      </div>
    </div>
    <!-- Lista scrolleable -->
    <div>
      <div class="flex-1 p-4 pb-0 space-y-3 bg-white">
      <div class="w-full mx-auto mb-3 bg-white rounded-full shadow-sm sm:mb-4">
        <div class="flex items-center p-2" onclick="activarInput()" style="padding-left: 15px; height: 40px;">
        <i class="bx bx-search-alt-2"></i>
        <div class="flex-shrink-0 buscar-input">
          <input type="text" id="buscar" name="buscaru" placeholder="Buscar" class="px-3 py-1 rounded-full">
        </div>
        </div>
      </div>
      </div>
      <div>
      <div id="resultados-busqueda" class="flex-1 p-4 pb-0 space-y-3 bg-white scrollable-list">
        @if (isset($users) && $users->count())
      @component('components.listar-perfiles', ['users' => $users]) @endcomponent
      @else
      <p>No hay usuarios disponibles.</p>
      @endif
      </div>
      </div>

    </div>
    </div>
  </div>

@endsection

@section("aviso-recuperacion-mobile")
  <div id="overlay1" class="fixed inset-0 items-end justify-center hidden sm:items-center"
    style="background-color: rgba(0, 0, 0, 0.6); z-index: 1100;">
    <!-- Backdrop para cerrar modal -->
    <div class="absolute inset-0 cursor-pointer" onclick="closeModal(1)"></div>

    <!-- Contenedor  panel de perfiles -->
    <div id="modalmenu1"
    class="fixed bottom-0 left-0 right-0 bg-white text-black rounded-t-2xl shadow-lg z-50 flex flex-col max-h-[80vh] w-full mx-auto sm:relative sm:w-96 sm:h-96 sm:rounded-xl overflow-hidden">
    <!-- Drag handle -->
    <div id="dragHandle1" class="p-4 text-lg font-semibold text-center cursor-grab touch-none sm:hidden">
      <div class="w-12 h-1 mx-auto mb-2 bg-gray-300 rounded-full"></div>
      <div class="flex items-center justify-between px-2">
      <span class="text-base font-bold text-gray-900"></span>
      <button onclick="closeModal(1)" class="p-1 transition-colors rounded-full hover:bg-gray-100">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      </div>
    </div>
    <!-- Header solo en desktop -->
    <div class="sticky top-0 z-10 flex-none hidden bg-white sm:block sm:rounded-t-xl">
      <div class="flex items-center justify-between px-4 py-3">
      <h3 class="text-base font-semibold text-gray-900"></h3>
      <button onclick="closeModal(1)" class="p-1 transition-colors rounded-full hover:bg-gray-100">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      </div>
    </div>
    <!-- Lista scrolleable -->
    @php
    // Determinar qué usuario mostrar en el modal de insignia
    $userToShow = null;
    if (isset($user)) {
      $userToShow = $user; // Usuario del perfil que se está viendo
    } elseif (Auth::check()) {
      $userToShow = Auth::user(); // Usuario logueado como fallback
    }
    @endphp

    @if($userToShow)
    @include('perfil.user-badge', ['user' => $userToShow, 'mode' => 'full'])
    @endif

    @guest
      @if (request()->routeIs('code.verific'))
      <div class="flex flex-col items-center justify-center flex-1 p-4 space-y-3 bg-white">
      <img src="https://res.cloudinary.com/dtmemrt1j/image/upload/v1754752332/Dise%C3%B1o_sin_t%C3%ADtulo_mxsofs.png"
      alt="" width="60" height="60">
      <h1 class="mb-3 text-2xl font-medium text-center text-black">Aviso</h1>
      <p class="mb-10 text-xs text-center text-black sm:text-sm">
      Estas en una zona de recuperación de contraseña, Sivar Social te enviará un código a tu correo, si no recibiste
      un código, revisa tu bandeja de spam.
      </p>
      </div>
      @elseif (request()->routeIs('restablecer'))
      <div class="flex flex-col items-center justify-center flex-1 p-4 space-y-3 bg-white">
      <img src="https://res.cloudinary.com/dtmemrt1j/image/upload/v1754752332/Dise%C3%B1o_sin_t%C3%ADtulo_mxsofs.png"
      alt="" width="60" height="60">
      <h1 class="mb-3 text-2xl font-medium text-center text-black">Aviso</h1>
      <p class="mb-10 text-xs text-center text-black sm:text-sm">
      recuerda usar un contraseña fuerte y guardarlo en un lugar donde no puedas olvidar, no compartas tu contraseña.
      </p>
      </div>
      @else


      @endif
    @endguest

    </div>
  </div>
@endsection