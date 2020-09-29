var tagInput = document.querySelector("#tag-input");
var tagify = new Tagify(tagInput, {
    whitelist: tagArray,
    enforceWhitelist: true,
    maxTags: 5,
    dropdown: {
        // show the dropdown immediately on focus
        enabled: 0,
        maxItems: 5,
        // place the dropdown near the typed text
        position: "text",
        // keep the dropdown open after selecting a suggestion
        closeOnSelect: false,
        highlightFirst: true
    }
});
