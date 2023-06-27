// 載入基本預設的語言
import hljs from 'highlight.js/lib/common';
// 加入 Dart
import dart from 'highlight.js/lib/languages/dart';
import dockerfile from 'highlight.js/lib/languages/dockerfile';
import nginx from 'highlight.js/lib/languages/nginx';

interface Window {
    hljs: any;
}

hljs.registerLanguage('dart', dart);
hljs.registerLanguage('dockerfile', dockerfile);
hljs.registerLanguage('nginx', nginx);

hljs.highlightAll();

window.hljs = hljs;
