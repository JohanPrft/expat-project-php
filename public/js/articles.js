document.addEventListener("DOMContentLoaded", () => {
    // article initialization without filter
    changeArticles("");
});

// get and change the articles
export function changeArticles(filter) {
    getArticles(filter)
        .done(function(response) {
            changeArticleDOM(response);
        })
        .fail(function(error) {
            console.error('Error:', error);
        });
}

// get articles from db, filter can be blank or corresponding to a category value
// return the articles in an array
function getArticles(filter) {
    return $.ajax({
        url: '../../src/get_data.php',
        method: 'GET',
        data: { filter: filter },
        dataType: 'json'
    });
}

// loop through the array and create DOM element while there's articles
function changeArticleDOM (articleArray) {
    const articlesBlock = document.getElementById('articles-block');

    if (!Array.isArray(articleArray) || articleArray.length === 0) {
        articlesBlock.innerHTML = "<p>No articles found.</p>";
        return;
    }

    // reset block
    articlesBlock.innerHTML = "";

    articleArray.forEach((article) => {
        let articleHtml = `
                        <div class="article">
                            <h3>${article.title}</h3>
                            <span>id: ${article.id}</span>
                            <span>category: ${article.category_name}</span>
                            <p>${article.content}</p>
                        </div>
                    `;
        articlesBlock.innerHTML += articleHtml;
    })
}
