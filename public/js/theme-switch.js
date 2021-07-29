/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/*!**************************************!*\
  !*** ./resources/ts/theme-switch.ts ***!
  \**************************************/


var themeSwitch = document.getElementsByClassName('theme-switch');
var themeSelect = document.getElementById('theme-select'); // Dark Mode Switch

Array.prototype.forEach.call(themeSwitch, function (element) {
  element.addEventListener('click', function () {
    if (document.documentElement.className === "") {
      document.documentElement.classList.add('dark'); // Store in local storage

      localStorage.setItem('theme', 'dark');
      syncThemeSelection();
    } else if (document.documentElement.className === "dark") {
      document.documentElement.classList.remove('dark');
      localStorage.setItem('theme', 'light');
      syncThemeSelection();
    }
  });
});

function syncThemeSelection() {
  if (themeSelect !== null) {
    if (localStorage.theme === 'light' || !('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: light)').matches) {
      themeSelect.selectedIndex = 0;
    } else {
      themeSelect.selectedIndex = 1;
    }
  }
} // Initialize Dark Mode Selection


syncThemeSelection(); // Dark Mode Selection

themeSelect === null || themeSelect === void 0 ? void 0 : themeSelect.addEventListener('change', function (event) {
  var optionValue = event.target.value;

  if (optionValue === 'dark') {
    document.documentElement.classList.add('dark');
    localStorage.setItem('theme', 'dark');
  } else {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('theme', 'light');
  }
});
/******/ })()
;