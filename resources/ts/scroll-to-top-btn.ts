interface Window {
    setupScrollToTopButton: any;
}

// 滾動至網頁最頂部
function scrollToTop(): void {
    window.scrollTo({ top: 0 });
}

window.setupScrollToTopButton = function (
    scrollToTopButton: HTMLButtonElement,
): void {
    scrollToTopButton.addEventListener('click', scrollToTop);

    let header = <HTMLElement>document.getElementById('header');
    let footer = <HTMLElement>document.getElementById('footer');

    // 根據 header 是否出現在畫面上調整按鈕的樣式
    let headerObserver = new IntersectionObserver(
        function (entries) {
            if (entries[0].isIntersecting) {
                // header 在畫面上
                scrollToTopButton.classList.remove('xl:flex');
            } else {
                // header 不在畫面上
                scrollToTopButton.classList.add('xl:flex');
            }
        },
        { threshold: [0] },
    );

    // 根據 footer 是否出現在畫面上調整按鈕的樣式
    let footerObserver = new IntersectionObserver(
        function (entries) {
            if (entries[0].isIntersecting) {
                // footer 在畫面上
                scrollToTopButton.classList.remove('fixed', 'bottom-7');
                scrollToTopButton.classList.add('absolute', 'bottom-1');
            } else {
                // footer 不在畫面上
                scrollToTopButton.classList.add('fixed', 'bottom-7');
                scrollToTopButton.classList.remove('absolute', 'bottom-1');
            }
        },
        { threshold: [0] },
    );

    headerObserver.observe(header);
    footerObserver.observe(footer);
};
