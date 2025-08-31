<?php
session_start();
require_once '../config/db.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php?error=unauthorized");
    exit();
}

$memberID = $_SESSION['user_id']; // กำหนด memberID จาก session

if (!isset($_GET['id'])) {
    die("ไม่พบรหัสผลงาน");
}

$portfolioID = intval($_GET['id']);

// ตรวจสอบว่าผลงานนี้เป็นของผู้ใช้จริงไหม
$stmtCheck = $conn->prepare("SELECT PortfolioID FROM performance WHERE PortfolioID = ? AND MemberID = ?");
$stmtCheck->bind_param("ii", $portfolioID, $memberID);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();
if ($resultCheck->num_rows === 0) {
    die("ไม่พบผลงานหรือไม่มีสิทธิ์ลบผลงานนี้");
}
$stmtCheck->close();

// ลบรูปภาพที่เกี่ยวข้องก่อน
$stmt = $conn->prepare("SELECT ImageURL FROM portfolio_images WHERE PortfolioID = ?");
$stmt->bind_param("i", $portfolioID);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $filePath = "../" . $row['ImageURL'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}
$stmt->close();

// ลบข้อมูลรูปภาพในฐานข้อมูล
$stmt = $conn->prepare("DELETE FROM portfolio_images WHERE PortfolioID = ?");
$stmt->bind_param("i", $portfolioID);
$stmt->execute();
$stmt->close();

// ลบผลงาน
$stmt = $conn->prepare("DELETE FROM performance WHERE PortfolioID = ? AND MemberID = ?");
$stmt->bind_param("ii", $portfolioID, $memberID);
if ($stmt->execute()) {
    $stmt->close();
    header("Location: works_list.php?msg=delete_success");
    exit();
} else {
    $stmt->close();
    die("ลบผลงานล้มเหลว: " . $conn->error);
}
?>
