<?php
require_once __DIR__ . '/../../config/db.php';

function getUserById($conn, $userId) {
    $stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
    if (!$stmt) {
        return null;
    }
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }
    $stmt->close();
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && !isset($_GET['action'])) {
    $userId = (int)$_GET['id'];
    $user = getUserById($conn, $userId);

    header('Content-Type: application/json; charset=utf-8');
    if ($user) {
        echo json_encode([
            "success" => true,
            "user" => $user
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "success" => false,
            "message" => "ไม่พบข้อมูลผู้ใช้"
        ]);
    }
    exit;
}


// ===== 1. อัปเดตข้อมูลผู้ใช้ =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $FullName = $_POST['FullName'] ?? '';
    $Username = $_POST['Username'] ?? '';
    $Email = $_POST['Email'] ?? '';
    $role_id = (int)($_POST['role_id'] ?? 2);
    $Password = $_POST['Password'] ?? '';

    // สร้างคำสั่ง SQL แบบไดนามิก
    $fields = "FullName=?, Username=?, Email=?, role_id=?";
    $types = "sssi";
    $params = [$FullName, $Username, $Email, $role_id];

    if (!empty($Password)) {
        if (strlen($Password) < 8) {
            http_response_code(400);
            echo "รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร";
            exit;
        }

        $hashed = password_hash($Password, PASSWORD_DEFAULT);
        $fields .= ", Password=?";
        $types .= "s";
        $params[] = $hashed;
    }

    $fields .= " WHERE id=?";
    $types .= "i";
    $params[] = $id;

    $stmt = $conn->prepare("UPDATE members SET $fields");
    if (!$stmt) {
        http_response_code(500);
        echo "Prepare failed: " . $conn->error;
        exit;
    }

    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo "อัปเดตข้อมูลสำเร็จ";
    } else {
        http_response_code(500);
        echo "ไม่สามารถอัปเดตข้อมูลได้";
    }
    exit;
}


// ===== 2. ลบผู้ใช้ (ยกเว้น admin) =====
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmtCheck = $conn->prepare("SELECT role_id FROM members WHERE id=?");
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        http_response_code(404);
        echo "ไม่พบผู้ใช้";
        exit;
    }
    if ($user['role_id'] == 1) {
        http_response_code(403);
        echo "ไม่อนุญาตให้ลบแอดมิน";
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM members WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "ลบผู้ใช้สำเร็จ";
    } else {
        http_response_code(500);
        echo "ลบผู้ใช้ไม่สำเร็จ";
    }
    exit;
}

// ถ้ามี ?id=xxx ให้ดึงข้อมูลผู้ใช้รายคน
// if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
//     $id = (int)$_GET['id'];

//     $stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
//     $stmt->bind_param("i", $id);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($result && $result->num_rows === 1) {
//         $user = $result->fetch_assoc();
//         echo json_encode(["success" => true, "user" => $user]);
//     } else {
//         echo json_encode(["success" => false, "message" => "ไม่พบข้อมูลผู้ใช้"]);
//     }
//     exit;
// }

// // ถ้าไม่มี id ดึงข้อมูลผู้ใช้ทั้งหมดที่ไม่ใช่ admin (role_id != 1)
// if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//     $stmt = $conn->prepare("SELECT * FROM members WHERE role_id != 1");
//     $stmt->execute();
//     $result = $stmt->get_result();

//     $users = [];
//     if ($result) {
//         while ($row = $result->fetch_assoc()) {
//             $users[] = $row;
//         }
//     }

//     echo json_encode(["success" => true, "users" => $users]);
//     exit;
// }
// var_dump($_GET);

// ฟังก์ชันสำหรับดึงข้อมูลผู้ใช้ตาม ID
// function getUserById($conn, $userId) {
//     $stmt = $conn->prepare("SELECT FullName, Username, Email, BirthDate, Telephone, Gender, ProfileImage FROM members WHERE id = ?");
//     if (!$stmt) {
//         // กรณี prepare ผิดพลาด
//         return null;
//     }
//     $stmt->bind_param("i", $userId);
//     $stmt->execute();
//     $result = $stmt->get_result();
    
//     if ($result && $result->num_rows === 1) {
//         $user = $result->fetch_assoc();
//         $stmt->close();
//         return $user;
//     }
    
//     $stmt->close();
//     return null;
// }
// if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
//     $id = (int)$_GET['id'];
//     $user = getUserById($conn, $id);
//     if ($user) {
//         echo json_encode([
//             "success" => true,
//             "user" => $user
//         ]);
//     } else {
//         echo json_encode([
//             "success" => false,
//             "message" => "ไม่พบข้อมูลผู้ใช้"
//         ]);
//     }
//     exit;
// }

?>
