<div>
    {{-- Título con contador --}}
    <div
        class="bg-white rounded-[1.5rem] sm:rounded-[2rem] shadow-md border border-gray-200 w-full max-w-md sm:max-w-2xl mx-auto p-4 sm:p-6 mb-4 sm:mb-6 text-center">
        <h2 class="text-lg sm:text-xl font-bold text-black mb-2">
            {{ $type === 'followers' ? 'Seguidores' : 'Siguiendo' }}
        </h2>
        <p class="text-sm sm:text-base text-gray-500">
            <span class="font-semibold">{{ number_format($totalCount) }}</span>
            {{ $type === 'followers' ? 'seguidores' : 'usuarios seguidos' }}
        </p>
    </div>

    {{-- Lista de usuarios --}}
    @if ($users->count())
        <div
            class="bg-white rounded-[1.5rem] sm:rounded-[2rem] shadow-md border border-gray-200 w-full max-w-md sm:max-w-2xl mx-auto overflow-hidden">
            {{-- Lista de usuarios --}}
            <div class="divide-y divide-gray-100">
                @foreach ($users as $listUser)
                    <x-user-list-item :user="$listUser" :show-follow-button="true"
                        :component-key="'follow-'.$type.'-'.$listUser->id" />
                @endforeach
            </div>
        </div>

        {{-- Paginación optimizada para móvil --}}
        <div class="flex justify-center w-full mt-4 sm:mt-6 px-4">
            {{ $users->links('custom.pagination') }}
        </div>

    @else
        <div
            class="bg-white rounded-[1.5rem] sm:rounded-[2rem] shadow-md border border-gray-200 w-full max-w-md sm:max-w-2xl mx-auto p-6 sm:p-8 text-center">
            <i class="fas fa-users text-gray-300 text-2xl sm:text-2xl mb-3 sm:mb-4"></i>

            <h3 class="text-base sm:text-lg font-bold text-black mb-2">
                {{ $type === 'followers' ? 'No hay seguidores aún' : 'No sigue a nadie aún' }}
            </h3>
            <p class="text-gray-500 text-sm px-2 sm:px-4">
                {{ $user->name }} {{ $type === 'followers' ? 'aún no tiene seguidores' : 'aún no sigue a ningún usuario' }}.
            </p>
        </div>
    @endif
</div>