/** @type {import('tailwindcss').Config} */

module.exports = {
    content: [
        "./resources/**/*.{html,js,jsx,ts,tsx,vue}", // Busca todos los archivos relevantes dentro de resources
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