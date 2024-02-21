interface Window {
    setupPostContent: any;
}

const viewportHeight: number = window.innerHeight;

function createPostContentLink(
    postContent: Element,
    headings: NodeListOf<HTMLHeadingElement>,
): void {
    if (headings.length > 0) {
        let postContentInnerHtml: string = '';

        postContentInnerHtml += `
            <div class="mb-4 flex items-center justify-center dark:text-gray-50">目錄</div>
            <hr class="mb-1 h-0.5 border-0 bg-gray-300 dark:bg-gray-700">
        `;

        headings.forEach((heading: HTMLHeadingElement, index: number): void => {
            let headingId: string = `heading-${index}`;
            heading.id = headingId;

            postContentInnerHtml += `
                <a
                  href="#${headingId}"
                  id="${headingId}-link"
                  class="mb-1 flex rounded p-1 text-sm text-gray-500 transition duration-150 hover:bg-gray-300 hover:text-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                >
                    <span class="flex items-center justify-center">⏵</span>
                    <span class="ml-2">${heading.textContent}</span>
                </a>
            `;
        });

        postContent.innerHTML = postContentInnerHtml;
    }
}

function activePostContentLink(
    headingPositions: number[],
    contentLinks: NodeListOf<HTMLAnchorElement>,
): void {
    let currentScrollPosition: number = window.scrollY;

    // change the style of heading link when scrolling
    headingPositions.forEach((position: number, index: number) => {
        if (
            currentScrollPosition <
            headingPositions[0] - (viewportHeight * 3) / 4
        ) {
            contentLinks.forEach((link: HTMLAnchorElement) => {
                link.classList.remove('bg-gray-300', 'dark:bg-gray-700');
            });
        }

        if (currentScrollPosition >= position - (viewportHeight * 3) / 4) {
            contentLinks.forEach((link: HTMLAnchorElement) => {
                link.classList.remove('bg-gray-300', 'dark:bg-gray-700');
            });

            contentLinks[index].classList.add(
                'bg-gray-300',
                'dark:bg-gray-700',
            );
        }
    });
}

function showWhereAmI(
    headingPositions: number[],
    contentLinks: NodeListOf<HTMLAnchorElement>,
): void {
    window.addEventListener('scroll', () => {
        activePostContentLink(headingPositions, contentLinks);
    });
}

window.setupPostContent = function (
    postContent: Element,
    postBody: Element,
): void {
    let headings: NodeListOf<HTMLHeadingElement> =
        postBody.querySelectorAll('h2');

    if (headings.length === 0) {
        return;
    }

    createPostContentLink(postContent, headings);

    // Get all content links, must be after createPostContentLink
    let contentLinks: NodeListOf<HTMLAnchorElement> =
        postContent.querySelectorAll('a');

    // Resize observer will execute at least once, and
    // When the post body is resized
    const resizeObserver = new ResizeObserver((entries) => {
        let headingPositions: number[] = [];

        headings.forEach((heading: HTMLHeadingElement) => {
            let position = window.scrollY + heading.getBoundingClientRect().top;
            headingPositions.push(position);
        });

        activePostContentLink(headingPositions, contentLinks);
        showWhereAmI(headingPositions, contentLinks);
    });

    resizeObserver.observe(postBody);
};
