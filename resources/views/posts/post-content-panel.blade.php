<!-- Panel contenedor -->
<div class="md:w-1/2 px-4 space-y-6">

    <!-- Contenido para post de imagen -->
    <div id="content-imagen"
        class="content-panel flex flex-col items-center justify-center transition-all duration-300 ease-in-out">
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="dropzone"
            class="w-full h-96 rounded-2xl border-2 border-dashed border-white/20 bg-white/10 backdrop-blur-md flex flex-col items-center justify-center transition-all duration-300 ease-in-out hover:border-white/40 hover:bg-white/15">
            @csrf
            <div class="flex flex-col items-center justify-center text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-2 text-white/70" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12V4m0 0L8 8m4-4l4 4" />
                </svg>
                <p class="text-sm text-white/60">Haz clic o arrastra tu imagen aquí</p>
            </div>
        </form>
        <p class="text-gray-300 text-sm text-center mt-4">PNG, JPG, GIF. Máx 2MB.</p>
    </div>

    <!-- Contenido para post de música -->
    <div id="content-musica" class="content-panel hidden transition-all duration-300 ease-in-out">
        @include('components.itunes.iTunes')
    </div>

</div>