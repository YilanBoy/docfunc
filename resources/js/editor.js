import MyUploadAdapter from "./upload-image-adapter.js";

// 設定文章最多字數限制
const maxCharacters = 20000;
// 設定要更新字數顯示的區塊
const characterCounter = document.querySelectorAll(".character-counter");

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
            characterCounter.forEach((element) => {
                element.textContent = `${stats.characters} / ${maxCharacters}`;
                // 如果字數接近限制，則將 wordsBox 的 class 加上一個 text-yellow-500，使文字變成黃色
                element.classList.toggle("text-yellow-500", isCloseToLimit);
                // 如果字數超過限制，則將 wordsBox 的 class 加上一個 text-red-400，使文字變成紅色
                element.classList.toggle("text-red-400", isLimitExceeded);
            });
        },
    },
})
    .then((editor) => {
        console.log("ckeditor was initialized");
    })
    .catch((err) => {
        console.error(err.stack);
    });
