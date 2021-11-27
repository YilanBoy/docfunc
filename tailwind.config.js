const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    mode: 'jit',
    darkMode: 'class',
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/*.js',
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
            typography: (theme) => ({
                DEFAULT: {
                    css: [
                        {
                            a: {
                                color: theme('colors.blue.400'),
                                '&:hover': {
                                    color: theme('colors.blue.500'),
                                    'text-decoration': 'underline',
                                },
                                'text-decoration': 'none',
                            },
                            'a code': {
                                color: theme('colors.blue.400'),
                                '&:hover': {
                                    color: theme('colors.blue.500'),
                                    'text-decoration': 'underline',
                                },
                                'text-decoration': 'none',
                            },
                            'code::before': {
                                content: '',
                            },
                            'code::after': {
                                content: '',
                            },
                        },
                    ],
                },
                dark: {
                    css: [
                        {
                            color: theme('colors.gray.50'),
                            '[class~="lead"]': {
                                color: theme('colors.gray.300'),
                            },
                            a: {
                                color: theme('colors.blue.400'),
                                '&:hover': {
                                    color: theme('colors.blue.300'),
                                    'text-decoration': 'underline',
                                },
                                'text-decoration': 'none',
                            },
                            'a code': {
                                color: theme('colors.blue.400'),
                                '&:hover': {
                                    color: theme('colors.blue.300'),
                                    'text-decoration': 'underline',
                                },
                                'text-decoration': 'none',
                            },
                            strong: {
                                color: theme('colors.gray.50'),
                            },
                            'mark strong': {
                                color: theme('colors.gray.900'),
                            },
                            'ol > li::before': {
                                color: theme('colors.gray.400'),
                            },
                            'ul > li::before': {
                                backgroundColor: theme('colors.gray.400'),
                            },
                            hr: {
                                borderColor: theme('colors.gray.200'),
                            },
                            blockquote: {
                                color: theme('colors.gray.50'),
                                borderLeftColor: theme('colors.gray.400'),
                            },
                            h1: {
                                color: theme('colors.gray.50'),
                            },
                            h2: {
                                color: theme('colors.gray.50'),
                            },
                            h3: {
                                color: theme('colors.gray.50'),
                            },
                            h4: {
                                color: theme('colors.gray.50'),
                            },
                            'figure figcaption': {
                                color: theme('colors.gray.50'),
                                backgroundColor: theme('colors.gray.500'),
                            },
                            code: {
                                color: theme('colors.gray.50'),
                            },
                            pre: {
                                color: theme('colors.gray.200'),
                                backgroundColor: theme('colors.gray.800'),
                            },
                            thead: {
                                color: theme('colors.gray.50'),
                                borderBottomColor: theme('colors.gray.400'),
                            },
                            'tbody tr': {
                                borderBottomColor: theme('colors.gray.600'),
                            },
                        },
                    ],
                },
            }),
        },
    },

    // just in time 預設已經開起所有 variants
    // variants: {
    //     extend: {
    //         opacity: ['disabled'],
    //     },
    // },

    plugins: [
        require("@tailwindcss/forms")({
            strategy: 'class',
        }),
        require('@tailwindcss/typography'),
    ],
};
