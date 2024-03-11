<?php

// check if filter is given in the request
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

$articles = getArticles($filter);

echo json_encode($articles);

// get all articles from the db, if filter is set to an existing category return only corresponding articles
// return an array of articles, category_name and category_description are available if set in the article
function getArticles($filter)
{
    require_once 'db_config.php';
    $mysqli = new mysqli($servername, $username, $password, $database, $port);
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $sql_filter = "";
    // safe from injection because not directly put in the sql request
    switch ($filter) {
        case "emploi":
            $sql_filter = 'WHERE category.name = "emploi"';
            break;
        case "immobilier":
            $sql_filter = 'WHERE category.name = "immobilier"';
            break;
    }

    // select : every article, and all name/description fields in category (var name: category/description_name)
    // specify primary table for modification
    // joins the rows (primary) with matching rows (article_has_category) based on the article_id column in both tables
    // joins the rows (article_has_category) with matching rows (category) based on the category_id column in both tables
    // order result by article.id descending order
    $sql = "SELECT article.*, category.name AS category_name, category.description AS category_description
            FROM article
            LEFT JOIN article_has_category ON article.id = article_has_category.article_id
            LEFT JOIN category ON article_has_category.category_id = category.id
            $sql_filter
            ORDER BY article.id DESC";

    // return a result set
    $result = $mysqli->query($sql);

    $articles = array();
    if ($result) {
        // get each row in the result set
        while ($row = $result->fetch_assoc()) {
            // Add the row data to the articles array
            $articles[] = $row;
        }
    } else {
        echo "Query failed: " . $mysqli->error;
    }

    $mysqli->close();
    return $articles;
}
