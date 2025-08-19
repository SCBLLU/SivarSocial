# SivarSocial
Documentación técnica del repositorio del proyecto.

Instalación y Ejecución

### Paso 1: Clonar el Proyecto

```bash
git clone <Link del repositorio>
cd SivarSocial
```

### Paso 2: Instalar Dependencias

```bash
composer install
npm install
```

### Paso 3: Configurar Entorno

```bash
copy .env.example .env
php artisan key:generate
```

### Paso 4: Configurar Base de Datos

1. Crear base de datos `sivarsocial` en MySQL
2. Configurar credenciales en `.env`
3. Ejecutar migraciones:

```bash
php artisan migrate
```

### Paso 5: Iniciar el Proyecto

```bash
composer run dev
```

## Estructura Completa del Proyecto

```
SivarSocial/
├── app/
│   ├── Console/Commands/
│   │   └── SetupDatabase.php           # Comando personalizado setup
│   ├── Http/Controllers/
│   │   ├── ColaboradoresController.php # Página de colaboradores
│   │   ├── ComentarioController.php    # Gestión de comentarios
│   │   ├── Controller.php              # Controlador base
│   │   ├── FollowerController.php      # Sistema de seguimiento
│   │   ├── HomeController.php          # Página principal/feed
│   │   ├── ImagenController.php        # Subida de imágenes
│   │   ├── iTunesApiController.php     # API de iTunes
│   │   ├── LikeController.php          # Sistema de likes
│   │   ├── LoginController.php         # Inicio de sesión
│   │   ├── LogoutController.php        # Cierre de sesión
│   │   ├── PerfilController.php        # Edición de perfiles
│   │   ├── PostController.php          # Gestión de posts
│   │   ├── RecoverController.php       # Recuperación de contraseñas
│   │   ├── RegisterController.php      # Registro de usuarios
│   │   ├── SpotifyApiController.php    # API de Spotify
│   │   └── UserController.php          # Gestión de usuarios
│   ├── Livewire/
│   │   ├── CommentPost.php             # Agregar comentarios reactivo
│   │   ├── CommentsSection.php         # Sección de comentarios
│   │   ├── FollowersList.php           # Lista de seguidores
│   │   ├── FollowUser.php              # Seguir/No seguir reactivo
│   │   ├── LikePost.php                # Likes reactivos
│   │   ├── LikesModal.php              # Modal de usuarios que dieron like
│   │   ├── NotificationButton.php      # Botón de notificaciones
│   │   ├── NotificationsModal.php      # Modal de notificaciones
│   │   ├── PostFollowButton.php        # Botón seguir en posts
│   │   └── UserStats.php               # Estadísticas de usuario
│   ├── Models/
│   │   ├── Comentario.php              # Modelo de comentarios
│   │   ├── Follower.php                # Modelo de seguidores
│   │   ├── Like.php                    # Modelo de likes
│   │   ├── Notification.php            # Modelo de notificaciones
│   │   ├── Post.php                    # Modelo de posts
│   │   └── User.php                    # Modelo de usuarios
│   ├── Policies/
│   │   └── PostPolicy.php              # Políticas de autorización posts
│   └── Services/
│       ├── CrossPlatformMusicService.php # URLs música multiplataforma
│       └── NotificationService.php     # Servicio de notificaciones
├── database/
│   ├── factories/                      # Factories para testing
│   ├── migrations/                     # Migraciones de BD
│   └── seeders/                        # Datos de prueba
├── resources/
│   ├── css/
│   │   ├── app.css                     # CSS principal
│   │   └── menu-mobile.css             # Estilos móviles
│   ├── js/
│   │   └── app.js                      # JavaScript principal
│   └── views/
│       ├── auth/                       # Vistas de autenticación
│       ├── colaboradores/              # Página colaboradores
│       ├── components/                 # Componentes reutilizables
│       ├── errors/                     # Páginas de error
│       ├── home.blade.php              # Página principal
│       ├── layouts/                    # Layouts principales
│       ├── livewire/                   # Vistas componentes Livewire
│       ├── perfil/                     # Vistas de perfil
│       ├── posts/                      # Vistas de posts
│       └── users/                      # Vistas de usuarios
├── routes/
│   ├── console.php                     # Rutas de comandos Artisan
│   └── web.php                         # Rutas web principales
└── public/
    ├── uploads/                        # Imágenes subidas
    ├── perfiles/                       # Fotos de perfil
    └── build/                          # Assets compilados
```

## Comandos Útiles

### Al cambiar de rama
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan clear-compiled
```
## Herramienta Interna para desarrollo

>**Nota:** Esta herramienta aún se encuentra en desarrollo.

Ejecuta el archivo `manage-seeders.bat` para automatizar la ejecución de los comandos de desarrollo.


## Tecnológias

- **Backend**: Laravel 12 + PHP 8.2
- **Frontend**: Livewire 3 + Alpine.js + TailwindCSS
- **Base de Datos**: MySQL
- **APIs**: Spotify Web API + iTunes Search API
- **Imágenes**: Intervention Image
- **Build**: Vite
