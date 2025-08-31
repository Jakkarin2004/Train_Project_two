<?php
session_start();
require_once '../config/db.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php?error=unauthorized");
    exit();
}

$userId = $_SESSION['user_id'];

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ข้อมูลส่วนตัว</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include("sidebar.php"); ?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Banner -->
            <div class="relative rounded-xl overflow-hidden h-40 bg-gradient-to-r from-purple-500 to-yellow-400">
                <?php if (!empty($member['ProfileImage'])): ?>
                    <img src="../<?= htmlspecialchars($member['ProfileImage']) ?>" alt="รูปโปรไฟล์" class="absolute bottom-[-2rem] left-8 w-24 h-24 rounded-full border-4 border-white shadow-md object-cover">
                <?php endif; ?>
            </div>

            <!-- Profile Card -->
            <div class="bg-white mt-16 p-8 rounded-xl shadow-md">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($member['Firstname']) ?> <?= htmlspecialchars($member['Lastname']) ?></h2>
                        <p class="text-gray-500">@<?= htmlspecialchars($member['Username']) ?></p>
                    </div>
                    <div class="flex gap-2">
                        <a href="profile_edit.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">แก้ไข</a>
                        <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">ออกจากระบบ</a>
                    </div>
                </div>

                <div class="mt-6 space-y-4">
                    <p><strong class="text-gray-600">อีเมล:</strong> <?= htmlspecialchars($member['Email']) ?></p>
                    <p><strong class="text-gray-600">เบอร์โทร:</strong> <?= htmlspecialchars($member['Telephone']) ?></p>
                    <p><strong class="text-gray-600">วันเกิด:</strong> <?= htmlspecialchars($member['BirthDate']) ?></p>
                    <p><strong class="text-gray-600">อาชีพ:</strong> <?= htmlspecialchars($member['Occupation']) ?></p>
                    <p><strong class="text-gray-600">เพศ:</strong> <?= htmlspecialchars($member['Gender']) ?></p>
                    <p><strong class="text-gray-600">ที่อยู่:</strong><br>
                        <?= nl2br(htmlspecialchars($member['Address'])) ?><br>
                        <?= htmlspecialchars($member['SubDistrict']) ?>,
                        <?= htmlspecialchars($member['District']) ?>,
                        <?= htmlspecialchars($member['Province']) ?> <?= htmlspecialchars($member['ZipCode']) ?>
                    </p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
