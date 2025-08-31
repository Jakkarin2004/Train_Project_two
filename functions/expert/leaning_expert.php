<?php
require_once '../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

// ตรวจสอบว่าเป็นการร้องขอแบบ GET เท่านั้น
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // ถ้ามีการส่ง id มา ดึงเฉพาะรายการนั้น
    if (isset($_GET['id'])) {
        $videoId = (int)$_GET['id'];

        $stmt = $conn->prepare("SELECT id, title, description, video_type, file_path, youtube_url, created_at FROM videos WHERE id = ?");
        $stmt->bind_param("i", $videoId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($video = $result->fetch_assoc()) {
            echo json_encode($video);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'ไม่พบวิดีโอ']);
        }
        exit;
    }

    // ดึงข้อมูลวิดีโอทั้งหมด
    $sql = "SELECT id, title, description, video_type, created_at, file_path, youtube_url FROM videos ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if ($result === false) {
        http_response_code(500);
        echo json_encode(['error' => 'ไม่สามารถดึงข้อมูลวิดีโอได้']);
        exit;
    }

    $videos = [];
    while ($row = $result->fetch_assoc()) {
        $videos[] = $row;
    }

    echo json_encode($videos);
    exit;
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method ไม่อนุญาต']);
    exit;
}
?>
