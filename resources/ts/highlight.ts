// 載入基本預設的語言
import hljs from 'highlight.js/lib/common';
// 加入 Dart
import dart from 'highlight.js/lib/languages/dart';
import dockerfile from 'highlight.js/lib/languages/dockerfile';
import nginx from 'highlight.js/lib/languages/nginx';

// https://github.com/highlightjs/highlightjs-terraform/blob/master/terraform.js
function terraform(hljs: any) {
    const NUMBERS = {
        className: 'number',
        begin: '\\b\\d+(\\.\\d+)?',
        relevance: 0,
    };
    const STRINGS = {
        className: 'string',
        begin: '"',
        end: '"',
        contains: [
            {
                className: 'variable',
                begin: '\\${',
                end: '\\}',
                relevance: 9,
                contains: [
                    {
                        className: 'string',
                        begin: '"',
                        end: '"',
                    },
                    {
                        className: 'meta',
                        begin: '[A-Za-z_0-9]*' + '\\(',
                        end: '\\)',
                        contains: [
                            NUMBERS,
                            {
                                className: 'string',
                                begin: '"',
                                end: '"',
                                contains: [
                                    {
                                        className: 'variable',
                                        begin: '\\${',
                                        end: '\\}',
                                        contains: [
                                            {
                                                className: 'string',
                                                begin: '"',
                                                end: '"',
                                                contains: [
                                                    {
                                                        className: 'variable',
                                                        begin: '\\${',
                                                        end: '\\}',
                                                    },
                                                ],
                                            },
                                            {
                                                className: 'meta',
                                                begin: '[A-Za-z_0-9]*' + '\\(',
                                                end: '\\)',
                                            },
                                        ],
                                    },
                                ],
                            },
                            'self',
                        ],
                    },
                ],
            },
        ],
    };

    return {
        aliases: ['tf', 'hcl'],
        keywords:
            'resource variable provider output locals module data terraform|10',
        literal: 'false true null',
        contains: [hljs.COMMENT('\\#', '$'), NUMBERS, STRINGS],
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
