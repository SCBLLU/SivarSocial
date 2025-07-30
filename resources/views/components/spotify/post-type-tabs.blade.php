<!-- Componente para los tabs de selección de tipo de post -->
<div class="flex justify-center mb-8">
    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-2 flex gap-2">
        <button id="tab-imagen"
            class="tab-button active px-6 py-3 rounded-xl font-semibold text-sm flex items-center gap-2 bg-gradient-to-br from-green-500 to-green-400 text-white">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                    clip-rule="evenodd"></path>
            </svg>
            Imagen
        </button>
        <button id="tab-musica"
            class="tab-button px-6 py-3 rounded-xl font-semibold text-sm flex items-center gap-2 bg-white/10 text-white hover:bg-white/20">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100 2 1 1 0 000-2z"
                    clip-rule="evenodd"></path>
            </svg>
            Música
        </button>
    </div>
</div>

@push('styles')
    <style>
        /* Estilos para los tabs */
        .tab-button {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .tab-button:not(.active) {
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
        }

        .tab-button.active {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            transform: translateY(-1px);
        }

        .tab-button:hover:not(.active) {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-1px);
        }

        /* Efecto de ondas al hacer clic */
        .tab-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
            transform: scale(0);
            transition: transform 0.5s ease;
        }

        .tab-button:active::before {
            transform: scale(1);
        }

        /* Animación de los iconos */
        .tab-button svg {
            transition: transform 0.3s ease;
        }

        .tab-button.active svg {
            transform: scale(1.1);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Script específico para los tabs
        document.addEventListener('DOMContentLoaded', function () {
            // Agregar efectos de sonido o feedback adicional si es necesario
            const tabButtons = document.querySelectorAll('.tab-button');

            tabButtons.forEach(button => {
                button.addEventListener('click', function () {
                    // Agregar efecto visual al hacer clic
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });
        });
    </script>
@endpush