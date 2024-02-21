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

function createHeadingSectionsInPostBdy(postBody: Element) {
    let childNodes: NodeListOf<ChildNode> = postBody.childNodes;

    let currentH2: Element | null = null;
    let currentSection: Element | null = null;
    let newPostBody: any[] = [];

    childNodes.forEach((childNode: ChildNode) => {
        if (
            currentH2 === null &&
            currentSection === null &&
            childNode.nodeName !== 'H2'
        ) {
            newPostBody.push(childNode.cloneNode(true));
        } else if (childNode.nodeName === 'H2') {
            currentH2 = childNode as Element;

            let section = document.createElement('div');
            currentSection = section;
            section.id = `${currentH2.id}-section`;

            section.appendChild(childNode.cloneNode(true));

            newPostBody.push(section);
        } else if (currentH2 && currentSection && childNode.nodeName !== 'H2') {
            // call by reference
            currentSection.appendChild(childNode.cloneNode(true));
        }
    });

    // Remove all child nodes from postBody
    postBody.innerHTML = '';

    // Append all sectionGroups to postBody
    newPostBody.forEach((sectionGroup: ChildNode) => {
        postBody.appendChild(sectionGroup);
    });
}

function showWhereAmI(contentLinks: NodeListOf<HTMLAnchorElement>) {
    contentLinks.forEach((contentLink: HTMLAnchorElement, index: number) => {
        let section: Element | null = document.getElementById(
            `heading-${index}-section`,
        );

        console.log(`heading-${index}-section`);

        if (section === null) {
            return;
        }

        let sectionObserver = new IntersectionObserver(
            function (entries) {
                console.log('show');
                if (entries[0].isIntersecting) {
                    contentLink.classList.add(
                        'bg-gray-300',
                        'dark:bg-gray-600',
                    );
                } else {
                    contentLink.classList.remove(
                        'bg-gray-300',
                        'dark:bg-gray-600',
                    );
                }
            },
            { threshold: [0] },
        );

        sectionObserver.observe(section);
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
    createHeadingSectionsInPostBdy(postBody);

    // Get all content links, must be after createPostContentLink
    let contentLinks: NodeListOf<HTMLAnchorElement> =
        postContent.querySelectorAll('a');

    showWhereAmI(contentLinks);
};
