<div>
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
            class="bg-white rounded-[1.5rem] sm:rounded-[2rem] shadow-md border border-gray-200 w-full max-w-md sm:max-w-2xl mx-auto p-3 sm:p-4 lg:p-6 text-center">
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