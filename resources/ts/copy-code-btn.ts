let preTags: HTMLCollectionOf<HTMLPreElement> = document.getElementsByTagName('pre');

// use Tailwind CSS class names
let buttonClassList: string[] = [
    'copy-code-button', 'absolute', 'top-2', 'right-2',
    'text-gray-50', 'bg-blue-400', 'rounded-md', 'px-2', 'py-1', 'text-sm',
    'hover:bg-blue-500', 'active:bg-blue-400',
    'opacity-0', 'group-hover:opacity-100', 'transition-all', 'duration-200'
];

// add class "relative" and copy button to all pre tags
for (let i = 0, preTagsLength = preTags.length; i < preTagsLength; i++) {
    preTags[i].classList.add('relative', 'group');

    let copyButton: HTMLButtonElement = document.createElement('button');
    copyButton.classList.add(...buttonClassList);
    copyButton.innerHTML = 'Copy';
    preTags[i].appendChild(copyButton);
}

// add event listener to all copy buttons, when button clicked, copy code to clipboard
let copyButtons: HTMLCollectionOf<Element> = document.getElementsByClassName('copy-code-button');
for (let i = 0, copyButtonsLength = copyButtons.length; i < copyButtonsLength; i++) {
    copyButtons[i].addEventListener('click', function (this: HTMLButtonElement) {
        if (!this.parentElement) {
            return;
        }

        let code = this.parentElement.getElementsByTagName('code')[0];
        let codeText: string = code.innerText;
        let textArea: HTMLTextAreaElement = document.createElement('textarea');
        textArea.value = codeText;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        textArea.remove();

        // change button inner text to "Copied!" for 2 seconds
        this.innerText = 'Copied!';
        setTimeout(function (this: HTMLButtonElement) {
            this.innerText = 'Copy';
        }.bind(this), 2000);
    });
}
