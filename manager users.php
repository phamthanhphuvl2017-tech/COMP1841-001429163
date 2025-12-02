<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user']['id']]);
    $current_user = $stmt->fetch();
    
    if (!$current_user || $current_user['is_admin'] != 1) {
        $_SESSION['error'] = "You don't have permission to access this page";
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header("Location: index.php");
    exit;
}

$action = $_GET['action'] ?? '';
$user_id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;
    $user_id = $_POST['user_id'] ?? 0;

    if (empty($username) || empty($email)) {
        $_SESSION['error'] = "Username and email are required";
        header("Location: manager users.php");
        exit;
    }

    if ($action === 'add' && empty($password)) {
        $_SESSION['error'] = "Password is required for new user";
        header("Location: manager users.php");
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
        $stmt->execute([$username, $email, $user_id]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = "Username or email already exists";
            header("Location: manager users.php");
            exit;
        }

        if ($action === 'edit' && $user_id) {
            if (!empty($password)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ?, is_admin = ? WHERE id = ?");
                $stmt->execute([$username, $email, $hash, $is_admin, $user_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, is_admin = ? WHERE id = ?");
                $stmt->execute([$username, $email, $is_admin, $user_id]);
            }
            $_SESSION['message'] = "User updated successfully";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hash, $is_admin]);
            $_SESSION['message'] = "User added successfully";
        }

        header("Location: manager users.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: manager users.php");
        exit;
    }
}

if ($action === 'delete' && $user_id) {
    try {        if ($user_id == $_SESSION['user']['id']) {
            $_SESSION['error'] = "You cannot delete yourself";
            header("Location: manager users.php");
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $_SESSION['message'] = "User deleted successfully";
        header("Location: manager users.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: manager users.php");
        exit;
    }
}

try {
    $stmt = $pdo->query("SELECT id, username, email, is_admin, created_at FROM users ORDER BY is_admin DESC, username ASC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $users = [];
    $_SESSION['error'] = "Failed to load users: " . $e->getMessage();
}

$edit_user = null;
if ($action === 'edit' && $user_id) {
    try {
        $stmt = $pdo->prepare("SELECT id, username, email, is_admin FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $edit_user = $stmt->fetch();
        
        if (!$edit_user) {
            $_SESSION['error'] = "User not found";
            header("Location: manager users.php");
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to load user: " . $e->getMessage();
        header("Location: manager users.php");
        exit;
    }
}

include 'header.html.php';
?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">User Management</h2>
            
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <div class="card mb-4">
                <div class="card-header">
                    <?= $edit_user ? 'Edit User' : 'Add New User' ?>
                </div>
                <div class="card-body">
                    <form method="POST" action="manager users.php?action=<?= $edit_user ? 'edit' : 'add' ?>">
                        <?php if ($edit_user): ?>
                            <input type="hidden" name="user_id" value="<?= $edit_user['id'] ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?= htmlspecialchars($edit_user['username'] ?? '') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($edit_user['email'] ?? '') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   <?= $edit_user ? '' : 'required' ?>
                                   placeholder="<?= $edit_user ? 'Leave blank to keep current password' : '' ?>">
                            <?php if ($edit_user): ?>
                                <div class="form-text">Leave blank to keep current password</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin" 
                                   <?= ($edit_user['is_admin'] ?? 0) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_admin">Administrator</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <?= $edit_user ? 'Update User' : 'Add User' ?>
                        </button>
                        
                        <?php if ($edit_user): ?>
                            <a href="manager users.php" class="btn btn-secondary">Cancel</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    User List
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= $user['id'] ?></td>
                                        <td><?= htmlspecialchars($user['username']) ?></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $user['is_admin'] ? 'danger' : 'primary' ?>">
                                                <?= $user['is_admin'] ? 'Admin' : 'User' ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                        <td>
                                            <a href="manager users.php?action=edit&id=<?= $user['id'] ?>" 
                                               class="btn btn-sm btn-warning">Edit</a>
                                            <?php if ($user['id'] != $_SESSION['user']['id']): ?>
                                                <a href="manager users.php?action=delete&id=<?= $user['id'] ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.html.php'; ?>