<?php

require_once '../../config/db.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

function addCategory($conn, $categoryName) {
    $categoryName = trim($categoryName);
    if ($categoryName === '') {
        return ["success" => false, "message" => "กรุณากรอกชื่อประเภท"];
    }
    if (strlen($categoryName) > 100) {
        return ["success" => false, "message" => "ชื่อประเภทยาวเกินไป"];
    }

    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM categories WHERE category_name = ?");
    $stmt->bind_param("s", $categoryName);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'] ?? 0;
    $stmt->close();

    if ($count > 0) {
        return ["success" => false, "message" => "ประเภทนี้มีอยู่แล้ว"];
    }

    $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
    $stmt->bind_param("s", $categoryName);
    if ($stmt->execute()) {
        $stmt->close();
        return ["success" => true, "message" => "เพิ่มประเภทสำเร็จ"];
    } else {
        $error = $stmt->error;
        $stmt->close();
        return ["success" => false, "message" => "เกิดข้อผิดพลาด: $error"];
    }
}

function getCategories($conn) {
    $categories = [];
    $result = $conn->query("SELECT id, category_name FROM categories ORDER BY category_name ASC");
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    return $categories;
}

function deleteCategory($conn, $id) {
    if ($id <= 0) {
        return ["success" => false, "message" => "ID ไม่ถูกต้อง"];
    }
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $stmt->close();
        return ["success" => true, "message" => "ลบสำเร็จ"];
    } else {
        $error = $stmt->error;
        $stmt->close();
        return ["success" => false, "message" => "ไม่สามารถลบได้: $error"];
    }
}

$action = $_REQUEST['action'] ?? '';

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    echo json_encode(addCategory($conn, $_POST['categoryName'] ?? ''));
} elseif ($action === 'get' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(["success" => true, "categories" => getCategories($conn)]);
} elseif ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    echo json_encode(deleteCategory($conn, (int)($_POST['id'] ?? 0)));
} else {
    echo json_encode(["success" => false, "message" => "คำขอไม่ถูกต้อง"]);
}

$conn->close();