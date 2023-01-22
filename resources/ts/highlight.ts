// 載入基本預設的語言
import hljs from "highlight.js/lib/common";
// 加入 Dart
import dart from "highlight.js/lib/languages/dart";

hljs.registerLanguage("dart", dart);

// only highlight code in the blog post
document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelectorAll('#post-body pre code').forEach((el: HTMLElement) => {
        hljs.highlightElement(el);
    });
});
