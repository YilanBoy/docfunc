// 設定文章最多字數限制
const maxCharacters = 10000;
// 設定要更新字數顯示的區塊
const wordsBoxs = document.querySelectorAll('.update-characters');
// 設定表單 submit 按鈕的區塊
const sendButton = document.querySelector('#post-save');
const LargeScreenSendButton = document.querySelector('#lg-post-save');

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
            wordsBoxs.forEach(element => {
                element.textContent = `${stats.characters} / ${maxCharacters}`;
                // 如果字數接近限制，則將 wordsBox 的 class 加上一個 characters-limit-exceeded，使文字變成橘色
                element.classList.toggle('characters-limit-close', isCloseToLimit);
                // 如果字數超過限制，則將 wordsBox 的 class 加上一個 characters-limit-exceeded，使文字變成紅色
                element.classList.toggle(
                    'characters-limit-exceeded',
                    isLimitExceeded
                );
            });

            // 如果字數超過最高限制，則將送出的按鈕 disable
            sendButton.toggleAttribute('disabled', isLimitExceeded);
            LargeScreenSendButton.toggleAttribute('disabled', isLimitExceeded);
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
document.getElementById('post-save').addEventListener('click', function () {
    saveButtonClicked = true;
});

window.addEventListener('beforeunload', event => {
    if (!saveButtonClicked) {
        // 取消事件的標準做法，但是 Chrome 不支援
        event.preventDefault();

        // Firefox 不支援
        // 取消事件，Chrome 要求 returnValue 必須給一個值
        // 以往這個值可以顯示在 alert 視窗上，現在已不再支援修改，因此給空值就好
        event.returnValue = '';
    }
});
