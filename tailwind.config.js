const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    mode: 'jit',
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/ts/*.ts',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
                noto: "'Noto Sans TC', sans-serif"
            },
            height: {
                '18': '4.5rem',
            },
            inset: {
                '103/100': '103%'
            },
        },
    },

    // just in time 預設已經開起所有 variants
    // variants: {
    //     extend: {
    //         opacity: ['disabled'],
    //     },
    // },

    plugins: [require('@tailwindcss/forms')],
};
