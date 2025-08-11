<div>
    {{-- Barra de búsqueda optimizada para móvil --}}
    <div class="bg-white rounded-[1.5rem] sm:rounded-[2rem] shadow-md border border-gray-200 w-full max-w-2xl mx-auto p-3 sm:p-4 lg:p-6 mb-4 sm:mb-6">
        <div class="relative">
            <input type="text" 
                   wire:model.live.debounce.300ms="searchTerm"
                   placeholder="Buscar usuarios..."
                   class="w-full pl-10 pr-10 py-2.5 sm:py-3 border border-gray-300 rounded-full focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base transition-all duration-200">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            {{-- Botón limpiar búsqueda en móvil --}}
            @if($searchTerm)
                <button wire:click="$set('searchTerm', '')" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400 hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>
        
        {{-- Contador de resultados --}}
        <div class="mt-2 sm:mt-3 text-center">
            <p class="text-xs sm:text-sm text-gray-600">
                @if($searchTerm)
                    <span class="block sm:inline">Resultados para "<span class="font-semibold">{{ $searchTerm }}</span>"</span>
                @else
                    <span class="font-semibold">{{ number_format($totalCount) }}</span> 
                    <span class="hidden sm:inline">{{ $type === 'followers' ? 'seguidores' : 'usuarios seguidos' }}</span>
                    <span class="sm:hidden">{{ $type === 'followers' ? 'seguidores' : 'seguidos' }}</span>
                @endif
            </p>
        </div>
    </div>

    {{-- Lista de usuarios --}}
    @if ($users->count())
        <div class="bg-white rounded-[1.5rem] sm:rounded-[2rem] shadow-md border border-gray-200 w-full max-w-2xl mx-auto overflow-hidden">
            {{-- Lista de usuarios --}}
            <div class="divide-y divide-gray-100">
                @foreach ($users as $listUser)
                    <x-user-list-item :user="$listUser" :component-key="$type.'-'.$listUser->id" />
                @endforeach
            </div>
        </div>

        {{-- Paginación optimizada para móvil --}}
        <div class="flex justify-center w-full mt-4 sm:mt-6 px-4">
            <div class="w-full max-w-2xl">
                {{ $users->links() }}
            </div>
        </div>

    @else
        <div class="bg-white rounded-[1.5rem] sm:rounded-[2rem] shadow-md border border-gray-200 w-full max-w-2xl mx-auto p-6 sm:p-8 text-center">
            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-300 mx-auto mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            
            @if($searchTerm)
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Sin resultados</h3>
                <p class="text-gray-500 text-sm mb-4">No se encontraron usuarios que coincidan con "{{ $searchTerm }}".</p>
                <button wire:click="$set('searchTerm', '')" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-full hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Limpiar búsqueda
                </button>
            @else
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">
                    {{ $type === 'followers' ? 'No hay seguidores aún' : 'No sigue a nadie aún' }}
                </h3>
                <p class="text-gray-500 text-sm px-2 sm:px-4">
                    {{ $user->name }} {{ $type === 'followers' ? 'aún no tiene seguidores' : 'aún no sigue a ningún usuario' }}.
                </p>
            @endif
        </div>
    @endif
</div>
