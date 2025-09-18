<div>
    @if($socialLinks->count() > 0)
        <div class="space-y-2">
            <h3 class="text-base font-semibold text-black dark:text-black mb-2">
                Enlaces Sociales
            </h3>

            <!-- Grid pequeño y sin efectos -->
            <div class="grid grid-cols-2 gap-2">
                @foreach($socialLinks as $link)
                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                        class="relative overflow-hidden bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-lg p-2 border border-gray-100 dark:border-gray-700 transition duration-200 ease-in-out hover:shadow-md hover:-translate-y-0.5 hover:bg-gray-100 dark:hover:bg-gray-800"
                        style="border-left: 3px solid {{ $this->getPlatformColor($link->platform) }};">
                        <!-- Contenido del enlace -->
                        <div class="relative flex items-center space-x-2">
                            <!-- Ícono de la plataforma -->
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 shadow"
                                style="background: linear-gradient(135deg, {{ $this->getPlatformColor($link->platform) }}15, {{ $this->getPlatformColor($link->platform) }}25);">
                                <i class="{{ $link->icon }} text-base"
                                    style="color: {{ $this->getPlatformColor($link->platform) }};"></i>
                            </div>

                            <!-- Información del enlace -->
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-gray-900 dark:text-white text-xs mb-0.5">
                                    {{ ucfirst($link->platform) }}
                                </div>
                            </div>

                            <!-- Ícono de enlace externo minimalista -->
                            <div class="text-gray-300 dark:text-gray-600">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>