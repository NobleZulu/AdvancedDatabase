<?php
require 'vendor/autoload.php';  


$client = new MongoDB\Client("mongodb://localhost:27017");

// Select the database and collection
$database = $client->selectDatabase('MoviesDB');
$collection = $database->selectCollection('Movies');

// Query 1: Find a specific movie by title
$query1 = $collection->find(['title' => 'Star Wars']);
echo "<h2>Query 1: Find Movie by Title</h2>";
foreach ($query1 as $document) {
    echo 'Title: ' . $document['title'] . ' - Year: ' . $document['year'] . "<br>";
}

// Query 2: Use of Aggregation - Calculate the average score of movies
$aggResult = $collection->aggregate([
    ['$unwind' => '$ratings'],
    ['$group' => [
        '_id' => '$title',
        'AverageScore' => ['$avg' => '$ratings.score']
    ]]
]);
echo "<h2>Query 2: Average Scores of Movies</h2>";
foreach ($aggResult as $doc) {
    echo 'Movie: ' . $doc['_id'] . ' - Average Score: ' . $doc['AverageScore'] . "<br>";
}

// Query 3: Find all movies released after 1970
$query3 = $collection->find(['year' => ['$gt' => 1970]]);
echo "<h2>Query 3: Movies Released After 1970</h2>";
foreach ($query3 as $document) {
    echo 'Title: ' . $document['title'] . ' - Year: ' . $document['year'] . "<br>";
}
?>
