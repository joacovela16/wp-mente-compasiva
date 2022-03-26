module.exports = {

    content: ['./**/*.php', '../mc_plugin/**/*.php'],
    theme: {
        extend: {
            fontFamily: {
                'roboto': "'Roboto', sans-serif"
            }
        },
    },
    daisyui: {
        themes: [
            'light',
           /* {
                mytheme: {
                    "primary": "#1d4ed8",
                    "secondary": "#F000B8",
                    "accent": "#37CDBE",
                    "neutral": "#f3f4f6",
                    "base-100": "#FFFFFF",
                    "info": "#3ABFF8",
                    "success": "#36D399",
                    "warning": "#FBBD23",
                    "error": "#F87272",
                },
            },*/
        ]
    },
    plugins: [
        // require('tailwindcss-textshadow'),
        require("daisyui")
    ],
};