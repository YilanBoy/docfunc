// 設定文章最多字數限制
const maxCharacters = 10000;
// 設定要更新字數顯示的區塊
const wordsBoxs = document.querySelectorAll('.update-characters');
// 設定表單 submit 按鈕的區塊
const sendButton = document.querySelector('#save-post');
const LargeScreenSendButton = document.querySelector('#lg-save-post');

class MyUploadAdapter {
    constructor(loader) {
        // The file loader instance to use during the upload. It sounds scary but do not
        // worry — the loader will be passed into the adapter later on in this guide.
        this.loader = loader;
    }

    // Starts the upload process.
    upload() {
        return this.loader.file
            .then(file => new Promise((resolve, reject) => {
                this._initRequest();
                this._initListeners(resolve, reject, file);
                this._sendRequest(file);
            }));
    }

    // Aborts the upload process.
    abort() {
        if (this.xhr) {
            this.xhr.abort();
        }
    }

    // Initializes the XMLHttpRequest object using the URL passed to the constructor.
    _initRequest() {
        const xhr = this.xhr = new XMLHttpRequest();

        // Note that your request may look different. It is up to you and your editor
        // integration to choose the right communication channel. This example uses
        // a POST request with JSON as a data structure but your configuration
        // could be different.
        xhr.open('POST', "/images/upload", true);
        xhr.setRequestHeader('x-csrf-token', document.querySelector('meta[name="csrf-token"]').content)
        xhr.responseType = 'json';
    }

    // Initializes XMLHttpRequest listeners.
    _initListeners(resolve, reject, file) {
        const xhr = this.xhr;
        const loader = this.loader;
        const genericErrorText = `Couldn't upload file: ${file.name}.`;

        xhr.addEventListener('error', () => reject(genericErrorText));
        xhr.addEventListener('abort', () => reject());
        xhr.addEventListener('load', () => {
            const response = xhr.response;

            // This example assumes the XHR server's "response" object will come with
            // an "error" which has its own "message" that can be passed to reject()
            // in the upload promise.
            //
            // Your integration may handle upload errors in a different way so make sure
            // it is done properly. The reject() function must be called when the upload fails.
            if (!response || response.error) {
                return reject(response && response.error ? response.error.message : genericErrorText);
            }

            // If the upload is successful, resolve the upload promise with an object containing
            // at least the "default" URL, pointing to the image on the server.
            // This URL will be used to display the image in the content. Learn more in the
            // UploadAdapter#upload documentation.
            resolve({
                default: response.url
            });
        });

        // Upload progress when it is supported. The file loader has the #uploadTotal and #uploaded
        // properties which are used e.g. to display the upload progress bar in the editor
        // user interface.
        if (xhr.upload) {
            xhr.upload.addEventListener('progress', evt => {
                if (evt.lengthComputable) {
                    loader.uploadTotal = evt.total;
                    loader.uploaded = evt.loaded;
                }
            });
        }
    }

    // Prepares the data and sends the request.
    _sendRequest(file) {
        // Prepare the form data.
        const data = new FormData();

        data.append('upload', file);

        // Important note: This is the right place to implement security mechanisms
        // like authentication and CSRF protection. For instance, you can use
        // XMLHttpRequest.setRequestHeader() to set the request headers containing
        // the CSRF token generated earlier by your application.

        // Send the request.
        this.xhr.send(data);
    }
}

function MyCustomUploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
        // Configure the URL to the upload script in your back-end here!
        return new MyUploadAdapter(loader);
    };
}

ClassicEditor.create(document.querySelector('#editor'), {
    // toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ]

    extraPlugins: [MyCustomUploadAdapterPlugin],

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
sendButton.addEventListener('click', function () {
    saveButtonClicked = true;
});
LargeScreenSendButton.addEventListener('click', function () {
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
