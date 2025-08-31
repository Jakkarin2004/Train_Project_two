<?php
// session_start();
// if (isset($_SESSION['user_id'])) {
//     header("Location: ../../functions/auth/login.php"); // เปลี่ยนตามหน้า Dashboard
//     exit();
// }

$errorMessages = [
  '1' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง',
  '2' => 'กรุณากรอกข้อมูลให้ครบถ้วน',
  // ...
];
$errorKey = $_GET['error'] ?? null;
$errorText = isset($errorMessages[$errorKey]) ? $errorMessages[$errorKey] : null;
?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8" />
  <title>เข้าสู่ระบบ | ศูนย์ฝึกวิชาชีพ</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Prompt', sans-serif;
    }

    body {
      background-color: #1e1e1e;
      color: #f0f0f0;
    }

    .login-container {
      max-width: 400px;
      margin: 120px auto 60px;
      background-color: #2a2a2a;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    }

    .login-container h2 {
      text-align: center;
      margin-bottom: 25px;
    }

    .login-container label {
      display: block;
      margin-bottom: 6px;
      font-weight: 500;
    }

    /* แก้จาก type="username" เป็น type="text" */
    .login-container input[type="text"],
    .login-container input[type="password"] {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: none;
      margin-bottom: 20px;
      background-color: #444;
      color: #fff;
    }

    .remember-row {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }

    .remember-row input {
      margin-right: 10px;
    }

    .login-container button {
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

    .login-container button:hover {
      background-color: #666;
    }

    .register-link {
      text-align: center;
      margin-top: 20px;
    }

    .register-link a {
      color: #fff;
      text-decoration: underline;
    }
  </style>
</head>

<body>

  <?php include '../includes/navbar.php'; ?>

  <div class="login-container">
    <h2>เข้าสู่ระบบ</h2>
    <form id="login" method="POST" action="../functions/auth/login.php">
      <label for="username">ชื่อผู้ใช้ (Username)</label>
      <input type="text" id="username" name="username" required />

      <label for="password">รหัสผ่าน</label>
      <input type="password" id="password" name="password" required />

      <div class="remember-row">
        <input type="checkbox" id="remember" name="remember" />
        <label for="remember">จดจำรหัสผ่าน</label>
      </div>

      <button type="submit">เข้าสู่ระบบ</button>
    </form>
    <div class="register-link">
      <p>ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></p>
    </div>
  </div>

  <?php include '../includes/footer.php'; ?>


<?php if ($errorText): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  Swal.fire({
    icon: 'error',
    title: 'เข้าสู่ระบบไม่สำเร็จ',
    text: '<?= htmlspecialchars($errorText, ENT_QUOTES) ?>',
  });
</script>
<?php endif; ?>


</body>

</html>