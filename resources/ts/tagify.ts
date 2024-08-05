import Tagify from '@yaireo/tagify';

declare global {
    interface Window {
        Tagify: any;
    }
}

window.Tagify = Tagify;
