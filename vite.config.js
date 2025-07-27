// vite.config.js
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                // Pisahkan Filament CSS
                "resources/css/filament/admin/theme.css",
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    // Pisahkan CSS untuk menghindari konflik
                    filament: ["resources/css/filament/admin/theme.css"],
                },
            },
        },
    },
});
