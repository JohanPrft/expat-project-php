document.addEventListener("DOMContentLoaded", () => {
    // article initialization without filter
    changeArticles("");
});

// get and change the articles
export async function changeArticles(filter) {
    try {
        const response = await getArticles(filter);
        changeArticleDOM(response);
    } catch (error) {
        console.error('Error:', error);
    }
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

        if (!article.category_name)
            article.category_name = "aucune";
        let articleHtml = `
                        <div class="article">
                            <h3>${article.title}</h3>
                            <span>Categorie: ${article.category_name}</span>
                            <p>${article.content}</p>
                        </div>
                    `;
        articlesBlock.innerHTML += articleHtml;
    })
}
