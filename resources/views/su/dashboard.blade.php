@extends('layouts.app-su')

@section('view-contenido')
<div id="page-content">
  <!-- main area part start -->
    <section>
      <div class="container max-w-7xl mx-auto">
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
          
          <!-- Profile card -->
          <div class="xl:col-span-4">
            <div class="card profile-card bg-white shadow rounded-xl p-6">
              <div class="">
              	<h3 class="card-title text-xl font-semibold text-gray-800">Usuarios</h3>
                <div class="mt-4 flex gap-3 mb-4">

                  <div class="bg-white rounded-full shadow-sm w-full mx-auto"> 
                    <div class="flex items-center p-2 relative" style="height: 40px;">
                      
                      <!-- Icono de búsqueda -->
                      <i class="bx bx-search-alt-2 absolute left-3 text-gray-400"></i>
                      
                      <!-- Input ocupa todo el ancho -->
                      <input 
                        type="text" 
                        id="buscar" 
                        name="buscaru" 
                        placeholder="Buscar"
                        class="w-full rounded-full pl-10 pr-3 py-1 focus:outline-none"
                      >
                    </div>
                  </div>

                </div>

                <div id="resultados-busqueda" class="p-4 space-y-3 scrollable-list flex-1 pb-0 bg-white overflow-y-auto max-h-[490px]">
                  @if (isset($users) && $users->count())
                  @component('components.listar-perfiles-su', ['users' => $users]) @endcomponent
                  @else
                  <p>No hay usuarios disponibles.</p>
                  @endif
               </div>
              </div>
            </div>
          </div>

          <!-- Right content -->
          <div class="xl:col-span-8 space-y-6">

            <!-- Services -->
            <div class="bg-white shadow rounded-xl p-6">
              <h3 class="card-title text-xl font-semibold text-gray-800 flex items-center justify-between">
                Funcones
<!--                 <a href="" class="link-btn text-blue-600 hover:underline text-sm flex items-center gap-1">
                  ver funciones
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 20 20">
                    <path d="M4.16699 10H15.8337" />
                    <path d="M10.833 15L15.833 10" />
                    <path d="M10.833 5L15.833 10" />
                  </svg>
                </a> -->
              </h3>


              <div class="services-main mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{route('su.insig')}}" class="grid">
                  <div class="services-item text-center shadow-sm">
                    <div class="image">
                      <i class='bx bx-star text-black text-[84px]'></i>
                    </div>
                     <div class="text">
                      <h3 class="title mt-2 text-sm font-medium">Insignia</h3>
                     </div>
                  </div>
                </a>
                <a href="{{ route('su.ads')}}" class="grid">
                  <div class="services-item text-center shadow-sm">
                    <div class="image">
                      <i class='bx bx-customize bx-rotate-270 text-black text-[84px]' ></i>
                    </div>
                     <div class="text">
                      <h3 class="title mt-2 text-sm font-medium">Anuncio</h3>
                     </div>
                  </div>
                </a>
                <a href="" class="grid">
                  <div class="services-item text-center shadow-sm relative overflow-hidden">
                    <div class="image">
                      <i class='bx bx-line-chart text-black text-[84px]'></i>
                    </div>
                     <div class="text">
                      <h3 class="title mt-2 text-sm font-medium">Reportes</h3>
                     </div>
                     <!-- Overlay con blur -->
                    <div class="absolute inset-0 flex items-center justify-center bg-white/60 backdrop-blur-sm">
                      <span class="text-3xl font-bold text-gray-700">SOON</span>
                    </div>
                  </div>
                </a>
                <a href="" class="grid">
                  <div class="services-item text-center shadow-sm relative overflow-hidden">
                    <div class="image">
                      <img src="assets/img/icons/branding.svg" alt="branding" class="mx-auto h-12">
                    </div>
                     <div class="text">
                      <h3 class="title mt-2 text-sm font-medium">Branding</h3>
                     </div>
                     <!-- Overlay con blur -->
                    <div class="absolute inset-0 flex items-center justify-center bg-white/60 backdrop-blur-sm">
                      <span class="text-3xl font-bold text-gray-700">SOON</span>
                    </div>
                  </div>
                </a>
              </div>
            </div>

            <!-- Expert area + Let's work together -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
              
              <!-- Expert area -->
              <div class="card expertise-card bg-white shadow rounded-xl p-6 relative overflow-hidden">
                <h3 class="card-title text-xl font-semibold text-gray-800">Actividad en la Red</h3>
                
                <div class="mt-6 grid grid-cols-3 gap-4">
                  <!-- Aquí iría tu contenido futuro -->
                </div>

                <!-- Overlay con blur -->
                <div class="absolute inset-0 flex items-center justify-center bg-white/60 backdrop-blur-sm">
                  <span class="text-3xl font-bold text-gray-700">SOON</span>
                </div>
              </div>

              <!-- Let's talk -->
              <div class="lets-talk-together-card bg-white shadow rounded-xl p-6 relative overflow-hidden">
                 <div class="scrolling-info">
	                <div class="slider-item">
	                  <p>
	                    Available For Hire 🚀 Crafting Digital Experiences 🎨 Available For Hire 🚀 Crafting Digital
	                    Experiences 🎨
	                  </p>
	                </div>
	              </div>
                <h3 class="card-title mt-6 text-xl font-semibold text-gray-800">
                  Let's👋 <span class="block">Work Together</span>
                </h3>
                <a href=""
                  class="link-btn mt-4 inline-flex items-center gap-2 text-blue-600 hover:underline text-sm">
                  Let's Talk
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 20 20">
                    <path d="M17.5 11.6665V6.6665H12.5" />
                    <path d="M17.5 6.6665L10 14.1665L2.5 6.6665" />
                  </svg>
                </a>
                <!-- Overlay con blur -->
                <div class="absolute inset-0 flex items-center justify-center bg-white/60 backdrop-blur-sm">
                  <span class="text-3xl font-bold text-gray-700">SOON</span>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
  <!-- main area part end -->
</div>
@endsection