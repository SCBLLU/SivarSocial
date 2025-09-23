{{--
Incluye los meta tags, scripts y estilos necesarios para la interfaz de Chatify.
- Meta: datos de usuario, color, tema, CSRF, etc.
- Scripts: jQuery, FontAwesome, autosize, app.js, NProgress.
- Estilos: NProgress, Chatify (normal y dark), app.css.
- Define el color primario del chat como variable CSS.
--}}
<title>SivarChat</title>
<link rel="icon" href="{{ asset('img/icon.svg') }}" type="image/svg+xml">

{{-- Meta tags --}}
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="id" content="{{ $id }}">
<meta name="messenger-color" content="{{ $messengerColor }}">
<meta name="messenger-theme" content="{{ $dark_mode }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="url" content="{{ url('') . '/' . config('chatify.routes.prefix') }}" data-user="{{ Auth::user()->id }}">

{{-- scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/chatify/font.awesome.min.js') }}"></script>
<script src="{{ asset('js/chatify/autosize.js') }}"></script>
<script src='https://unpkg.com/nprogress@0.2.0/nprogress.js'></script>

{{-- styles --}}
<link rel='stylesheet' href='https://unpkg.com/nprogress@0.2.0/nprogress.css' />
<link href="{{ asset('css/chatify/style.css') }}" rel="stylesheet" />
<link href="{{ asset('css/chatify/' . $dark_mode . '.mode.css') }}" rel="stylesheet" />
@php
    $manifest = file_exists(public_path('build/manifest.json'))
        ? json_decode(file_get_contents(public_path('build/manifest.json')), true)
        : [];
    $viteAppCss = isset($manifest['resources/css/app.css']['file'])
        ? asset('build/' . $manifest['resources/css/app.css']['file'])
        : null;
@endphp
@if ($viteAppCss)
    <link href="{{ $viteAppCss }}" rel="stylesheet" />
@endif

{{-- Setting messenger primary color to css --}}
<style>
    :root {
        --primary-color: {{ $messengerColor }};
    }

    /* Estilos específicos para el indicador BETA */
    .beta-indicator {
        background: var(--primary-color) !important;
        font-size: 10px !important;
        padding: 2px 8px !important;
        border-radius: 5px !important;
        margin-left: 8px !important;
        letter-spacing: 1px !important;
        color: white !important;
        display: inline-block !important;
        vertical-align: baseline !important;
        position: relative !important;
        top: -3.1px !important;
    }
</style>
