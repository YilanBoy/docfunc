import { ClassicEditor as ClassicEditorBase } from '@ckeditor/ckeditor5-editor-classic';

import './custom.css';

import { Essentials } from '@ckeditor/ckeditor5-essentials';
import { Autoformat } from '@ckeditor/ckeditor5-autoformat';
import {
    Bold,
    Code,
    Italic,
    Strikethrough,
    Underline,
} from '@ckeditor/ckeditor5-basic-styles';
import { BlockQuote } from '@ckeditor/ckeditor5-block-quote';
import {
    Heading,
    HeadingButtonsUI,
    HeadingOption,
} from '@ckeditor/ckeditor5-heading';
import {
    Image,
    ImageCaption,
    ImageInsert,
    ImageResize,
    ImageStyle,
    ImageToolbar,
    ImageUpload,
    PictureEditing,
} from '@ckeditor/ckeditor5-image';
import { Indent, IndentBlock } from '@ckeditor/ckeditor5-indent';
import { Link } from '@ckeditor/ckeditor5-link';
import { List } from '@ckeditor/ckeditor5-list';
import { MediaEmbed } from '@ckeditor/ckeditor5-media-embed';
import { Paragraph, ParagraphButtonUI } from '@ckeditor/ckeditor5-paragraph';
import { PasteFromOffice } from '@ckeditor/ckeditor5-paste-from-office';
import { Table, TableToolbar } from '@ckeditor/ckeditor5-table';
import { TextTransformation } from '@ckeditor/ckeditor5-typing';
import { Alignment } from '@ckeditor/ckeditor5-alignment';
import { CodeBlock } from '@ckeditor/ckeditor5-code-block';
import { WordCount } from '@ckeditor/ckeditor5-word-count';
import { Font } from '@ckeditor/ckeditor5-font';
import { FindAndReplace } from '@ckeditor/ckeditor5-find-and-replace';
import { SimpleUploadAdapter } from '@ckeditor/ckeditor5-upload';

class ClassicEditor extends ClassicEditorBase {}

ClassicEditor.builtinPlugins = [
    Essentials,
    Autoformat,
    Bold,
    Italic,
    BlockQuote,
    Heading,
    Image,
    ImageCaption,
    ImageStyle,
    ImageToolbar,
    ImageUpload,
    Indent,
    Link,
    List,
    MediaEmbed,
    Paragraph,
    PasteFromOffice,
    PictureEditing,
    Table,
    TableToolbar,
    TextTransformation,
    HeadingButtonsUI,
    ParagraphButtonUI,
    IndentBlock,
    Code,
    Alignment,
    ImageResize,
    CodeBlock,
    Underline,
    Strikethrough,
    WordCount,
    ImageInsert,
    Font,
    FindAndReplace,
    SimpleUploadAdapter,
];

ClassicEditor.defaultConfig = {
    toolbar: {
        items: [
            'paragraph',
            'heading2',
            'heading3',
            '|',
            'fontSize',
            'bold',
            'italic',
            'underline',
            'strikethrough',
            'code',
            '|',
            'bulletedList',
            'numberedList',
            '|',
            'alignment',
            '|',
            'indent',
            'outdent',
            '-',
            'link',
            'blockQuote',
            'imageInsert',
            'insertTable',
            'mediaEmbed',
            'codeBlock',
            '|',
            'findAndReplace',
        ],
        shouldNotGroupWhenFull: true,
    },
    heading: {
        options: [
            {
                model: 'paragraph',
                title: 'Paragraph',
                class: 'ck-heading_paragraph',
            },
            {
                model: 'heading2',
                view: 'h2',
                title: 'Heading 2',
                class: 'ck-heading_heading2',
            },
            {
                model: 'heading3',
                view: 'h3',
                title: 'Heading 3',
                class: 'ck-heading_heading3',
            },
        ] as HeadingOption[],
    },
    fontSize: {
        options: ['tiny', 'default', 'big'],
    },
    link: {
        addTargetToExternalLinks: true,
    },
    image: {
        toolbar: ['toggleImageCaption', 'imageTextAlternative'],
    },
    table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'],
    },
    codeBlock: {
        languages: [
            { language: 'text', label: 'Plain text' }, // The default language.
            { language: 'bash', label: 'Bash' },
            { language: 'c', label: 'C' },
            { language: 'cs', label: 'C#' },
            { language: 'cpp', label: 'C++' },
            { language: 'css', label: 'CSS' },
            { language: 'dart', label: 'Dart' },
            { language: 'dockerfile', label: 'Dockerfile' },
            { language: 'go', label: 'Go' },
            { language: 'hcl', label: 'HCL' },
            { language: 'html', label: 'HTML' },
            { language: 'ini', label: 'INI' },
            { language: 'java', label: 'Java' },
            { language: 'javascript', label: 'JavaScript' },
            { language: 'json', label: 'JSON' },
            { language: 'kotlin', label: 'Kotlin' },
            { language: 'nginx', label: 'Nginx Config' },
            { language: 'php', label: 'PHP' },
            { language: 'python', label: 'Python' },
            { language: 'ruby', label: 'Ruby' },
            { language: 'rust', label: 'Rust' },
            { language: 'shell', label: 'Shell' },
            { language: 'sql', label: 'SQL' },
            { language: 'swift', label: 'Swift' },
            { language: 'toml', label: 'TOML' },
            { language: 'typescript', label: 'TypeScript' },
            { language: 'xml', label: 'XML' },
            { language: 'yaml', label: 'YAML' },
        ],
        indentSequence: '    ',
    },
    language: 'en',
};

declare global {
    interface Window {
        ClassicEditor: typeof ClassicEditor;
    }
}

window.ClassicEditor = ClassicEditor;
