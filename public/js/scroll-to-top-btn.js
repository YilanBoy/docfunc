/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/*!*******************************************!*\
  !*** ./resources/ts/scroll-to-top-btn.ts ***!
  \*******************************************/
 // 用 id 取得置頂按鈕

var scrollToTopButton = document.getElementById('scroll-to-top-btn');
scrollToTopButton === null || scrollToTopButton === void 0 ? void 0 : scrollToTopButton.addEventListener('click', scrollToTop); // 滾動至網頁最頂部

function scrollToTop() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}

var header = document.getElementById('header');

if (header !== null) {
  // 根據 header 是否出現在畫面上調整按鈕的樣式
  var headerObserver = new IntersectionObserver(function (entries) {
    // isIntersecting is true when element and viewport are overlapping
    // isIntersecting is false when element and viewport don't overlap
    if (entries[0].isIntersecting === true) {
      // header 在畫面上
      scrollToTopButton === null || scrollToTopButton === void 0 ? void 0 : scrollToTopButton.classList.add('hidden');
      scrollToTopButton === null || scrollToTopButton === void 0 ? void 0 : scrollToTopButton.classList.remove('block');
    } else {
      // header 不在畫面上
      scrollToTopButton === null || scrollToTopButton === void 0 ? void 0 : scrollToTopButton.classList.remove('hidden');
      scrollToTopButton === null || scrollToTopButton === void 0 ? void 0 : scrollToTopButton.classList.add('block');
    }
  }, {
    threshold: [0]
  });
  headerObserver.observe(header);
}

var footer = document.getElementById('footer');

if (footer !== null) {
  // 根據 footer 是否出現在畫面上調整按鈕的樣式
  var footerObserver = new IntersectionObserver(function (entries) {
    if (entries[0].isIntersecting === true) {
      // footer 在畫面上
      scrollToTopButton === null || scrollToTopButton === void 0 ? void 0 : scrollToTopButton.classList.remove('fixed');
      scrollToTopButton === null || scrollToTopButton === void 0 ? void 0 : scrollToTopButton.classList.add('absolute');
    } else {
      // footer 不在畫面上
      scrollToTopButton === null || scrollToTopButton === void 0 ? void 0 : scrollToTopButton.classList.add('fixed');
      scrollToTopButton === null || scrollToTopButton === void 0 ? void 0 : scrollToTopButton.classList.remove('absolute');
    }
  }, {
    threshold: [0]
  });
  footerObserver.observe(footer);
}
/******/ })()
;