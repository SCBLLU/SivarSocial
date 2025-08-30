<!-- Sugerencias de géneros estilo iTunes -->
<div class="space-y-3">
    <h4 class="text-white text-sm font-medium mb-3">Géneros populares</h4>
    <div class="grid grid-cols-2 gap-2">
        @foreach ($genres as $genre)
            <button type="button" 
                    onclick="searchByGenre('{{ $genre }}')"
                    class="bg-gray-800/60 hover:bg-gray-700/60 text-white text-sm px-4 py-2 rounded-full border border-gray-600/50 hover:border-blue-500/50 transition-all duration-200 text-left">
                <span class="block truncate">{{ $genre }}</span>
            </button>
        @endforeach
    </div>
</div>
