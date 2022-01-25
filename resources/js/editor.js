import MyUploadAdapter from "./upload-image-adapter.js";

// 設定文章最多字數限制
const maxCharacters = 20000;
// 設定要更新字數顯示的區塊
const wordsBoxs = document.querySelectorAll(".update-post-characters");
// 設定表單 submit 按鈕的區塊
const savePostButton = document.getElementsByClassName("save-post");

function MyCustomUploadAdapterPlugin(editor) {
    editor.plugins.get("FileRepository").createUploadAdapter = (loader) => {
        // Configure the URL to the upload script in your back-end here!
        return new MyUploadAdapter(loader);
    };
}

ClassicEditor.create(document.querySelector("#editor"), {
    // 圖片上傳
    extraPlugins: [MyCustomUploadAdapterPlugin],

    // Editor configuration.
    wordCount: {
        onUpdate: (stats) => {
            // 字數超過最高限制
            const isLimitExceeded = stats.characters > maxCharacters;
            // 接近字數限制
            const isCloseToLimit =
                !isLimitExceeded && stats.characters > maxCharacters * 0.8;

            // 文章總字數更新
            wordsBoxs.forEach((element) => {
                element.textContent = `${stats.characters} / ${maxCharacters}`;
                // 如果字數接近限制，則將 wordsBox 的 class 加上一個 text-yellow-500，使文字變成黃色
                element.classList.toggle("text-yellow-500", isCloseToLimit);
                // 如果字數超過限制，則將 wordsBox 的 class 加上一個 text-red-400，使文字變成紅色
                element.classList.toggle("text-red-400", isLimitExceeded);
            });

            // 如果字數超過最高限制，則將送出的按鈕 disable
            Array.prototype.forEach.call(savePostButton, (element) => {
                element.toggleAttribute("disabled", isLimitExceeded);
            });
        },
    },
})
    .then((editor) => {
        console.log("Editor was initialized");
    })
    .catch((err) => {
        console.error(err.stack);
    });

// 若以非送出表單的方式離開編輯頁面，以 alert 視窗提醒使用者
let saveButtonClicked = false;

Array.prototype.forEach.call(savePostButton, (element) => {
    element.addEventListener("click", () => {
        saveButtonClicked = true;
    });
});

window.addEventListener("beforeunload", (event) => {
    if (!saveButtonClicked) {
        // 取消事件的標準做法，但是 Chrome 不支援
        event.preventDefault();

        // Firefox 不支援
        // 取消事件，Chrome 要求 returnValue 必須給一個值
        // 以往這個值可以顯示在 alert 視窗上，現在已不再支援修改，因此給空值就好
        event.returnValue = "";
    }
});
