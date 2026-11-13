/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
    ],
    theme: {
        extend: {
            colors: {
                sigap: {
                    950: '#061527',
                    900: '#0A2540', // Biru Tua Utama Sidebar
                    800: '#1E3A8A', // Biru Royal Bawaslu
                    700: '#1D4ED8', // Biru Tombol Utama
                    600: '#2563EB',
                    100: '#DBEAFE',
                    50:  '#EFF6FF',
                    gold: '#F59E0B', // Aksen Emas
                }
            }
        },
    },
    plugins: [],
};