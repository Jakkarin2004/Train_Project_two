<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = isset($_POST['project_id']) ? intval($_POST['project_id']) : 0;
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';

    if ($project_id <= 0) {
        die('ข้อมูลโครงการไม่ถูกต้อง');
    }
    if (empty($name)) {
        die('กรุณากรอกชื่อ-นามสกุล');
    }

    // ตรวจสอบว่าโครงการนี้มีจริงหรือไม่
    $stmtCheck = $conn->prepare("SELECT id FROM training_projects WHERE id = ?");
    $stmtCheck->bind_param("i", $project_id);
    $stmtCheck->execute();
    $resCheck = $stmtCheck->get_result();
    if ($resCheck->num_rows === 0) {
        die('ไม่พบโครงการที่ต้องการสมัคร');
    }
    $stmtCheck->close();

    // ใช้ user_id จาก session เป็น member_id
    $member_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

    if ($member_id) {
        $stmt = $conn->prepare("INSERT INTO project_registrations (project_id, member_id, name, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $project_id, $member_id, $name);
    } else {
        $stmt = $conn->prepare("INSERT INTO project_registrations (project_id, name, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("is", $project_id, $name);
    }

    if ($stmt->execute()) {
        echo '<!DOCTYPE html><html lang="th"><head><meta charset="UTF-8"><title>สมัครสำเร็จ</title>
              <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
              </head><body class="bg-green-50 min-h-screen flex items-center justify-center">
              <div class="bg-white p-8 rounded-lg shadow-md max-w-lg w-full text-center">
              <h1 class="text-2xl font-bold mb-6 text-green-800">สมัครสำเร็จ!</h1>
              <p class="mb-6">ขอบคุณที่สมัครเข้าร่วมโครงการอบรมของเรา</p>
              <a href="trainProject_view.php" class="inline-block bg-green-600 text-white py-2 px-6 rounded hover:bg-green-700 transition">กลับไปหน้าโครงการ</a>
              </div></body></html>';
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $conn->error;
    }
    $stmt->close();
} else {
    header('Location: trainProject_view.php');
    exit;
}
