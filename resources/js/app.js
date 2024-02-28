import './bootstrap';

document.addEventListener('livewire:init', () => {
    let currentScrollY = 0;

    Livewire.hook('commit.prepare', ({ component }) => {
        if (component.name === 'shared.comments.comments') {
            currentScrollY = window.scrollY;
        }
    });

    Livewire.hook('morph.updated', ({ component }) => {
        // when show more comments, stick the current scrollY
        if (component.name === 'shared.comments.comments') {
            window.scrollTo({
                top: currentScrollY,
                behavior: 'instant',
            });
        }

        // Make sure this will be executed after the DOM is updated
        queueMicrotask(() => {
            // when update the DOM, check the code block and highlight it
            if (
                [
                    'shared.comments.comments',
                    'shared.comments.comment-group',
                    'shared.comments.comment-card',
                    'shared.comments.create-comment-modal',
                    'shared.comments.edit-comment-modal',
                    'shared.users.comments',
                ].includes(component.name)
            ) {
                document
                    .querySelectorAll('pre code:not(.hljs)')
                    .forEach((element) => {
                        hljs.highlightElement(element);
                    });
            }

            if (
                [
                    'shared.comments.comments',
                    'shared.comments.comment-group',
                    'shared.comments.comment-card',
                ].includes(component.name)
            ) {
                codeBlockCopyButton(document.documentElement);
            }
        });
    });
});
