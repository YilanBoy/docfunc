import algoliasearch from 'algoliasearch';
import autocomplete from 'autocomplete.js';

const client = algoliasearch(algoliaId, algoliaSearchKey);
const posts = client.initIndex(algoliaIndex);

function newHitsSource(index, params) {
    return function doSearch(query, cb) {
        index
            .search(query, params)
            .then(function (res) {
                cb(res.hits, res);
            })
            .catch(function (err) {
                console.error(err);
                cb([]);
            });
    };
}

autocomplete(
    '#aa-search-input',
    {
        hint: false,
        templates: {
            dropdownMenu: '<div class="aa-dataset-post"></div>',
            footer: `
                <div class="flex justify-center items-center border-t-2 border-gray-400 p-3">
                    <a href="https://www.algolia.com">
                        <img src="/images/icon/search-by-algolia-light-background.png" alt="Search by Algolia">
                    </a>
                </div>
            `
        }
    },
    [
        {
            source: newHitsSource(posts, { hitsPerPage: 10 }),
            displayKey: 'title',
            templates: {
                suggestion: function (suggestion) {
                    return `
                        <span class="w-full">
                            <a class="block text-gray-400 hover:text-gray-700 w-full" href="${suggestion.url}">
                                ${suggestion._highlightResult.title.value}
                            </a>
                        </span>
                    `;
                },
                empty:
                    '<div class="flex justify-center items-center p-3">找不到符合搜尋字詞的文章</div>'
            }
        }
    ]
).on('autocomplete:selected', function (event, suggestion) {
    location.href = suggestion.url;
});

