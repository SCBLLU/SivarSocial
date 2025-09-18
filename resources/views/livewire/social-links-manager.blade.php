<div class="space-y-6">
    <!-- Header minimalista -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                Enlaces Sociales
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Conecta tus redes sociales favoritas
            </p>
        </div>
        @if(count($socialLinks) < 4)
            <button type="button" id="btn-agregar-enlace" wire:click="toggleForm"
                onclick="console.log('Botón clickeado directamente'); if(typeof window.Livewire !== 'undefined') { console.log('Livewire disponible'); } else { console.log('Livewire NO disponible'); }"
                class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-all duration-200 hover:shadow-lg hover:shadow-blue-500/25 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Agregar enlace
            </button>
        @else
            <div class="text-xs text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 px-3 py-2 rounded-lg">
                Máximo 4 enlaces alcanzado
            </div>
        @endif
    </div>

    <!-- Mensajes de estado mejorados -->
    @if (session()->has('success'))
        <div
            class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-xl flex items-center space-x-3">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div
            class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-xl flex items-center space-x-3">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Formulario moderno para agregar/editar enlaces -->
    @if($showForm)
        <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg"
            x-data="{ isLoading: false }" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100">

            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                    ➕ Nuevo enlace social
                </h4>
                <button type="button" wire:click="toggleForm"
                    class="p-2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="addLink" @submit="isLoading = true">
                <div class="space-y-5">
                    <div>
                        <label for="url" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            URL del perfil social
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="url" id="url" wire:model.live="url" placeholder="https://instagram.com/tu_usuario"
                                class="w-full px-4 py-3.5 pl-12 border rounded-xl focus:ring-2 focus:border-transparent dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 transition-all duration-200 
                                        {{ $errors->has('url') ? 'border-red-500 dark:border-red-500 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:ring-blue-500' }}"
                                required>
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                            </div>

                            <!-- Loading indicator for real-time validation -->
                            <div wire:loading wire:target="url" class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <svg class="w-5 h-5 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div>
                        </div>

                        @error('url')
                            <p class="text-red-500 text-sm mt-2 flex items-center space-x-1" x-transition>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror

                        <!-- Preview de la plataforma detectada -->
                        @if($url && !$errors->has('url'))
                            <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800"
                                x-transition>
                                <div class="flex items-center space-x-2 text-sm text-blue-700 dark:text-blue-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Plataforma detectada automáticamente</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex space-x-3 pt-2">
                        <button type="submit" wire:loading.attr="disabled" wire:target="addLink"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-lg hover:shadow-blue-500/25 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                            <span wire:loading.remove wire:target="addLink">
                                ➕ Agregar enlace
                            </span>
                            <span wire:loading wire:target="addLink" class="flex items-center space-x-2">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span>Agregando...</span>
                            </span>
                        </button>
                        <button type="button" wire:click="toggleForm"
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            ❌ Cancelar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif

    <!-- Lista de enlaces modernos -->
    @if($socialLinks->count() > 0)
        <div class="space-y-3">
            @foreach($socialLinks as $link)
                <div
                    class="group relative bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition-all duration-200 hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <!-- Ícono de la plataforma moderno -->
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-sm"
                                style="background: linear-gradient(135deg, {{ $this->getPlatformColor($link->platform) }}15, {{ $this->getPlatformColor($link->platform) }}25);">
                                <i class="{{ $link->icon }} text-xl"
                                    style="color: {{ $this->getPlatformColor($link->platform) }};"></i>
                            </div>

                            <!-- Información del enlace -->
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-gray-900 dark:text-white text-sm">
                                    {{ ucfirst($link->platform) }}
                                </div>
                                @if($link->username)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                        @{{ $link->username }}
                                    </div>
                                @else
                                    <div class="text-xs text-gray-400 dark:text-gray-500 italic">
                                        {{ parse_url($link->url, PHP_URL_HOST) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Controles simplificados - solo eliminar -->
                        <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <!-- Botón eliminar con confirmación -->
                            <div x-data="{ showConfirm: false }" class="relative">
                                <button type="button" @click="showConfirm = true" x-show="!showConfirm"
                                    class="p-2.5 text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all duration-200"
                                    title="Eliminar enlace">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>

                                <!-- Confirmación de eliminación -->
                                <div x-show="showConfirm" x-transition class="flex items-center space-x-1">
                                    <button type="button" wire:click="deleteLink({{ $link->id }})" wire:loading.attr="disabled"
                                        wire:target="deleteLink({{ $link->id }})" @click="showConfirm = false"
                                        class="p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all duration-200 text-xs font-medium disabled:opacity-50"
                                        title="Confirmar eliminación">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span wire:loading wire:target="deleteLink({{ $link->id }})"
                                            class="absolute inset-0 flex items-center justify-center">
                                            <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </span>
                                    </button>
                                    <button type="button" @click="showConfirm = false"
                                        class="p-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg transition-all duration-200 text-xs"
                                        title="Cancelar">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Separador visual -->
                            <div class="w-px h-6 bg-gray-200 dark:bg-gray-600"></div>

                            <!-- Enlace externo -->
                            <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                                class="p-2.5 text-gray-400 hover:text-green-600 dark:text-gray-500 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-xl transition-all duration-200"
                                title="Visitar {{ ucfirst($link->platform) }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Estado vacío mejorado -->
        <div class="text-center py-16">
            <div
                class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/20 dark:to-blue-800/20 rounded-3xl flex items-center justify-center">
                <svg class="w-10 h-10 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                🌟 Conecta tus redes sociales
            </h3>
            <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto leading-relaxed">
                Agrega hasta 4 enlaces de tus plataformas favoritas para que otros puedan encontrarte fácilmente
            </p>

            <!-- Ejemplos de plataformas -->
            <div class="flex justify-center space-x-4 mb-8">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-pink-100 to-pink-200 dark:from-pink-900/20 dark:to-pink-800/20 rounded-2xl flex items-center justify-center">
                    <i class="fab fa-instagram text-lg text-pink-500"></i>
                </div>
                <div
                    class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 rounded-2xl flex items-center justify-center">
                    <i class="fab fa-github text-lg text-gray-600 dark:text-gray-400"></i>
                </div>
                <div
                    class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl flex items-center justify-center">
                    <i class="fab fa-linkedin text-lg text-blue-500"></i>
                </div>
                <div
                    class="w-12 h-12 bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/20 dark:to-red-800/20 rounded-2xl flex items-center justify-center">
                    <i class="fab fa-youtube text-lg text-red-500"></i>
                </div>
            </div>

            @if(count($socialLinks) < 4)
                <button type="button" wire:click="toggleForm"
                    class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-base font-semibold rounded-2xl transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/25 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Agregar mi primer enlace
                </button>
            @endif
        </div>
    @endif

    <!-- Información adicional minimalista -->
    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <div class="flex items-start space-x-3">
            <div class="w-5 h-5 text-blue-500 mt-0.5">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                    <span class="font-medium">Plataformas compatibles:</span> Instagram, GitHub, Discord, Twitter/X,
                    LinkedIn, YouTube, TikTok, Facebook, Spotify, Twitch.
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-500">
                    Los íconos y usernames se detectan automáticamente según la URL.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('SocialLinksManager: DOM loaded');

        // Verificar si Livewire está disponible
        if (typeof window.Livewire !== 'undefined') {
            console.log('SocialLinksManager: Livewire está disponible');
        } else {
            console.error('SocialLinksManager: Livewire no está disponible');
        }

        // Fallback: Si el botón de Livewire no funciona, usar JavaScript directo
        const btnAgregar = document.getElementById('btn-agregar-enlace');
        if (btnAgregar) {
            btnAgregar.addEventListener('click', function () {
                console.log('Click detectado en botón agregar');

                // Intentar con Livewire primero
                if (typeof window.Livewire !== 'undefined') {
                    try {
                        // Para Livewire v3
                        const component = window.Livewire.find('{{ $_instance->getId() }}');
                        if (component) {
                            console.log('Ejecutando toggleForm via Livewire v3');
                            component.call('toggleForm');
                            return;
                        }
                    } catch (e) {
                        console.log('Error con Livewire v3:', e);
                    }
                }

                // Fallback: mostrar/ocultar formulario manualmente
                console.log('Usando fallback JavaScript');
                const formulario = document.querySelector('[wire\\:submit\\.prevent]')?.closest('.bg-gradient-to-br');

                if (formulario) {
                    if (formulario.style.display === 'none') {
                        formulario.style.display = 'block';
                    } else {
                        formulario.style.display = 'none';
                    }
                } else {
                    // Si no existe el formulario, crear uno básico
                    const container = btnAgregar.closest('.space-y-6');
                    if (container && !container.querySelector('.formulario-temp')) {
                        const formHTML = `
                            <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg formulario-temp">
                                <form action="{{ route('social-links.store') }}" method="POST">
                                    @csrf
                                    <div class="space-y-5">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                                URL del perfil social
                                            </label>
                                            <div class="relative">
                                                <input 
                                                    type="url" 
                                                    name="url"
                                                    placeholder="https://instagram.com/tu_usuario"
                                                    class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 transition-all duration-200"
                                                    required
                                                >
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex space-x-3 pt-2">
                                            <button 
                                                type="submit" 
                                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-lg hover:shadow-blue-500/25"
                                            >
                                                Agregar enlace
                                            </button>
                                            <button 
                                                type="button" 
                                                onclick="this.closest('.formulario-temp').style.display='none'"
                                                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-medium transition-all duration-200"
                                            >
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        `;
                        container.insertAdjacentHTML('beforeend', formHTML);
                    }
                }
            });
        }
    });

    // Para Livewire v3
    document.addEventListener('livewire:init', function () {
        console.log('SocialLinksManager: Livewire inicializado');
    });

    // Para Livewire v2 (fallback)
    document.addEventListener('livewire:load', function () {
        console.log('SocialLinksManager: Livewire cargado (v2)');
    });
</script>