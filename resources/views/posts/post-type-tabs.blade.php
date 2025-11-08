<!-- Componente para los tabs de selección de tipo de post -->
<div class="flex justify-center mb-8" x-data="{ tab: 'imagen' }">
    <div class="bg-white rounded-2xl p-2 shadow-lg flex flex-wrap gap-2 justify-center border border-gray-100">
        <button id="tab-imagen"
            :class="tab === 'imagen' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-50'"
            class="tab-button px-6 py-3 rounded-xl font-semibold text-sm flex items-center gap-2 transition-all duration-200"
            @click="tab = 'imagen'; switchTab('imagen')">
            <i class="fas fa-camera text-lg"></i>
            Foto
        </button>
        <button id="tab-musica"
            :class="tab === 'musica' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-50'"
            class="tab-button px-6 py-3 rounded-xl font-semibold text-sm flex items-center gap-2 transition-all duration-200"
            @click="tab = 'musica'; switchTab('musica')">
            <i class="fas fa-music text-lg"></i>
            Música
        </button>
        <button id="tab-texto"
            :class="tab === 'texto' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-50'"
            class="tab-button px-6 py-3 rounded-xl font-semibold text-sm flex items-center gap-2 transition-all duration-200"
            @click="tab = 'texto'; switchTab('texto')">
            <i class="fas fa-font text-lg"></i>
            Texto
        </button>
        <button id="tab-archivo"
            :class="tab === 'archivo' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-50'"
            class="tab-button px-6 py-3 rounded-xl font-semibold text-sm flex items-center gap-2 transition-all duration-200"
            @click="tab = 'archivo'; switchTab('archivo')">
            <i class="fas fa-file text-lg"></i>
            Archivo
        </button>
    </div>
</div>
