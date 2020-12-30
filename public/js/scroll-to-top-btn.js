// Get the button
let scrollToTopButton = document.getElementById('scroll-to-top-btn');

// When the user clicks on the button, scroll to the top of the document
function scrollToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

// 根據 header 是否出現在畫面上調整按鈕的樣式
let headerObserver = new IntersectionObserver(
    function(entries) {
        // isIntersecting is true when element and viewport are overlapping
        // isIntersecting is false when element and viewport don't overlap
        // header 是否不在畫面上
        if (entries[0].isIntersecting === false) {
            scrollToTopButton.classList.remove('d-none');
            scrollToTopButton.classList.add('d-block');
        } else {
            scrollToTopButton.classList.add('d-none');
            scrollToTopButton.classList.remove('d-block');
        }
    },
    { threshold: [0] }
);

headerObserver.observe(document.getElementById('header'));

// 根據 footer 是否出現在畫面上調整按鈕的樣式
let footerObserver = new IntersectionObserver(
    function(entries) {
        // footer 是否在畫面上
        if (entries[0].isIntersecting === true) {
            scrollToTopButton.classList.remove('position-fixed');
            scrollToTopButton.classList.add('position-absolute');
        } else {
            scrollToTopButton.classList.add('position-fixed');
            scrollToTopButton.classList.remove('position-absolute');
        }
    },
    { threshold: [0] }
);

footerObserver.observe(document.getElementById('footer'));
