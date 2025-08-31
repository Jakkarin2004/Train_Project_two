<?php
$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>สมัครสมาชิก | ศูนย์ฝึกวิชาชีพ</title>
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap');
    * {
      margin: 0; padding: 0; box-sizing: border-box;
      font-family: 'Prompt', sans-serif;
    }
    body {
      background-color: #1e1e1e;
      color: #f0f0f0;
    }
    .register-container {
      max-width: 500px;
      margin: 120px auto 60px;
      background-color: #2a2a2a;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    }
    .register-container h2 {
      text-align: center;
      margin-bottom: 25px;
    }
    .register-container label {
      display: block;
      margin-bottom: 6px;
      font-weight: 500;
    }
    .register-container input[type="text"],
    .register-container input[type="password"] {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: none;
      margin-bottom: 20px;
      background-color: #444;
      color: #fff;
    }
    .register-container button {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 8px;
      background-color: #444;
      color: #fff;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }
    .register-container button:hover {
      background-color: #666;
    }
    .login-link {
      text-align: center;
      margin-top: 20px;
    }
    .login-link a {
      color: #fff;
      text-decoration: underline;
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

  <!-- Navbar -->
  <?php include '../includes/navbar.php'; ?>

  <div class="register-container">
    <h2>สมัครสมาชิก</h2>
    <form method="POST" action="../functions/auth/register.php">
      <label for="fullname">ชื่อ - นามสกุล</label>
      <input type="text" id="fullname" name="fullname" required>

      <label for="username">ชื่อผู้ใช้ (Username)</label>
      <input type="text" id="username" name="username" required>

      <label for="password">รหัสผ่าน</label>
      <input type="password" id="password" name="password" required>

      <label for="confirm_password">ยืนยันรหัสผ่าน</label>
      <input type="password" id="confirm_password" name="confirm_password" required>

      <button type="submit">สมัครสมาชิก</button>
    </form>

    <div class="login-link">
      <p>มีบัญชีอยู่แล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
    </div>
  </div>

  <!-- Footer -->
  <?php include '../includes/footer.php'; ?>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    <?php if ($error): ?>
      Swal.fire({
        icon: 'error',
        title: 'ผิดพลาด',
        text: '<?= htmlspecialchars($error, ENT_QUOTES) ?>',
      });
    <?php elseif ($success): ?>
      Swal.fire({
        icon: 'success',
        title: 'สำเร็จ',
        text: '<?= htmlspecialchars($success, ENT_QUOTES) ?>',
      });
    <?php endif; ?>
  });
  </script>

</body>
</html>
