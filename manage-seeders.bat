@echo off
:menu
cls
echo.
echo ========================================
echo    SIVARSOCIAL - GESTOR DE BASE DE DATOS
echo ========================================
echo.
echo Bienvenido al sistema de gestion de datos
echo Selecciona una opcion:
echo.
echo 📊 ESTADISTICAS Y CONSULTAS
echo 1. Ver estadisticas actuales de la base de datos
echo 2. Ver usuarios registrados (con detalles)
echo 3. Ver posts publicados (resumido)
echo 4. Ver actividad social (likes, comentarios, seguidores)
echo.
echo 🗄️ GESTION DE BASE DE DATOS
echo 5. Limpiar base de datos completamente (PELIGRO)
echo 6. Poblar base de datos desde cero (recomendado)
echo 7. Solo agregar mas datos (mantener existentes)
echo.
echo ➕ AGREGAR CONTENIDO ESPECIFICO
echo 8. Crear solo usuarios nuevos (1-50)
echo 9. Crear solo posts nuevos (1-30)
echo 10. Generar solo imagenes con colores solidos
echo 11. Agregar solo comentarios aleatorios
echo 12. Agregar solo likes aleatorios
echo 13. Crear relaciones de seguimiento aleatorias
echo.
echo 🔧 HERRAMIENTAS TECNICAS
echo 14. Verificar conexion a base de datos
echo 15. Ver migraciones aplicadas
echo 16. Backup de base de datos actual
echo 17. Restaurar backup
echo.
echo 0. Salir
echo.
set /p choice="Elige una opcion (0-17): "

if "%choice%"=="1" goto stats
if "%choice%"=="2" goto view_users
if "%choice%"=="3" goto view_posts
if "%choice%"=="4" goto view_activity
if "%choice%"=="5" goto clean_db
if "%choice%"=="6" goto fresh_seed
if "%choice%"=="7" goto add_seed
if "%choice%"=="8" goto create_users
if "%choice%"=="9" goto create_posts
if "%choice%"=="10" goto create_images
if "%choice%"=="11" goto add_comments
if "%choice%"=="12" goto add_likes
if "%choice%"=="13" goto add_followers
if "%choice%"=="14" goto test_db
if "%choice%"=="15" goto view_migrations
if "%choice%"=="16" goto backup_db
if "%choice%"=="17" goto restore_db
if "%choice%"=="0" goto exit
goto menu

:stats
echo.
echo 📊 ESTADISTICAS DETALLADAS DE LA BASE DE DATOS:
echo.
php artisan tinker --execute="echo '=== RESUMEN GENERAL ===' . PHP_EOL; echo 'Usuarios totales: ' . App\Models\User::count() . PHP_EOL; echo 'Posts publicados: ' . App\Models\Post::count() . PHP_EOL; echo 'Comentarios: ' . App\Models\Comentario::count() . PHP_EOL; echo 'Likes dados: ' . App\Models\Like::count() . PHP_EOL; echo 'Relaciones de seguimiento: ' . App\Models\Follower::count() . PHP_EOL; echo PHP_EOL . '=== DETALLES POR TIPO ===' . PHP_EOL; echo 'Posts con imagenes: ' . App\Models\Post::where('tipo', 'imagen')->count() . PHP_EOL; echo 'Posts de musica: ' . App\Models\Post::where('tipo', 'musica')->count() . PHP_EOL; echo 'Usuarios con foto de perfil: ' . App\Models\User::whereNotNull('imagen')->count() . PHP_EOL; echo PHP_EOL . '=== ACTIVIDAD SOCIAL ===' . PHP_EOL; echo 'Promedio de likes por post: ' . round(App\Models\Like::count() / max(App\Models\Post::count(), 1), 2) . PHP_EOL; echo 'Promedio de comentarios por post: ' . round(App\Models\Comentario::count() / max(App\Models\Post::count(), 1), 2) . PHP_EOL; echo 'Promedio de seguidores por usuario: ' . round(App\Models\Follower::count() / max(App\Models\User::count(), 1), 2) . PHP_EOL;"
echo.
pause
goto menu

:view_users
echo.
echo 👥 USUARIOS REGISTRADOS EN LA PLATAFORMA:
echo.
php artisan tinker --execute="App\Models\User::select('id', 'name', 'username', 'profession', 'gender')->get()->each(function(\$user) { echo sprintf('ID: %-3s | %-25s (@%-15s) | %-25s | %s', \$user->id, \$user->name, \$user->username, \$user->profession ?? 'Sin profesion', \$user->gender ?? 'N/A') . PHP_EOL; });"
echo.
echo Presiona cualquier tecla para continuar...
pause >nul
goto menu

:view_posts
echo.
echo 📝 POSTS PUBLICADOS (ULTIMOS 20):
echo.
php artisan tinker --execute="App\Models\Post::with('user')->latest()->take(20)->get()->each(function(\$post) { echo sprintf('ID: %-3s | Tipo: %-7s | Usuario: %-20s | %s', \$post->id, \$post->tipo, \$post->user->name, substr(\$post->descripcion ?? \$post->titulo ?? 'Sin descripcion', 0, 50) . '...') . PHP_EOL; });"
echo.
echo Presiona cualquier tecla para continuar...
pause >nul
goto menu

:view_activity
echo.
echo 🎯 ACTIVIDAD SOCIAL DETALLADA:
echo.
php artisan tinker --execute="echo '=== USUARIOS MAS POPULARES ===' . PHP_EOL; App\Models\User::withCount(['followers', 'posts', 'likes'])->orderBy('followers_count', 'desc')->take(5)->get()->each(function(\$user) { echo sprintf('%-25s | Seguidores: %-3s | Posts: %-3s | Likes: %-3s', \$user->name, \$user->followers_count, \$user->posts_count, \$user->likes_count) . PHP_EOL; }); echo PHP_EOL . '=== POSTS MAS POPULARES ===' . PHP_EOL; App\Models\Post::withCount(['likes', 'comentarios'])->with('user')->orderBy('likes_count', 'desc')->take(5)->get()->each(function(\$post) { echo sprintf('Post %-3s (%-15s) | Likes: %-3s | Comentarios: %-3s | %s', \$post->id, \$post->user->username, \$post->likes_count, \$post->comentarios_count, substr(\$post->descripcion ?? \$post->titulo ?? 'Sin descripcion', 0, 30) . '...') . PHP_EOL; });"
echo.
echo Presiona cualquier tecla para continuar...
pause >nul
goto menu

:clean_db
echo.
echo ⚠️  ATENCION: ELIMINACION COMPLETA DE DATOS
echo Esta accion eliminara TODOS los datos de la base de datos
echo incluyendo usuarios, posts, comentarios, likes y seguidores.
echo.
echo ¿Estas completamente seguro? Esta accion NO se puede deshacer.
set /p confirm1="Escribe 'ELIMINAR' para confirmar: "
if /i "%confirm1%" neq "ELIMINAR" goto menu
echo.
echo Ultima oportunidad para cancelar...
set /p confirm2="¿Realmente quieres eliminar todo? (si/no): "
if /i "%confirm2%" neq "si" goto menu
echo.
echo Eliminando todos los datos...
php artisan migrate:fresh
echo.
echo ✅ Base de datos limpiada completamente!
echo La base de datos esta ahora vacia y lista para nuevos datos.
pause
goto menu

:fresh_seed
echo.
echo 🌱 POBLANDO BASE DE DATOS DESDE CERO
echo Esta accion creara una base de datos completa con:
echo - 25 usuarios con perfiles tecnologicos
echo - 40+ posts con imagenes en tonos negros
echo - Posts de musica electronica de iTunes
echo - Comentarios, likes y relaciones sociales
echo.
set /p confirm="¿Continuar? (s/n): "
if /i "%confirm%" neq "s" goto menu
echo.
echo Limpiando base de datos...
php artisan migrate:fresh
echo.
echo Creando datos de ejemplo...
php artisan db:seed
echo.
echo ✅ Base de datos poblada desde cero!
echo Ya puedes usar la aplicacion con datos realistas.
pause
goto menu

:add_seed
echo.
echo ➕ AGREGANDO MAS DATOS A LA BASE EXISTENTE
echo Se agregaran mas usuarios, posts y actividad social
echo sin eliminar los datos actuales.
echo.
set /p confirm="¿Continuar? (s/n): "
if /i "%confirm%" neq "s" goto menu
echo.
echo Agregando mas contenido...
php artisan db:seed
echo.
echo ✅ Datos adicionales agregados!
pause
goto menu

:create_users
echo.
echo 👤 CREAR USUARIOS PERSONALIZADOS
echo.
set /p user_count="¿Cuantos usuarios quieres crear? (1-50): "
if "%user_count%"=="" set user_count=5
if %user_count% gtr 50 set user_count=50
if %user_count% lss 1 set user_count=1
echo.
echo Creando %user_count% usuarios nuevos con fotos de perfil automaticas...
php artisan users:create-with-avatars %user_count%
echo.
pause
goto menu

:create_posts
echo.
echo 📝 CREAR POSTS PERSONALIZADOS
echo.
set /p post_count="¿Cuantos posts quieres crear? (1-30): "
if "%post_count%"=="" set post_count=10
if %post_count% gtr 30 set post_count=30
if %post_count% lss 1 set post_count=1
echo.
echo Creando %post_count% posts nuevos con imagenes automaticas...
php artisan posts:create-with-images %post_count%
echo.
pause
goto menu

:create_images
echo.
echo 🖼️  GENERANDO IMAGENES NUEVAS
echo Se crearan imagenes con colores solidos para perfiles y posts...
echo.
php artisan db:seed --class=ImageSeeder
echo.
echo ✅ Imagenes nuevas generadas!
pause
goto menu

:add_comments
echo.
echo � AGREGANDO COMENTARIOS ALEATORIOS
echo.
set /p comment_rounds="¿Cuantas rondas de comentarios? (1-5): "
if "%comment_rounds%"=="" set comment_rounds=1
if %comment_rounds% gtr 5 set comment_rounds=5
if %comment_rounds% lss 1 set comment_rounds=1
echo.
echo Agregando comentarios en %comment_rounds% ronda(s)...
for /l %%i in (1,1,%comment_rounds%) do (
    echo Ronda %%i de %comment_rounds%...
    php artisan db:seed --class=ComentarioSeeder
)
echo.
echo ✅ Comentarios agregados exitosamente!
pause
goto menu

:add_likes
echo.
echo ❤️  AGREGANDO LIKES ALEATORIOS
echo.
set /p like_rounds="¿Cuantas rondas de likes? (1-3): "
if "%like_rounds%"=="" set like_rounds=1
if %like_rounds% gtr 3 set like_rounds=3
if %like_rounds% lss 1 set like_rounds=1
echo.
echo Agregando likes en %like_rounds% ronda(s)...
for /l %%i in (1,1,%like_rounds%) do (
    echo Ronda %%i de %like_rounds%...
    php artisan db:seed --class=LikeSeeder
)
echo.
echo ✅ Likes agregados exitosamente!
pause
goto menu

:add_followers
echo.
echo 👥 CREANDO RELACIONES DE SEGUIMIENTO
echo Se crearan nuevas relaciones entre usuarios existentes...
echo.
php artisan db:seed --class=FollowerSeeder
echo.
echo ✅ Nuevas relaciones de seguimiento creadas!
pause
goto menu

:test_db
echo.
echo 🔍 VERIFICANDO CONEXION A BASE DE DATOS...
echo.
php artisan migrate:status
echo.
echo Estado de las tablas:
php artisan tinker --execute="try { \$tables = ['users', 'posts', 'comentarios', 'likes', 'followers']; foreach(\$tables as \$table) { \$count = DB::table(\$table)->count(); echo sprintf('Tabla %-12s: %s registros', \$table, \$count) . PHP_EOL; } } catch(Exception \$e) { echo 'Error: ' . \$e->getMessage() . PHP_EOL; }"
echo.
pause
goto menu

:view_migrations
echo.
echo 📋 MIGRACIONES DE BASE DE DATOS:
echo.
php artisan migrate:status
echo.
pause
goto menu

:backup_db
echo.
echo 💾 CREANDO BACKUP DE BASE DE DATOS...
echo.
set backup_name=sivarsocial_backup_%date:~6,4%%date:~3,2%%date:~0,2%_%time:~0,2%%time:~3,2%
set backup_name=%backup_name: =0%
echo Creando backup: %backup_name%.sql
mysqldump -u root -p sivarsocial > "%backup_name%.sql"
if exist "%backup_name%.sql" (
    echo ✅ Backup creado exitosamente: %backup_name%.sql
) else (
    echo ❌ Error al crear backup
)
echo.
pause
goto menu

:restore_db
echo.
echo � RESTAURAR BACKUP DE BASE DE DATOS
echo.
echo ⚠️  ATENCION: Esto eliminara todos los datos actuales
echo.
dir *.sql /b 2>nul
if errorlevel 1 (
    echo No se encontraron archivos de backup (.sql)
    pause
    goto menu
)
echo.
set /p backup_file="Nombre del archivo de backup (sin .sql): "
if not exist "%backup_file%.sql" (
    echo Archivo no encontrado: %backup_file%.sql
    pause
    goto menu
)
echo.
set /p confirm="¿Restaurar desde %backup_file%.sql? (s/n): "
if /i "%confirm%" neq "s" goto menu
echo.
echo Restaurando base de datos...
mysql -u root -p sivarsocial < "%backup_file%.sql"
echo ✅ Base de datos restaurada desde %backup_file%.sql
echo.
pause
goto menu

:exit
echo.
echo ¡Gracias por usar el Gestor de Base de Datos de SivarSocial!
echo.
pause
exit
