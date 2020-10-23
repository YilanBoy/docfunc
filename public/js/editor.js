// 設定文章最多字數限制
const maxCharacters = 5000;
// 設定要更新字數顯示的區塊
const wordsBox = document.querySelector('.update-characters');
// 設定表單 submit 按鈕的區塊
const sendButton = document.querySelector('#post-save');

ClassicEditor.create(document.querySelector('#editor'), {
    // toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ]

    ckfinder: {
        // Upload the images to the server using the CKFinder QuickUpload command.
        uploadUrl:
            appUrl +
            '/ckfinder/connector?command=QuickUpload&type=Images&responseType=json'
    },

    // Editor configuration.
    wordCount: {
        onUpdate: stats => {
            // 字數超過最高限制
            const isLimitExceeded = stats.characters > maxCharacters;
            // 接近字數限制
            const isCloseToLimit =
                !isLimitExceeded && stats.characters > maxCharacters * 0.8;

            // 文章總字數更新
            wordsBox.textContent = `文章總字數：${stats.characters} / ${maxCharacters}`;

            // 如果字數接近限制，則將 wordsBox 的 class 加上一個 characters-limit-exceeded，使文字變成橘色
            wordsBox.classList.toggle('characters-limit-close', isCloseToLimit);

            // 如果字數超過限制，則將 wordsBox 的 class 加上一個 characters-limit-exceeded，使文字變成紅色
            wordsBox.classList.toggle(
                'characters-limit-exceeded',
                isLimitExceeded
            );

            // 如果字數超過最高限制，則將送出的按鈕 disable
            sendButton.toggleAttribute('disabled', isLimitExceeded);
        }
    }
})
    .then(editor => {
        window.editor = editor;
    })
    .catch(err => {
        console.error(err.stack);
    });

// 若以非送出表單的方式離開編輯頁面，以 alert 視窗提醒使用者
var saveButtonClicked = false;
document.getElementById('post-save').addEventListener('click', function() {
    saveButtonClicked = true;
});

window.onbeforeunload = function(event) {
    if (!saveButtonClicked) {
        event.returnValue = '您尚未儲存，是否離開編輯頁面？';
    }
};
