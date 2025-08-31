<!-- navbar.php -->
<style>


    .navbar {
      background-color: #111;
      color: #f0f0f0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 40px;
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 100;
      box-shadow: 0 2px 8px rgba(255, 255, 255, 0.05);
    }

    .navbar .logo img {
      height: 50px;
    }

    .navbar .nav-links {
      list-style: none;
      display: flex;
      gap: 20px;
    }

    .navbar .nav-links li a {
      color: #f0f0f0;
      text-decoration: none;
      font-weight: 500;
      padding: 8px 12px;
      border-radius: 6px;
      transition: 0.3s;
    }

    .navbar .nav-links li a:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }

</style>

  <nav class="navbar">
    <div class="logo">
      <a href="../index.php"><img src="../images/logoNav.png" alt="LRU Logo" /></a>
    </div>
    <ul class="nav-links">
      <li><a href="../index.php">หน้าหลัก</a></li>
      <li><a href="../courses.php">คอร์สอบรม</a></li>
      <li><a href="../performance.php">ผลงานและบริการ</a></li>
      <li><a href="./pages/auth/login.php">เข้าสู่ระบบ / ลงทะเบียน</a></li>
    </ul>
  </nav>
