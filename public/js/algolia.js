autocomplete(
    '#aa-search-input',
    {
        debug: true,
        templates: {
            dropdownMenu: '<div class="aa-dataset-post"></div>'
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
                    console.log(_highlightResult);
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
