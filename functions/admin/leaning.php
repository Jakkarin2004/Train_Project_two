<?php
require_once '../../config/db.php';


// ✅ ตรวจสอบว่ามีการส่ง id มาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $videoId = (int)$_GET['id'];

    $stmt = $conn->prepare("SELECT id, title, description, video_type, file_path, youtube_url FROM videos WHERE id = ?");
    $stmt->bind_param("i", $videoId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($video = $result->fetch_assoc()) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($video);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'ไม่พบวิดีโอ']);
    }
    exit;
}

// ลบ

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ถ้ามี action=delete ให้ลบ
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = isset($_POST['id']) && is_numeric($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo "ID วิดีโอไม่ถูกต้อง";
            exit;
        }

        // ลบข้อมูลจากฐานข้อมูล
        $stmt = $conn->prepare("DELETE FROM videos WHERE id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "ลบวิดีโอสำเร็จ";
        } else {
            http_response_code(500);
            echo "ลบวิดีโอไม่สำเร็จ";
        }
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) && is_numeric($_POST['id']) ? (int)$_POST['id'] : null;  // ดักรับ id ให้แน่นอน
    $title = trim($_POST['videoTitle'] ?? '');
    $description = trim($_POST['videoDescription'] ?? '');
    $videoType = $_POST['videoType'] ?? '';
    $createdAt = date('Y-m-d H:i:s');

    // ตรวจสอบค่าพื้นฐาน
    if ($title === '' || $description === '' || $videoType === '') {
        http_response_code(400);
        echo "กรุณากรอกข้อมูลให้ครบถ้วน";
        exit;
    }

    // เตรียมข้อมูลพื้นฐาน
    $fields = "title=?, description=?, video_type=?";
    $types = "sss";
    $params = [$title, $description, $videoType];

    // ถ้าเป็นประเภทอัปโหลดไฟล์
    if ($videoType === 'upload' && isset($_FILES['videoFile']) && $_FILES['videoFile']['error'] === 0) {
        $file = $_FILES['videoFile'];
        $uploadDir = '../../uploads/videos/';
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExt = ['mp4', 'avi', 'mov', 'wmv'];
        if (!in_array($ext, $allowedExt)) {
            http_response_code(400);
            echo "ไฟล์วิดีโอไม่รองรับนามสกุลนี้";
            exit;
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = 'video_' . uniqid() . '.' . $ext;
        $filePath = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            http_response_code(500);
            echo "ไม่สามารถอัปโหลดไฟล์ได้";
            exit;
        }

        $fields .= ", file_path=?";
        $types .= "s";
        $params[] = 'uploads/videos/' . $filename;
    } else if ($videoType === 'upload' && !$id) {
        // กรณีเพิ่มใหม่แต่ไม่ได้เลือกไฟล์
        http_response_code(400);
        echo "กรุณาเลือกไฟล์วิดีโอ";
        exit;
    }

    // ถ้าเป็นประเภท YouTube
    if ($videoType === 'youtube') {
        $youtubeUrl = trim($_POST['videoUrl'] ?? '');
        if ($youtubeUrl === '' || !filter_var($youtubeUrl, FILTER_VALIDATE_URL)) {
            http_response_code(400);
            echo "URL YouTube ไม่ถูกต้อง";
            exit;
        }

        $fields .= ", youtube_url=?";
        $types .= "s";
        $params[] = $youtubeUrl;
    }

    if ($id) {
        // แก้ไขข้อมูล (UPDATE)
        $sql = "UPDATE videos SET $fields WHERE id=?";
        $types .= "i";
        $params[] = $id;

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            http_response_code(500);
            echo "Prepare failed: " . $conn->error;
            exit;
        }

        $stmt->bind_param($types, ...$params);
        if ($stmt->execute()) {
            echo "แก้ไขวิดีโอสำเร็จ";
        } else {
            http_response_code(500);
            echo "ไม่สามารถแก้ไขวิดีโอได้";
        }
    } else {
        // เพิ่มใหม่ (INSERT)
        $fields .= ", created_at=?";
        $types .= "s";
        $params[] = $createdAt;

        $sql = "INSERT INTO videos SET $fields";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            http_response_code(500);
            echo "Prepare failed: " . $conn->error;
            exit;
        }

        $stmt->bind_param($types, ...$params);
        if ($stmt->execute()) {
            echo "เพิ่มวิดีโอสำเร็จ";
        } else {
            http_response_code(500);
            echo "ไม่สามารถเพิ่มวิดีโอได้";
        }
    }
    exit;
}

// GET request ดึงข้อมูลวิดีโอทั้งหมด
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($videos);
    exit;
}


?>
