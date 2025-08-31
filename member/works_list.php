<?php
session_start();
require_once '../config/db.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php?error=unauthorized");
    exit();
}

$memberID = $_SESSION['user_id']; // กำหนด memberID จาก session

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if (!isset($conn) || !$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// ดึงผลงานพร้อมชื่อหมวดหมู่
$stmt = $conn->prepare("
    SELECT p.*, c.category_name 
    FROM performance p
    LEFT JOIN categories c ON p.CategoryID = c.id
    WHERE p.MemberID = ?
    ORDER BY p.PortfolioID DESC
");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $memberID);
$stmt->execute();
$result = $stmt->get_result();
$works = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ฟังก์ชันช่วยดึงรูปภาพหลักของผลงาน (ภาพแรก)
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
    <meta charset="UTF-8" />
    <title>ผลงานและบริการของฉัน</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">

    <!-- Sidebar -->
    <?php include("sidebar.php"); ?>

    <!-- Main content -->
    <main class="flex-1 max-w-6xl mx-auto p-8">
        <h2 class="text-3xl font-bold text-green-700 mb-6">ผลงานและบริการของฉัน</h2>

        <p class="mb-6 space-x-4">
            <a href="works_add.php" class="text-green-600 hover:underline font-semibold">เพิ่มผลงานใหม่</a>
            <a href="profile.php" class="text-green-600 hover:underline font-semibold">กลับหน้าข้อมูลส่วนตัว</a>
        </p>

        <?php if (count($works) > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-md shadow-md">
                    <thead class="bg-green-600 text-white">
                        <tr>
                            <th class="py-3 px-6 text-left">รูปภาพ</th>
                            <th class="py-3 px-6 text-left">รหัสผลงาน</th>
                            <th class="py-3 px-6 text-left">ชื่อผลงาน</th>
                            <th class="py-3 px-6 text-left">รายละเอียด</th>
                            <th class="py-3 px-6 text-left">หมวดหมู่</th>
                            <th class="py-3 px-6 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($works as $work):
                            $mainImage = getMainImage($conn, $work['PortfolioID']);
                        ?>
                            <tr class="border-b hover:bg-blue-50">
                                <td class="py-3 px-6">
                                    <?php if ($mainImage): ?>
                                        <img
                                            src="<?= htmlspecialchars($mainImage) ?>"
                                            alt="ผลงาน <?= htmlspecialchars($work['Title']) ?>"
                                            class="w-12 h-12 rounded-full object-cover cursor-pointer"
                                            onclick="openModal(<?= (int)$work['PortfolioID'] ?>)"
                                            onerror="console.log('Image failed to load: <?= htmlspecialchars($mainImage) ?>')">
                                    <?php else: ?>
                                        <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center text-gray-500 cursor-default">N/A</div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-6"><?= (int)$work['PortfolioID'] ?></td>
                                <td class="py-3 px-6"><?= htmlspecialchars($work['Title']) ?></td>
                                <td class="py-3 px-6 max-w-xs truncate" title="<?= htmlspecialchars($work['Description']) ?>">
                                    <?= htmlspecialchars($work['Description']) ?>
                                </td>
                                <td class="py-3 px-6"><?= htmlspecialchars($work['category_name'] ?? 'ไม่ระบุ') ?></td>
                                <td class="py-3 px-6 text-center space-x-4">
                                    <button
                                        onclick="openModal(<?= (int)$work['PortfolioID'] ?>)"
                                        class="text-green-600 hover:underline font-semibold underline cursor-pointer"
                                        type="button">ดูภาพรวม</button>
                                    <a href="works_edit.php?id=<?= (int)$work['PortfolioID'] ?>" class="text-blue-600 hover:underline font-semibold">แก้ไข</a>
                                    <a href="works_delete.php?id=<?= (int)$work['PortfolioID'] ?>" onclick="return confirm('ยืนยันการลบผลงานนี้?')" class="text-red-600 hover:underline font-semibold">ลบ</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Modal แสดงภาพทั้งหมด -->
            <?php foreach ($works as $work):
                $allImages = getAllImages($conn, $work['PortfolioID']);
            ?>
                <div
                    id="modal-<?= (int)$work['PortfolioID'] ?>"
                    class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
                    <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-auto p-6 relative shadow-lg">
                        <!-- ปุ่มปิด -->
                        <button
                            onclick="closeModal(<?= (int)$work['PortfolioID'] ?>)"
                            class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-xl font-bold"
                            aria-label="Close modal">&times;</button>

                        <!-- ข้อมูลผลงาน -->
                        <h3 class="text-2xl font-bold text-green-700 mb-2"><?= htmlspecialchars($work['Title']) ?></h3>
                        <p class="text-sm text-gray-500 mb-4">รหัสผลงาน: <?= (int)$work['PortfolioID'] ?></p>

                        <div class="mb-4">
                            <label class="font-semibold text-gray-700">หมวดหมู่:</label>
                            <span class="text-gray-800 ml-1"><?= htmlspecialchars($work['category_name'] ?? 'ไม่ระบุ') ?></span>
                        </div>

                        <div class="mb-6">
                            <label class="font-semibold text-gray-700">รายละเอียด:</label>
                            <p class="text-gray-800 whitespace-pre-line mt-1"><?= nl2br(htmlspecialchars($work['Description'])) ?></p>
                        </div>

                        <!-- แสดงภาพทั้งหมด -->
                        <div class="grid grid-cols-3 md:grid-cols-5 gap-4">
                            <?php if ($allImages): ?>
                                <?php foreach ($allImages as $img): ?>
                                    <img
                                        src="<?= htmlspecialchars($img['ImageURL']) ?>"
                                        alt="ภาพผลงาน"
                                        class="rounded-lg object-cover w-full h-32 cursor-pointer hover:scale-105 transition-transform"
                                        onclick="viewImageInNewTab('<?= htmlspecialchars($img['ImageURL']) ?>')"
                                        onerror="console.log('Image failed to load: <?= htmlspecialchars($img['ImageURL']) ?>')">
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="col-span-full text-gray-500">ไม่มีรูปภาพ</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p class="text-gray-600">ยังไม่มีผลงาน</p>
        <?php endif; ?>
    </main>

    <script>
        function openModal(id) {
            const modal = document.getElementById('modal-' + id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeModal(id) {
            const modal = document.getElementById('modal-' + id);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function viewImageInNewTab(url) {
            window.open(url, '_blank');
        }

        // ปิด modal เมื่อกดที่พื้นหลัง (outside modal)
        window.addEventListener('click', function(event) {
            const modals = document.querySelectorAll('[id^="modal-"]');
            modals.forEach(modal => {
                if (!modal.classList.contains('hidden') && event.target === modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        });

        // กด Esc ปิด modal ได้ด้วย
        window.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                const modals = document.querySelectorAll('[id^="modal-"]');
                modals.forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                });
            }
        });
    </script>
</body>
</html>