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

const csrfMeta: HTMLMetaElement | null = document.querySelector(
    'meta[name="csrf-token"]'
);

// foreach oembed element
allOembedElement.forEach((oembed: Element) => {
    // get the url
    const oembedUrl: string | null = oembed.getAttribute("url");

    // if url is not null
    if (oembedUrl !== null) {
        embedMedia(oembedUrl, oembed);
    }
});

function embedMedia(url: string, element: Element): void {
    if (url.match(youtubeUrlRegex)) {
        insertYoutubeIframe(url, element).catch((error) =>
            console.error(error)
        );
    } else if (url.match(twitterUrlRegex)) {
        insertTwitterIframe(url, element).catch((error) =>
            console.error(error)
        );
    }
}

async function insertYoutubeIframe(
    url: string,
    element: Element
): Promise<void> {
    let body = { url: url };

    const response = await fetch(`/api/oembed/youtube`, {
        method: "POST",
        body: JSON.stringify(body),
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            "x-csrf-token": csrfMeta ? csrfMeta.content : "",
        },
    });

    let data = await response.json();

    // append the iframe after the element
    element.insertAdjacentHTML("afterend", data.html);
}

async function insertTwitterIframe(
    url: string,
    element: Element
): Promise<void> {
    let body = { url: url };

    const response = await fetch("/api/oembed/twitter", {
        method: "POST",
        body: JSON.stringify(body),
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            "x-csrf-token": csrfMeta ? csrfMeta.content : "",
        },
    });

    let data = await response.json();

    // append the iframe after the element
    element.insertAdjacentHTML("afterend", data.html);
    // scan blog post and embed tweets
    window.twttr.widgets.load(document.getElementById("blog-post"));
}
