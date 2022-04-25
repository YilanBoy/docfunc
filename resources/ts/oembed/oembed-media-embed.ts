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

// scan all oembed element, and embed the media
async function embedAllMedia() {
    for (let oembed of allOembedElementArray) {
        // get the url
        let oembedUrl: string | null = oembed.getAttribute("url");

        // if url is not null
        if (oembedUrl !== null) {
            await embedMedia(oembedUrl, oembed);
        }
    }
}

async function embedMedia(url: string, element: Element): Promise<void> {
    if (url.match(youtubeUrlRegex)) {
        await insertYoutubeIframe(url, element);
    }

    if (url.match(twitterUrlRegex)) {
        await insertTwitterIframe(url, element);
    }
}

function insertYoutubeIframe(url: string, element: Element): Promise<void> {
    return fetch(`/api/oembed/youtube`, {
        method: "POST",
        body: JSON.stringify({ url: url }),
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            "x-csrf-token": csrfMeta ? csrfMeta.content : "",
        },
    })
        .then((response) => response.json())
        .then((responseJson) => {
            console.log(
                "get youtube embed iframe, insert the iframe into the post"
            );
            // append the iframe after the element
            element.insertAdjacentHTML("afterend", responseJson.html);
        })
        .catch((error) => console.error(error));
}

function insertTwitterIframe(url: string, element: Element): Promise<void> {
    return fetch("/api/oembed/twitter", {
        method: "POST",
        body: JSON.stringify({ url: url }),
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            "x-csrf-token": csrfMeta ? csrfMeta.content : "",
        },
    })
        .then((response) => response.json())
        .then((responseJson) => {
            console.log(
                "get twitter embed iframe, insert the iframe into the post"
            );
            element.insertAdjacentHTML("afterend", responseJson.html);
        })
        .catch((error) => console.error(error));
}

embedAllMedia().then(() => {
    console.log("load twitter card");
    // scan blog post and embed tweets
    window.twttr.widgets.load(document.getElementById("blog-post"));
});
