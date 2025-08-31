<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';

// ตรวจสอบการล็อกอิน
$userId = $_SESSION['user_id'] ?? 0;
if ($userId <= 0) {
    echo "กรุณาเข้าสู่ระบบก่อน";
    exit;
}

// ฟังก์ชันอัปเดตข้อมูลผู้ใช้
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
        // ตรวจสอบซ้ำ
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
    if (!empty($data['Occupation'])) {
        $fields[] = "Occupation=?";
        $params[] = $data['Occupation'];
        $types   .= "s";
    }
    if (!empty($data['Address'])) {
        $fields[] = "Address=?";
        $params[] = $data['Address'];
        $types   .= "s";
    }
    if (!empty($data['District'])) {
        $fields[] = "District=?";
        $params[] = $data['District'];
        $types   .= "s";
    }
    if (!empty($data['SubDistrict'])) {
        $fields[] = "SubDistrict=?";
        $params[] = $data['SubDistrict'];
        $types   .= "s";
    }
    if (!empty($data['Province'])) {
        $fields[] = "Province=?";
        $params[] = $data['Province'];
        $types   .= "s";
    }
    if (!empty($data['ZipCode'])) {
        $fields[] = "ZipCode=?";
        $params[] = $data['ZipCode'];
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

// ถ้าเป็นการส่งข้อมูลอัปเดตจากฟอร์ม (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'FullName' => $_POST['FullName'] ?? '',
        'Username' => $_POST['Username'] ?? '',
        'Email' => $_POST['Email'] ?? '',
        'Phone' => $_POST['Phone'] ?? '',
        'Birthday' => $_POST['Birthday'] ?? '',
        'Gender' => $_POST['Gender'] ?? '',
        'Occupation' => $_POST['Occupation'] ?? '',
        'Address' => $_POST['Address'] ?? '',
        'District' => $_POST['District'] ?? '',
        'SubDistrict' => $_POST['SubDistrict'] ?? '',
        'Province' => $_POST['Province'] ?? '',
        'ZipCode' => $_POST['ZipCode'] ?? '',
        'ProfileImage' => $_POST['newProfileImage'] ?? null,
        'Password' => trim($_POST['Password'] ?? ''),
    ];

    $result = updateUser($conn, $userId, $data);

    if ($result['success']) {
        $_SESSION['success'] = $result['message'];
    } else {
        $_SESSION['error'] = $result['message'];
    }

     header("Location: /project_admin/expert/account_expert.php");
    exit;
}

// ถ้าไม่ใช่ POST ให้ดึงข้อมูลมาแสดงในฟอร์ม

$stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    $fullName = $user['FullName'];
    $username = $user['Username'];
    $email = $user['Email'];
    $phone = $user['Telephone'];
    $birthday = $user['BirthDate'];
    $gender = $user['Gender'];
    $occupation = $user['Occupation'];
    $address = $user['Address'];
    $district = $user['District'];
    $subDistrict = $user['SubDistrict'];
    $province = $user['Province'];
    $zipCode = $user['ZipCode'];
    $roleId = $user['role_id'];
} else {
    echo "ไม่พบข้อมูลผู้ใช้";
    exit;
}
?>
