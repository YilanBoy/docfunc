/**
 * @description This is a typescript clone of sharer.js(https://github.com/ellisonleao/sharer.js)
 *
 */
declare global {
    interface Window {
        setupSharer: Function;
    }
}

type facebookParams = {
    shareUrl: string;
    params: {
        quote: string;
        u: string;
        hashtag: string;
    };
    width: number;
    height: number;
};

type twitterParams = {
    shareUrl: string;
    params: {
        hashtags: string;
        text: string;
        url: string;
        via: string;
    };
    width: number;
    height: number;
};

class Sharer {
    private elem: HTMLButtonElement;

    constructor(elem: HTMLButtonElement) {
        this.elem = elem;
    }

    // 拿取標籤中所有屬性前綴為 data- 的值
    private getValue(attr: String): string {
        let val: string | null = this.elem.getAttribute('data-' + attr);
        // handing facebook hashtag attribute
        if (val && attr === 'hashtag') {
            if (!val.startsWith('#')) {
                val = '#' + val;
            }
        }

        return val === null ? '' : val;
    }

    // share 與 urlSharer 用來設定分享參數並開啟分享小視窗
    public share(): void | boolean {
        // 取得標籤屬性 data-sharer 的值
        let sharer: string = this.getValue('sharer').toLowerCase();
        let sharers = {
            facebook: {
                shareUrl: 'https://www.facebook.com/sharer/sharer.php',
                params: {
                    u: this.getValue('url'),
                    hashtag: this.getValue('hashtag'),
                    quote: this.getValue('quote'),
                },
                width: 0,
                height: 0,
            },
            twitter: {
                shareUrl: 'https://twitter.com/intent/tweet/',
                params: {
                    text: this.getValue('title'),
                    url: this.getValue('url'),
                    hashtags: this.getValue('hashtags'),
                    via: this.getValue('via'),
                },
                width: 0,
                height: 0,
            },
        };

        let s = sharers[sharer as keyof typeof sharers];

        // 調整彈出視窗 (popups) 的尺寸
        if (s) {
            s.width = Number(this.getValue('width'));
            s.height = Number(this.getValue('height'));
        }

        return s !== undefined ? this.urlSharer(s) : false;
    }

    private urlSharer(sharer: facebookParams | twitterParams): void {
        let params = sharer.params || {};
        let keys = Object.keys(params);
        let str = keys.length > 0 ? '?' : '';

        for (let i = 0, l = keys.length; i < l; i++) {
            if (str !== '?') {
                str += '&';
            }
            if (params[keys[i] as keyof typeof params]) {
                str +=
                    keys[i] +
                    '=' +
                    encodeURIComponent(params[keys[i] as keyof typeof params]);
            }
        }

        sharer.shareUrl += str;

        let isLink = this.getValue('link') === 'true';
        let isBlank = this.getValue('blank') === 'true';

        if (isLink) {
            if (isBlank) {
                window.open(sharer.shareUrl, '_blank');
            } else {
                window.location.href = sharer.shareUrl;
            }
        } else {
            console.log(sharer.shareUrl);
            // 如果沒有設定 data-link，幫彈出視窗設定初始值
            let popWidth = sharer.width || 600;
            let popHeight = sharer.height || 480;
            let left = window.innerWidth / 2 - popWidth / 2 + window.screenX;
            let top = window.innerHeight / 2 - popHeight / 2 + window.screenY;
            let popParams: string =
                `scrollbars=no, width=${popWidth}` +
                `, height=${popHeight}` +
                `, top=${top}` +
                `, left=${left}`;
            let newWindow = window.open(sharer.shareUrl, '', popParams);

            newWindow?.focus();
        }
    }
}

// 取得所有屬性有 data-sharer 的按鈕
function init(): void {
    let elems: NodeListOf<HTMLButtonElement> =
        document.querySelectorAll('[data-sharer]');

    let clipboardElems: NodeListOf<HTMLButtonElement> =
        document.querySelectorAll('[data-clipboard]');

    for (const elem of elems) {
        elem.addEventListener('click', addShareFeature);
    }

    for (const clipboardElem of clipboardElems) {
        clipboardElem.addEventListener('click', copyToClipboard);
    }
}

// 加入分享功能
function addShareFeature(event: Event): void {
    let target = event.currentTarget;

    if (target instanceof HTMLButtonElement) {
        let sharer = new Sharer(target);

        sharer.share();
    }
}

function copyToClipboard(event: Event): void {
    let target = event.currentTarget;

    if (target instanceof HTMLButtonElement) {
        let text = target.getAttribute('data-clipboard');

        if (text) {
            navigator.clipboard.writeText(text).then(
                () => console.log('copy success'),
                () => console.log('copy fail'),
            );
        }
    }
}

window.setupSharer = function () {
    // 在 DOMContentLoaded 的事件中加入分享功能設定
    if (
        document.readyState === 'complete' ||
        document.readyState !== 'loading'
    ) {
        init();
    } else {
        document.addEventListener('DOMContentLoaded', init);
    }
};
