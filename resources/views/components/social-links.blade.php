{{-- Vista unificada para enlaces sociales - Display y Management --}}
@php
    $isManagementMode = $managementMode ?? false;
    $targetUser = $user ?? auth()->user();
    $socialLinks = $targetUser->socialLinks()->ordered()->get();
    $canManage = $isManagementMode && auth()->check() && auth()->id() === $targetUser->id;
    $hasLinks = $socialLinks->count() > 0;
    $shouldShow = $hasLinks || $canManage; // Mostrar si tiene enlaces O si puede gestionarlos
@endphp

@if($shouldShow)
    <div id="social-links-unified" class="space-y-4">
        {{-- Header --}}
        @if($canManage)
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-base font-semibold text-black">
                        Enlaces Sociales
                    </h3>
                </div>

                @if($socialLinks->count() < 4)
                    <button type="button" id="toggleFormBtn"
                        class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Agregar
                    </button>
                @elseif($socialLinks->count() >= 4)
                    <div
                        class="text-xs font-semibold text-yellow-800 bg-yellow-100 border border-yellow-300 px-2 py-1 rounded shadow-sm">
                        Máximo alcanzado
                    </div>
                @endif
            </div>
        @else
            <div class="text-center lg:text-left">
                <h3 class="text-base font-semibold text-black">
                    Enlaces Sociales
                </h3>
            </div>
        @endif

        {{-- Formulario de agregar (solo en modo gestión) --}}
        @if($canManage)
            <div id="addLinkForm"
                class="hidden bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <form id="socialLinkForm">
                    @csrf
                    <div>
                        <label for="url" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                            URL del perfil social
                        </label>
                        <input type="url" id="url" name="url" placeholder="https://instagram.com/usuario"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:border-transparent dark:bg-gray-700 dark:text-white placeholder-gray-400 text-sm transition-all duration-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500"
                            required>
                        <div id="urlError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>

                    <div class="flex space-x-2 mt-3">
                        <button type="submit" id="submitBtn"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 disabled:opacity-50">
                            <span class="submit-text">Agregar</span>
                            <span class="loading-text hidden">Agregando...</span>
                        </button>
                        <button type="button" id="cancelBtn"
                            class="px-3 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition-all duration-200">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Grid de enlaces sociales --}}
        @if($socialLinks->count() > 0)
            @php
                $gridClasses = $canManage ? 'grid-cols-1' : ($socialLinks->count() == 1 ? 'grid-cols-1' : 'grid-cols-2');
                $containerClasses = !$canManage && $socialLinks->count() == 1 
                    ? 'justify-items-center max-w-xs mx-auto lg:max-w-sm lg:mx-0 lg:justify-items-start' 
                    : (!$canManage ? 'justify-items-center max-w-sm mx-auto lg:max-w-none lg:mx-0 lg:justify-items-start' : '');
            @endphp
            <div class="grid {{ $gridClasses }} gap-2 {{ $containerClasses }}"
                id="socialLinksGrid">
                @foreach($socialLinks as $index => $link)
                    @php
                        $singleLinkClass = !$canManage && $socialLinks->count() == 1 ? 'max-w-sm' : '';
                    @endphp
                    <div class="social-link-item relative group overflow-hidden bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-lg p-3 border border-gray-100 dark:border-gray-700 transition duration-200 ease-in-out sm:hover:shadow-md sm:hover:-translate-y-0.5 sm:hover:bg-gray-100 dark:sm:hover:bg-gray-800 w-full {{ $singleLinkClass }}"
                        style="border-left: 3px solid {{ $link->getPlatformColor() }};">

                        <div class="relative flex items-center space-x-3 {{ !$canManage ? 'justify-between' : '' }}">
                            {{-- Ícono de la plataforma --}}
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm"
                                style="background: linear-gradient(135deg, {{ $link->getPlatformColor() }}15, {{ $link->getPlatformColor() }}25);">
                                <i class="{{ $link->getPlatformIcon() }} text-lg"
                                    style="color: {{ $link->getPlatformColor() }};"></i>
                            </div>

                            {{-- Información del enlace --}}
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-gray-900 dark:text-white text-sm mb-0.5">
                                    {{ ucfirst($link->platform) }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    {{ $link->username ?? (explode('/', parse_url($link->url, PHP_URL_PATH))[1] ?? parse_url($link->url, PHP_URL_HOST)) }}
                                </div>
                            </div>

                            {{-- Botones de acción (solo en modo gestión) --}}
                            @if($canManage)
                                <div
                                    class="flex items-center space-x-1 social-link-actions opacity-100 transition-opacity duration-200">
                                    {{-- Mover arriba --}}
                                    @if(!$loop->first)
                                        <button type="button" onclick="moveLink({{ $link->id }}, 'up')"
                                            class="p-2 text-gray-500 rounded-lg"
                                            title="Mover arriba">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                            </svg>
                                        </button>
                                    @endif

                                    {{-- Mover abajo --}}
                                    @if(!$loop->last)
                                        <button type="button" onclick="moveLink({{ $link->id }}, 'down')"
                                            class="p-2 text-gray-500 rounded-lg"
                                            title="Mover abajo">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    @endif

                                    {{-- Eliminar --}}
                                    <button type="button" onclick="deleteLink({{ $link->id }})"
                                        class="p-2 text-red-500 rounded-lg"
                                        title="Eliminar enlace">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                {{-- En modo display solo mostrar enlace externo --}}
                                <div class="text-gray-400 dark:text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Enlace clicable en toda el área --}}
                        <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" class="absolute inset-0 z-0"
                            title="Visitar {{ ucfirst($link->platform) }}">
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">
                @if($canManage)
                    No hay enlaces sociales agregados. Haz clic en "Agregar" para agregar tu primer enlace.
                @else
                    No hay enlaces sociales disponibles.
                @endif
            </div>
        @endif

        {{-- Mensajes --}}
        <div id="messageContainer" class="hidden"></div>
    </div>
@endif

{{-- CSS para efectos --}}
<style>
    .social-link-item {
        position: relative;
        z-index: 1;
    }

    .social-link-item button {
        position: relative;
        z-index: 10;
    }

    .social-link-actions {
        opacity: 1; /* Siempre visible en móvil */
        transition: opacity 0.2s;
    }

    /* Solo aplicar efectos hover en pantallas medianas y grandes (640px+) */
    @media (min-width: 640px) {
        .social-link-actions {
            opacity: 0;
        }
        
        .social-link-item:hover .social-link-actions {
            opacity: 1;
        }
    }
</style>

{{-- JavaScript solo para modo gestión --}}
@if($canManage)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Elementos del DOM
            const toggleFormBtn = document.getElementById('toggleFormBtn');
            const addLinkForm = document.getElementById('addLinkForm');
            const socialLinkForm = document.getElementById('socialLinkForm');
            const cancelBtn = document.getElementById('cancelBtn');
            const submitBtn = document.getElementById('submitBtn');
            const urlInput = document.getElementById('url');
            const urlError = document.getElementById('urlError');
            const messageContainer = document.getElementById('messageContainer');

            // Event listeners
            if (toggleFormBtn) toggleFormBtn.addEventListener('click', toggleForm);
            if (cancelBtn) cancelBtn.addEventListener('click', hideForm);
            if (socialLinkForm) socialLinkForm.addEventListener('submit', handleSubmit);

            function toggleForm() {
                if (addLinkForm.classList.contains('hidden')) {
                    showForm();
                } else {
                    hideForm();
                }
            }

            function showForm() {
                addLinkForm.classList.remove('hidden');
                urlInput.focus();
            }

            function hideForm() {
                addLinkForm.classList.add('hidden');
                socialLinkForm.reset();
                hideError();
            }

            function handleSubmit(e) {
                e.preventDefault();

                const formData = new FormData(socialLinkForm);

                // Mostrar loading
                setLoading(true);
                hideError();

                fetch('/social-links', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        setLoading(false);

                        if (data.success) {
                            showMessage(data.message, 'success');
                            hideForm();
                            // Recargar página para mostrar el nuevo enlace
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showError(data.error);
                        }
                    })
                    .catch(error => {
                        setLoading(false);
                        showError('Error al agregar el enlace. Inténtalo de nuevo.');
                        console.error('Error:', error);
                    });
            }

            // Funciones globales para los botones
            window.deleteLink = function (linkId) {
                if (!confirm('¿Estás seguro de eliminar este enlace?')) {
                    return;
                }

                fetch(`/social-links/${linkId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showMessage(data.message, 'success');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showMessage(data.error, 'error');
                        }
                    })
                    .catch(error => {
                        showMessage('Error al eliminar el enlace.', 'error');
                        console.error('Error:', error);
                    });
            };

            window.moveLink = function (linkId, direction) {
                const endpoint = direction === 'up' ? 'move-up' : 'move-down';

                fetch(`/social-links/${linkId}/${endpoint}`, {
                    method: 'PATCH',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Recargar para mostrar el nuevo orden
                            window.location.reload();
                        } else {
                            showMessage(data.error, 'error');
                        }
                    })
                    .catch(error => {
                        showMessage('Error al mover el enlace.', 'error');
                        console.error('Error:', error);
                    });
            };

            function setLoading(loading) {
                const submitText = submitBtn.querySelector('.submit-text');
                const loadingText = submitBtn.querySelector('.loading-text');

                if (loading) {
                    submitText.classList.add('hidden');
                    loadingText.classList.remove('hidden');
                    submitBtn.disabled = true;
                } else {
                    submitText.classList.remove('hidden');
                    loadingText.classList.add('hidden');
                    submitBtn.disabled = false;
                }
            }

            function showError(message) {
                urlError.textContent = message;
                urlError.classList.remove('hidden');
            }

            function hideError() {
                urlError.classList.add('hidden');
            }

            function showMessage(message, type) {
                const className = type === 'success'
                    ? 'bg-green-100 border border-green-400 text-green-700'
                    : 'bg-red-100 border border-red-400 text-red-700';

                messageContainer.innerHTML = `
                        <div class="${className} px-4 py-3 rounded">
                            ${message}
                        </div>
                        `;
                messageContainer.classList.remove('hidden');

                setTimeout(() => {
                    messageContainer.classList.add('hidden');
                }, 5000);
            }
        });
    </script>
@endif