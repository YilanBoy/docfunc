const editor = document.querySelector('#editor');
// Set a maximum word count limit for the post
const maxCharacters = 20000;
// Set up an HTML element to display the character count
const characterCounter = document.querySelectorAll('.character-counter');

ClassicEditor.create(editor, {
    // Editor configuration.
    wordCount: {
        onUpdate: (stats) => {
            // The character count has exceeded the maximum limit
            const isLimitExceeded = stats.characters > maxCharacters;
            // The character count is approaching the maximum limit
            const isCloseToLimit =
                !isLimitExceeded && stats.characters > maxCharacters * 0.8;

            // update character count in HTML element
            characterCounter.forEach((element) => {
                element.textContent = `${stats.characters} / ${maxCharacters}`;
                // If the character count is approaching the limit
                // add the class 'text-yellow-500' to the 'wordsBox' element to turn the text yellow
                element.classList.toggle('text-yellow-500', isCloseToLimit);
                // If the character count exceeds the limit
                // add the class 'text-red-400' to the 'wordsBox' element to turn the text red
                element.classList.toggle('text-red-400', isLimitExceeded);
            });
        },
    },
    simpleUpload: {
        // The URL that the images are uploaded to.
        uploadUrl: '/api/images/upload',

        // laravel sanctum need csrf token to authenticate
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                .content,
        },
    },
})
    .then((editor) => {
        console.log('ckeditor was initialized');
    })
    .catch((err) => {
        console.error(err.stack);
    });
