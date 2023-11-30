<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the base64 image data from the POST request
    $base64Image = $_POST['image'];

    // Insert the base64 image data into the database (replace with your database connection logic)
    $conn = new mysqli('localhost', 'root', '', 'test');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO images (image_data) VALUES ('$base64Image')";
    if ($conn->query($sql) === TRUE) {
        echo "Image stored successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
