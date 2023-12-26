import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
    ],

    build: {
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    let ext = assetInfo.name.split('.').at(1);

                    if(ext === 'css'){
                        return `assets/[name]-[hash][extname]`;
                    }

                    if(ext === 'eot' || ext === 'ttf' || ext === 'woff' || ext === 'woff2' || assetInfo.name === 'feather.svg'){
                        return `fonts/[name][extname]`;
                    }

                    if(ext === 'png' || ext === 'svg'){
                        return `images/[name][extname]`;
                    }

                    return `${ext}/[name][extname]`;
                },
                chunkFileNames: 'assets/[name]-[hash].js',
                entryFileNames: 'assets/[name]-[hash].js',
            },
        },
    },
});
