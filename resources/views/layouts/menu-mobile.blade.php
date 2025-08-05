@section('menu-mobile')
<!-- Header Start -->    
<div id="header" class="header2">

    <header class="header__navground header  d-flex justify-content-between">

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
                        if ($routeUser && $routeUser->username === Auth::user()->username) {
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
                  <a class="menu__link btn" onclick="openComments()">
                      <i class='bx bx-group'></i>
                    </a>
                </li>
                <li class="menu__item">
                  <a class="menu__link btn" href="{{ route('posts.create') }}">
                    <i class='bx bx-plus'></i>
                  </a>
                </li>
                <!-- Notificaciones -->

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

              @else

              <li class="menu__item">
                  <a class="menu__link btn" href="{{ url('/') }}#home">
                    <i class='bx bx-home-smile'></i>
                  </a>
                </li>
                <li class="menu__item">
                  <a class="menu__link btn" onclick="openComments()">
                    <i class='bx bx-group'></i>
                  </a>
                </li>
                <li class="menu__item">
                  <a class="menu__link btn" href="{{ route('posts.create') }}">
                    <i class='bx bx-plus'></i>
                  </a>
                </li>
                <!-- Notificaciones -->

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

                @else

                <li class="menu__item">
                  <a class="menu__link btn" href="{{ route('login') }}">
                    <i class='bx bx-log-in-circle'></i>
                  </a>
                </li>
                <li class="menu__item">
                  <a class="menu__link btn" href="{{ url('/register') }}">
                    <i class='bx bx-user-plus' ></i>
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

            <div class="profile-img">
                <a id="notify-trigger" class="header__trigger btn" href="{{ url('/register') }}">
                  <i class='bx bx-user-plus' ></i>
                </a>
            </div>


            @elseif (request()->routeIs('register'))

            <div class="profile-img">
                <a id="notify-trigger" class="header__trigger btn" href="{{ route('login') }}">
                 <i class='bx bx-user' ></i>
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

<div id="overlay" class="fixed inset-0 bg-transparent bg-opacity-100 hidden z-40" onclick="closeComments()"></div>

<!-- Panel deslizante -->
<div
  id="perfilPanel"
  class="fixed bottom-0 left-0 right-0 bg-white text-black rounded-t-2xl shadow-lg transform translate-y-full transition-transform duration-300 ease-in-out z-50 flex flex-col"
>
  <!-- Zona de arrastre -->
  <div
    id="dragHandle"
    class="p-4 border-b border-gray-200 text-center text-lg font-semibold cursor-grab touch-none"
  >
    <div class="w-12 h-1 bg-gray-300 rounded-full mx-auto mb-2" ></div>
    <div class="encabezado">
        perfiles&nbsp;<i class="bx bx-group"></i>
        <!-- <div class="close-perfil" onclick="closeComments()">
            <i class="bx bx-x"></i>
        </div>  --> 
    </div>
    
  </div>

  <!-- Contenido desplazable -->
  <div class="p-4 space-y-3 overflow-y-auto flex-1 pb-0">
    <div class="w-full h-full">
      <div id="" class="flex flex-col p-4 bg-white pb-0" style="max-height: 600px;">

        <div class="bg-white rounded-xl shadow-sm mb-3 sm:mb-4 w-full max-w-lg mx-auto">
            <div class="flex items-center p-2" onclick="activarInput()" style="padding-left: 15px;     height: 40px;">
                <i id="busc" class="bx bx-search-alt-2"></i>
                <div class="flex-shrink-0 buscar-input">
                   <input type="text" id="buscar" name="buscaru" placeholder="Buscar" >
                </div>
            </div>
          </div>
        
        <hr class="border-gray-300 mb-4 w-[80%] mx-auto">

        <div id="resultados-busqueda" class="overflow-y-auto">
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