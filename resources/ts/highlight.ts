// 載入基本預設的語言
import hljs from "highlight.js/lib/common";
// 加入 Dart
import dart from "highlight.js/lib/languages/dart";

interface Window {
    hljs: any;
}

hljs.registerLanguage("dart", dart);

hljs.highlightAll();

window.hljs = hljs;
