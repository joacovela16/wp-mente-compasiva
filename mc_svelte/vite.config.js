import {defineConfig} from 'vite'
import {svelte} from '@sveltejs/vite-plugin-svelte'
import WindiCSS from "vite-plugin-windicss"
// https://vitejs.dev/config/
export default defineConfig({
    build: {
        lib: {
            entry: "src/main.js",
            name: "MC_SVELTE_LIB",
            fileName: format => `mc_svelte_lib.${format}.js`
        }
    },
    plugins: [WindiCSS(), svelte({compilerOptions: {enableSourcemap: true}})]
})
