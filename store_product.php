<?php
// Uncomment for debugging
// var_dump($_POST, $_FILES);

require("php/cmsdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_FILES['pic']['error'] === UPLOAD_ERR_OK) {
        $temp_file = $_FILES['pic']['tmp_name'];
        $target_file = 'productimg/' . basename($_FILES['pic']['name']);  // Adjust path as necessary

        if (move_uploaded_file($temp_file, $target_file)) {
            echo "File uploaded successfully.";
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "File upload error: " . $_FILES['pic']['error'];
    }

    // Process other form data and insert into database
    $product_name = $_POST['product_name'];
    $category_id = $_POST['category_id'];
    $model_year = $_POST['model_year'];
    $list_price = $_POST['list_price'];

    // Assuming you have additional validation and sanitization here for $_POST values

    // Example: Move uploaded file to destination (if you're not using the $target_file approach above)
    // $savePath = "productimg/";
    // $new_pic_name = "unique_filename"; // Replace with your filename logic
    // if (!move_uploaded_file($pic_tmp, $savePath . $new_pic_name)) {
    //     die('Failed to move uploaded file');
    // }

    // Insert into database
    $query = "INSERT INTO products (name, category_id, model_year, list_price, picture) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('siids',
            $product_name,    // name
            $category_id,     // category_id
            $model_year,      // model_year
            $list_price,      // list_price
            $_FILES['pic']['name']  // picture (assuming you want to store the filename)
        );
        if (!$stmt->execute()) {
            die('Error: ' . $stmt->error);
        }
        $stmt->close();
    } else {
        die('Error: ' . $conn->error);
    }

    // Redirect after successful insertion
    header("location: edit_product.php?Msg=1&id=" . $conn->insert_id);
    exit;
} else {
    die("Method not allowed");
}
?>
