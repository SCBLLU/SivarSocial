@auth
    <a href="{{ route('posts.create') }}"
        class="block bg-white border border-gray-200 rounded-2xl shadow-sm p-4 mb-6 transition-colors duration-200 hover:bg-gray-100 hover:border-gray-300 cursor-pointer">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0 flex items-center space-x-2">
                <img class="w-10 h-10 rounded-full object-cover border-2 border-[#3B25DD]"
                    src="{{ auth()->user()->imagen ? asset('perfiles/' . auth()->user()->imagen) : asset('img/usuario.svg') }}"
                    alt="Foto de perfil de {{ auth()->user()->username }}">
                <div class="flex flex-col">
                    <span class="text-sm font-semibold text-gray-900">{{ auth()->user()->username }}</span>
                    <span class="select-none text-gray-500">¿Qué Novedades Tienes?</span>
                </div>
            </div>
        </div>
    </a>
@endauth