@auth
    <a href="{{ route('posts.create') }}"
        class="new-post block bg-white border border-gray-200 rounded-2xl shadow-sm p-3 sm:p-4 mb-4 sm:mb-6 transition-colors duration-200 hover:bg-gray-100 hover:border-gray-300 cursor-pointer">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2 sm:space-x-3">
                <img class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover border-2 border-[#3B25DD]"
                    src="{{ auth()->user()->imagen ? asset('perfiles/' . auth()->user()->imagen) : asset('img/img.jpg') }}"
                    alt="Foto de perfil de {{ auth()->user()->username }}">
                <div class="flex flex-col">
                    <span class="text-xs sm:text-sm font-semibold text-gray-900">{{ auth()->user()->username }}</span>
                    <span class="select-none text-gray-500 text-xs sm:text-sm flex items-center">
                        ¿Qué Novedades Tienes?
                    </span>
                </div>
            </div>
            <!-- Icono de lápiz alineado al final -->
            <div class="flex-shrink-0 pl-2 sm:pl-3">
                <!-- Icono de nuevo collage -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 sm:w-10 sm:h-10 text-[#121212]" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <rect x="3" y="3" width="7" height="7" rx="2" stroke="currentColor" stroke-width="2" fill="none" />
                    <rect x="14" y="3" width="7" height="7" rx="2" stroke="currentColor" stroke-width="2" fill="none" />
                    <rect x="3" y="14" width="7" height="7" rx="2" stroke="currentColor" stroke-width="2" fill="none" />
                    <rect x="14" y="14" width="7" height="7" rx="2" stroke="currentColor" stroke-width="2" fill="none" />
                </svg>
            </div>
        </div>
    </a>
@endauth