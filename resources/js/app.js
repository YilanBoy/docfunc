require("./bootstrap");

import Alpine from "alpinejs";
import focus from "@alpinejs/focus";

Alpine.plugin(focus);
window.Alpine = Alpine;

Alpine.data("cardLink", () => ({
    // Card 連結
    directToCardLink(event, refs) {
        let ignores = ["a"];
        let targetTagName = event.target.tagName.toLowerCase();

        if (!ignores.includes(targetTagName)) {
            refs.cardLinkUrl.click();
        }
    },
}));

Alpine.start();
