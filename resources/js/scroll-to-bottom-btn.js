// Get the button
let scrollToBottomButton = document.getElementById('scroll-to-bottom-btn');
scrollToBottomButton.addEventListener('click', scrollToBottom);

// When the user clicks on the button, scroll to the bottom
function scrollToBottom() {
    window.scrollTo(0, document.body.scrollHeight);
}

// 根據 footer 是否出現在畫面上調整按鈕的樣式
let footerObserver = new IntersectionObserver(
    function (entries) {
        if (entries[0].isIntersecting === true) {
            // footer 在畫面上
            scrollToBottomButton.classList.add('d-none');
            scrollToBottomButton.classList.remove('d-block');
        } else {
            // footer 不在畫面上
            scrollToBottomButton.classList.remove('d-none');
            scrollToBottomButton.classList.add('d-block');
        }
    },
    { threshold: [0] }
);

footerObserver.observe(document.getElementById('footer'));
