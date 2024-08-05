declare global {
    interface Window {
        codeBlockCopyButton: any;
    }
}

// use Tailwind CSS class names
const buttonClassList: string[] = [
    'fixed',
    'top-2',
    'right-2',
    'h-8',
    'w-8',
    'flex',
    'justify-center',
    'items-center',
    'text-gray-50',
    'bg-green-500',
    'dark:bg-lividus-500',
    'rounded-md',
    'text-lg',
    'hover:bg-green-600',
    'dark:hover:bg-lividus-400',
    'active:bg-green-500',
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

window.codeBlockCopyButton = function (element: HTMLElement) {
    let preTags: HTMLCollectionOf<HTMLPreElement> =
        element.getElementsByTagName('pre');

    // add copy button to all pre tags
    Array.prototype.forEach.call(preTags, function (preTag) {
        if (preTag.classList.contains('copy-code-button-added')) {
            return;
        }

        preTag.classList.add('copy-code-button-added');

        // to make the copy button fixed in the container, we wrap it in the container
        let wrapper: HTMLDivElement = document.createElement('div');
        // add 'translate-x-0' to make wrapper be a container
        // make sure the copy button won't be fixed in viewport but container
        wrapper.classList.add('group', 'translate-x-0');

        // set the wrapper as sibling of the pre tag
        preTag.parentNode?.insertBefore(wrapper, preTag);
        // set element as child of wrapper
        wrapper.appendChild(preTag);

        // create copy button
        let copyButton: HTMLButtonElement = document.createElement('button');
        copyButton.classList.add(...buttonClassList);
        copyButton.innerHTML = clipboardIcon;

        // when copy button is clicked, copy code to clipboard
        copyButton.addEventListener(
            'click',
            function (this: HTMLButtonElement) {
                let code = preTag.getElementsByTagName('code')[0];

                // copy code to clipboard
                let codeText: string = code.innerText;
                navigator.clipboard.writeText(codeText).then(
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
            },
        );

        wrapper.appendChild(copyButton);
    });
};
