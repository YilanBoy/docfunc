// 用 id 取得置頂按鈕
const scrollToTopButton: HTMLElement | null = document.getElementById('scroll-to-top-btn');
scrollToTopButton?.addEventListener('click', scrollToTop);

// 滾動至網頁最頂部
function scrollToTop(): void {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

const header: HTMLElement | null = document.getElementById('header');

if (header !== null) {
    // 根據 header 是否出現在畫面上調整按鈕的樣式
    let headerObserver = new IntersectionObserver(
        function (entries) {
            // isIntersecting is true when element and viewport are overlapping
            // isIntersecting is false when element and viewport don't overlap
            if (entries[0].isIntersecting === true) {
                // header 在畫面上
                scrollToTopButton?.classList.add('d-none');
                scrollToTopButton?.classList.remove('d-block');
            } else {
                // header 不在畫面上
                scrollToTopButton?.classList.remove('d-none');
                scrollToTopButton?.classList.add('d-block');
            }
        },
        { threshold: [0] }
    );
    headerObserver.observe(header);
}

const footer: HTMLElement | null = document.getElementById('footer');

if (footer !== null) {
    // 根據 footer 是否出現在畫面上調整按鈕的樣式
    let footerObserver = new IntersectionObserver(
        function (entries) {
            if (entries[0].isIntersecting === true) {
                // footer 在畫面上
                scrollToTopButton?.classList.remove('position-fixed');
                scrollToTopButton?.classList.add('position-absolute');
            } else {
                // footer 不在畫面上
                scrollToTopButton?.classList.add('position-fixed');
                scrollToTopButton?.classList.remove('position-absolute');
            }
        },
        { threshold: [0] }
    );

    footerObserver.observe(footer);
}
