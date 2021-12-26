let preTags: HTMLCollectionOf<HTMLPreElement> = document.getElementsByTagName('pre');

// use Tailwind CSS class names
let buttonClassList: string[] = [
    'copy-code-button', 'absolute', 'top-2', 'right-2',
    'h-8', 'w-8', 'flex', 'justify-center', 'items-center',
    'text-gray-50', 'bg-blue-400', 'rounded-md', 'text-lg',
    'hover:bg-blue-500', 'active:bg-blue-400',
    'opacity-0', 'group-hover:opacity-100', 'transition-all', 'duration-200'
];

// add copy button to all pre tags
for (let i = 0, preTagsLength = preTags.length; i < preTagsLength; i++) {
    preTags[i].classList.add('relative', 'group');

    // create copy button
    let copyButton: HTMLButtonElement = document.createElement('button');
    copyButton.classList.add(...buttonClassList);
    copyButton.innerHTML = '<i class="bi bi-clipboard"></i>';

    // when copy button is clicked, copy code to clipboard
    copyButton.addEventListener('click', function (this: HTMLButtonElement) {
        let code = preTags[i].getElementsByTagName('code')[0];

        // copy code to clipboard
        let codeText: string = code.innerText;
        navigator.clipboard.writeText(codeText)
            .then(() => console.log('Copied to clipboard'), () => console.log('Failed to copy to clipboard'));

        // change button icon to "Copied!" for 2 seconds
        this.innerHTML = '<i class="bi bi-clipboard-check"></i>';
        setTimeout(function (this: HTMLButtonElement) {
            this.innerHTML = '<i class="bi bi-clipboard"></i>';
        }.bind(this), 2000);
    });

    preTags[i].appendChild(copyButton);
}
