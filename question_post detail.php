<?php
require 'config.php';
session_start();

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "Invalid question ID.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
$stmt->execute([$id]);
$question = $stmt->fetch();

if (!$question) {
    echo "Question not found.";
    exit;
}

// Xử lý bình luận
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $_SESSION['comments'][$id][] = $_POST['comment'];
}
?>

<?php include 'header.html.php'; ?>
<div class="container mt-4">
  <a href="questions.php" class="btn btn-sm btn-secondary mb-3">← Back to questions</a>

  <div class="card">
    <div class="card-body">
      <h3><?= htmlspecialchars($question['title']) ?></h3>
      <h6 class="text-muted"><?= htmlspecialchars($question['module']) ?></h6>
      <p><?= nl2br(htmlspecialchars($question['description'])) ?></p>

      <?php if ($question['image']): ?>
        <img src="<?= htmlspecialchars($question['image']) ?>" class="img-fluid mb-3" alt="Question Image">
      <?php endif; ?>
    </div>
  </div>

  <div class="mt-4">
    <h5>Comments</h5>
    <?php if (!empty($_SESSION['comments'][$id])): ?>
      <ul class="list-group mb-3">
        <?php foreach ($_SESSION['comments'][$id] as $c): ?>
          <li class="list-group-item"><?= htmlspecialchars($c) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p class="text-muted">No comments yet.</p>
    <?php endif; ?>

    <form method="post" class="d-flex">
      <input type="text" name="comment" class="form-control me-2" placeholder="Write a comment..." required>
      <button class="btn btn-primary">Post</button>
    </form>
  </div>
</div>
<?php include 'footer.html.php'; ?>