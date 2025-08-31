
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// ✅ ตรวจสอบว่าเป็น admin (role_id = 1)
function getAdminById($conn, $userId) {
    $stmt = $conn->prepare("SELECT id, FullName, Username, Email, BirthDate, Telephone, Gender, ProfileImage FROM members WHERE id = ? AND role_id = 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    $stmt->close();
    return null;
}

// ✅ อัปเดตข้อมูลผู้ใช้
function updateUser($conn, $userId, $data) {
    $fields = [];
    $params = [];
    $types  = "";

    if (!empty($data['FullName'])) {
        $fields[] = "FullName=?";
        $params[] = $data['FullName'];
        $types   .= "s";
    }

    if (!empty($data['Username'])) {
        $checkStmt = $conn->prepare("SELECT id FROM members WHERE Username = ? AND id != ?");
        $checkStmt->bind_param("si", $data['Username'], $userId);
        $checkStmt->execute();
        $checkStmt->store_result();
        if ($checkStmt->num_rows > 0) {
            return ["success" => false, "message" => "Username นี้ถูกใช้ไปแล้ว"];
        }
        $checkStmt->close();

        $fields[] = "Username=?";
        $params[] = $data['Username'];
        $types   .= "s";
    }

    if (!empty($data['Email'])) {
        $fields[] = "Email=?";
        $params[] = $data['Email'];
        $types   .= "s";
    }

    if (!empty($data['Phone'])) {
        $fields[] = "Telephone=?";
        $params[] = $data['Phone'];
        $types   .= "s";
    }

    if (!empty($data['Birthday'])) {
        $fields[] = "BirthDate=?";
        $params[] = $data['Birthday'];
        $types   .= "s";
    }

    if (!empty($data['Gender'])) {
        $fields[] = "Gender=?";
        $params[] = $data['Gender'];
        $types   .= "s";
    }

    if (!empty($data['ProfileImage'])) {
        $fields[] = "ProfileImage=?";
        $params[] = $data['ProfileImage'];
        $types   .= "s";
    }

    if (!empty($data['Password'])) {
        if (strlen($data['Password']) < 8) {
            return ["success" => false, "message" => "รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร"];
        }
        $hashed = password_hash($data['Password'], PASSWORD_DEFAULT);
        $fields[] = "Password=?";
        $params[] = $hashed;
        $types   .= "s";
    }

    if (count($fields) === 0) {
        return ["success" => false, "message" => "ไม่มีข้อมูลให้อัปเดต"];
    }

    $sql = "UPDATE members SET " . implode(", ", $fields) . " WHERE id=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return ["success" => false, "message" => "Prepare failed: " . $conn->error];
    }

    $types .= "i";
    $params[] = $userId;

    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $stmt->close();
        return ["success" => true, "message" => "อัปเดตข้อมูลสำเร็จแล้ว"];
    } else {
        $error = $stmt->error;
        $stmt->close();
        return ["success" => false, "message" => "เกิดข้อผิดพลาด: " . $error];
    }
}

// ✅ เมื่อมีการส่งฟอร์ม (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = isset($_POST['Password']) ? trim($_POST['Password']) : '';

    $data = [
        'FullName' => $_POST['FullName'] ?? '',
        'Username' => $_POST['Username'] ?? '',
        'Email' => $_POST['Email'] ?? '',
        'Phone' => $_POST['Phone'] ?? '',
        'Birthday' => $_POST['Birthday'] ?? '',
        'Gender' => $_POST['Gender'] ?? 'อื่น ๆ',
        'Password' => $password,
        'ProfileImage' => $_POST['newProfileImage'] ?? null,
    ];

    $result = updateUser($conn, $userId, $data);

    if ($result['success']) {
        $_SESSION['success'] = $result['message'];
    } else {
        $_SESSION['error'] = $result['message'];
    }

    header("Location: /project_admin/admin/account_setting.php");
    exit();
}

// ✅ โหลดข้อมูลผู้ดูแลระบบ
$user = getAdminById($conn, $userId);
if (!$user) {
    echo "ไม่พบข้อมูลผู้ดูแลระบบ หรือคุณไม่มีสิทธิ์เข้าถึง";
    exit();
}

$conn->close();


?>
