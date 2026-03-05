import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js'
            ],
            refresh: true
        })
    ],

    server: {
        host: true,
        port: 5173,
        hmr: {
            host: '0.0.0.0'
        }
    },

    build: {
        outDir: 'public/build',
        manifest: true,
        emptyOutDir: true,
        rollupOptions: {
            output: {
                manualChunks: undefined
            }
        }
    }
})