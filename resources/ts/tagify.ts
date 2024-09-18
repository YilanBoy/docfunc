import Tagify from '@yaireo/tagify';

declare global {
    interface Window {
        Tagify: typeof Tagify;
    }
}

window.Tagify = Tagify;
