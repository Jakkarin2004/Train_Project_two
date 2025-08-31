<?php
session_start();
require_once '../config/db.php';

// ดึงรายการวิดีโอที่เป็น YouTube เท่านั้น
$sql = "SELECT * FROM videos WHERE video_type = 'youtube' AND youtube_url IS NOT NULL ORDER BY created_at DESC";
$result = $conn->query($sql);

$videos = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $videos[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แหล่งการเรียนรู้ศูนย์ฝึก</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-green-50 min-h-screen font-sans flex">

    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>

    <!-- Main Content -->
    <main class="flex-1 p-8 max-w-7xl mx-auto overflow-auto">
        <h1 class="text-3xl font-bold text-green-800 mb-8">🎓 แหล่งการเรียนรู้ศูนย์ฝึก</h1>

        <?php if (count($videos) === 0): ?>
            <p class="text-center text-gray-500">ยังไม่มีวิดีโอการเรียนรู้ในขณะนี้</p>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($videos as $video): 
                    // ดึงเฉพาะ YouTube Video ID จาก URL (รองรับลิงก์เต็ม)
                    preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([\w\-]+)/', $video['youtube_url'], $matches);
                    $videoID = $matches[1] ?? '';
                    $embedURL = $videoID ? "https://www.youtube.com/embed/" . $videoID : '';
                ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="w-full aspect-w-16 aspect-h-9">
                            <?php if ($embedURL): ?>
                                <iframe class="w-full h-64" src="<?= $embedURL ?>" frameborder="0" allowfullscreen></iframe>
                            <?php else: ?>
                                <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-gray-400">
                                    ไม่พบวิดีโอ
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-4">
                            <h2 class="text-lg font-semibold text-green-700"><?= htmlspecialchars($video['title']) ?></h2>
                            <p class="text-sm text-gray-600 mt-1 whitespace-pre-line"><?= nl2br(htmlspecialchars($video['description'])) ?></p>
                            <p class="text-xs text-gray-500 mt-2">อัปโหลดเมื่อ: <?= date("d/m/Y", strtotime($video['created_at'])) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

</body>
</html>