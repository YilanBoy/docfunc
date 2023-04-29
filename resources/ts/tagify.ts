import Tagify from '@yaireo/tagify';

declare global {
    interface Window {
        tagify: any;
    }
}

const tags = <HTMLInputElement>document.querySelector('#tags');

fetch('/api/tags')
    .then((response) => response.json())
    .then(function (tagsJson) {
        window.tagify = new Tagify(tags, {
            whitelist: tagsJson.data,
            enforceWhitelist: true,
            maxTags: 5,
            dropdown: {
                // show the dropdown immediately on focus
                enabled: 0,
                maxItems: 5,
                // place the dropdown near the typed text
                position: 'text',
                // keep the dropdown open after selecting a suggestion
                closeOnSelect: false,
                highlightFirst: true,
            },
        });
    });
