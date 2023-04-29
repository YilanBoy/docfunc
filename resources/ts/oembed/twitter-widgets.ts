interface Window {
    twttr: any;
}

// source code :
// https://developer.twitter.com/en/docs/twitter-for-websites/javascript-api/guides/set-up-twitter-for-websites
window.twttr = (function (d, s, id) {
    let js;
    let fjs = d.getElementsByTagName(s)[0];
    let t = window.twttr || {};

    if (d.getElementById(id)) return t;

    js = <HTMLScriptElement>d.createElement(s);
    js.id = id;
    js.src = 'https://platform.twitter.com/widgets.js';

    if (fjs.parentNode !== null) fjs.parentNode.insertBefore(js, fjs);

    t._e = [];
    t.ready = function (f: any) {
        t._e.push(f);
    };

    return t;
})(document, 'script', 'twitter-wjs');
