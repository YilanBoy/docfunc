interface Window {
    setupPostSectionLink: any;
}

function createPostSectionLink(
    postSectionMenu: HTMLElement,
    headings: NodeListOf<HTMLHeadingElement>,
): void {
    if (headings.length === 0) {
        return;
    }

    let postSectionMenuInnerHtml: string = '';

    postSectionMenuInnerHtml += `
        <div class="mb-4 flex items-center justify-center dark:text-gray-50">目錄</div>
        <hr class="mb-1 h-0.5 border-0 bg-gray-300 dark:bg-gray-700">
    `;

    headings.forEach((heading: HTMLHeadingElement, index: number): void => {
        heading.id = `heading-${index}`;

        postSectionMenuInnerHtml += `
            <a
                href="#${heading.id}"
                id="${heading.id}-link"
                class="mb-1 flex rounded p-1 text-sm text-gray-500 transition duration-150 hover:bg-gray-300 hover:text-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
            >
                <span class="flex items-center justify-center">⏵</span>
                <span class="ml-2">${heading.textContent}</span>
            </a>
        `;
    });

    postSectionMenu.innerHTML = postSectionMenuInnerHtml;
}

function createSectionInPostBdy(postBody: HTMLElement): void {
    let childNodes: NodeListOf<ChildNode> = postBody.childNodes;

    let newSection: HTMLDivElement | null = null;
    let newPostBody: HTMLElement[] = [];

    childNodes.forEach((childNode: ChildNode) => {
        if (newSection === null && childNode.nodeName !== 'H2') {
            newPostBody.push(childNode.cloneNode(true) as HTMLElement);
        } else if (childNode.nodeName === 'H2') {
            let heading = childNode as HTMLHeadingElement;

            // call by reference
            newSection = document.createElement('div');

            // id example: heading-0-section
            newSection.id = `${heading.id}-section`;

            newSection.appendChild(childNode.cloneNode(true) as HTMLElement);

            newPostBody.push(newSection);
        } else if (newSection && childNode.nodeName !== 'H2') {
            newSection.appendChild(childNode.cloneNode(true));
        }
    });

    // Remove all child nodes from postBody
    postBody.innerHTML = '';

    // Append all sectionGroups to postBody
    newPostBody.forEach((section: HTMLElement) => {
        postBody.appendChild(section);
    });
}

function showWhichSectionIAmIn(sectionLinks: NodeListOf<HTMLAnchorElement>) {
    sectionLinks.forEach((sectionLink: HTMLAnchorElement, index: number) => {
        let section: Element | null = document.getElementById(
            `heading-${index}-section`,
        );

        if (section === null) {
            return;
        }

        sectionLink.addEventListener('click', (event) => {
            event.preventDefault();
            section?.scrollIntoView({
                behavior: 'smooth',
            });
        });

        let sectionObserver = new IntersectionObserver(
            function (entries) {
                if (entries[0].isIntersecting) {
                    sectionLink.classList.add(
                        'bg-gray-300',
                        'dark:bg-gray-600',
                    );
                } else {
                    sectionLink.classList.remove(
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

window.setupPostSectionLink = function (
    postSectionMenu: HTMLElement,
    postBody: HTMLElement,
): void {
    let headings: NodeListOf<HTMLHeadingElement> =
        postBody.querySelectorAll('h2');

    if (headings.length === 0) {
        return;
    }

    createPostSectionLink(postSectionMenu, headings);
    createSectionInPostBdy(postBody);

    // Get all section links, must be after createPostSectionLink
    let sectionLinks: NodeListOf<HTMLAnchorElement> =
        postSectionMenu.querySelectorAll('a');

    // Must be after createSectionInPostBdy
    showWhichSectionIAmIn(sectionLinks);
};
