const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .copy(
        'node_modules/@yaireo/tagify/dist/tagify.min.js',
        'public/js/tagify.min.js'
    )
    .copy(
        'node_modules/@yaireo/tagify/dist/tagify.css',
        'public/css/tagify.css'
    )
    .copy(
        'node_modules/algoliasearch/dist/algoliasearch-lite.umd.js',
        'public/js/algoliasearch-lite.umd.js'
    )
    .copy(
        'node_modules/autocomplete.js/dist/autocomplete.min.js',
        'public/js/autocomplete.min.js'
    )
    .options({
        terser: {
            extractComments: false
        }
    });
