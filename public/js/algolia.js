autocomplete(
    '#aa-search-input',
    {
        templates: {
            dropdownMenu: '<div class="aa-dataset-post"></div>',
            footer: `
                <div style="text-align: right; display: block; font-size:16px; margin: 5px 5px 5px 5px;">
                    Powered by <a href="http://www.algolia.com"><img src="https://www.algolia.com/assets/algolia128x40.png" style="height: 16px;" /></a>
                </div>
            `
        }
    },
    [
        {
            source: autocomplete.sources.hits(posts, { hitsPerPage: 10 }),
            displayKey: 'name',
            name: 'post',
            templates: {
                header: '<div class="aa-suggestions-category">文章</div>',
                suggestion({ _highlightResult }) {
                    return `
                        <span>
                            <a href="${_highlightResult.url.value}">${_highlightResult.title.value}</a>
                        </span>
                        <span></span>
                    `;
                },
                empty: '<div class="aa-empty">No matching posts</div>'
            }
        }
    ]
);
