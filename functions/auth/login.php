<?php
session_start();
require_once '../../config/db.php'; // เชื่อมต่อฐานข้อมูล

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    header("Location: login.php?error=2"); // กรอกข้อมูลไม่ครบ
    exit();
}

// ป้องกัน SQL Injection ด้วย prepared statement
$stmt = $conn->prepare("SELECT id, MemberID, FullName, Password, role_id FROM members WHERE Username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // ตรวจสอบรหัสผ่าน
    if (password_verify($password, $user['Password'])) {
        // ตั้ง session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['full_name'] = $user['FullName'];

        // ปิด statement
        $stmt->close();

        // แยกเส้นทางตาม role
        switch ($user['role_id']) {
            case 1:
                header("Location: /project_admin/admin/account_setting.php");
                break;
            case 2:
                header("Location: /project_admin/expert/account_expert.php");
                break;
            case 3:
                header("Location: /project_admin/member/profile.php");
                break;
            default:
                session_destroy();
                header("Location: /project_admin/auth/login.php?error=1");
                break;
        }
        exit();
    }
}

// หากไม่ผ่านการตรวจสอบรหัสผ่านหรือไม่พบ user
$stmt->close();
header("Location: /project_admin/auth/login.php?error=1"); // ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง
exit();


// session_start();
// require_once '../../config/db.php';
// require_once __DIR__ . '/../../vendor/autoload.php';

// use Firebase\JWT\JWT;

// $username = $_POST['username'] ?? '';
// $password = $_POST['password'] ?? '';

// if (empty($username) || empty($password)) {
//     header("Location: login.php?error=2");
//     exit();
// }

// $stmt = $conn->prepare("SELECT id, FullName, Password, role_id FROM members WHERE Username = ?");
// $stmt->bind_param("s", $username);
// $stmt->execute();
// $result = $stmt->get_result();

// if ($result->num_rows === 1) {
//     $user = $result->fetch_assoc();

//     if (password_verify($password, $user['Password'])) {
//         // สร้าง JWT token
//         $secretKey  = 'your_secret_key_here'; // ตั้งค่า secret key ให้เหมาะสม
//         $issuedAt   = time();
//         $expire     = $issuedAt + 3600*24; // token หมดอายุ 24 ชม.
//         $payload = [
//             'iat' => $issuedAt,
//             'exp' => $expire,
//             'userId' => $user['id'],
//             'role_id' => $user['role_id'],
//             'full_name' => $user['FullName']
//         ];

//         $jwt = JWT::encode($payload, $secretKey, 'HS256');

//         // เก็บ token ไว้ใน cookie หรือส่งใน response (ตัวอย่างใช้ cookie)
//         setcookie('token', $jwt, $expire, '/', '', false, true);

//         // แยกเส้นทางตาม role
//         switch ($user['role_id']) {
//             case 1:
//                 header("Location: /project_admin/admin/account_setting.php");
//                 break;
//             case 2:
//                 header("Location: /project_admin/pages/member/index.php");
//                 break;
//             default:
//                 header("Location: /project_admin/auth/login.php?error=1");
//                 break;
//         }
//         exit();
//     }
// }

// $stmt->close();
// header("Location: /project_admin/auth/login.php?error=1");
// exit();