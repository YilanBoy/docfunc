/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************!*\
  !*** ./resources/js/editor.js ***!
  \********************************/
function _classCallCheck(instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
}

function _defineProperties(target, props) {
  for (var i = 0; i < props.length; i++) {
    var descriptor = props[i];
    descriptor.enumerable = descriptor.enumerable || false;
    descriptor.configurable = true;
    if ("value" in descriptor) descriptor.writable = true;
    Object.defineProperty(target, descriptor.key, descriptor);
  }
}

function _createClass(Constructor, protoProps, staticProps) {
  if (protoProps) _defineProperties(Constructor.prototype, protoProps);
  if (staticProps) _defineProperties(Constructor, staticProps);
  return Constructor;
} // 設定文章最多字數限制


var maxCharacters = 10000; // 設定要更新字數顯示的區塊

var wordsBoxs = document.querySelectorAll('.update-post-characters'); // 設定表單 submit 按鈕的區塊

var savePostButton = document.getElementsByClassName('save-post');

var MyUploadAdapter = /*#__PURE__*/function () {
  function MyUploadAdapter(loader) {
    _classCallCheck(this, MyUploadAdapter); // The file loader instance to use during the upload. It sounds scary but do not
    // worry — the loader will be passed into the adapter later on in this guide.


    this.loader = loader;
  } // Starts the upload process.


  _createClass(MyUploadAdapter, [{
    key: "upload",
    value: function upload() {
      var _this = this;

      return this.loader.file.then(function (file) {
        return new Promise(function (resolve, reject) {
          _this._initRequest();

          _this._initListeners(resolve, reject, file);

          _this._sendRequest(file);
        });
      });
    } // Aborts the upload process.

  }, {
    key: "abort",
    value: function abort() {
      if (this.xhr) {
        this.xhr.abort();
      }
    } // Initializes the XMLHttpRequest object using the URL passed to the constructor.

  }, {
    key: "_initRequest",
    value: function _initRequest() {
      var xhr = this.xhr = new XMLHttpRequest(); // Note that your request may look different. It is up to you and your editor
      // integration to choose the right communication channel. This example uses
      // a POST request with JSON as a data structure but your configuration
      // could be different.

      xhr.open('POST', "/images/upload", true);
      xhr.setRequestHeader('x-csrf-token', document.querySelector('meta[name="csrf-token"]').content);
      xhr.responseType = 'json';
    } // Initializes XMLHttpRequest listeners.

  }, {
    key: "_initListeners",
    value: function _initListeners(resolve, reject, file) {
      var xhr = this.xhr;
      var loader = this.loader;
      var genericErrorText = "Couldn't upload file: ".concat(file.name, ".");
      xhr.addEventListener('error', function () {
        return reject(genericErrorText);
      });
      xhr.addEventListener('abort', function () {
        return reject();
      });
      xhr.addEventListener('load', function () {
        var response = xhr.response; // This example assumes the XHR server's "response" object will come with
        // an "error" which has its own "message" that can be passed to reject()
        // in the upload promise.
        //
        // Your integration may handle upload errors in a different way so make sure
        // it is done properly. The reject() function must be called when the upload fails.

        if (!response || response.error) {
          return reject(response && response.error ? response.error.message : genericErrorText);
        } // If the upload is successful, resolve the upload promise with an object containing
        // at least the "default" URL, pointing to the image on the server.
        // This URL will be used to display the image in the content. Learn more in the
        // UploadAdapter#upload documentation.


        resolve({
          "default": response.url
        });
      }); // Upload progress when it is supported. The file loader has the #uploadTotal and #uploaded
      // properties which are used e.g. to display the upload progress bar in the editor
      // user interface.

      if (xhr.upload) {
        xhr.upload.addEventListener('progress', function (evt) {
          if (evt.lengthComputable) {
            loader.uploadTotal = evt.total;
            loader.uploaded = evt.loaded;
          }
        });
      }
    } // Prepares the data and sends the request.

  }, {
    key: "_sendRequest",
    value: function _sendRequest(file) {
      // Prepare the form data.
      var data = new FormData();
      data.append('upload', file); // Important note: This is the right place to implement security mechanisms
      // like authentication and CSRF protection. For instance, you can use
      // XMLHttpRequest.setRequestHeader() to set the request headers containing
      // the CSRF token generated earlier by your application.
      // Send the request.

      this.xhr.send(data);
    }
  }]);

  return MyUploadAdapter;
}();

function MyCustomUploadAdapterPlugin(editor) {
  editor.plugins.get('FileRepository').createUploadAdapter = function (loader) {
    // Configure the URL to the upload script in your back-end here!
    return new MyUploadAdapter(loader);
  };
}

ClassicEditor.create(document.querySelector('#editor'), {
  // 圖片上傳
  extraPlugins: [MyCustomUploadAdapterPlugin],
  // Editor configuration.
  wordCount: {
    onUpdate: function onUpdate(stats) {
      // 字數超過最高限制
      var isLimitExceeded = stats.characters > maxCharacters; // 接近字數限制

      var isCloseToLimit = !isLimitExceeded && stats.characters > maxCharacters * 0.8; // 文章總字數更新

      wordsBoxs.forEach(function (element) {
        element.textContent = "".concat(stats.characters, " / ").concat(maxCharacters); // 如果字數接近限制，則將 wordsBox 的 class 加上一個 text-yellow-500，使文字變成黃色

        element.classList.toggle('text-yellow-500', isCloseToLimit); // 如果字數超過限制，則將 wordsBox 的 class 加上一個 text-red-500，使文字變成紅色

        element.classList.toggle('text-red-500', isLimitExceeded);
      }); // 如果字數超過最高限制，則將送出的按鈕 disable

      Array.prototype.forEach.call(savePostButton, function (element) {
        element.toggleAttribute('disabled', isLimitExceeded);
      });
    }
  }
}).then(function (editor) {
  window.editor = editor;
})["catch"](function (err) {
  console.error(err.stack);
}); // 若以非送出表單的方式離開編輯頁面，以 alert 視窗提醒使用者

var saveButtonClicked = false;
Array.prototype.forEach.call(savePostButton, function (element) {
  element.addEventListener('click', function () {
    saveButtonClicked = true;
  });
});
window.addEventListener('beforeunload', function (event) {
  if (!saveButtonClicked) {
    // 取消事件的標準做法，但是 Chrome 不支援
    event.preventDefault(); // Firefox 不支援
    // 取消事件，Chrome 要求 returnValue 必須給一個值
    // 以往這個值可以顯示在 alert 視窗上，現在已不再支援修改，因此給空值就好

    event.returnValue = '';
  }
});
/******/ })()
;