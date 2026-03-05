import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true
        })
    ],

    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: { host: 'localhost' }
    },

    build: {
        outDir: 'public/build',
        manifest: 'manifest.json',
        emptyOutDir: true,
        rollupOptions: {
            output: {
                entryFileNames: 'assets/[name].js',
                chunkFileNames: 'assets/[name].js',
                assetFileNames: 'assets/[name].[ext]',
            }
        }
    }
})
