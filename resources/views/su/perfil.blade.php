@extends('layouts.app-su')

@section('view-contenido')
<div class="page-content">
	<main>
		<section>
		  <div class="container mx-auto px-4">
		    <div class="flex flex-wrap md:flex-nowrap -mx-4">
		      <!-- Perfil izquierdo -->
		      <div class="w-full xl:w-1/3 px-4 mb-4 xl:mb-0">
		        <div class="card profile-card bg-white shadow-sm p-6">
		        	<!-- contendo -->
		          <div class="image text-center mb-4">
		          	<div class="img-aspect hidden" id="profileimg">
		          		<img src="{{ $user->imagen_url }}" id="profileImage" alt="profile" class="mx-auto object-cover hidden">	
		          	</div>
		          </div>

		          <!-- skeleton -->
		          <div class="image skeleton" id="skeletonImage">
		          	<div role="status" class="flex space-y-8 animate-pulse md:space-y-0 md:space-x-8 rtl:space-x-reverse md:flex md:items-center justify-center">
								    <div class="flex items-center justify-center w-48 sm:w-[300px] h-48 sm:h-[300px] rounded-sm bg-gray-500">
								        <svg class="w-10 h-10 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
								            <path d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z"/>
								        </svg>
								    </div>
								</div>
		          </div>
		          <!-- contenido -->
		          <div class="text hidden" id="profileText">
		            <h3 class="card-title text-xl font-bold mb-2 text-black">{{ $user->name }}
								@if($user->insignia === 'Creador')
								    <x-user-badge :badge="$user->insignia" size="large" />
								@elseif($user->insignia)
								    <x-user-badge :badge="$user->insignia" size="large" />

								    {{-- Botones editar / eliminar --}}
								    <button 
								        type="button" 
								        id="openModal2"
								        class="ml-2 w-8 h-8 flex items-center justify-center border-2 border-dashed border-gray-400 rounded-full hover:border-blue-500 transition"
								    >
								        <i class='bx bx-edit-alt text-blue-600 text-lg'></i>
								    </button>

								    <button 
								        type="button" 
								        id="openModal3"
								        class="ml-2 w-8 h-8 flex items-center justify-center border-2 border-dashed border-gray-400 rounded-full hover:border-red-500 transition"
								    >
								        <i class='bx bx-trash text-red-600 text-lg'></i>
								    </button>
			           @else

								  <button 
								    type="button" 
								    id="openModal"
								    class="ml-2 w-8 h-8 flex items-center justify-center border-2 border-dashed border-gray-400 rounded-full hover:border-blue-500 transition"
								  >
								    <i class='bx bx-badge text-gray-600 text-lg'></i>
								  </button>
			            @endif	
		            </h3>
		            <p class="text-gray-600 text-sm mb-4">
		              <span class="font-semibold">Usuario:</span> {{ '@' . $user->username }} <br>
		              <span class="font-semibold">Profesión:</span> {{ $user->profession }} <br>
		              <span class="font-semibold">Email:</span> {{ $user->email }}
		            </p>

		          </div>
		          <!-- skeleton -->
		          <div class="text skeleton"  id="skeletonText">
								<div role="status" class="max-w-sm animate-pulse">
								    <div class="flex items-center w-full mb-4">
								        <div class="h-3.5 rounded-full bg-gray-500 w-18"></div>
								        <div class="h-6 ms-5 rounded-full bg-gray-400 w-6"></div>
								    </div>
								</div>
								<div role="status" class="space-y-2.5 animate-pulse max-w-lg">
								    <div class="flex items-center w-full">
								        <div class="h-2.5 rounded-full bg-gray-500 w-20"></div>
								        <div class="h-2.5 ms-2 rounded-full bg-gray-400 w-20"></div>
								    </div>
								    <div class="flex items-center w-full">
								        <div class="h-2.5 rounded-full bg-gray-500 w-26"></div>
								        <div class="h-2.5 ms-2 rounded-full bg-gray-400 w-10"></div>
								    </div>
								    <div class="flex items-center w-full">
								        <div class="h-2.5 rounded-full bg-gray-500 w-15"></div>
								        <div class="h-2.5 ms-2 rounded-full bg-gray-400 w-32"></div>
								    </div>
								</div>
		          </div>

		          <!-- Modal para agregar insignia -->
							<div id="badgeModal" class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden z-50">
							  <div id="modalContent" 
							    class="bg-white rounded-xl shadow-lg p-6 w-96 relative transform transition-all duration-300 opacity-0 scale-95">
							    
							    <!-- Botón cerrar -->
							    <button id="closeModal" class="absolute top-2 right-2 bg-gray-200 text-black w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-200">
							      <i class="bx bx-x text-xl"></i>
							    </button>
							    
							    <h2 class="text-xl text-black font-bold mb-4">Asignar insignia</h2>
								<form action="{{ route('su.add.insig', $user) }}" method="POST" class="space-y-5">
								    @csrf
								    <div>
								        <label for="type" class="block text-sm font-medium text-gray-700">Tipo</label>
								        <select id="type" name="type"
								            class="p-[10px] border border-[#adadad] mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500">
								            <option value="" disabled selected>Selecciona un tipo</option>
								            <option value="Colaborador">Colaborador</option>
								            <option value="Comunidad">Comunidad</option>
								        </select>
								     </div>

								     <!-- Botones -->
								    <div class="pt-4 flex gap-3">
								      <button type="submit"
								        class="flex-1 bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-blue-700 transition">
								        Agregar Insignia
								      </button>
								    </div>
								</form>
							  </div>
							</div>

							<!-- Modal para editar insignia -->
							<div id="badgeModal2" class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden z-50">
							  <div id="modalContent2" 
							    class="bg-white rounded-xl shadow-lg p-6 w-96 relative transform transition-all duration-300 opacity-0 scale-95">
							    
							    <!-- Botón cerrar -->
							    <button id="closeModal2" class="absolute top-2 right-2 bg-gray-200 text-black w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-200">
							      <i class="bx bx-x text-xl"></i>
							    </button>
							    
							    <h2 class="text-xl text-black font-bold mb-4">Editar insignia</h2>
							    <form action="{{ route('su.update.insig', $user) }}" method="POST" class="space-y-5">
							        @csrf
							        @method('PUT')
							        <div>
							            <label for="type" class="block text-sm font-medium text-gray-700">Tipo</label>
							            <select id="editType" name="type"
							                class="p-[10px] border border-[#adadad] mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500">
							                <option value="" disabled>Selecciona un tipo</option>
							                <option value="Colaborador" {{ $user->insignia === 'Colaborador' ? 'selected' : '' }}>Colaborador</option>
							                <option value="Comunidad" {{ $user->insignia === 'Comunidad' ? 'selected' : '' }}>Comunidad</option>
							            </select>
							         </div>

							         <!-- Botones -->
							        <div class="pt-4 flex gap-3">
							          <button type="submit" id="editBtn"
							            class="flex-1 bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
							            disabled>
							            Guardar cambios
							          </button>
							        </div>
							    </form>
							  </div>
							</div>

							<!-- Modal para eliminar insignia -->
							<div id="badgeModal3" class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden z-50">
							  <div id="modalContent3" 
							    class="bg-white rounded-xl shadow-lg p-6 w-96 relative transform transition-all duration-300 opacity-0 scale-95">
							    
							    <!-- Botón cerrar -->
							    <button id="closeModal3" class="absolute top-2 right-2 bg-gray-200 text-black w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-200">
							      <i class="bx bx-x text-xl"></i>
							    </button>
							    
							    <h2 class="text-xl text-black font-bold mb-4">Eliminar insignia</h2>
							    <p class="text-gray-600 mb-4">
							      ¿Estás seguro de eliminar la insignia del usuario: <span class="font-bold text-red-600">{{ $user->name }}</span>?
							    </p>
							    <p class="text-gray-600 mb-4">
							      Por seguridad Ingresa tu contraseña de verificación.
							    </p>
								<form action="{{ route('su.delete.insig', $user) }}" method="POST" class="space-y-5">
								    @csrf
								    @method('DELETE')

								    <div>
								        <label for="pass_verific" class="block text-sm font-medium text-gray-700">Password Verific:</label>
								        <div class="mt-1 flex flex-col md:flex-row gap-3">
								            <input type="password" id="pass_verific" name="pass_verific" placeholder="***********"
								                class="p-[10px] border border-[#adadad] w-full rounded-lg border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500" />
								        </div>

								        {{-- Aquí mostramos el error si la contraseña no es correcta --}}
								        @error('pass_verific')
								            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
								        @enderror
								    </div>

								    <!-- Botones -->
								    <div class="pt-4 flex gap-3">
								        <button type="submit"
								            id="deleteBtn"
								            class="flex-1 bg-red-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-red-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
								            disabled>
								            Eliminar Insignia
								        </button>
								    </div>
								</form>
							  </div>
							</div>

		        </div>
		      </div>

		      <!-- Contenido derecho -->
		      <div class="w-full xl:w-2/3 px-4">
		        <div class="card content-box-card bg-white shadow-sm rounded-xl p-6">
		          @if (session('success'))
							    <div class="flex justify-center px-4">
							        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
							            class="relative w-full px-4 py-3 mb-6 text-green-700 bg-green-100 border border-green-400 rounded">
							            <span class="block sm:inline">{{ session('success') }}</span>
							        </div>
							    </div>
							@endif
		          <h3 class="card-title text-xl font-semibold text-gray-800">Detalles</h3>
		          <div class="mt-4 flex gap-3 mb-4">
		          	<p class="text-black font-semibold">Se siente solo por aqui</p>
	            </div>
		        </div>
		      </div>

		    </div>
		  </div>
		</section>
	</main>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const img = document.getElementById("profileImage");
  const imgc = document.getElementById("profileimg");
  const skeletonImage = document.getElementById("skeletonImage");
  const skeletonText = document.getElementById("skeletonText");
  const profileText = document.getElementById("profileText");

  if (img.complete) {
    // La imagen ya está cargada (caso de cache)
    skeletonImage.classList.add("hidden");
    skeletonText.classList.add("hidden");
    img.classList.remove("hidden");
    imgc.classList.remove("hidden");
    profileText.classList.remove("hidden");
  } else {
    img.addEventListener("load", () => {
      skeletonImage.classList.add("hidden");
      skeletonText.classList.add("hidden");
      img.classList.remove("hidden");
      imgc.classList.remove("hidden");
      profileText.classList.remove("hidden");
    });
  }
});

document.getElementById('pass_verific').addEventListener('input', function() {
    document.getElementById('deleteBtn').disabled = this.value.trim() === "";
});

const editType = document.getElementById("editType");
const editBtn = document.getElementById("editBtn");

if (editType && editBtn) {
  const currentValue = editType.value;

  editType.addEventListener("change", () => {
    if (editType.value !== currentValue && editType.value !== "") {
      editBtn.disabled = false;
    } else {
      editBtn.disabled = true;
    }
  });
}

function initModal(openBtnId, modalId, modalContentId, closeBtnId) {
  const modal = document.getElementById(modalId);
  const modalContent = document.getElementById(modalContentId);
  const openBtn = document.getElementById(openBtnId);
  const closeBtn = document.getElementById(closeBtnId);

  if (!modal || !modalContent || !openBtn || !closeBtn) return;

  // Abrir modal con animación
  openBtn.addEventListener("click", () => {
    modal.classList.remove("hidden");
    setTimeout(() => {
      modalContent.classList.remove("opacity-0", "scale-95");
      modalContent.classList.add("opacity-100", "scale-100");
    }, 10);
  });

  // Función cerrar modal
  function closeModal() {
    modalContent.classList.remove("opacity-100", "scale-100");
    modalContent.classList.add("opacity-0", "scale-95");
    setTimeout(() => {
      modal.classList.add("hidden");
    }, 300);
  }

  // Botón cerrar
  closeBtn.addEventListener("click", closeModal);

  // Cerrar al hacer clic fuera del contenido
  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      closeModal();
    }
  });
}

// Inicializar modales
initModal("openModal", "badgeModal", "modalContent", "closeModal");
initModal("openModal2", "badgeModal2", "modalContent2", "closeModal2");
initModal("openModal3", "badgeModal3", "modalContent3", "closeModal3");
</script>
@endsection