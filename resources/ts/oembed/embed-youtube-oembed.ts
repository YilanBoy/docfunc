interface Window {
    processYoutubeOEmbeds: any;
}

// 定義一個函式來處理 oembed 轉換
async function convertOEmbedToIframe(oembedElement: HTMLElement) {
    const screenWidth: number = window.screen.width;

    let maxwidth: number = 640;
    let maxheight: number = 360;

    if (screenWidth <= 425) {
        maxwidth = 320;
        maxheight = 180;
    }

    const url = oembedElement.getAttribute('url');

    if (!url || !isYouTubeUrl(url)) {
        return;
    }

    let oembedApiUrl = `https://www.youtube.com/oembed?format=json&url=${encodeURIComponent(
        url,
    )}`;
    oembedApiUrl += `&maxwidth=${maxwidth}&maxheight=${maxheight}`;

    await fetch(oembedApiUrl)
        .then((response) => response.json())
        .then((data) => {
            if (data.html) {
                oembedElement.insertAdjacentHTML('afterend', data.html);
            }
        });
}

// 定義一個函式來檢查是否為 YouTube 連結
function isYouTubeUrl(url: string) {
    return /^https?:\/\/(www\.)?youtube\.com\/watch\?v=/.test(url);
}

// 主要處理函式
window.processYoutubeOEmbeds = function () {
    console.log('processOEmbeds');

    const oembedElements: NodeListOf<HTMLElement> =
        document.querySelectorAll('oembed');

    oembedElements.forEach((oembedElement) => {
        const figureElement = oembedElement.closest('figure.media');

        if (figureElement) {
            convertOEmbedToIframe(oembedElement);
        }
    });
};
