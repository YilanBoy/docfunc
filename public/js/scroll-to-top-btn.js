/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*******************************************!*\
  !*** ./resources/js/scroll-to-top-btn.js ***!
  \*******************************************/
// Get the button
var scrollToTopButton = document.getElementById('scroll-to-top-btn');
scrollToTopButton.addEventListener('click', scrollToTop); // When the user clicks on the button, scroll to the top of the document

function scrollToTop() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
} // 根據 header 是否出現在畫面上調整按鈕的樣式


var headerObserver = new IntersectionObserver(function (entries) {
  // isIntersecting is true when element and viewport are overlapping
  // isIntersecting is false when element and viewport don't overlap
  if (entries[0].isIntersecting === true) {
    // header 在畫面上
    scrollToTopButton.classList.add('d-none');
    scrollToTopButton.classList.remove('d-block');
  } else {
    // header 不在畫面上
    scrollToTopButton.classList.remove('d-none');
    scrollToTopButton.classList.add('d-block');
  }
}, {
  threshold: [0]
});
headerObserver.observe(document.getElementById('header')); // 根據 footer 是否出現在畫面上調整按鈕的樣式

var footerObserver = new IntersectionObserver(function (entries) {
  if (entries[0].isIntersecting === true) {
    // footer 在畫面上
    scrollToTopButton.classList.remove('position-fixed');
    scrollToTopButton.classList.add('position-absolute');
  } else {
    // footer 不在畫面上
    scrollToTopButton.classList.add('position-fixed');
    scrollToTopButton.classList.remove('position-absolute');
  }
}, {
  threshold: [0]
});
footerObserver.observe(document.getElementById('footer'));
/******/ })()
;