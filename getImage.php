<?php
// Retrieve the base64 image data from the database (replace with your database connection logic)
$conn = new mysqli('localhost', 'root', '', 'test');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT image_data FROM images ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row['image_data'];
} else {
    echo "No image found";
}

$conn->close();
?>
