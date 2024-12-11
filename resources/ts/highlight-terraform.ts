// https://github.com/highlightjs/highlightjs-terraform/blob/master/terraform.js
import { HLJSApi } from 'highlight.js';

export default function (hljs: HLJSApi) {
    const KWS = [
        'resource',
        'variable',
        'provider',
        'output',
        'locals',
        'module',
        'data',
        'terraform|10',
        'for',
        'in',
        'if',
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
            RIGHT_BRACKET,
        ],
    };
}
