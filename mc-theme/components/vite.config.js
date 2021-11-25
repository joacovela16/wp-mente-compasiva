import {defineConfig} from 'vite'
import {svelte} from '@sveltejs/vite-plugin-svelte'
import WindiCSS from 'vite-plugin-windicss';
// https://vitejs.dev/config/
export default defineConfig({
    build: {
        lib: {
            entry: './src/lib.js',
            name: 'mc-lib',
            formats: ["es"],
            fileName: (format) => `mc-lib.${format}.js`
        }
    },

    plugins: [WindiCSS(), svelte(),]
})
