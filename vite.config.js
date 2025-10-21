// vite.config.js

import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import strip from "@rollup/plugin-strip";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                // Pisahkan Filament CSS
                "resources/css/filament/admin/theme.css",
            ],
            refresh: [
                // Refresh otomatis untuk file-file ini
                "resources/views/**/*.blade.php",
                "config/**/*.php",
                "app/Http/Controllers/**/*.php",
                "routes/**/*.php",
            ],
        }),
    ],
    build: {
        rollupOptions: {
            plugins: [
                strip({
                    include: ["**/*.js", "**/*.ts", "**/*.vue"],
                    functions: ["console.*", "assert.*", "debug", "alert"],
                }),
            ],
            output: {
                manualChunks: {
                    // Pisahkan CSS untuk menghindari konflik
                    filament: ["resources/css/filament/admin/theme.css"],
                },
            },
        },
    },
    server: {
        hmr: {
            host: "localhost",
        },
    },
});
