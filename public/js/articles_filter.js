function changeArticles(select) {
    let value = select.options[select.selectedIndex].value;
    showArticles(value);
}

function showArticles(category) {
    $.ajax({
        url: '../../src/get_data.php',
        method: 'GET',
        data: { category: category },
        dataType: 'json',
        success: function(response) {
            $('#article-block').empty(); // Clear existing articles
            response.forEach(function(article) {
                let articleHtml = `
                        <div class="article">
                            <h3>${article.title}</h3>
                            <span>id: ${article.id}</span>
                            <span>category: ${article.category_name}</span>
                            <p>${article.content}</p>
                        </div>
                    `;
                $('#article-block').append(articleHtml); // Append each article to the article block
            });
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}
