import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                // js
                'resources/ts/sharer.ts',
                'resources/ts/highlight.ts',
                'resources/ts/tagify.ts',
                'resources/ts/scroll-to-top-btn.ts',
                'resources/ts/copy-code-btn.ts',
                'resources/ts/oembed/embed-youtube-oembed.ts',
                'resources/ts/oembed/embed-twitter-oembed.ts',
                'resources/ts/oembed/twitter-widgets.ts',
                'resources/ts/progress-bar.ts',
                // css
                'node_modules/@yaireo/tagify/dist/tagify.css',
                'resources/css/editor.css',
                'resources/css/missing-content-style.css',
                'resources/css/icon.css',
                // scss
                'node_modules/highlight.js/scss/atom-one-dark.scss',
            ],
            refresh: true,
        }),
    ],
});
