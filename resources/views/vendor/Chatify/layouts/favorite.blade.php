{{--
    Vista de un usuario favorito en la barra de favoritos del chat.
    Muestra el avatar y el nombre del usuario favorito.
    El atributo data-imagen permite que el JS fuerce la imagen de perfil del usuario ya que daba problema.
--}}
<div class="favorite-list-item">
    @if ($user)
        {{-- Contenedor del avatar con posición relativa para el círculo de estado --}}
        <div style="position: relative; display: inline-block;">
            {{-- Avatar del usuario favorito --}}
            <div data-id="{{ $user->id }}" data-action="0" class="avatar av-m"
                style="background-image: url('{{ $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/img.jpg') }}');"
                data-imagen="{{ $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/img.jpg') }}">
            </div>
        </div>
        {{-- Nombre del usuario favorito --}}
        <p>{{ strlen($user->name) > 5 ? substr($user->name, 0, 6) . '..' : $user->name }}</p>
    @endif
</div>
