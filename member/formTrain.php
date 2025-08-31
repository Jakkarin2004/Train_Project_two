<?php 
session_start();
require_once '../config/db.php';

// ตรวจสอบว่ามีการส่ง id มาหรือไม่
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ไม่พบรหัสโครงการอบรมที่ระบุ');
}

$projectId = intval($_GET['id']);

// ดึงข้อมูลโครงการอบรมตาม id
$stmt = $conn->prepare("SELECT id, title, description, date AS training_date, location FROM training_projects WHERE id = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $projectId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('ไม่พบโครงการอบรมที่ต้องการ');
}

$project = $result->fetch_assoc();

// ฟังก์ชันแปลงวันที่เป็นรูปแบบไทย
function formatDateForDisplay($dateString)
{
    if (!$dateString) return '';

    $date = new DateTime($dateString);
    $thaiMonths = [
        'ม.ค.',
        'ก.พ.',
        'มี.ค.',
        'เม.ย.',
        'พ.ค.',
        'มิ.ย.',
        'ก.ค.',
        'ส.ค.',
        'ก.ย.',
        'ต.ค.',
        'พ.ย.',
        'ธ.ค.'
    ];

    $day = $date->format('j');
    $month = $thaiMonths[$date->format('n') - 1];
    $year = $date->format('Y');

    return "{$day} {$month} {$year}";
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <title>ลงทะเบียนโครงการอบรม</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>

<body class="bg-green-50 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-lg w-full">
        <h1 class="text-2xl font-bold mb-6 text-green-800">ลงทะเบียนโครงการ: <?= htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8') ?></h1>

        <p class="mb-4"><strong>วันที่อบรม:</strong> <?= formatDateForDisplay($project['training_date']) ?></p>
        <p class="mb-4"><strong>สถานที่:</strong> <?= htmlspecialchars($project['location'], ENT_QUOTES, 'UTF-8') ?></p>
        <p class="mb-6 whitespace-pre-line"><?= nl2br(htmlspecialchars($project['description'], ENT_QUOTES, 'UTF-8')) ?></p>

        <form action="submitRegistration.php" method="post" class="space-y-4">
            <!-- ซ่อน id โครงการไว้ส่งต่อ -->
            <input type="hidden" name="project_id" value="<?= htmlspecialchars($project['id'], ENT_QUOTES, 'UTF-8') ?>" />

            <div>
                <label for="name" class="block text-green-700 font-semibold mb-1">ชื่อ-นามสกุล</label>
                <input id="name" name="name" type="text" required
                    class="w-full border border-green-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" />
            </div>

            <div>
                <button type="submit"
                    class="w-full bg-green-600 text-white font-semibold py-2 rounded hover:bg-green-700 transition">
                    ส่งใบสมัคร
                </button>
                <p class="mt-6">
                    <a href="trainProject_view.php" class="text-green-600 hover:underline">กลับไปหน้าโครงการ</a>
                </p>
            </div>
        </form>
    </div>

</body>

</html>