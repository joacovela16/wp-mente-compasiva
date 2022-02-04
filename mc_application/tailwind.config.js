module.exports = {
    content: ['./**/*.php', '../mc_plugin/**/*.php'],
    theme: {extend: {}},
    plugins: [
        require('tailwindcss-textshadow'),
    ],
};