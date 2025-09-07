// tailwind.config.js (ROOT)
import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        // EXCLUDE Filament paths untuk menghindari konflik
        "!./resources/views/filament/**",
        "!./app/Filament/**",
    ],
    safelist: [
        // Safelist custom button classes agar tidak di-purge
        "btn-primary",
        "btn-secondary",
        "btn-outline",
        "btn-cream",
        "btn-outline-white-hover",
        // Safelist utility classes yang mungkin digunakan di config
        "text-sm",
        "lg:text-base",
        "group",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                "tria-navy": "#1e1b4b",
                "tria-navy-light": "#312e81",
                "tria-orange": "#ff6b35",
                "tria-orange-light": "#ff8c69",
                primary: {
                    50: "#eff6ff",
                    500: "#3b82f6",
                    600: "#2563eb",
                    700: "#1d4ed8",
                },
                brand: {
                    blue: "#000334",
                    orange: "#FF7900",
                    orangeDark: "#e66e00",
                    cream: "#EFEEEA",
                },
                navy: {
                    50: "#f7f8fc",
                    100: "#eef1f8",
                    200: "#d8e0ef",
                    300: "#b5c6e0",
                    400: "#8ba6ce",
                    500: "#6a8abd",
                    600: "#5270ab",
                    700: "#445d9a",
                    800: "#3a4f7f",
                    900: "#000334",
                },
            },
        },
    },
    plugins: [],
    // Tambahkan prefix untuk menghindari konflik dengan Filament
    corePlugins: {
        preflight: true,
    },
};
