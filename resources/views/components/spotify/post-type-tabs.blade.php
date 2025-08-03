<!-- Componente para los tabs de selección de tipo de post -->
<div class="flex justify-center mb-8" x-data="{ tab: 'foto' }">
    <div class="bg-black rounded-2xl p-2 flex gap-2">
        <button id="tab-imagen"
            :class="tab === 'foto' ? 'bg-white text-black border border-white' : 'bg-black text-white hover:bg-gray-900 border border-gray-700'"
            class="tab-button px-6 py-3 rounded-xl font-semibold text-sm flex items-center gap-2 transition-colors duration-200"
            @click="tab = 'foto'">
            <i class="fas fa-camera text-xl"></i>
            Foto
        </button>
        <button id="tab-musica"
            :class="tab === 'spotify' ? 'bg-white text-black border border-white' : 'bg-black text-white hover:bg-gray-900 border border-gray-700'"
            class="tab-button px-6 py-3 rounded-xl font-semibold text-sm flex items-center gap-2 transition-colors duration-200"
            @click="tab = 'spotify'">
            <i class="fab fa-spotify text-xl"></i>
            Spotify
        </button>
    </div>
</div>