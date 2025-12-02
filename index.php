<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    /* RESET + BODY */
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f2f6fa;
      color: #333;
    }

    /* HEADER */
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #ffffff;
      padding: 15px 40px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 999;
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
      color: #2196F3;
      text-decoration: none;
    }

    nav a {
      margin-left: 20px;
      text-decoration: none;
      color: #444;
      font-size: 16px;
      transition: color 0.3s ease;
    }

    nav a:hover {
      color: #2196F3;
    }

    /* BODY - HERO SECTION */
    .home-body {
      padding: 60px 40px;
      background-color: #f2f6fa;
    }

    .hero {
      text-align: center;
      margin-bottom: 60px;
    }

    .hero h2 {
      font-size: 36px;
      color: #2c3e50;
    }

    .hero .highlight {
      color: #2196F3;
    }

    .hero p {
      font-size: 18px;
      color: #555;
      margin: 20px 0;
    }

    .btn-primary {
      display: inline-block;
      background-color: #2196F3;
      color: white;
      padding: 12px 24px;
      border-radius: 6px;
      font-size: 16px;
      text-decoration: none;
      transition: background-color 0.3s;
    }

    .btn-primary:hover {
      background-color: #1976D2;
    }

    /* FEATURES */
    .features {
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
      gap: 30px;
    }

    .feature-box {
      background-color: white;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      flex: 1;
      min-width: 250px;
      max-width: 300px;
      text-align: center;
    }

    .feature-box i {
      font-size: 36px;
      color: #2196F3;
      margin-bottom: 15px;
    }

    .feature-box h3 {
      color: #333;
      margin-bottom: 10px;
    }

    .feature-box p {
      color: #666;
    }

    /* FOOTER (if used) */
    footer {
      background-color: #222;
      color: #eee;
      text-align: center;
      padding: 20px;
      font-size: 14px;
    }
  </style>
</head>

<body>

  <!-- Header -->
  <header>
    <a href="#" class="logo"><i class="fas fa-graduation-cap"></i> Student Q&A</a>
    <nav>
      <a href="Question.php">Questions</a>
      <a href="post question.php">Post a Question</a>
      <a href="manager users.php">Manage Users</a>
      <a href="manage modules.php">Manage Modules</a>
      <a href="contact.php">Contact</a>
      <a href="login.php">Login</a>
    </nav>
  </header>

  <!-- Body content -->
  <div class="home-body">
    <section class="hero">
      <h2>Welcome to <span class="highlight">Student Q&A</span></h2>
      <p>Your go-to platform for asking, answering, and exploring academic questions.</p>
      <a href="#" class="btn-primary"><i class="fas fa-question-circle"></i> Ask a Question</a>
    </section>

    <section class="features">
      <div class="feature-box">
        <i class="fas fa-users"></i>
        <h3>Community Driven</h3>
        <p>Connect with other students and educators who are ready to help and learn together.</p>
      </div>
      <div class="feature-box">
        <i class="fas fa-lightbulb"></i>
        <h3>Smart Search</h3>
        <p>Find relevant answers fast with our intelligent search engine and tagging system.</p>
      </div>
      <div class="feature-box">
        <i class="fas fa-star"></i>
        <h3>Reputation Points</h3>
        <p>Earn points and recognition by helping others and contributing quality content.</p>
      </div>
    </section>
  </div>

  <!-- Footer -->
  <footer>
    &copy; <?php echo date("Y"); ?> Student Q&A. All rights reserved.
  </footer>

</body>
</html>
