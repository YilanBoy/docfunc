// 用 id 取得置頂按鈕
const scrollToTopButton: HTMLElement | null = document.getElementById('scroll-to-top-btn');
scrollToTopButton?.addEventListener('click', scrollToTop);

// 滾動至網頁最頂部
function scrollToTop(): void {
    window.scrollTo({top: 0, behavior: 'smooth'});
}

// 監聽滾動
window.addEventListener('scroll', function () {
    if (window.scrollY > 0) {
        scrollToTopButton?.classList.add('xl:flex');
    } else {
        scrollToTopButton?.classList.remove('xl:flex');
    }
});

const footer: HTMLElement | null = document.getElementById('footer');

if (footer !== null) {
    // 根據 footer 是否出現在畫面上調整按鈕的樣式
    let footerObserver = new IntersectionObserver(
        function (entries) {
            if (entries[0].isIntersecting) {
                // footer 在畫面上
                scrollToTopButton?.classList.remove('fixed');
                scrollToTopButton?.classList.add('absolute');
            } else {
                // footer 不在畫面上
                scrollToTopButton?.classList.add('fixed');
                scrollToTopButton?.classList.remove('absolute');
            }
        },
        {threshold: [0]}
    );

    footerObserver.observe(footer);
}
