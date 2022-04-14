// youtube url regex
const youtubeUrlRegex =
    /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=)?(.+)/g;
// twitter url regex
const twitterUrlRegex =
    /(?:https?:\/\/)?(?:www\.)?twitter\.com\/(?:[^\/]+\/)+status(?:es)?\/(\d+)/g;

// get all oembed elements
const allOembedElement: NodeListOf<Element> = document.querySelectorAll(
    "figure.media > oembed"
);
// turn NodeListOf (array-like) into Array instance
const allOembedElementArray: Array<Element> = Array.from(
    allOembedElement,
    (element) => element
);

const csrfMeta: HTMLMetaElement | null = document.querySelector(
    'meta[name="csrf-token"]'
);

// foreach oembed element
async function embedAllMedia() {
    for (const oembed of allOembedElementArray) {
        // get the url
        const oembedUrl: string | null = oembed.getAttribute("url");

        // if url is not null
        if (oembedUrl !== null) {
            await embedMedia(oembedUrl, oembed);
        }
    }
}

async function embedMedia(url: string, element: Element): Promise<void> {
    if (url.match(youtubeUrlRegex)) {
        await insertYoutubeIframe(url, element).catch((error) =>
            console.error(error)
        );
    } else if (url.match(twitterUrlRegex)) {
        await insertTwitterIframe(url, element).catch((error) =>
            console.error(error)
        );
    }
}

async function insertYoutubeIframe(
    url: string,
    element: Element
): Promise<void> {
    await fetch(`/api/oembed/youtube`, {
        method: "POST",
        body: JSON.stringify({ url: url }),
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            "x-csrf-token": csrfMeta ? csrfMeta.content : "",
        },
    })
        .then((response) => response.json())
        .then((responseJson) =>
            // append the iframe after the element
            element.insertAdjacentHTML("afterend", responseJson.html)
        );
}

async function insertTwitterIframe(
    url: string,
    element: Element
): Promise<void> {
    await fetch("/api/oembed/twitter", {
        method: "POST",
        body: JSON.stringify({ url: url }),
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            "x-csrf-token": csrfMeta ? csrfMeta.content : "",
        },
    })
        .then((response) => response.json())
        .then((responseJson) =>
            element.insertAdjacentHTML("afterend", responseJson.html)
        );
}

embedAllMedia().then(() => {
    // scan blog post and embed tweets
    window.twttr.widgets.load(document.getElementById("blog-post"));
});
