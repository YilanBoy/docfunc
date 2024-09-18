// 載入基本預設的語言
import hljs from 'highlight.js/lib/common';
// 加入 Dart
import dart from 'highlight.js/lib/languages/dart';
import dockerfile from 'highlight.js/lib/languages/dockerfile';
import nginx from 'highlight.js/lib/languages/nginx';
import terraform from './highlight-terraform';

declare global {
    interface Window {
        hljs: typeof hljs;
    }
}

hljs.registerLanguage('dart', dart);
hljs.registerLanguage('dockerfile', dockerfile);
hljs.registerLanguage('nginx', nginx);
hljs.registerLanguage('terraform', terraform);

// hljs.highlightAll();

window.hljs = hljs;
