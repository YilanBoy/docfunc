declare global {
    interface Window {
        codeBlockHelper: (element: HTMLElement) => void;
    }
}

// use Tailwind CSS class names
const BASE_BUTTON_CLASS_NAME: string[] = [
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

const CHECK_ICON_SVG: string = `
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
  <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
</svg>
`;

const CLIPBOARD_ICON_SVG: string = `
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M10 1.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5zm-5 0A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5v1A1.5 1.5 0 0 1 9.5 4h-3A1.5 1.5 0 0 1 5 2.5zm-2 0h1v1A2.5 2.5 0 0 0 6.5 5h3A2.5 2.5 0 0 0 12 2.5v-1h1a2 2 0 0 1 2 2V14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V3.5a2 2 0 0 1 2-2"/>
</svg>
`;

const ARROWS_ANGLE_EXPAND_ICON_SVG: string = `
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrows-angle-expand" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M5.828 10.172a.5.5 0 0 0-.707 0l-4.096 4.096V11.5a.5.5 0 0 0-1 0v3.975a.5.5 0 0 0 .5.5H4.5a.5.5 0 0 0 0-1H1.732l4.096-4.096a.5.5 0 0 0 0-.707m4.344-4.344a.5.5 0 0 0 .707 0l4.096-4.096V4.5a.5.5 0 1 0 1 0V.525a.5.5 0 0 0-.5-.5H11.5a.5.5 0 0 0 0 1h2.768l-4.096 4.096a.5.5 0 0 0 0 .707"/>
</svg>
`;

const X_CIRCLE_FILL_ICON_SVG: string = `
<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-10" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
</svg>
`;

const BACKGROUND_BACKDROP_ID: string = 'expand-code-block-background-backdrop';
const MODAL_PANEL_ID: string = 'expand-code-block-modal-panel';
const CLOSE_MODAL_BUTTON_ID: string = 'expand-code-block-close-modal-button';

function createCopyCodeButton(code: string): HTMLButtonElement {
    // create copy button
    const copyButton: HTMLButtonElement = document.createElement('button');
    copyButton.classList.add(...BASE_BUTTON_CLASS_NAME);
    // set button position
    copyButton.classList.add('top-2', 'right-2');
    copyButton.innerHTML = CLIPBOARD_ICON_SVG;

    // when copy button is clicked, copy code to clipboard
    copyButton.addEventListener('click', function (this: HTMLButtonElement) {
        // copy code to clipboard
        navigator.clipboard.writeText(code).then(
            () => console.log('Copied to clipboard'),
            () => console.log('Failed to copy to clipboard'),
        );

        // change button icon to "Copied!" for 2 seconds
        this.innerHTML = CHECK_ICON_SVG;
        setTimeout(
            function (this: HTMLButtonElement) {
                this.innerHTML = CLIPBOARD_ICON_SVG;
            }.bind(this),
            2000,
        );
    });

    return copyButton;
}

function modalTemplate(html: string): string {
    return `
        <div class="relative z-30">
            <!-- Background backdrop, show/hide based on modal state -->
            <div
                id="${BACKGROUND_BACKDROP_ID}"
                class="fixed inset-0 bg-gray-500/75 transition-opacity backdrop-blur-md ease-in duration-200 opacity-0"
            ></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full justify-center p-4 text-center items-center">
                    <!-- Modal panel, show/hide based on modal state. -->
                    <div
                        id="${MODAL_PANEL_ID}"
                        class="relative transform overflow-hidden rounded-lg text-left transition-all sm:w-full sm:max-w-6xl ease-in duration-200 opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        ${html}
                    </div>
                </div>
            </div>

            <div class="fixed z-10 right-10 top-10">
                <button
                    id="${CLOSE_MODAL_BUTTON_ID}"
                    type="button"
                    class="text-gray-200 hover:text-gray-50"
                >
                   ${X_CIRCLE_FILL_ICON_SVG}
                </button>
            </div>
        </div>
    `;
}

function createExpandCodeButton(preOuterHtml: string): {
    expandButton: HTMLButtonElement;
    modal: HTMLDivElement;
} {
    const expandButton: HTMLButtonElement = document.createElement('button');
    expandButton.classList.add(...BASE_BUTTON_CLASS_NAME);
    expandButton.classList.add('top-2', 'right-12');
    expandButton.innerHTML = ARROWS_ANGLE_EXPAND_ICON_SVG;

    const modalInnerHtml = modalTemplate(preOuterHtml);

    const modal: HTMLDivElement = document.createElement('div');
    modal.innerHTML = modalInnerHtml;

    // transport modal to another part of the DOM on the page entirely
    expandButton.addEventListener('click', function (this: HTMLButtonElement) {
        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';

        const backgroundBackdrop = document.getElementById(
            BACKGROUND_BACKDROP_ID,
        );

        const modalPanel = document.getElementById(MODAL_PANEL_ID);

        setTimeout(() => {
            backgroundBackdrop?.classList.remove(
                'ease-in',
                'duration-200',
                'opacity-0',
            );

            backgroundBackdrop?.classList.add(
                'ease-out',
                'duration-300',
                'opacity-100',
            );

            modalPanel?.classList.remove(
                'ease-in',
                'duration-200',
                'opacity-0',
                'translate-y-4',
                'sm:translate-y-0',
                'sm:scale-95',
            );

            modalPanel?.classList.add(
                'ease-out',
                'duration-300',
                'opacity-100',
                'translate-y-0',
                'sm:scale-100',
            );
        }, 100);

        const closeExpandButton = document.getElementById(
            CLOSE_MODAL_BUTTON_ID,
        );

        closeExpandButton?.addEventListener(
            'click',
            () => {
                backgroundBackdrop?.classList.remove(
                    'ease-out',
                    'duration-300',
                    'opacity-100',
                );

                backgroundBackdrop?.classList.add(
                    'ease-in',
                    'duration-200',
                    'opacity-0',
                );

                modalPanel?.classList.remove(
                    'ease-out',
                    'duration-300',
                    'opacity-100',
                    'translate-y-0',
                    'sm:scale-100',
                );

                modalPanel?.classList.add(
                    'ease-in',
                    'duration-20',
                    'opacity-0',
                    'translate-y-4',
                    'sm:translate-y-0',
                    'sm:scale-95',
                );

                setTimeout(() => {
                    document.body.removeChild(modal);
                    document.body.style.overflow = '';
                }, 300);
            },
            { once: true },
        );
    });

    return { expandButton: expandButton, modal: modal };
}

window.codeBlockHelper = function (element: HTMLElement): void {
    const preTags: HTMLCollectionOf<HTMLPreElement> =
        element.getElementsByTagName('pre');

    // add copy button to all pre tags
    for (const preTag of preTags) {
        if (preTag.classList.contains('code-block-helper-added')) {
            return;
        }

        preTag.classList.add(
            'code-block-helper-added',
            'group',
            // add 'translate-x-0' to make pre tag be a container
            // make sure the copy button won't be fixed in viewport but container
            'translate-x-0',
        );

        const code = preTag.getElementsByTagName('code')[0];

        // start to create copy button...
        const copyButton: HTMLButtonElement = createCopyCodeButton(
            code.innerText,
        );

        const { expandButton, modal } = createExpandCodeButton(
            preTag.outerHTML,
        );

        // append these button in pre tag
        preTag.appendChild(copyButton);
        preTag.appendChild(expandButton);

        // remove these new element that create in this script,
        // when user want to navigate to next page...
        document.addEventListener(
            'livewire:navigating',
            () => {
                modal.remove();
                copyButton.remove();
                expandButton.remove();
                preTag.classList.remove(
                    'code-block-helper-added',
                    'group',
                    'translate-x-0',
                );
            },
            { once: true },
        );
    }
};
