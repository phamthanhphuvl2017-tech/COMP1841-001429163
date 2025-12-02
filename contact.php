<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

$message_sent = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $fromEmail = $_POST["email"];
    $message = $_POST["message"];

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'phamthanhphuvl2017@gmail.com';  
        $mail->Password = 'xqdkbycerljeawdn';              
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // From & To
        $mail->setFrom($fromEmail, $name);
        $mail->addAddress($fromEmail );

        $mail->isHTML(true);
        $mail->Subject = "New Contact Message from $name";
        $mail->Body    = nl2br($message);

        $mail->send();
        $message_sent = "✅ Message sent successfully!";
    } catch (Exception $e) {
        $message_sent = "❌ Message could not be sent. Error: " . $mail->ErrorInfo;
    }
}
?>

<?php include 'header.html.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
  <h2>Contact Admin</h2>

  <?php if ($message_sent): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message_sent) ?></div>
  <?php endif; ?>

  <form method="post" action="">
    <div class="mb-3">
      <label for="name" class="form-label">Name</label>
      <input type="text" name="name" class="form-control" id="name" required placeholder="Your name">
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" name="email" class="form-control" id="email" required placeholder="Your email">
    </div>
    <div class="mb-3">
      <label for="message" class="form-label">Message</label>
      <textarea name="message" class="form-control" id="message" rows="4" required placeholder="Your message"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Send Message</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include 'footer.html.php'; ?>
