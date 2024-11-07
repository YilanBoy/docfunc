import Tagify, { ChangeEventData } from '@yaireo/tagify';

declare global {
    interface Window {
        createTagify: (
            element: HTMLInputElement,
            whitelist: Tagify.TagData[],
            callbackOnChange: (event: CustomEvent<ChangeEventData>) => void,
        ) => Tagify;
    }
}

window.createTagify = function (
    element: HTMLInputElement,
    whitelist: Tagify.TagData[],
    callbackOnChange: (event: CustomEvent<ChangeEventData>) => void,
) {
    return new Tagify(element, {
        whitelist: whitelist,
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
        callbacks: {
            // binding the value of the tag input to the livewire attribute 'tags'
            change: callbackOnChange,
        },
    });
};
