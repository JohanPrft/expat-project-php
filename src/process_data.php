<?php

main();

function main ()
{
    global $mysqli;

    // end script if request check return false
    $validRequest = check_request();
    if (!$validRequest) return ;

    // connect php backend to db with mysqli
    require_once 'db_config.php';
    $mysqli = new mysqli($servername, $username, $password, $database, $port);
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // quit if insertion of article failed
    $insertedArticleId = insertArticle();
    if (!$insertedArticleId) {
        echo "Article insertion failed \n";
        $mysqli->close();
        return;
    }
    echo "Inserted article id: $insertedArticleId \n";

    // check if request has category
    if (hasCategory()) {
        // check if category has id
        $categoryId = categoryId();
        if (!$categoryId) {
            echo "Article has category but doesnt exists \n";
        } else {
            echo "Article has category id: $categoryId \n";
            insertArticleHasCategory($insertedArticleId, $categoryId);
        }
    }
    else
        echo "Article has no category \n";

    $mysqli->close();
}

function insertArticleHasCategory(int $insertedArticleId, int $categoryId) {
    global $mysqli;

    $stmt = $mysqli->prepare("INSERT INTO article_has_category (article_id, category_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $insertedArticleId, $categoryId);
    $stmt->execute();

}

// return the category id, or 0 if it doesn't exist
function categoryId(): int {
    global $mysqli;

    $category = $mysqli->real_escape_string($_POST['category']);

    // get the id from the category
    $stmt = $mysqli->prepare("SELECT id FROM category WHERE name = ?");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($categoryId);
    $stmt->fetch(); // needed to store in $categoryId
    // $categoryId is a number or null

    $stmt->close();
    return $categoryId ? $categoryId : 0;
}

function hasCategory (): bool {
    return !empty($_POST['category']);
}

// return inserted article id, 0 if it fails
function insertArticle (): int
{
    global $mysqli;

    // prepare the statement (server check and initializes server internal resources for later)
    $stmt = $mysqli->prepare("INSERT INTO article (title, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $content);

    // sanitize
    $title = $mysqli->real_escape_string($_POST['title']);
    $content = $mysqli->real_escape_string($_POST['content']);
    $stmt->execute();
    $stmt->store_result();

    // get value of inserted article
    $insertedId = $mysqli->insert_id;

    $stmt->close();
    return $insertedId;
}


// check request method and values, then confirm or reject
function check_request() : bool
{
    $isRequestValid = true;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(array('error' => 'Only POST requests are allowed'));
        $isRequestValid = false;
    }
    else {
        $data = [];
        $errors = [];

        // double check data (already done client side)
        $title_len = strlen(($_POST['title']));
        if ($title_len < 5) {
            $errors['title'] = 'Title >= 10 char is required';
        }
        else if ($title_len > 80) {
            $errors['title'] = 'Title <= 80 char is required';
        }

        $content_len = strlen(($_POST['content']));
        if ($content_len < 10) {
            $errors['content'] = 'Content >= 10 char is required';
        }
        else if ($content_len > 65535) {
            $errors['content'] = 'Content >= 65535 char is required';
        }

        if (!empty($errors)) {
            http_response_code(400);
            $data['success'] = false;
            $data['errors'] = $errors;
            $isRequestValid = false;
        } else {
            http_response_code(200);
            $data['success'] = true;
            $data['message'] = 'Success!';
        }

        echo json_encode($data);
    }
    return $isRequestValid;
}



