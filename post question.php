<?php
// Include header template file
// Nhúng file template header
include 'header.html.php';

// Load database configuration
// Tải cấu hình cơ sở dữ liệu
require 'config.php'; 

// Check if form is submitted
// Kiểm tra nếu form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data with default empty values
    // Lấy dữ liệu form với giá trị mặc định là rỗng
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $module = $_POST['module'] ?? '';
    $imagePath = ''; // Initialize image path / Khởi tạo đường dẫn ảnh

    // Handle image upload if file was uploaded without errors
    // Xử lý upload ảnh nếu file được tải lên không có lỗi
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Get file extension
        // Lấy phần mở rộng file
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        // Generate unique filename with uniqid()
        // Tạo tên file duy nhất bằng uniqid()
        $imagePath = 'uploads/' . uniqid() . '.' . $ext;
        // Move uploaded file to destination
        // Di chuyển file tải lên đến thư mục đích
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    // Prepare SQL statement to insert question
    $stmt = $pdo->prepare("INSERT INTO questions (title, description, module, image) VALUES (:title, :description, :module, :image)");
    
    // Execute prepared statement with parameters
    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'module' => $module,
        'image' => $imagePath
    ]);

    // Redirect to questions list after submission
    // Chuyển hướng đến trang danh sách câu hỏi sau khi gửi
    header('Location: question.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Basic meta tags -->
  <!-- Các thẻ meta cơ bản -->
  <meta charset="UTF-8">
  <title>Post a Question</title>
  <!-- Bootstrap CSS -->
  <!-- Nhúng CSS của Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Main container -->
<!-- Container chính -->
<div class="container mt-4">
  <!-- Page heading -->
  <!-- Tiêu đề trang -->
  <h2>Post a Question</h2>
  
  <!-- Question form -->
  <!-- Form đăng câu hỏi -->
  <form method="POST" enctype="multipart/form-data">
    <!-- Title input -->
    <!-- Ô nhập tiêu đề -->
    <div class="mb-3">
      <label for="title" class="form-label">Title</label>
      <input type="text" class="form-control" id="title" name="title" required>
    </div>

    <!-- Description textarea -->
    <!-- Ô nhập mô tả -->
    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
    </div>

    <!-- Module dropdown -->
    <!-- Dropdown chọn module -->
    <div class="mb-3">
      <label for="module" class="form-label">Module</label>
      <select class="form-select" id="module" name="module" required>
        <option selected value="">Select a module</option>
        <option>Web Programming</option>
        <option>Database Systems</option>
        <option>Object Oriented Programming</option>
      </select>
    </div>

    <!-- Image upload -->
    <!-- Tải lên ảnh -->
    <div class="mb-3">
      <label for="image" class="form-label">Upload Image</label>
      <input type="file" class="form-control" id="image" name="image">
    </div>

    <!-- Submit button -->
    <!-- Nút gửi -->
    <button type="submit" class="btn btn-primary">Post Question</button>
  </form>
</div>

<!-- Bootstrap JS bundle -->
<!-- Gói JS của Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!-- Include footer template -->
<!-- Nhúng template footer -->
<?php include 'footer.html.php'; ?>