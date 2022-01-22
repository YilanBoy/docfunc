const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ])
    .js('resources/js/sweet-alert.js', 'public/js')
    .js('resources/js/editor.js', 'public/js')
    .ts('resources/ts/tagify.ts', 'public/js')
    .ts('resources/ts/scroll-to-top-btn.ts', 'public/js')
    .ts('resources/ts/set-theme.ts', 'public/js')
    .ts('resources/ts/copy-code-btn.ts', 'public/js')
    .ts('resources/ts/oembed/oembed-media-embed.ts', 'public/js')
    .ts('resources/ts/count-up.ts', 'public/js')
    .ts('resources/ts/oembed/twitter-widgets.ts', 'public/js')
    .ts('resources/ts/progress-bar.ts', 'public/js')
    .copy('node_modules/@yaireo/tagify/dist/tagify.css', 'public/css/tagify.css')
    .css('resources/css/content-styles.css', 'public/css')
    .css('resources/css/editor.css', 'public/css')
    .css('resources/css/progress-bar.css', 'public/css')
    .options({
        terser: {
            extractComments: false
        }
    });
