{{--
    Incluye los scripts JS necesarios para el funcionamiento de Chatify.
    - Pusher: para tiempo real.
    - EmojiButton: selector de emojis.
    - Variables globales de configuración (nombre, sonidos, extensiones, pusher, etc).
    - utils.js: utilidades varias.
    - code.js: lógica principal del chat.
--}}
<script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.0.3/dist/index.min.js"></script>
<script>
    // Gloabl Chatify variables from PHP to JS
    window.chatify = {
        name: "{{ config('chatify.name') }}",
        sounds: {!! json_encode(config('chatify.sounds')) !!},
        allowedImages: {!! json_encode(config('chatify.attachments.allowed_images')) !!},
        allowedFiles: {!! json_encode(config('chatify.attachments.allowed_files')) !!},
        maxUploadSize: {{ Chatify::getMaxUploadSize() }},
        pusher: {!! json_encode(config('chatify.pusher')) !!},
        pusherAuthEndpoint: '{{ route('pusher.auth') }}'
    };
    window.chatify.allAllowedExtensions = chatify.allowedImages.concat(chatify.allowedFiles);
    window.authUserId = {{ auth()->id() }};
    {{-- Variable necesaria para global-status.js --}}
</script>
<script src="{{ asset('js/chatify/utils.js') }}"></script>
<script src="{{ asset('js/chatify/code.js') }}"></script>
<script src="{{ asset('js/global-status.js') }}"></script> {{-- Sistema de estado activo global --}}
