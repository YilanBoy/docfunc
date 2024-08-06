declare global {
    interface Window {
        scrollToAnchor: Function;
    }
}

window.scrollToAnchor = function (): void {
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
