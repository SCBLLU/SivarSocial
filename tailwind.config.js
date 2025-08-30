/** @type {import('tailwindcss').Config} */

module.exports = {
    content: [
        "./resources/**/*.blade.php", "./resources/**/*/*.js", "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php" // Busca todos los archivos relevantes dentro de resources
    ],
    purge: [],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {},
    },
    variants: {
        extend: {},
    },
    plugins: [],
}