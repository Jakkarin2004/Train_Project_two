<?php
require_once './config/db.php';

// ตรวจสอบการเชื่อมต่อ
if (!isset($conn) || !$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// ดึงผลงานทั้งหมดพร้อมชื่อหมวดหมู่
$stmt = $conn->prepare("
    SELECT p.*, c.category_name 
    FROM performance p
    LEFT JOIN categories c ON p.CategoryID = c.id
    ORDER BY p.PortfolioID DESC
");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->execute();
$result = $stmt->get_result();
if (!$result) {
    die("Query failed: " . $conn->error);
}
$works = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ฟังก์ชันช่วยดึงรูปภาพหลักของผลงาน
function getMainImage($conn, $portfolioID)
{
    $stmt = $conn->prepare("SELECT ImageURL FROM portfolio_images WHERE PortfolioID = ? LIMIT 1");
    if (!$stmt) {
        error_log("Prepare failed for PortfolioID $portfolioID: " . $conn->error);
        return null;
    }
    $stmt->bind_param("i", $portfolioID);
    $stmt->execute();
    $result = $stmt->get_result();
    $image = $result->fetch_assoc();
    if (!$image) {
        error_log("No image found for PortfolioID: $portfolioID");
    }
    $stmt->close();
    return $image ? '/project_admin/image/' . $image['ImageURL'] : null;
}

// ฟังก์ชันช่วยดึงรูปทั้งหมดของผลงาน (สำหรับ modal)
function getAllImages($conn, $portfolioID)
{
    $stmt = $conn->prepare("SELECT ImageURL FROM portfolio_images WHERE PortfolioID = ?");
    if (!$stmt) {
        error_log("Prepare failed for PortfolioID $portfolioID: " . $conn->error);
        return [];
    }
    $stmt->bind_param("i", $portfolioID);
    $stmt->execute();
    $result = $stmt->get_result();
    $images = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    // แปลง ImageURL ให้รวม path เต็ม
    return array_map(function($img) {
        return ['ImageURL' => '/project_admin/image/' . $img['ImageURL']];
    }, $images);
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ผลงานและบริการ | ศูนย์ฝึกวิชาชีพ</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://unpkg.com/tailwindcss@^3/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Prompt', sans-serif;
        }

        /* พื้นหลังหลัก */
        body {
            background-color: #1e1e1e;
            color: #f0f0f0;
        }

        /* Navbar */
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

        /* Services Container */
        .services-container {
            max-width: 1100px;
            margin: 120px auto 50px;
            padding: 20px;
        }

        .services-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .services-header h2 {
            color: #ffffff;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .services-header p {
            color: #ccc;
            font-size: 16px;
        }

        .services-list {
            display: grid;
            grid-template-columns: auto auto auto auto;
            gap: 20px;
        }

        .service-card {
            background: #2a2a2a;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.7);
        }

        .service-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .service-title {
            font-size: 20px;
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .service-desc {
            font-size: 15px;
            color: #ddd;
            line-height: 1.5;
        }

        .contact-btn {
            all: unset;
            display: inline-block;
            padding: 6px 12px;
            background-color: #a8e6cf;
            color: #000;
            text-align: center;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .contact-btn:hover {
            background-color: #94dbc0;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            margin: 5% auto;
            padding: 20px;
            max-width: 600px;
            border-radius: 8px;
            position: relative;
            overflow-y: auto;
            max-height: 80vh;
        }

        .modal-content img {
            width: 100%;
            margin-bottom: 10px;
            border-radius: 8px;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            cursor: pointer;
            font-size: 20px;
            color: #333;
        }

        /* Footer */
        .footer {
            background-color: #111;
            color: #f0f0f0;
            padding: 40px 20px 20px;
            margin-top: 60px;
        }

        .footer-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 30px;
            max-width: 1100px;
            margin: auto;
        }

        .footer h3 {
            margin-bottom: 10px;
            font-size: 20px;
            color: #fff;
        }

        .footer-left,
        .footer-center,
        .footer-right {
            flex: 1;
            min-width: 250px;
        }

        .fb-box-vertical {
            border-radius: 12px;
            overflow: hidden;
        }

        .quick-links,
        .contact-info {
            list-style: none;
            padding: 0;
        }

        .quick-links li,
        .contact-info li {
            margin-bottom: 10px;
            font-size: 14px;
        }

        .quick-links a {
            color: #ccc;
            text-decoration: none;
            transition: 0.3s;
        }

        .quick-links a:hover {
            text-decoration: underline;
            color: #fff;
        }

        .footer .icon {
            width: 16px;
            margin-right: 8px;
            vertical-align: middle;
            filter: brightness(0.9);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            font-size: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Service Footer Button */
        .service-footer {
            text-align: center;
            margin-top: 40px;
        }

        .service-footer a {
            background-color: #444;
            color: white;
            padding: 10px 25px;
            border-radius: 10px;
            text-decoration: none;
            transition: 0.3s;
        }

        .service-footer a:hover {
            background-color: #666;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <a href="index.php"><img src="/project_admin/images/logoNav.png" alt="LRU Logo"></a>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">หน้าหลัก</a></li>
            <li><a href="courses.php">คอร์สอบรม</a></li>
            <li><a href="performance.php">ผลงานและบริการ</a></li>
            <li><a href="./auth/login.php">เข้าสู่ระบบ / ลงทะเบียน</a></li>
        </ul>
    </nav>

    <div class="services-container">
        <div class="services-header">
            <h2>ผลงานและบริการจากสมาชิก</h2>
            <p>แหล่งบริการและผลงานที่พัฒนาจากสมาชิกของศูนย์ฝึก</p>
        </div>

        <div class="services-list">
            <?php if (empty($works)): ?>
                <p class="text-center text-gray-500">ไม่มีผลงานที่สามารถแสดงได้ในขณะนี้</p>
            <?php else: ?>
                <?php foreach ($works as $work):
                    $mainImage = getMainImage($conn, $work['PortfolioID']);
                ?>
                    <div class="service-card">
                        <?php if ($mainImage): ?>
                            <img src="<?= htmlspecialchars($mainImage) ?>"
                                alt="ผลงาน <?= htmlspecialchars($work['Title']) ?>"
                                class="cursor-pointer"
                                onclick="openModal(<?= (int)$work['PortfolioID'] ?>)"
                                onerror="console.log('Image failed to load: <?= htmlspecialchars($mainImage) ?>')">
                        <?php else: ?>
                            <div class="w-24 h-24 rounded-lg bg-gray-300 flex items-center justify-center text-gray-500 cursor-default">N/A</div>
                        <?php endif; ?>
                        <div class="service-title"><?= htmlspecialchars($work['Title']) ?></div>
                        <div class="service-desc"><?= nl2br(htmlspecialchars($work['Description'])) ?></div>
                        <a href="#" class="contact-btn">ข้อมูลเพิ่มเติม</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="service-footer">
            <a href="index.php">← กลับหน้าแรก</a>
        </div>
    </div>

    <!-- Modal -->
    <div id="imageModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <div id="modalImages"></div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div id="fb-root"></div>
        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v22.0"></script>

        <div class="footer-container">
            <div class="footer-left">
                <h3>ติดตามเพจศูนย์ฝึก</h3>
                <div class="fb-box-vertical">
                    <div class="fb-page" data-href="https://www.facebook.com/profile.php?id=61554638219599" data-tabs="timeline" data-width="250" data-height="400" data-small-header="false"
                        data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                        <blockquote cite="https://www.facebook.com/profile.php?id=61554638219599" class="fb-xfbml-parse-ignore">
                            <a href="https://www.facebook.com/profile.php?id=61554638219599">
                                ศูนย์ฝึกวิชาชีพทางด้านวิทยาการคอมพิวเตอร์ มหาวิทยาลัยราชภัฏเลย
                            </a>
                        </blockquote>
                    </div>
                </div>
            </div>

            <div class="footer-center">
                <h3>เมนูลัด</h3>
                <ul class="quick-links">
                    <li><a href="#login">เข้าสู่ระบบ</a></li>
                    <li><a href="courses.php">คอร์สอบรม</a></li>
                    <li><a href="#services">แหล่งเรียนรู้</a></li>
                    <li><a href="#contact">ติดต่อเรา</a></li>
                </ul>
            </div>

            <div class="footer-right">
                <h3>ติดต่อเรา</h3>
                <ul class="contact-info">
                    <li><img src="https://cdn-icons-png.flaticon.com/512/1384/1384053.png" alt="fb" class="icon" /> Facebook: LRU Digital Training</li>
                    <li><img src="https://cdn-icons-png.flaticon.com/512/2111/2111392.png" alt="line" class="icon" /> Line: @lrucscenter</li>
                    <li><img src="https://cdn-icons-png.flaticon.com/512/732/732200.png" alt="email" class="icon" /> Email: lrudigitalstartupteam.cs@gmail.com</li>
                    <li><img src="https://cdn-icons-png.flaticon.com/512/724/724664.png" alt="phone" class="icon" /> โทร: 042-000-000</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 ศูนย์ฝึกวิชาชีพทางด้านวิทยาการคอมพิวเตอร์ มหาวิทยาลัยราชภัฏเลย</p>
        </div>
    </footer>

    <script>
        function openModal(portfolioID) {
            fetch('/project_admin/get_images.php?portfolioID=' + portfolioID)
                .then(response => response.json())
                .then(data => {
                    const modal = document.getElementById('imageModal');
                    const modalImages = document.getElementById('modalImages');
                    modalImages.innerHTML = data.length ?
                        data.map(img => `<img src="${img.ImageURL}" alt="Portfolio Image" onerror="console.log('Modal image failed to load: ${img.ImageURL}')">`).join('') :
                        '<p>ไม่มีรูปภาพ</p>';
                    modal.style.display = 'block';
                })
                .catch(error => console.error('Error fetching images:', error));
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
        }

        // ปิด modal เมื่อกดที่พื้นหลัง
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('imageModal');
            if (event.target === modal) {
                closeModal();
            }
        });

        // ปิด modal เมื่อกดปุ่ม Esc
        window.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeModal();
            }
        });
    </script>
</body>
</html>