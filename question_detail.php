<?php
// Load database configuration and start session
// Tải cấu hình CSDL và bắt đầu session
require 'config.php';
session_start();

// Get question ID from URL parameter
// Lấy ID câu hỏi từ tham số URL
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "Invalid question ID.";
    exit;
}

// Fetch question from database
// Lấy câu hỏi từ CSDL
$stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
$stmt->execute([$id]);
$question = $stmt->fetch();

if (!$question) {
    echo "Question not found.";
    exit;
}

// Initialize comments array if not exists
// Khởi tạo mảng bình luận nếu chưa tồn tại
if (!isset($_SESSION['comments'][$id])) {
    $_SESSION['comments'][$id] = [];
}

// Handle adding new comment
// Xử lý thêm bình luận mới
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment']) && isset($_POST['action']) && $_POST['action'] === 'add') {
    $_SESSION['comments'][$id][] = $_POST['comment'];
    header("Location: question_detail.php?id=$id");
    exit;
}

// Handle deleting comment
// Xử lý xóa bình luận
if (isset($_GET['delete'])) {
    $deleteIndex = $_GET['delete'];
    if (isset($_SESSION['comments'][$id][$deleteIndex])) {
        array_splice($_SESSION['comments'][$id], $deleteIndex, 1);
    }
    header("Location: question_detail.php?id=$id");
    exit;
}

// Handle editing comment
// Xử lý chỉnh sửa bình luận
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $editIndex = $_POST['edit_index'];
    $newComment = $_POST['comment'];
    if (isset($_SESSION['comments'][$id][$editIndex])) {
        $_SESSION['comments'][$id][$editIndex] = $newComment;
    }
    header("Location: question_detail.php?id=$id");
    exit;
}
?>

<?php include 'header.html.php'; ?>

<!-- Main container -->
<!-- Container chính -->
<div class="container mt-4">
  <!-- Back button -->
  <!-- Nút quay lại -->
  <a href="Question.php" class="btn btn-sm btn-secondary mb-3">← Back to questions</a>

  <!-- Question display card -->
  <!-- Thẻ hiển thị câu hỏi -->
  <div class="card mb-4">
    <div class="card-body">
      <h3><?= htmlspecialchars($question['title']) ?></h3>
      <h6 class="text-muted"><?= htmlspecialchars($question['module']) ?></h6>
      <p><?= nl2br(htmlspecialchars($question['description'])) ?></p>

      <!-- Display question image if exists -->
      <!-- Hiển thị ảnh nếu có -->
      <?php if ($question['image']): ?>
        <img src="<?= htmlspecialchars($question['image']) ?>" class="img-fluid mb-3" alt="Question Image">
      <?php endif; ?>
    </div>
  </div>

  <!-- Comments section -->
  <!-- Phần bình luận -->
  <div class="card">
    <div class="card-body">
      <h5 class="card-title mb-3">Comments</h5>

      <?php if (!empty($_SESSION['comments'][$id])): ?>
        <!-- Display all comments in reverse order -->
        <!-- Hiển thị tất cả bình luận theo thứ tự ngược -->
        <?php foreach (array_reverse($_SESSION['comments'][$id], true) as $index => $c): ?>
          <div class="mb-2 border rounded p-2 bg-light">
            <?php
              // Check if this comment is being edited
              // Kiểm tra xem bình luận này đang được chỉnh sửa không
              $editing = isset($_GET['edit']) && $_GET['edit'] == $index;
            ?>
            <?php if ($editing): ?>
              <!-- Edit comment form -->
              <!-- Form chỉnh sửa bình luận -->
              <form method="post" class="d-flex">
                <input type="hidden" name="edit_index" value="<?= $index ?>">
                <input type="hidden" name="action" value="edit">
                <input type="text" name="comment" class="form-control me-2" value="<?= htmlspecialchars($c) ?>" required>
                <button class="btn btn-success me-2">Save</button>
                <a href="question_detail.php?id=<?= $id ?>" class="btn btn-secondary">Cancel</a>
              </form>
            <?php else: ?>
              <!-- Display comment with edit/delete buttons -->
              <!-- Hiển thị bình luận với nút sửa/xóa -->
              <div class="d-flex justify-content-between">
                <div>
                  <strong>User:</strong> <?= htmlspecialchars($c) ?>
                </div>
                <div>
                  <a href="?id=<?= $id ?>&edit=<?= $index ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a href="?id=<?= $id ?>&delete=<?= $index ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this comment?')">Delete</a>
                </div>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-muted">No comments yet.</p>
      <?php endif; ?>

      <!-- Add new comment form -->
      <!-- Form thêm bình luận mới -->
      <form method="post" class="d-flex mt-3">
        <input type="hidden" name="action" value="add">
        <input type="text" name="comment" class="form-control me-2" placeholder="Write a comment..." required>
        <button class="btn btn-primary">Post</button>
      </form>
    </div>
  </div>
</div>

<?php include 'footer.html.php'; ?>