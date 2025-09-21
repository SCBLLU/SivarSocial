@extends('layouts.app-su')

@section('view-contenido')
<div class="page-content">
	<main>
		<section>
		  <div class="container mx-auto px-4">
		    <div class="flex flex-wrap -mx-4 ">
		      <!-- Perfil izquierdo -->
		      <div class="w-full xl:w-1/3 px-4 mb-4 xl:mb-0">
		        <div class="card profile-card bg-white shadow-sm p-6">
		          <h3 class="card-title text-xl font-semibold text-gray-800">Anuncios Creados</h3>
		        </div>
		      </div>

		      <!-- Contenido derecho -->
		      <div class="w-full xl:w-2/3 px-4">
		        <div class="card content-box-card bg-white shadow-sm rounded-xl p-6">
		          <div class="gap-3">

								@if (session('success'))
								    <div class="flex justify-center px-4">
								        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
								            class="relative w-full px-4 py-3 mb-6 text-green-700 bg-green-100 border border-green-400 rounded">
								            <span class="block sm:inline">{{ session('success') }}</span>
								        </div>
								    </div>
								@endif

								<div class="pl-2 pr-2 mx-auto">
								  <h2 class="text-2xl font-bold mb-6 text-gray-800">Crear Anuncio</h2>

									<form id="adForm" action="{{ route('su.ads.create') }}" method="POST" class="space-y-5">
										@csrf
										<!-- URL O Imagen -->
										<div id="upload-area"
										    class="border-2 border-dashed border-gray-300 rounded-xl p-8 transition-all relative">
										    <!-- agregar la clase: hover:border-blue-400 hover:bg-blue-50/50 cursor-pointer al activar denuevo -->
										    
										    <!-- Input oculto -->
										    <input type="file" id="file-input" name="file" accept=".jpg,.jpeg,.png,.svg" class="hidden" disabled>

										    <!-- Contenido por defecto -->
										    <div id="upload-content" class="flex flex-col items-center gap-4">
										        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
										            <i class="fas fa-cloud-upload-alt text-gray-500 text-2xl"></i>
										        </div>
										        <div class="text-center">
										            <h3 class="text-lg font-semibold text-gray-800 mb-2">Subir Foto</h3>
										            <p class="text-gray-600 text-sm">
										                <span class="hidden md:inline">Arrastra una imagen aquí o </span>
										                <span class="text-gray-500 font-medium">haz clic para seleccionar</span>
										            </p>
										            <p class="text-gray-400 text-xs mt-1">JPG, PNG o SVG hasta 20MB • La imagen debe ser 1:1</p>
										        </div>
										    </div>

										    <!-- Vista previa oculta -->
										    <div id="preview" class="hidden flex flex-col items-center gap-3">
										        <img id="preview-img" class="w-32 h-32 object-contain rounded-lg border" alt="Vista previa">
										        <button id="remove-btn" type="button" 
										            class="text-red-500 text-sm hover:underline">
										            Quitar imagen
										        </button>
										    </div>
										</div>

										<!-- Divisor -->
										<div class="flex items-center my-2 mb-[20px]">
										  <div class="flex-grow border-t-2 border-dashed border-gray-400"></div>
										  <span class="mx-4 text-gray-600 font-medium">o</span>
										  <div class="flex-grow border-t-2 border-dashed border-gray-400"></div>
										</div>

										<!-- Imagen URL -->
									    <div>
									      <label for="image_url" class="block text-sm font-medium text-gray-700">Imagen (URL) • La imagen debe ser 1:1</label>
									      <div class="mt-1 flex flex-col md:flex-row gap-3">
									        <input type="text" id="image_url" name="image_url" placeholder="https://ejemplo.com/imagen.svg"
									          class="p-[10px] border border-[#adadad] w-full rounded-lg border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500" />

									      </div>
									    </div>

									    <!-- Divisor -->
										<div class="flex items-center my-6 mt-[55px]">
										  <div class="flex-grow border-t-2 border-dashed border-gray-400"></div>
										  <span class="mx-4 text-gray-600 font-medium">Body</span>
										  <div class="flex-grow border-t-2 border-dashed border-gray-400"></div>
										</div>

									    <!-- Título -->
									    <div>
									      <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
									      <input type="text" id="title" placeholder="Agregar un titulo al anuncio" name="title"
									        class="p-[10px] border border-[#adadad] mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500" />
									    </div>

									    <!-- Contenido -->
									    <div>
									      <label for="content" class="block text-sm font-medium text-gray-700">Contenido</label>
									      <textarea id="content" name="content" placeholder="Agregar un contenido" rows="3"
									        class="p-[10px] border border-[#adadad] mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500"></textarea>
									    </div>

									    <!-- Texto del botón -->
									    <div>
									      <label for="action_text" class="block text-sm font-medium text-gray-700">Texto del Botón</label>
									      <input type="text" id="action_text" placeholder="ej: iniciar" name="action_text"
									        class="p-[10px] border border-[#adadad] mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500" />
									    </div>

									    <!-- URL del botón -->
									    <div>
									      <label for="action_url" class="block text-sm font-medium text-gray-700">URL del Botón</label>
									      <input type="text" id="action_url" name="action_url" placeholder="(agregar la ruta ej: url.view)" 
									        class="p-[10px] border border-[#adadad] mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500" />
									    </div>

									    <!-- Divisor -->
										<div class="flex items-center my-6">
										  <div class="flex-grow border-t-2 border-dashed border-gray-400"></div>
										  <span class="mx-4 text-gray-600 font-medium">More</span>
										  <div class="flex-grow border-t-2 border-dashed border-gray-400"></div>
										</div>

									    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
									    	<!-- Tipo -->
									      <div>
									        <label for="type" class="block text-sm font-medium text-gray-700">Tipo</label>
									        <select id="type" name="type"
													  class="p-[10px] border border-[#adadad] mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500">
													  <option value="" disabled selected>Selecciona un tipo</option>
													  <option value="feature">Feature</option>
													  <option value="update">Update</option>
													  <option value="info">Info</option>
													</select>
									      </div>
									        <!-- Estado -->
									      <div>
									        <label for="is_active" class="block text-sm font-medium text-gray-700">Activo</label>
										     <input type="number" id="is_active" placeholder="ej:0" name="is_active" min="0" max="1"
										     class="p-[10px] border border-[#adadad] mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500" />
									      </div>
									    </div>

									    <!-- Fechas -->
									    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
									      <div>
									        <label for="start_date" class="block text-sm font-medium text-gray-700">Fecha de inicio</label>
									        <input type="date" id="start_date" name="start_date"
									          class="p-[10px] border border-[#adadad] mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500" />
									      </div>
									      <div>
									        <label for="end_date" class="block text-sm font-medium text-gray-700">Fecha de fin</label>
									        <input type="date" id="end_date" name="end_date"
									          class="p-[10px] border border-[#adadad] mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500" />
									      </div>
									    </div>

									    <!-- Botones -->
									    <div class="pt-4 flex gap-3">
									      <button type="submit"
									        class="flex-1 bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-blue-700 transition">
									        Guardar Anuncio
									      </button>
									      <button type="button" id="previewBtn"
									        class="flex-1 bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-gray-700 transition">
									        Vista Previa
									      </button>
									    </div>
									  </form>
								</div>

								<!-- preview -->

								<!-- Modal Banner de Novedades - Estilo Instagram/Spotify -->
								<div id="bannerModal" class="fixed inset-0 flex items-end justify-center md:items-center hidden"  style="background-color: rgba(0, 0, 0, 0.75); z-index: 1200;">
								    <!-- Contenedor del Modal -->
								    <div id="modalContainer" class="relative bg-white w-full md:mx-4 md:mb-0 rounded-t-2xl md:rounded-2xl shadow-2xl md:max-w-md lg:max-w-lg transition-transform duration-300 ease-out" style="height: auto; max-height: 85vh; overflow: hidden !important;">
								        <!-- Drag handle para móvil -->
								        <div id="dragHandle" class="flex justify-center pt-4 pb-2 md:hidden cursor-grab">
								            <div class="w-12 h-1 bg-gray-300 rounded-full"></div>
								        </div>

								        <!-- Imagen/Icono del banner -->
								        <div class="flex justify-center py-4" id="previewIconArea">
								            <!-- Aquí se rellena dinámicamente -->
								        </div>

								        <!-- Contenido del modal -->
								        <div class="px-6 pb-6 text-center">
								            <!-- Título -->
								            <h2 id="previewTitle" class="text-xl md:text-2xl font-bold text-gray-900 mb-3 leading-tight"></h2>

								            <!-- Descripción -->
								            <div id="previewContent" class="text-gray-600 text-sm md:text-base mb-6 leading-relaxed"></div>

								            <!-- Botones de acción -->
								            <div id="previewActions" class="space-y-3"></div>
								        </div>
								    </div>
								</div>
								<!-- End preview -->

	            </div>
		        </div>
		      </div>

		    </div>
		  </div>
		</section>
	</main>
</div>

<script>
	const uploadArea = document.getElementById("upload-area");
	const fileInput = document.getElementById("file-input");
	const preview = document.getElementById("preview");
	const previewImg = document.getElementById("preview-img");
	const uploadContent = document.getElementById("upload-content");
	const removeBtn = document.getElementById("remove-btn");

	// Abrir selector al hacer clic
	uploadArea.addEventListener("click", () => fileInput.click());

	// Arrastrar sobre el área
	uploadArea.addEventListener("dragover", (e) => {
	  e.preventDefault();
	  uploadArea.classList.add("border-blue-500", "bg-blue-50/50");
	});
	uploadArea.addEventListener("dragleave", () => {
	  uploadArea.classList.remove("border-blue-500", "bg-blue-50/50");
	});

	// Soltar archivo
	uploadArea.addEventListener("drop", (e) => {
	  e.preventDefault();
	  uploadArea.classList.remove("border-blue-500", "bg-blue-50/50");
	  if (e.dataTransfer.files.length) {
	    fileInput.files = e.dataTransfer.files;
	    showPreview(fileInput.files[0]);
	  }
	});

	// Seleccionar archivo con el input
	fileInput.addEventListener("change", () => {
	  if (fileInput.files.length) {
	    showPreview(fileInput.files[0]);
	  }
	});

	// Mostrar vista previa
	function showPreview(file) {
	  const reader = new FileReader();
	  reader.onload = (e) => {
	    previewImg.src = e.target.result;
	    preview.classList.remove("hidden");
	    uploadContent.classList.add("hidden");
	  };
	  reader.readAsDataURL(file);
	}

	// Quitar imagen
	removeBtn.addEventListener("click", () => {
	  fileInput.value = "";
	  preview.classList.add("hidden");
	  uploadContent.classList.remove("hidden");
	});

	// Extensiones permitidas
	const allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/svg+xml"];

	// Mostrar vista previa con validación
	function showPreview(file) {
	  if (!allowedTypes.includes(file.type)) {
	    alert("Formato no válido. Solo se permiten: JPG, JPEG, PNG, SVG");
	    fileInput.value = ""; // limpiar input
	    return;
	  }

	  const reader = new FileReader();
	  reader.onload = (e) => {
	    previewImg.src = e.target.result;
	    preview.classList.remove("hidden");
	    uploadContent.classList.add("hidden");
	  };
	  reader.readAsDataURL(file);
	}

	// --- Integración con Preview Modal ---
	document.getElementById("previewBtn").addEventListener("click", () => {
	    const type = document.getElementById("type").value;
	    const title = document.getElementById("title").value;
	    const content = document.getElementById("content").value;
	    const actionText = document.getElementById("action_text").value;
	    const actionUrl = document.getElementById("action_url").value;
	    const imageUrl = document.getElementById("image_url").value;

	    // --- Icono o imagen ---
	    const iconArea = document.getElementById("previewIconArea");
	    iconArea.innerHTML = "";
	    if (previewImg.src && !preview.classList.contains("hidden")) {
	        // Si subió un archivo
	        iconArea.innerHTML = `<img src="${previewImg.src}" alt="Preview" class="w-20 h-20 object-cover rounded-lg">`;
	    } else if (imageUrl) {
	        // Si puso una URL
	        iconArea.innerHTML = `<img src="${imageUrl}" alt="Preview" class="w-20 h-20 object-cover rounded-lg">`;
	    } else {
	        // Si no hay imagen -> ícono por tipo
	        let iconClass = "fas fa-info-circle text-gray-600";
	        if (type === "feature") iconClass = "fas fa-star text-purple-600";
	        if (type === "update") iconClass = "fas fa-sync-alt text-blue-600";
	        iconArea.innerHTML = `<i class="${iconClass} text-4xl"></i>`;
	    }

	    // --- Título y contenido ---
	    document.getElementById("previewTitle").textContent = title;
	    document.getElementById("previewContent").innerHTML = content;

	    // --- Botones según tipo ---
	    const actions = document.getElementById("previewActions");
	    actions.innerHTML = "";
	    if (type === "feature") {
	        if (actionUrl) {
	            actions.innerHTML += `
	                <button onclick="window.open('${actionUrl}','_blank')"
	                    class="w-full bg-blue-800 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-xl">
	                    Probar ${actionText || "Nueva Función"}
	                </button>`;
	        }
	        actions.innerHTML += `
	            <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-xl"
	                onclick="closeModal()">
	                Tal vez más tarde
	            </button>`;
	    }
	    if (type === "update") {
	        actions.innerHTML += `
	            <button class="w-full bg-blue-800 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-xl"
	                onclick="closeModal()">
	                Enterado
	            </button>`;
	        if (actionUrl) {
	            actions.innerHTML += `
	                <button onclick="window.open('${actionUrl}','_blank')"
	                    class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-xl">
	                    ${actionText || "Ver detalles"}
	                </button>`;
	        }
	    }
	    if (type === "info" && actionUrl) {
	        actions.innerHTML += `
	            <button onclick="closeModal()"
	                class="w-full bg-blue-800 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-xl">
	                ${actionText || "Enterado"}
	            </button>`;
	    }

	    // Mostrar modal
	    document.getElementById("bannerModal").classList.remove("hidden");
	    document.body.style.overflow = 'hidden';
	});

	function closeModal() {
	    document.getElementById("bannerModal").classList.add("hidden");
	    document.body.style.overflow = '';
	}

</script>
@endsection