<?php
// include db credentials
require_once 'db_config.php';

// connect php backend to db with mysqli
$mysqli = new mysqli($servername, $username, $password, $database, $port);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// SQL queries to create tables and insert base data
$sql = "
CREATE TABLE article (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(80) NOT NULL,
    content TEXT NOT NULL
);

CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(45),
    description VARCHAR(255)
);

CREATE TABLE article_has_category (
    article_id INT,
    category_id INT,
    FOREIGN KEY (article_id) REFERENCES article(id),
    FOREIGN KEY (category_id) REFERENCES category(id)
);

INSERT INTO category (name, description) VALUES 
('emploi', 'Recherche et demande d’emploi'),
('immobilier', 'Recherche et proposition de logement');
";

$result = $mysqli->multi_query($sql);
if ($result) {
    // recommended struct when using multi_query, avoid checking only first request
    do {
        // grab the result of the next query but return value unreliable so double check for error
        if (($result = mysqli_store_result($mysqli)) === false && $mysqli->error != '') {
            echo "Query failed: " . $mysqli->error;
        }
    } while (mysqli_more_results($mysqli) && mysqli_next_result($mysqli)); // check if there is more && do next request
} else {
    echo "Error in first query: " . $mysqli->error;
}

$mysqli->close();
