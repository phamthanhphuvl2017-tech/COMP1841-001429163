<?php
// Xử lý khi người dùng nhấn submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_dir = "uploads/";

    // Tạo thư mục nếu chưa tồn tại
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $message = "✅ File uploaded successfully: " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"]));
    } else {
        $message = "❌ Error uploading the file.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Upload File</title>
</head>
<body>
    <h2>Upload an Image</h2>

    <?php if (!empty($message)) echo "<p>$message</p>"; ?>

    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label>Select file to upload:</label>
        <input type="file" name="fileToUpload" required>
        <br><br>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
