const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    darkMode: "class",
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/*.js",
        "./resources/ts/*.ts",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
                noto: "'Noto Sans TC', sans-serif",
            },
            keyframes: {
                "fade-in": {
                    from: {
                        opacity: 0,
                        transform: "translateY(20px)",
                    },
                    to: {
                        opacity: 1,
                        transform: "translateY(0)",
                    },
                },
                "grow-width": {
                    from: {
                        width: 0,
                    },
                    to: {
                        width: "100%",
                    },
                },
            },
            animation: {
                "fade-in": "fade-in 0.5s ease-in-out",
                "grow-width": "grow-width 1s forwards",
            },
            typography: (theme) => ({
                DEFAULT: {
                    css: {
                        // blockquote
                        blockquote: {
                            borderColor: theme("colors.slate.300"),
                            color: theme("colors.slate.500"),
                        },
                        "blockquote p:first-of-type::before": null,
                        "blockquote p:last-of-type::after": null,
                        ".dark blockquote": {
                            borderColor: theme("colors.slate.600"),
                            color: theme("colors.slate.400"),
                        },
                        // code
                        code: {
                            backgroundColor: theme("colors.green.100"),
                            color: theme("colors.green.600"),
                            padding: "0.25rem",
                            fontWeight: "600",
                            borderRadius: "0.25rem",
                        },
                        ".dark :not(pre) > code": {
                            backgroundColor: theme("colors.gray.500"),
                            color: theme("colors.gray.100"),
                        },
                    },
                },
            }),
        },
    },
    plugins: [
        require("@tailwindcss/forms")({
            strategy: "class",
        }),
        require("@tailwindcss/typography"),
    ],
};
