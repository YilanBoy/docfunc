/******/
(() => { // webpackBootstrap
    /******/
    "use strict";
    var __webpack_exports__ = {};
    /*!***************************************!*\
      !*** ./resources/ts/copy-code-btn.ts ***!
      \***************************************/


    var _a;

    var preTags = document.getElementsByTagName('pre');
    var buttonClassList = ['absolute', 'top-2', 'right-2', 'opacity-0', 'bg-blue-400', 'rounded-md', 'px-4', 'py-2', 'text-sm', 'text-gray-50', 'hover:bg-blue-500', 'active:bg-blue-400', 'group-hover:opacity-100', 'transition-all', 'duration-200']; // add class "relative" to all pre tags

    for (var i = 0, preTagsLength = preTags.length; i < preTagsLength; i++) {
        preTags[i].classList.add('relative', 'group');
        var btn = document.createElement('button');

        (_a = btn.classList).add.apply(_a, buttonClassList);

        btn.innerHTML = 'Copy';
        preTags[i].appendChild(btn);
    }
    /******/
})()
;
