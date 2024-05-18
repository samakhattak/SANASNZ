<?php
include '../inc/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image'];

    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($image["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (getimagesize($image["tmp_name"]) !== false) {
        if (!file_exists($target_file)) {
            if ($image["size"] <= 5000000) {
                if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg") {
                    if (move_uploaded_file($image["tmp_name"], $target_file)) {
                        $sql = "INSERT INTO products (name, description, price, image_url) VALUES ('$name', '$description', '$price', '$target_file')";

                        if ($conn->query($sql) === TRUE) {
                            echo "New product added successfully";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                } else {
                    echo "Sorry, only JPG, JPEG, and PNG files are allowed.";
                }
            } else {
                echo "Sorry, your file is too large.";
            }
        } else {
            echo "Sorry, file already exists.";
        }
    } else {
        echo "File is not an image.";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Add Product</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Add New Product</h1>
    <form action="add_product.php" method="post" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>

        <label for="price">Price:</label>
        <input type="text" id="price" name="price" required><br>

        <label for="image">Product Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required><br>

        <button type="submit">Add Product</button>
    </form>
</body>
</html>
