import {defineConfig} from 'windicss/helpers'

export default defineConfig({
    theme:{
        fontFamily:{
            mc: ["Roboto","sans-serif"]
        },
    },
    extract: {
        include: ["./**/*.{php,js,svelte}"],
    },
})
