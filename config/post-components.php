<?php

/**
 * Configuración para los componentes de posts
 */

return [
    
    // Configuración del componente Spotify
    'spotify' => [
        
        // Configuración de búsqueda
        'search' => [
            'debounce_delay' => 300, // ms
            'min_characters' => 2,
            'max_results' => 20,
            'market' => 'ES' // Mercado de Spotify
        ],
        
        // Configuración de UI
        'ui' => [
            'show_recent_searches' => true,
            'max_recent_searches' => 5,
            'auto_hide_status' => 3000, // ms
            'enable_preview' => true,
            'show_progress_bar' => false,
            'show_popularity_indicator' => true,
            'animate_results' => true,
            'show_album_art_overlay' => true
        ],
        
    ],
    
    // Configuración del formulario
    'form' => [
        'validation' => [
            'titulo' => [
                'max_length' => 100,
                'required' => true
            ],
            'descripcion' => [
                'max_length' => 500,
                'required' => true
            ]
        ],
        
        'ui' => [
            'show_character_counter' => true,
            'enable_real_time_validation' => true,
            'submit_button_loading_text' => 'Creando...'
        ]
    ],
    
    // Configuración de animaciones
    'animations' => [
        'tab_transition_duration' => '0.3s',
        'panel_transition_duration' => '0.3s',
        'button_hover_scale' => '1.02',
        'shake_duration' => '0.5s'
    ],
    
    // Configuración de dropzone
    'dropzone' => [
        'accepted_files' => 'image/*',
        'max_file_size' => 20, // MB
        'max_files' => 1,
        'add_remove_links' => true,
        'dictDefaultMessage' => 'Arrastra aquí tu imagen o haz clic para seleccionar'
    ]
    
];
