<?php
// Load PHPMailer libraries
// Tải các thư viện PHPMailer
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

// Import PHPMailer classes
// Nhập các lớp PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $to = $_POST["to"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    // Create new PHPMailer instance
    // Tạo đối tượng PHPMailer mới
    $mail = new PHPMailer(true);

    try {
        // Server settings
        // Cấu hình máy chủ
        $mail->isSMTP();                            // Use SMTP
        $mail->Host       = 'smtp.gmail.com';       // SMTP server address
        $mail->SMTPAuth   = true;                   // Enable SMTP authentication
        $mail->Username   = 'your@gmail.com';       // SMTP username (your email)
        $mail->Password   = 'your-app-password';     // SMTP password (app password)
        $mail->SMTPSecure = 'tls';                  // Enable TLS encryption
        $mail->Port       = 587;                    // TCP port to connect to

        // Recipients
        // Người nhận
        $mail->setFrom('your@gmail.com', 'Your Name');  // Sender info
        $mail->addAddress($to);                         // Add recipient

        // Email content
        // Nội dung email
        $mail->isHTML(false);                      // Set email format to plain text
        $mail->Subject = $subject;                 // Email subject
        $mail->Body    = $message;                 // Email body

        // Send email
        // Gửi email
        $mail->send();
        echo '✅ Message sent!';                   // Success message
    } catch (Exception $e) {
        echo "❌ Error: {$mail->ErrorInfo}";       // Error message
    }
}
?>

<!-- HTML Form -->
<!-- Mẫu HTML -->
<form method="post" action="sendmail.php">
    <label>To:</label><br>
    <input type="email" name="to" required><br><br>
    
    <label>Subject:</label><br>
    <input type="text" name="subject" required><br><br>
    
    <label>Message:</label><br>
    <textarea name="message" rows="6" cols="40" required></textarea><br><br>
    
    <button type="submit">Send Email</button>
</form>