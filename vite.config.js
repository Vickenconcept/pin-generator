import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/frontend.js'],
            refresh: true,
        }),
        vue()
    ],
    optimizeDeps: {
      include: ['fabric']
    },
    resolve: {
        alias: {
          vue: 'vue/dist/vue.esm-bundler.js',
        },
      },
});