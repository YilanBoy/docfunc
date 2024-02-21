interface Window {
    scrollToAnchor: any;
}

let scrollToAnchor = function (): void {
    if (window.location.hash !== '') {
        let target: Element | null = document.querySelector(
            window.location.hash,
        );

        if (target instanceof Element) {
            target.scrollIntoView({
                behavior: 'smooth',
            });
        }
    }
};

window.scrollToAnchor = scrollToAnchor;
