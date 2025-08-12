@php
    // Configuración centralizada de todas las insignias
    $badgeConfig = [
        'Colaborador' => [
            'url' => asset('img/colaboradores.svg'),
            'alt' => 'Colaborador',
            'title' => 'Usuario Colaborador',
            'color' => 'text-blue-600',
            'description' => 'Sivar Social, reconoce a este usuario como "colaborador", con el propósito de valorar su aporte al desarrollo de la red social.',
            'link' => 'colaboradores',
            'linkText' => 'Más información sobre su colaboración'
        ],
        'Creador' => [
            'url' => asset('img/creador.svg'),
            'alt' => 'Creador',
            'title' => 'Usuario Creador',
            'color' => 'text-blue-600',
            'description' => isset($user) && $user->username 
            ? 'Sivar Social, reconoce @' . $user->username . ' como el creador por impulsar esta plataforma, por su visión y esfuerzo a la creación de esta red social.'
            : 'Sivar Social, reconoce al "creador" por impulsar esta plataforma, por su visión y esfuerzo a la creación de esta red social.',
            'link' => null,
            'linkText' => null
        ],
        'Docente' => [
            'url' => asset('img/docente.svg'),
            'alt' => 'Docente',
            'title' => 'Docente Verificado',
            'color' => 'text-green-600',
            'description' => 'Sivar Social, reconoce a este usuario como "docente", con el propósito de identificar su rol y facilitar su interacción dentro de la red social.',
            'link' => null,
            'linkText' => null
        ],
        'Comunidad' => [
            'url' => asset('img/comunidad.svg'),
            'alt' => 'Comunidad',
            'title' => 'Miembro de la Comunidad',
            'color' => 'text-purple-600',
            'description' => 'Sivar Social, reconoce a este usuario como "comunidad", con el propósito de demostrar las interacciones continuas dentro de la red social.',
            'link' => null,
            'linkText' => null
        ]
    ];

    // Configuración de tamaños
    $sizes = [
        'small' => ['width' => '12', 'height' => '12'],
        'medium' => ['width' => '16', 'height' => '16'],
        'large' => ['width' => '20', 'height' => '20'],
        'xl' => ['width' => '40', 'height' => '40'],
        'modal' => ['width' => '40', 'height' => '40'] // Para uso en modales
    ];

    // Parámetros del componente
    $badge = $badge ?? ($user->insignia ?? null);
    $size = $size ?? 'small';
    $showHover = $showHover ?? true;
    $mode = $mode ?? 'badge'; // 'badge' para insignia simple, 'full' para modal completo
    $user = $user ?? null;

    $currentBadge = $badgeConfig[$badge] ?? null;
    $currentSize = $sizes[$size] ?? $sizes['small'];
@endphp

@if($mode === 'full')
    {{-- Modo completo para modales --}}
    @if($currentBadge)
        <div class="flex flex-col items-center justify-center p-4 space-y-3 flex-1 pb-0 bg-white">
            <img src="{{ $currentBadge['url'] }}" alt="{{ $currentBadge['alt'] }}" width="45" height="45" class="object-contain"
                loading="lazy">

            <h1 class="text-center text-black mb-3 font-medium text-2xl">Insignia</h1>

            <p class="text-center text-black text-xs sm:text-sm mb-10">
                {{ $currentBadge['description'] }}
            </p>

            @if($currentBadge['link'] && $currentBadge['linkText'])
                <a href="{{ $currentBadge['link'] }}" class="text-center {{ $currentBadge['color'] }} text-xs sm:text-sm mb-10">
                    {{ $currentBadge['linkText'] }}
                </a>
            @endif
        </div>
    @else
        {{-- Usuario sin insignia --}}
        <div class="flex flex-col items-center justify-center p-4 space-y-3 flex-1 pb-0 bg-white">
            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h1 class="text-center text-black mb-3 font-medium text-2xl">Sin Insignia</h1>
            <p class="text-center text-black text-xs sm:text-sm mb-10">
                @if($user && Auth::check() && Auth::id() === $user->id)
                    Aún no tienes ninguna insignia especial en Sivar Social.
                @else
                    Este usuario aún no tiene ninguna insignia especial en Sivar Social.
                @endif
            </p>
        </div>
    @endif
@else
    {{-- Modo badge simple --}}
    @if($currentBadge)
        <span
            class="flex-shrink-0 inline-flex items-center {{ $showHover ? 'transition-transform duration-200 hover:scale-110' : '' }}"
            title="{{ $currentBadge['title'] }}">
            <img src="{{ $currentBadge['url'] }}" alt="{{ $currentBadge['alt'] }}" width="{{ $currentSize['width'] }}"
                height="{{ $currentSize['height'] }}" class="object-contain" loading="lazy">
        </span>
    @endif
@endif