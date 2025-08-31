<?php
// เริ่มต้นไฟล์ PHP
require_once './config/db.php';
function getProjectsByDisplayOrder($conn) {
    // กำหนดค่า display_order ที่ต้องการดึง
    $validOrders = [1, 2, 3, 4];

    // สร้าง SQL ใช้ WHERE IN
    $placeholders = implode(',', array_fill(0, count($validOrders), '?'));

    $sql = "SELECT id, title, description, date, location, image, display_order, status, project_status
            FROM training_projects
            WHERE display_order IN ($placeholders)
            ORDER BY display_order ASC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('Prepare failed: ' . $conn->error);
    }

    // ผูกพารามิเตอร์แบบ dynamic
    $types = str_repeat('i', count($validOrders));
    $stmt->bind_param($types, ...$validOrders);

    $stmt->execute();

    $result = $stmt->get_result();

    $projects = [];
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }

    $stmt->close();

    return $projects;
}

// เรียกใช้ฟังก์ชัน
$projects = getProjectsByDisplayOrder($conn);

// แสดงผลลัพธ์
// foreach ($projects as $project) {
//     echo "ID: {$project['id']} | Title: {$project['title']} | Display Order: {$project['display_order']}<br>";
// }

// ดึงข้อมูลโครงการที่ display_order = 1
$sql = "SELECT * FROM training_projects WHERE display_order = 1 LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $project = $result->fetch_assoc();
} else {
    $project = null;
}

// ดึงข้อมูล display_order 2, 3, 4
$validOrders = [2, 3, 4];
$placeholders = implode(',', array_fill(0, count($validOrders), '?'));
$sql = "SELECT * FROM training_projects WHERE display_order IN ($placeholders) ORDER BY display_order ASC";
$stmt = $conn->prepare($sql);
$types = str_repeat('i', count($validOrders));
$stmt->bind_param($types, ...$validOrders);
$stmt->execute();
$result = $stmt->get_result();

$projects = [];
while ($row = $result->fetch_assoc()) {
  $projects[] = $row;
}
$stmt->close();

?>
<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ศูนย์ฝึกวิชาชีพ - LRU</title>
  <link rel="stylesheet" href="index.css" />
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
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
</head>

<body>
  <!-- Navbar -->
    <nav class="navbar">
    <div class="logo">
      <a href="index.php"><img src="images/logoNav.png" alt="LRU Logo" /></a>
    </div>
    <ul class="nav-links">
      <li><a href="index.php">หน้าหลัก</a></li>
      <li><a href="courses.php">คอร์สอบรม</a></li>
      <li><a href="performance.php">ผลงานและบริการ</a></li>
      <li><a href="./auth/login.php">เข้าสู่ระบบ / ลงทะเบียน</a></li>
    </ul>
  </nav>


  <!-- Section Content -->
  <section class="main-section" id="hero">
    <div class="content">
      <h1>ยินดีต้อนรับสู่ศูนย์ฝึกวิชาชีพทางด้านวิทยาการคอมพิวเตอร์</h1>
      <p>เรามุ่งมั่นในการพัฒนาทักษะอาชีพ เพิ่มโอกาสในการเรียนรู้ และเติบโตในสายงานเทคโนโลยีสารสนเทศ</p>
      <a href="./auth/login.php" class="btn">เข้าสู่ระบบ / ลงทะเบียน</a>
    </div>
  </section>

<section class="featured-project">
  <div class="featured-content">
    <div class="featured-image">
      <img src="<?= htmlspecialchars( ($project['image'] && $project['image'] != '') ? './uploads/projects/' . $project['image'] : 'https://uknowva.com/images/employeetrainingprogram.jpg' ) ?>" alt="รูปโครงการอบรม" />

    </div>
    <div class="featured-details">
      <h2><?= htmlspecialchars($project['title']) ?></h2>
      <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>
      <p><strong>สถานที่:</strong> <?= htmlspecialchars($project['location']) ?></p>
      <a href="#" class="register-btn" onclick="openModal('<?= htmlspecialchars($project['id']) ?>')">ลงทะเบียน</a>
    </div>
  </div>
</section>

  <!-- Training Projects -->
 <section class="training-projects">
  <div class="training-container">
    <div class="section-title">
      <div class="text-white text-3xl">โครงการอบรมที่กำลังจะเกิดขึ้น</div>
    </div>
    <div class="training-list" id="show-training-comming-soon">
      <?php if (!empty($projects)): ?>
        <?php foreach ($projects as $project): ?>
          <div class="training-card">
            <img class="training-img" src="<?= htmlspecialchars( ($project['image'] && $project['image'] != '') ? './uploads/projects/' . $project['image'] : 'https://uknowva.com/images/employeetrainingprogram.jpg' ) ?>" alt="รูปโครงการอบรม" />
            <div class="training-content">
              
              <h3><?= htmlspecialchars($project['title']) ?></h3>
              <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>
              <p><strong>สถานที่:</strong> <?= htmlspecialchars($project['location']) ?></p>
              <a href="#details" class="btn">ดูรายละเอียด</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>ไม่มีโครงการอบรมที่จะแสดงในขณะนี้</p>
      <?php endif; ?>
    </div>
  </div>
</section>

  <!-- Info Section -->
  <section class="info-section-alt">
    <div class="section-title">
      <h2>🧠 เกี่ยวกับเว็บไซต์ศูนย์ฝึกวิชาชีพ</h2>
    </div>

    <div class="info-item left">
      <p>🧑‍🏫 <strong>แหล่งเรียนรู้:</strong> เรารวบรวมสื่อการเรียนรู้ด้านไอที...</p>
    </div>
    <div class="info-item right">
      <p>📁 <strong>ผลงานนักศึกษา:</strong> ชมตัวอย่างโปรเจกต์จริงจากนักศึกษา...</p>
    </div>
    <div class="info-item left">
      <p>🛠️ <strong>ให้คำปรึกษา:</strong> หากคุณมีคำถามเกี่ยวกับสายงาน...</p>
    </div>
    <div class="info-item right">
      <p>🌐 <strong>ระบบอบรมออนไลน์:</strong> คุณสามารถเข้าร่วมคอร์สต่างๆ ผ่านระบบออนไลน์</p>
    </div>
  </section>

  <!-- Thank You Section -->
  <section class="thankyou-section">
    <div class="container">
      <h2 class="thankyou-title">ขอบคุณที่เข้ามาเยี่ยมชมเว็บไซต์ของเรา</h2>
      <p class="thankyou-text">หวังว่าเนื้อหาและบริการที่เราจัดเตรียมไว้จะเป็นประโยชน์...</p>
    </div>
  </section>

  <!-- Footer -->
<?php include './includes/footer.php'; ?>

<!-- Facebook SDK ควรวางไว้ด้านล่างสุดของ body -->
<div id="fb-root"></div>
<script async defer crossorigin="anonymous"
  src="https://connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v22.0"></script>

  <!-- JavaScript -->
  <script>
    const images = [
      'https://images.unsplash.com/photo-1742827871480-4962b0653e1d?...',
      'https://plus.unsplash.com/premium_photo-1663075847012-c781e0d194ce?...',
      'https://images.unsplash.com/photo-1742827871480-4962b0653e1d?...',
    ];
    let index = 0;
    const section = document.getElementById('hero');

    function changeBackground() {
      section.style.backgroundImage = `url('${images[index]}')`;
      section.style.backgroundSize = 'cover';
      section.style.backgroundPosition = 'center';
      section.style.transition = 'background-image 1s ease-in-out';
      index = (index + 1) % images.length;
    }

    setInterval(changeBackground, 5000);
    changeBackground();

    function openModal() {
      document.getElementById("registerModal").style.display = "block";
    }

    function closeModal() {
      document.getElementById("registerModal").style.display = "none";
    }

    window.onclick = function (event) {
      const modal = document.getElementById("registerModal");
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- <script src="../env.js"></script>
  <script src="../header-users.js"></script>
  <script src="../auth.js"></script> -->
</body>

</html>
