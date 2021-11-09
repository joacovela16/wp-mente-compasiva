import {defineConfig} from 'vite'
import {svelte} from '@sveltejs/vite-plugin-svelte'
import WindiCSS from 'vite-plugin-windicss';
// https://vitejs.dev/config/
export default defineConfig({
    assetsInclude: ['**/*.mp4'],

    server: {},
    optimizeDeps: {
        exclude: [""]
    },
    build: {
        rollupOptions: {},
        // assetsInlineLimit: 0,
        /*lib: {
            entry: './src/lib.js',
            name: 'mc-lib',
            fileName: (format) => `mc-lib.${format}.js`
        }*/
    },
    plugins: [WindiCSS(), svelte()]
})
