declare global {
    interface Window {
        processYoutubeOembeds: Function;
    }
}

// 定義一個函式來處理 oembed 轉換
async function convertOembedToIframe(oembedElement: HTMLElement) {
    const screenWidth: number = window.screen.width;

    let maxWidth: number = 640;
    let maxHeight: number = 360;

    if (screenWidth <= 425) {
        maxWidth = 320;
        maxHeight = 180;
    }

    const url = oembedElement.getAttribute('url');

    if (!url || !isYouTubeUrl(url)) {
        return;
    }

    let oembedApiUrl = `https://www.youtube.com/oembed?format=json&url=${encodeURIComponent(
        url,
    )}`;
    oembedApiUrl += `&maxwidth=${maxWidth}&maxheight=${maxHeight}`;

    let response = await fetch(oembedApiUrl);
    let data = await response.json();

    if (data.html) {
        oembedElement.insertAdjacentHTML('afterend', data.html);
        // 標記為已處理，在 SPA 應用中，避免重複處理
        oembedElement.classList.add('oembed-processed');
    }
}

// 定義一個函式來檢查是否為 YouTube 連結
function isYouTubeUrl(url: string): boolean {
    return (
        /^https?:\/\/(www\.)?youtube\.com\/watch\?v=/.test(url) ||
        /^https?:\/\/youtu\.be\//.test(url)
    );
}

// 主要處理函式
window.processYoutubeOembeds = function () {
    const oembedElements: NodeListOf<HTMLElement> = document.querySelectorAll(
        'oembed:not(.oembed-processed)',
    );

    oembedElements.forEach((oembedElement) => {
        const figureElement = oembedElement.closest('figure.media');

        if (figureElement) {
            convertOembedToIframe(oembedElement).catch((error) => {
                console.error(error);
            });
        }
    });
};
