<?php
session_start();
require 'config.php';
include 'header.html.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($email) && !empty($password)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hash]);

            $_SESSION['message'] = "Registration successful!";
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            $error = "Registration failed: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Register</button>
</form>

<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<?php include 'footer.html.php';?>