declare global {
    interface Window {
        codeBlockHelper: (element: HTMLElement) => void;
    }
}

// use Tailwind CSS class names
const baseButtonClassName: string[] = [
    'fixed',
    'size-8',
    'flex',
    'justify-center',
    'items-center',
    'text-gray-50',
    'bg-emerald-500',
    'dark:bg-lividus-500',
    'rounded-md',
    'text-lg',
    'hover:bg-emerald-600',
    'dark:hover:bg-lividus-400',
    'active:bg-emerald-500',
    'dark:active:bg-lividus-500',
    'opacity-0',
    'group-hover:opacity-100',
    'transition-all',
    'duration-200',
];

const checkIcon: string = `
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
  <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
</svg>
`;

const clipboardIcon: string = `
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M10 1.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5zm-5 0A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5v1A1.5 1.5 0 0 1 9.5 4h-3A1.5 1.5 0 0 1 5 2.5zm-2 0h1v1A2.5 2.5 0 0 0 6.5 5h3A2.5 2.5 0 0 0 12 2.5v-1h1a2 2 0 0 1 2 2V14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V3.5a2 2 0 0 1 2-2"/>
</svg>
`;

function createWrapper(element: HTMLElement): HTMLDivElement {
    // to make the copy button fixed in the container, we wrap it in the container
    const wrapper: HTMLDivElement = document.createElement('div');
    // add 'translate-x-0' to make wrapper be a container
    // make sure the copy button won't be fixed in viewport but container
    wrapper.classList.add('group', 'translate-x-0');
    // set the wrapper as sibling of the pre tag
    element.parentNode?.insertBefore(wrapper, element);
    // set element as child of wrapper
    wrapper.appendChild(element);

    return wrapper;
}

function createCopyCodeButton(code: string): HTMLButtonElement {
    // create copy button
    const copyButton: HTMLButtonElement = document.createElement('button');
    copyButton.classList.add(...baseButtonClassName);
    // set button position
    copyButton.classList.add('top-2', 'right-2');
    copyButton.innerHTML = clipboardIcon;

    // when copy button is clicked, copy code to clipboard
    copyButton.addEventListener('click', function (this: HTMLButtonElement) {
        // copy code to clipboard
        navigator.clipboard.writeText(code).then(
            () => console.log('Copied to clipboard'),
            () => console.log('Failed to copy to clipboard'),
        );

        // change button icon to "Copied!" for 2 seconds
        this.innerHTML = checkIcon;
        setTimeout(
            function (this: HTMLButtonElement) {
                this.innerHTML = clipboardIcon;
            }.bind(this),
            2000,
        );
    });

    return copyButton;
}

window.codeBlockHelper = function (element: HTMLElement): void {
    const preTags: HTMLCollectionOf<HTMLPreElement> =
        element.getElementsByTagName('pre');

    // add copy button to all pre tags
    for (const preTag of preTags) {
        if (preTag.classList.contains('code-block-helper-added')) {
            return;
        }

        preTag.classList.add('code-block-helper-added');

        // to make the copy button fixed in the container, we wrap it in the container
        const wrapper: HTMLDivElement = createWrapper(preTag);

        const code = preTag.getElementsByTagName('code')[0];

        // create copy button
        const copyButton: HTMLButtonElement = createCopyCodeButton(
            code.innerText,
        );

        wrapper.appendChild(copyButton);
    }
};