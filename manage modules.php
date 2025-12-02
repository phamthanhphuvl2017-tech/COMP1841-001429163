<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $pdo = new PDO("mysql:host=localhost;dbname=student_qa", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}

// ======= DELETE MODULE =======
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete']; 

    try {
        $pdo->beginTransaction();

        $stmt1 = $pdo->prepare("DELETE FROM posts WHERE module_id = ?");
        $stmt1->execute([$id]);

        $stmt2 = $pdo->prepare("DELETE FROM modules WHERE id = ?");
        $stmt2->execute([$id]);

        $pdo->commit();

        echo "<script>alert('Module deleted successfully!'); window.location='manage modules.php';</script>";
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "<div class='alert alert-danger'>❌ Error: " . $e->getMessage() . "</div>";
    }
}

// ======= ADD/EDIT MODULE =======
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $module_name = trim($_POST["module_name"]);
    
    if (!empty($module_name)) {
        try {
            if (isset($_POST["module_id"])) {
                // EDIT MODE
                $id = (int)$_POST["module_id"];
                $stmt = $pdo->prepare("UPDATE modules SET module_name = ? WHERE id = ?");
                $stmt->execute([$module_name, $id]);
                $message = "Module updated successfully!";
            } else {
                // ADD MODE
                $stmt = $pdo->prepare("INSERT INTO modules (module_name) VALUES (?)");
                $stmt->execute([$module_name]);
                $message = "Module added successfully!";
            }
            
            echo "<script>alert('$message'); window.location='manage modules.php';</script>";
            exit;
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>❌ Error: " . $e->getMessage() . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>⚠️ Module name cannot be empty!</div>";
    }
}

// ======= LOAD MODULES =======
try {
    $stmt = $pdo->query("SELECT * FROM modules ORDER BY id DESC");
    $modules = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>❌ Error fetching modules: " . $e->getMessage() . "</div>";
    $modules = [];
}

$editModule = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM modules WHERE id = ?");
        $stmt->execute([$editId]);
        $editModule = $stmt->fetch();
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>❌ Error fetching module: " . $e->getMessage() . "</div>";
    }
}
?>

<?php include 'header.html.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Modules</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .module-form {
      background-color: #f8f9fa;
      padding: 20px;
      border-radius: 5px;
      margin-bottom: 30px;
    }
  </style>
</head>
<body>

<div class="container mt-4">
  <h2 class="mb-3">Manage Modules</h2>

  <!-- ADD/EDIT MODULE FORM -->
  <div class="module-form">
    <h4><?= $editModule ? 'Edit Module' : 'Add New Module' ?></h4>
    <form method="post">
      <?php if ($editModule): ?>
        <input type="hidden" name="module_id" value="<?= $editModule['id'] ?>">
      <?php endif; ?>
      
      <div class="mb-3">
        <label for="moduleName" class="form-label">Module Name</label>
        <input type="text" class="form-control" id="moduleName" name="module_name" 
               value="<?= isset($editModule['module_name']) ? htmlspecialchars($editModule['module_name']) : '' ?>" 
               required placeholder="Enter module name">
      </div>
      <button type="submit" class="btn btn-primary">
        <?= $editModule ? 'Update Module' : 'Add Module' ?>
      </button>
      
      <?php if ($editModule): ?>
        <a href="manage modules.php" class="btn btn-secondary">Cancel</a>
      <?php endif; ?>
    </form>
  </div>

  <!-- MODULE LIST -->
  <h5>Existing Modules</h5>
  
  <?php if (empty($modules)): ?>
    <div class="alert alert-info">No modules found. Please add a new module.</div>
  <?php else: ?>
    <table class="table table-bordered table-hover">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Module Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($modules as $module): ?>
          <tr>
            <td><?= $module['id'] ?></td>
            <td><?= htmlspecialchars($module['module_name'] ?? 'Unknown') ?></td>
            <td>
              <a href="?edit=<?= $module['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="?delete=<?= $module['id'] ?>" class="btn btn-danger btn-sm"
                 onclick="return confirm('Are you sure you want to delete this module? ALL RELATED POSTS WILL BE DELETED!')">
                Delete
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include 'footer.html.php'; ?>
