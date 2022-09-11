import Tagify from "@yaireo/tagify";

const tags = <HTMLInputElement>document.querySelector("#tags");

fetch("/api/tags")
    .then((response) => response.json())
    .then(function (tagsJson) {
        new Tagify(tags, {
            whitelist: tagsJson,
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
                highlightFirst: true,
            },
        });
    });
