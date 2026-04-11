<?php include './config/db.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <?php include "./xtras/link.php";?>
  <title>404 | Page Not Found</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Inter", Arial, sans-serif;
    }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #edede9;
      color: #000000;
    }

    .container {
      text-align: center;
      padding: 40px;
      max-width: 520px;
      background: #f8fafc;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    h1 {
      font-size: 96px;
      font-weight: 700;
      background: linear-gradient(135deg, #6366f1, #4f46e5);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 10px;
    }

    h2 {
      font-size: 24px;
      font-weight: 600;
      color: #000000;
      margin-bottom: 12px;
    }

    p {
      font-size: 16px;
      color: #4b5563;
      margin-bottom: 28px;
      line-height: 1.6;
    }

    .actions button {
      display: inline-block;
      text-decoration: none;
      background: #6366f1;
      color: #ffffff;
      padding: 12px 24px;
      border-radius: 6px;
      font-weight: 600;
      border: 1px solid #4f46e5;
      margin: 0 6px;
      transition: all 0.2s ease;
      cursor: pointer;
      text-transform: uppercase;
      font-size: 14px;
    }

    .actions button:hover {
      background: #4f46e5;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    @media (max-width: 480px) {
      h1 {
        font-size: 72px;
      }
      .container {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>404</h1>
    <h2>Page Not Found</h2>
    <p>
      The page you are looking for might have been removed, had its name changed,
      or is temporarily unavailable.
    </p>
    <div class="actions">
      <?php
      $role = $_SESSION['role'] ?? 'user';
      $username = $_SESSION['username'] ?? 'User';
      // Role to Dashboard mapping for the Back button
      $dashboard_map = [
          'admin'    => 'http://localhost/lab/dashboard/admin.php',
          'analyst'  => 'http://localhost/lab/dashboard/analyst.php',
          'tester'   => 'http://localhost/lab/dashboard/tester.php',
          'supplier' => 'http://localhost/lab/dashboard/supplier.php',
          'user'     => 'http://localhost/lab/index.php'
      ];
      $back_url = $dashboard_map[$role] ?? 'index.php';
      ?>
      <button onclick="window.location.href = '<?php echo $back_url ?>'">Go Back</button>
    </div>
  </div>
</body>
</html>
