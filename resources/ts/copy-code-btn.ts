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
    'bg-green-600',
    'dark:bg-[#1b87f5]',
    'rounded-md',
    'text-lg',
    'hover:bg-green-700',
    'dark:hover:bg-[#44adff]',
    'active:bg-green-600',
    'active:bg-blue-700',
    'opacity-0',
    'group-hover:opacity-100',
    'transition-all',
    'duration-200',
];

interface Window {
    codeBlockCopyButton: any;
}

window.codeBlockCopyButton = function (selectors: string) {
    let preTags = document.querySelectorAll(selectors);

    if (!selectors.endsWith('pre')) {
        console.log("selectors must be end with 'pre' tag");

        return;
    }

    // add copy button to all pre tags
    for (let i = 0, preTagsLength = preTags.length; i < preTagsLength; i++) {
        if (preTags[i].classList.contains('copy-code-button-added')) {
            continue;
        }

        preTags[i].classList.add('copy-code-button-added');

        // to make the copy button fixed in the container, we wrap it in the container
        let wrapper: HTMLDivElement = document.createElement('div');
        // add 'translate-x-0' to make wrapper be a container
        // make sure the copy button won't be fixed in viewport but container
        wrapper.classList.add('group', 'translate-x-0');

        // set the wrapper as sibling of the pre tag
        preTags[i].parentNode?.insertBefore(wrapper, preTags[i]);
        // set element as child of wrapper
        wrapper.appendChild(preTags[i]);

        // create copy button
        let copyButton: HTMLButtonElement = document.createElement('button');
        copyButton.classList.add(...buttonClassList);
        copyButton.innerHTML = '<i class="bi bi-clipboard"></i>';

        // when copy button is clicked, copy code to clipboard
        copyButton.addEventListener(
            'click',
            function (this: HTMLButtonElement) {
                let code = preTags[i].getElementsByTagName('code')[0];

                // copy code to clipboard
                let codeText: string = code.innerText;
                navigator.clipboard.writeText(codeText).then(
                    () => console.log('Copied to clipboard'),
                    () => console.log('Failed to copy to clipboard')
                );

                // change button icon to "Copied!" for 2 seconds
                this.innerHTML = `<i class="bi bi-check-lg"></i>`;
                setTimeout(
                    function (this: HTMLButtonElement) {
                        this.innerHTML = `<i class="bi bi-clipboard"></i>`;
                    }.bind(this),
                    2000
                );
            }
        );

        wrapper.appendChild(copyButton);
    }
};

window.codeBlockCopyButton('body pre');
