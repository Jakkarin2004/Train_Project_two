<?php
session_start();
require_once '../config/db.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php?error=unauthorized");
    exit();
}
if (!isset($_GET['id'], $_GET['portfolio'])) {
    die("ข้อมูลไม่ครบ");
}

$imageID = intval($_GET['id']);
$portfolioID = intval($_GET['portfolio']);
$memberID = $_SESSION['user_id']; // กำหนด memberID จาก session

// ตรวจสอบสิทธิ์ก่อนลบภาพ
$stmt = $conn->prepare("SELECT pi.ImageURL FROM portfolio_images pi JOIN performance p ON pi.PortfolioID = p.PortfolioID WHERE pi.ImageID = ? AND p.MemberID = ?");
$stmt->bind_param("ii", $imageID, $memberID);
$stmt->execute();
$result = $stmt->get_result();
$image = $result->fetch_assoc();
$stmt->close();

if (!$image) {
    die("ไม่พบรูปภาพนี้หรือคุณไม่มีสิทธิ์ลบ");
}

// ลบไฟล์ภาพ
$filePath = "../" . $image['ImageURL'];
if (file_exists($filePath)) {
    unlink($filePath);
}

// ลบข้อมูลใน DB
$stmt = $conn->prepare("DELETE FROM portfolio_images WHERE ImageID = ?");
$stmt->bind_param("i", $imageID);
$stmt->execute();
$stmt->close();

header("Location: works_edit.php?id=" . $portfolioID);
exit();
?>