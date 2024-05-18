<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "moviesdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully<br>";

// Query 1: Create the view
echo "<h2>Query 1: Create a View to Count Movie Reviews</h2>";
$viewQuery = "CREATE OR REPLACE VIEW movie_review_count AS 
SELECT m.movieId, m.title, COUNT(sm.movieId) AS review_count
FROM movie m
LEFT JOIN score_movie sm ON m.movieId = sm.movieId
GROUP BY m.movieId;";
if ($conn->query($viewQuery) === TRUE) {
    echo "View created successfully.<br>";
} else {
    echo "Error creating view: " . $conn->error . "<br>";
}

// Fetch and display results from the view
$result = $conn->query("SELECT * FROM movie_review_count");
echo "<table border='1'><tr><th>Movie ID</th><th>Title</th><th>Review Count</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row["movieId"] . "</td><td>" . $row["title"] . "</td><td>" . $row["review_count"] . "</td></tr>";
}
echo "</table>";

// Query 2: 
echo "<h2>Query 2: List Movies Released After 2000</h2>";
$joinQuery = "SELECT m.title, m.year FROM movie m WHERE m.year > 2000;";
$result = $conn->query($joinQuery);
echo "<table border='1'><tr><th>Movie Title</th><th>Release Year</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row["title"] . "</td><td>" . $row["year"] . "</td></tr>";
}
echo "</table>";

// Query 3: Aggregation Query
echo "<h2>Query 3: Count of Movies by Genre</h2>";
$aggQuery = "SELECT m.genre, COUNT(m.movieId) AS movie_count FROM movie m GROUP BY m.genre;";
$result = $conn->query($aggQuery);
echo "<table border='1'><tr><th>Genre</th><th>Movie Count</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row["genre"] . "</td><td>" . $row["movie_count"] . "</td></tr>";
}
echo "</table>";

// Close the database connection
$conn->close();
?>
