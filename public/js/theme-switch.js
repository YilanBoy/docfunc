/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/*!**************************************!*\
  !*** ./resources/ts/theme-switch.ts ***!
  \**************************************/


var themeSwitch = document.getElementsByClassName('theme-switch'); // Dark Mode Switch

Array.prototype.forEach.call(themeSwitch, function (element) {
  element.addEventListener('click', function () {
    if (document.documentElement.className === "") {
      document.documentElement.classList.add('dark'); // Store in local storage

      localStorage.setItem('theme', 'dark');
    } else if (document.documentElement.className === "dark") {
      document.documentElement.classList.remove('dark');
      localStorage.setItem('theme', 'light');
    }
  });
});
/******/ })()
;