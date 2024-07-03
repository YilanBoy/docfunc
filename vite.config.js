import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { createRequire } from 'node:module';
import ckeditor5 from '@ckeditor/vite-plugin-ckeditor5';

const require = createRequire(import.meta.url);

export default defineConfig({
    build: { chunkSizeWarningLimit: 1000 },
    plugins: [
        ckeditor5({ theme: require.resolve('@ckeditor/ckeditor5-theme-lark') }),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                // js
                'resources/ts/ckeditor/ckeditor.ts',
                'resources/ts/sharer.ts',
                'resources/ts/highlight.ts',
                'resources/ts/tagify.ts',
                'resources/ts/scroll-to-top-btn.ts',
                'resources/ts/copy-code-btn.ts',
                'resources/ts/oembed/embed-youtube-oembed.ts',
                'resources/ts/oembed/embed-twitter-oembed.ts',
                'resources/ts/progress-bar.ts',
                'resources/ts/scroll-to-anchor.ts',
                'resources/ts/post-outline.ts',
                // css
                'node_modules/@yaireo/tagify/dist/tagify.css',
                'resources/css/editor.css',
                'resources/css/missing-content-style.css',
                'node_modules/highlight.js/styles/atom-one-dark.css',
            ],
            refresh: true,
        }),
    ],
});
