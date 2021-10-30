import {defineConfig} from 'vite'
import {svelte} from '@sveltejs/vite-plugin-svelte'
import WindiCss from "vite-plugin-windicss";
import  path from "path";
// https://vitejs.dev/config/
export default  defineConfig({
    build:{
        lib:{
            entry:  './src/index.js',
            name: 'MyLib',
            fileName: (format) => `my-lib.${format}.js`
        }
    },
    plugins: [WindiCss(), svelte()]
})
