<?php 
session_start();
require_once '../config/db.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php?error=unauthorized");
    exit();
}

$memberID = $_SESSION['user_id']; // กำหนด memberID จาก session


// ดึงหมวดหมู่มาทุกกรณี (ไม่ว่า POST หรือ GET)
$categoryList = [];
$catQuery = $conn->query("SELECT id, category_name FROM categories ORDER BY category_name ASC");
if ($catQuery) {
    $categoryList = $catQuery->fetch_all(MYSQLI_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $categoryID = intval($_POST['category']); // รับเป็น ID ไม่ใช่ชื่อ

    // เพิ่มผลงาน
    $stmt = $conn->prepare("INSERT INTO performance (MemberID, Title, Description, CategoryID) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issi", $memberID, $title, $description, $categoryID);

    if ($stmt->execute()) {
        $portfolioID = $stmt->insert_id;
        $stmt->close();

        // อัปโหลดรูปภาพ
        if (isset($_FILES['images'])) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                $fileType = $_FILES['images']['type'][$key];
                $fileError = $_FILES['images']['error'][$key];
                if ($fileError === 0 && in_array($fileType, $allowedTypes)) {
                    $ext = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
                    $imageName = time() . "_" . uniqid() . "." . $ext;
                    $targetPath = "../image/" . $imageName;
                    if (move_uploaded_file($tmp_name, $targetPath)) {
                        $imageURL = $imageName;
                        $stmt2 = $conn->prepare("INSERT INTO portfolio_images (PortfolioID, ImageURL) VALUES (?, ?)");
                        $stmt2->bind_param("is", $portfolioID, $imageURL);
                        $stmt2->execute();
                        $stmt2->close();
                    }
                }
            }
        }

        header("Location: works_list.php");
        exit();
    } else {
        echo "เพิ่มผลงานล้มเหลว: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <title>เพิ่มผลงานใหม่</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex">

    <!-- Sidebar -->
    <?php include("sidebar.php"); ?>

    <!-- Main Content -->
    <main class="flex-1 max-w-4xl mx-auto p-8">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-green-700 mb-6 text-center">เพิ่มผลงานใหม่</h2>

            <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- ชื่อผลงาน -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">ชื่อผลงาน</label>
                    <input type="text" name="title" required class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
                </div>

                <!-- รายละเอียด -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">รายละเอียดผลงาน</label>
                    <textarea name="description" rows="4" required class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                </div>

                <!-- หมวดหมู่ -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">หมวดหมู่</label>
                    <select name="category" required class="w-full border border-gray-300 rounded-md px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">-- กรุณาเลือกหมวดหมู่ --</option>
                        <?php foreach ($categoryList as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- อัปโหลดรูป -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">รูปภาพผลงาน <span class="text-sm text-gray-500">(สามารถเลือกได้หลายภาพ)</span></label>
                    <input 
                        type="file" 
                        name="images[]" 
                        multiple 
                        accept="image/jpeg,image/png,image/gif"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm
                        file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm
                        file:font-semibold file:bg-green-100 file:text-green-700 hover:file:bg-green-200" />
                </div>

                <!-- ปุ่มบันทึก -->
                <div class="text-center">
                    <button type="submit" class="bg-green-600 text-white font-semibold px-6 py-2 rounded-md hover:bg-green-700 transition">บันทึกผลงาน</button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="works_list.php" class="text-green-600 hover:underline">← กลับไปหน้าผลงาน</a>
            </div>
        </div>
    </main>

</body>

</html>
