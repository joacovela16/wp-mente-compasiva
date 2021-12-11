const {defineConfig} = require('windicss/helpers');

module.exports = defineConfig({
    extract: {
        extractors: [
            {
                extractor: (content) => {
                    return {classes: content.match(/[\"']class[\"']\s*=>\s*[\"']([\w\d\s\-!:\/]*)[\"']/g) || []}
                },
                extensions: ["php"]
            }
        ],
        include: ["./**/*.{php}"],
    },
})
