<?php
// Include header template / Nhúng template header
include 'header.html.php';
// Load database configuration / Tải cấu hình database
require 'config.php';

// Handle update or delete requests / Xử lý yêu cầu cập nhật hoặc xóa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update question /
    if (isset($_POST['update'])) {
        // Prepare update statement / 
        $stmt = $pdo->prepare("UPDATE questions SET title = ?, module = ? WHERE id = ?");
        // Execute with parameters / 
        $stmt->execute([$_POST['title'], $_POST['module'], $_POST['id']]);
    }
    
    // Delete question / 
    if (isset($_POST['delete'])) {
        // Prepare delete statement /
        $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
        // Execute with parameter /
        $stmt->execute([$_POST['id']]);
    }
    
    // Redirect to prevent form resubmission / Chuyển hướng để tránh gửi lại form
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Load all questions /
$stmt = $pdo->query("SELECT * FROM questions ORDER BY id DESC");
$questions = $stmt->fetchAll();
?>

<!-- Main container / Container chính -->
<div class="container mt-5">
  <!-- Page heading / Tiêu đề trang -->
  <h2 class="mb-4">All Questions</h2>
  
  <!-- Questions list / Danh sách câu hỏi -->
  <?php foreach ($questions as $q): ?>
    <!-- Question form (each question is a form) / Form cho mỗi câu hỏi -->
    <form method="post" class="card p-3 mb-3 shadow-sm border rounded">
      <!-- Hidden ID field / Trường ID ẩn -->
      <input type="hidden" name="id" value="<?= $q['id'] ?>">

      <div class="row g-2 align-items-center">
        <!-- Title input / Ô nhập tiêu đề -->
        <div class="col-md-5">
          <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($q['title']) ?>" placeholder="Question Title">
        </div>

        <!-- Module input / Ô nhập module -->
        <div class="col-md-3">
          <input type="text" name="module" class="form-control" value="<?= htmlspecialchars($q['module']) ?>" placeholder="Module">
        </div>

        <!-- Action buttons / Các nút hành động -->
        <div class="col-md-4 d-flex gap-2 justify-content-end">
          <!-- Save button / Nút lưu -->
          <button type="submit" name="update" class="btn btn-outline-success btn-sm">💾 Save</button>
          <!-- Delete button (with confirmation) / Nút xóa (có xác nhận) -->
          <button type="submit" name="delete" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">🗑 Delete</button>
          <!-- View button / Nút xem chi tiết -->
          <a href="question_detail.php?id=<?= $q['id'] ?>" class="btn btn-outline-primary btn-sm">🔍 View</a>
        </div>
      </div>
    </form>
  <?php endforeach; ?>
</div>

<!-- Bootstrap JS / Nhúng Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Include footer template / Nhúng template footer -->
<?php include 'footer.html.php'; ?>