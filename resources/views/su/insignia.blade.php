@extends('layouts.app-su')

@section('view-contenido')
<div class="page-content">
	<main>
		<section>
			<div class=" flex items-center justify-center">
		        <div class="w-full max-w-2xl mx-auto text-center">
		            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
		                {{-- Icono principal --}}
		                <div class="mb-8">
		                    <h2 class="text-4xl md:text-9xl font-bold text-[#3B25DD] mb-4">
		                        Soon
		                    </h2>
		                </div>

		                {{-- Mensaje principal --}}
		                <div class="mb-8">
		                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">
		                        ¡Oops! Esta página esta en desarrollo
		                    </h2>
		                    <p class="text-gray-600 text-lg mb-6 max-w-md mx-auto">
		                        Regresa nuevamente para ver las maravillas que tendremos para ti.
		                    </p>
		                </div>

		                {{-- Botones de acción --}}
		                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
		                    <a href="{{ route('su.dash') }}"
		                        class="inline-flex items-center px-6 py-3 bg-[#3B25DD] text-white font-semibold rounded-full">
		                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
		                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
		                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
		                            </path>
		                        </svg>
		                        Ir al dashboard
		                    </a>
		                </div>
		            </div>
		        </div>
			</div>
		</section>
	</main>
</div>
@endsection