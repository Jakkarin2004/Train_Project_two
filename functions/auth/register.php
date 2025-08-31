<?php
require_once '../../config/db.php'; // เชื่อมต่อฐานข้อมูล

$fullname = trim($_POST['fullname'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$role_id = 3; // กำหนด role สมาชิกธรรมดา

// ฟังก์ชันสร้าง MemberID แบบสุ่มไม่ซ้ำ
function generateMemberID($conn) {
    do {
        $memberID = 'MB' . time() . rand(1000, 9999);
        $stmt = $conn->prepare("SELECT id FROM members WHERE MemberID = ?");
        $stmt->bind_param("s", $memberID);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
    } while ($exists);
    return $memberID;
}

// ตรวจสอบข้อมูลครบ
if (empty($fullname) || empty($username) || empty($password) || empty($confirm_password)) {
    header("Location: /project_admin/auth/register.php?error=" . urlencode("กรุณากรอกข้อมูลให้ครบถ้วน"));
    exit();
}

// รหัสผ่านตรงกันไหม
if ($password !== $confirm_password) {
    header("Location: /project_admin/auth/register.php?error=" . urlencode("รหัสผ่านไม่ตรงกัน"));
    exit();
}

// ตรวจสอบ username ซ้ำ
$stmt = $conn->prepare("SELECT id FROM members WHERE Username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    header("Location: /project_admin/auth/register.php?error=" . urlencode("ชื่อผู้ใช้นี้มีอยู่แล้ว"));
    exit();
}
$stmt->close();

// สร้าง MemberID
$memberID = generateMemberID($conn);

// เข้ารหัสรหัสผ่าน
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// เพิ่มสมาชิกใหม่
$stmt = $conn->prepare("INSERT INTO members (MemberID, FullName, Username, Password, role_id) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $memberID, $fullname, $username, $hashedPassword, $role_id);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: /project_admin/auth/register.php?success=" . urlencode("สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ"));
    exit();
} else {
    $stmt->close();
    $conn->close();
    header("Location: /project_admin/auth/register.php?error=" . urlencode("เกิดข้อผิดพลาดในการสมัคร กรุณาลองใหม่อีกครั้ง"));
    exit();
}
