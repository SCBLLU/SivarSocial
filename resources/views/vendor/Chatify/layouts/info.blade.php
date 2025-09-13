{{--
    Panel lateral derecho: muestra la información del usuario seleccionado en el chat.
    Incluye el avatar, nombre y botón para eliminar la conversación.
--}}
{{-- Avatar y nombre de usuario genéricos --}}
<div class="avatar av-l chatify-d-flex"></div>
<p class="info-name">{{ config('chatify.name') }}</p>
{{-- Botón para ver perfil --}}
<div class="messenger-infoView-btns">
    <a href="#" class="view-profile-btn default" style="display: none;">Ver Perfil</a>
</div>
{{-- Botón para eliminar la conversación --}}
<div class="messenger-infoView-btns">
    <a href="#" class="danger delete-conversation">Eliminar Conversación</a>
</div>
{{-- Fotos compartidas en la conversación --}}
<div class="messenger-infoView-shared">
    <p class="messenger-title"><span>Fotos Compartidas</span></p>
    <div class="shared-photos-list"></div>
</div>
