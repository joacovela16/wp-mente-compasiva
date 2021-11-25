import {defineConfig} from 'windicss/helpers';

export default defineConfig({
    extract: {
        include: ['./src/**/*.{svelte,js,html}'],
        exclude: ['node_modules', '.git'],
    },
})
