// 載入基本預設的語言
import hljs from "highlight.js/lib/common";
// 加入 Dart
import dart from "highlight.js/lib/languages/dart";
import dockerfile from "highlight.js/lib/languages/dockerfile";
import nginx from "highlight.js/lib/languages/nginx";

// https://github.com/highlightjs/highlightjs-terraform/blob/master/terraform.js
function terraform(hljs: any) {
    const KWS = [
        'resource',
        'variable',
        'provider',
        'output',
        'locals',
        'module',
        'data',
        'terraform|10',
    ];

    const LITERAL = ['true', 'false', 'null'];

    const KEYWORDS = {
        keyword: KWS,
        literal: LITERAL,
    };

    const NUMBERS = {
        scope: 'number',
        begin: /\b\d+(\.\d+)?/,
        relevance: 0,
    };

    const STRINGS = {
        scope: 'string',
        begin: /"/,
        end: /"/,
        contains: [
            {
                scope: 'variable',
                begin: /\${/,
                end: /}/,
                relevance: 9,
            },
        ],
    };

    const PARAMETER = {
        scope: 'variable',
        match: /\n\s*[a-zA-Z0-9_]+\s*(?==)/,
    };

    const BLOCK_PARAMETER = {
        scope: 'keyword',
        match: /\n\s*[a-zA-Z0-9_]+\s*(?={)/,
    };

    const FUNCTION = {
        scope: 'title.function',
        match: /[a-zA-Z0-9_]+(?=\()/,
    };

    const LEFT_BRACE = {
        scope: 'punctuation',
        match: /\{/,
    };

    const RIGHT_BRACE = {
        scope: 'punctuation',
        match: /}/,
    };

    const LEFT_BRACKET = {
        scope: 'punctuation',
        match: /\[/,
    };

    const RIGHT_BRACKET = {
        scope: 'punctuation',
        match: /]/,
    };

    const EQUALS = {
        scope: 'operator',
        match: /=/,
    };

    return {
        case_insensitive: false,
        aliases: ['tf', 'hcl'],
        keywords: KEYWORDS,
        contains: [
            hljs.COMMENT(/#/, /$/),
            NUMBERS,
            STRINGS,
            PARAMETER,
            BLOCK_PARAMETER,
            FUNCTION,
            EQUALS,
            LEFT_BRACE,
            RIGHT_BRACE,
            LEFT_BRACKET,
            LEFT_BRACKET,
        ],
    };
}

declare global {
    interface Window {
        hljs: any;
    }
}

hljs.registerLanguage('dart', dart);
hljs.registerLanguage('dockerfile', dockerfile);
hljs.registerLanguage('nginx', nginx);
hljs.registerLanguage('terraform', terraform);

// hljs.highlightAll();

window.hljs = hljs;
