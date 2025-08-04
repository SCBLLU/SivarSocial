<!-- Sugerencias de géneros musicales -->
<div class="bg-black/50 backdrop-blur-sm rounded-lg p-4">
    <h4 class="text-white font-medium mb-3 text-sm">Explorar por género</h4>
    
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
        @foreach($genres as $genre)
            <button type="button" 
                    onclick="performSearch('{{ $genre }}')"
                    class="p-2 text-sm bg-gray-800 hover:bg-gray-700 rounded-lg text-white transition-colors duration-200 hover:scale-105">
                {{ $genre }}
            </button>
        @endforeach
    </div>   
</div>
