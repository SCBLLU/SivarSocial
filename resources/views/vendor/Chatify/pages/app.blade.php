{{--
    Vista principal de Chatify: contiene la lista de contactos, la ventana de mensajes y el panel de información.
    Cada sección está separada y se incluyen los componentes por partes.
--}}
@include('Chatify::layouts.headLinks')
<div class="messenger">
    {{-- ----------------------Users/Groups lists side---------------------- --}}
    <div class="messenger-listView {{ !!$id ? 'conversation-active' : '' }}">
        {{-- Header and search bar --}}
        <div class="m-header">
            <nav>
                <a href="/" aria-label="Ir al inicio" style="margin-right: 10px;"><i class="fas fa-home"></i></a>
                <span class="messenger-headTitle"
                    style="position: absolute; left: 45%; transform: translateX(-50%); text-align: center; color: white;">SIVAR
                    CHAT
                    <div class="beta-indicator">BETA</div>
                </span>
                {{-- header buttons --}}
                <nav class="m-header-right">
                    <a href="#"><i class="fas fa-cog settings-btn"></i></a>
                    <a href="#" class="listView-x"><i class="fas fa-times"></i></a>
                </nav>
            </nav>
            {{-- Search input --}}
            <input type="text" class="messenger-search" placeholder="Search" />
            {{-- Tabs --}}
            {{-- <div class="messenger-listView-tabs">
                <a href="#" class="active-tab" data-view="users">
                    <span class="far fa-user"></span> Contacts</a>
            </div> --}}
        </div>
        {{-- tabs and lists --}}
        <div class="m-body contacts-container">
            {{-- Lists [Users/Group] --}}
            {{-- ---------------- [ User Tab ] ---------------- --}}
            <div class="show messenger-tab users-tab app-scroll" data-view="users">
                {{-- Favorites --}}
                {{-- Barra de favoritos oculta --}}
                <div class="favorites-section" style="display:none;">
                    <p class="messenger-title"><span>Favoritos</span></p>
                    <div class="messenger-favorites app-scroll-hidden"></div>
                </div>
                {{-- Saved Messages --}}
                <p class="messenger-title"><span>Tu Espacio</span></p>
                {!! view('Chatify::layouts.listItem', ['get' => 'saved']) !!}
                {{-- Contact --}}
                <p class="messenger-title"><span>Mi Gente</span></p>
                @auth
                    @php
                        $user = auth()->user();
                        $mutualFollowers = \App\Models\User::whereIn('id', function ($query) use ($user) {
                            $query->select('follower_id')->from('followers')->where('user_id', $user->id);
                        })
                            ->whereIn('id', function ($query) use ($user) {
                                $query->select('user_id')->from('followers')->where('follower_id', $user->id);
                            })
                            ->get();
                    @endphp
                    @if ($mutualFollowers->count())
                        <div class="mutual-followers-list">
                            @foreach ($mutualFollowers as $follower)
                                @php
                                    $lastMessage = \App\Models\ChMessage::where(function ($q) use ($user, $follower) {
                                        $q->where('from_id', $user->id)->where('to_id', $follower->id);
                                    })
                                        ->orWhere(function ($q) use ($user, $follower) {
                                            $q->where('from_id', $follower->id)->where('to_id', $user->id);
                                        })
                                        ->orderBy('created_at', 'desc')
                                        ->first();

                                    // CALCULAR MENSAJES NO LEÍDOS
                                    $unseenCounter = \App\Models\ChMessage::where('from_id', $follower->id)
                                        ->where('to_id', $user->id)
                                        ->where('seen', 0)
                                        ->count();
                                @endphp
                                <table class="messenger-list-item" data-contact="{{ $follower->id }}">
                                    <tbody>
                                        <tr data-action="1">
                                            <td style="position: relative">
                                                <div class="avatar av-m"
                                                    style="background-image: url('{{ $follower->imagen ? asset('perfiles/' . $follower->imagen) : asset('img/img.jpg') }}');"
                                                    data-imagen="{{ $follower->imagen ? asset('perfiles/' . $follower->imagen) : asset('img/img.jpg') }}">
                                                </div>
                                                {{-- NO mostrar estado activo inicial - se manejará via JavaScript/Pusher --}}
                                            </td>
                                            <td>
                                                <p data-id="{{ $follower->id }}" data-type="user">
                                                    {{ $follower->name ?? $follower->username }}
                                                    <span class="contact-item-time"
                                                        data-time="{{ $lastMessage ? $lastMessage->created_at : '' }}">
                                                        {{ $lastMessage ? $lastMessage->created_at->diffForHumans() : '' }}
                                                    </span>
                                                </p>
                                                <span class="lastMessageIndicator">
                                                    @if ($lastMessage)
                                                        @if ($lastMessage->from_id == $user->id)
                                                            You :
                                                        @else
                                                            {{ $follower->name ?? $follower->username }} :
                                                        @endif
                                                        @php
                                                            $messageBody = $lastMessage->body;
                                                            $truncatedMessage =
                                                                strlen($messageBody) > 20
                                                                    ? mb_substr($messageBody, 0, 20, 'UTF-8') . '...'
                                                                    : $messageBody;
                                                        @endphp
                                                        {{ $truncatedMessage }}
                                                    @endif
                                                </span>
                                                {{-- CONTADOR DE MENSAJES NO LEÍDOS --}}
                                                @if ($unseenCounter > 0)
                                                    <b>{{ $unseenCounter }}</b>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-2 text-xs text-center text-gray-400">No tienes seguidores mutuos.</p>
                    @endif
                @endauth
            </div>
            {{-- ---------------- [ Search Tab ] ---------------- --}}
            <div class="messenger-tab search-tab app-scroll" data-view="search">
                {{-- items --}}
                <p class="messenger-title"><span>Buscar</span></p>
                <div class="search-records">
                    <p class="message-hint center-el"><span>Escribe para buscar..</span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- ----------------------Messaging side---------------------- --}}
    <div class="messenger-messagingView">
        {{-- header title [conversation name] amd buttons --}}
        <div class="m-header m-header-messaging">
            <nav class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                {{-- header back button, avatar and user name --}}
                <div class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                    <a href="#" class="show-listView"><i class="fas fa-arrow-left"></i></a>
                    <div class="avatar av-s header-avatar"
                        style="margin: 0px 10px; margin-top: -5px; margin-bottom: -5px;">
                    </div>
                    <a href="#" class="user-name">{{ config('chatify.name') }}</a>
                </div>
                {{-- header buttons --}}
                <nav class="m-header-right">
                    <a href="#" class="add-to-favorite"><i class="fas fa-star"></i></a>
                    <a href="#" class="show-infoSide"><i class="fas fa-info-circle"></i></a>
                </nav>
            </nav>
            {{-- Internet connection --}}
            <div class="internet-connection">
                <span class="ic-connected">Conectado a SIVAR CHAT</span>
                <span class="ic-connecting">Conectando...</span>
                <span class="ic-noInternet">Sin acceso a internet</span>
            </div>
        </div>

        {{-- Messaging area --}}
        <div class="m-body messages-container app-scroll">
            <div class="messages">
                <p class="message-hint center-el"><span>Por favor selecciona un chat para comenzar a enviar
                        mensajes</span></p>
            </div>
            {{-- Typing indicator --}}
            <div class="typing-indicator">
                <div class="message-card typing">
                    <div class="message">
                        <span class="typing-dots">
                            <span class="dot dot-1"></span>
                            <span class="dot dot-2"></span>
                            <span class="dot dot-3"></span>
                        </span>
                    </div>
                </div>
            </div>

        </div>
        {{-- Send Message Form --}}
        @include('Chatify::layouts.sendForm')
    </div>
    {{-- ---------------------- Info side ---------------------- --}}
    <div class="messenger-infoView app-scroll">
        {{-- nav actions --}}
        <nav>
            <p>User Details</p>
            <a href="#"><i class="fas fa-times"></i></a>
        </nav>
        {!! view('Chatify::layouts.info')->render() !!}
    </div>
</div>

@include('Chatify::layouts.modals')
@include('Chatify::layouts.footerLinks')
