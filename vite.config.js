import { defineConfig } from 'vite'
import vue              from '@vitejs/plugin-vue'
import laravel          from 'laravel-vite-plugin'
import { resolve }      from 'path'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.ts',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base:            null,
                    includeAbsolute: false,
                },
            },
        }),
    ],

    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
        },
    },

    build: {
        // Chunks separados para vendor y app
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules/vue'))       return 'vue'
                    if (id.includes('node_modules/@inertiajs')) return 'inertia'
                },
            },
        },
        // Alerta si un chunk supera 500kB
        chunkSizeWarningLimit: 500,
    },

    // Variables de entorno disponibles en el frontend
    define: {
        __APP_VERSION__: JSON.stringify(process.env.npm_package_version),
    },
})
