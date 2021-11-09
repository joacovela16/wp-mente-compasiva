import { defineConfig } from 'windicss/helpers';

export default defineConfig({
    extract: {
        include: [ 'src/**/*.svelte', 'src/**/*.js'],
    },
})
