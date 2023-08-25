interface Window {
    processTwitterOEmbeds: any;
}

// 定義一個函式來處理 Twitter oembed 轉換
async function convertTwitterOEmbedToIframe(oembedElement: HTMLElement) {
    const csrfMeta: HTMLMetaElement | null = document.querySelector(
        'meta[name="csrf-token"]',
    );

    let theme: string = 'light';
    if (document.documentElement.classList.contains('dark')) {
        theme = 'dark';
    }

    const url = oembedElement.getAttribute('url');
    if (!url || !isTwitterUrl(url)) {
        return;
    }

    const response = await fetch('/api/oembed/twitter', {
        method: 'POST',
        body: JSON.stringify({ url: url, theme: theme }),
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'x-csrf-token': csrfMeta ? csrfMeta.content : '',
        },
    });

    const data = await response.json();

    if (data.html) {
        oembedElement.insertAdjacentHTML('afterend', data.html);
        oembedElement.classList.add('oembed-processed');
    }
}

// 定義一個函式來檢查是否為 Twitter 連結
function isTwitterUrl(url: string) {
    return /^https?:\/\/(www\.)?twitter\.com\/[^/]+\/status\/\d+/.test(url);
}

// 主要處理函式
window.processTwitterOEmbeds = function () {
    const oembedElements: NodeListOf<HTMLElement> = document.querySelectorAll(
        'oembed:not(.oembed-processed)',
    );

    oembedElements.forEach((oembedElement) => {
        const figureElement = oembedElement.closest('figure.media');

        if (figureElement) {
            convertTwitterOEmbedToIframe(oembedElement);
        }
    });
};
