import { defineConfig } from 'windicss/helpers'
export default defineConfig({
    extract: {
        include: ["src/**/*.svelte","node_modules/gui-components/**/*.svelte"]
    },
    plugins: [
    ]
});