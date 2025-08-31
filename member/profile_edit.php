<?php
session_start();
require_once("../config/db.php");

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php?error=unauthorized");
    exit();
}

$userId = $_SESSION['user_id'];

// ดึงข้อมูลสมาชิก
$stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname  = trim($_POST['firstname']);
    $lastname   = trim($_POST['lastname']);
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $telephone  = trim($_POST['telephone']);
    $birthdate  = $_POST['birthdate'];
    $occupation = trim($_POST['occupation']);
    $gender     = $_POST['gender'];
    $address    = trim($_POST['address']);
    $subdistrict = trim($_POST['subdistrict']);
    $district   = trim($_POST['district']);
    $province   = trim($_POST['province']);
    $zipcode    = trim($_POST['zipcode']);
    $oldImage   = $_POST['oldImage'] ?? '';
    $imagePath  = $oldImage;

    // ตรวจสอบชื่อผู้ใช้ซ้ำ
    $checkStmt = $conn->prepare("SELECT id FROM members WHERE Username = ? AND id != ?");
    $checkStmt->bind_param("si", $username, $userId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    if ($checkResult->num_rows > 0) {
        header("Location: profile_edit.php?error=username");
        exit();
    }
    $checkStmt->close();

    // จัดการรูปโปรไฟล์
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == 0) {
        echo '<pre>';
print_r($_FILES['profileImage']);
echo '</pre>';

        if (!in_array($_FILES['profileImage']['type'], $allowedTypes)) {
            header("Location: profile_edit.php?error=filetype");
            exit();
        }

        if ($_FILES['profileImage']['size'] > 2 * 1024 * 1024) {
            header("Location: profile_edit.php?error=filesize");
            exit();
        }

        $imageName = time() . "_" . basename($_FILES['profileImage']['name']);
        $targetPath = "../image/" . $imageName;

        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $targetPath)) {
            $imagePath = "image/" . $imageName;
            if ($oldImage && file_exists("../" . $oldImage)) {
                unlink("../" . $oldImage);
            }
        } else {
            header("Location: profile_edit.php?error=uploadfail");
            exit();
        }
    }

    // อัปเดตฐานข้อมูล
    $stmt = $conn->prepare("UPDATE members 
        SET Firstname=?, Lastname=?, ProfileImage=?, Username=?, Email=?, Telephone=?, 
            BirthDate=?, Occupation=?, Gender=?, Address=?, SubDistrict=?, District=?, Province=?, ZipCode=?
        WHERE id=?");

    $stmt->bind_param(
        "ssssssssssssssi",
        $firstname, $lastname, $imagePath, $username, $email, $telephone,
        $birthdate, $occupation, $gender, $address, $subdistrict, $district, $province, $zipcode,
        $userId
    );

    if ($stmt->execute()) {
        header("Location: profile.php?success=1");
        exit();
    } else {
        header("Location: profile_edit.php?error=update");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8" />
    <title>แก้ไขข้อมูลส่วนตัว</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">

<?php include("sidebar.php"); ?>

<main class="flex-1 max-w-4xl mx-auto p-4">
    <h2 class="text-3xl font-bold text-green-700 mb-8">แก้ไขข้อมูลส่วนตัว</h2>

    <form action="" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md space-y-6">
        <!-- ชื่อ - นามสกุล - ชื่อผู้ใช้ -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="firstname" class="block font-semibold text-gray-700">ชื่อ</label>
                <input type="text" name="firstname" value="<?= htmlspecialchars($member['Firstname']) ?>" required class="w-full border rounded px-3 py-2" />
            </div>
            <div>
                <label for="lastname" class="block font-semibold text-gray-700">นามสกุล</label>
                <input type="text" name="lastname" value="<?= htmlspecialchars($member['Lastname']) ?>" required class="w-full border rounded px-3 py-2" />
            </div>
            <div>
                <label for="username" class="block font-semibold text-gray-700">ชื่อผู้ใช้</label>
                <input type="text" name="username" value="<?= htmlspecialchars($member['Username']) ?>" required class="w-full border rounded px-3 py-2" />
            </div>
            <div>
                <label for="email" class="block font-semibold text-gray-700">อีเมล</label>
                <input type="email" name="email" value="<?= htmlspecialchars($member['Email']) ?>" class="w-full border rounded px-3 py-2" />
            </div>
            <div>
                <label for="telephone" class="block font-semibold text-gray-700">เบอร์โทร</label>
                <input type="text" name="telephone" value="<?= htmlspecialchars($member['Telephone']) ?>" class="w-full border rounded px-3 py-2" />
            </div>
            <div>
                <label for="birthdate" class="block font-semibold text-gray-700">วันเกิด</label>
                <input type="date" name="birthdate" value="<?= htmlspecialchars($member['BirthDate']) ?>" class="w-full border rounded px-3 py-2" />
            </div>
            <div>
                <label for="occupation" class="block font-semibold text-gray-700">อาชีพ</label>
                <input type="text" name="occupation" value="<?= htmlspecialchars($member['Occupation']) ?>" class="w-full border rounded px-3 py-2" />
            </div>
            <div>
                <label for="gender" class="block font-semibold text-gray-700">เพศ</label>
                <select name="gender" class="w-full border rounded px-3 py-2">
                    <option value="ชาย" <?= $member['Gender'] === 'ชาย' ? 'selected' : '' ?>>ชาย</option>
                    <option value="หญิง" <?= $member['Gender'] === 'หญิง' ? 'selected' : '' ?>>หญิง</option>
                    <option value="อื่น ๆ" <?= $member['Gender'] === 'อื่น ๆ' ? 'selected' : '' ?>>อื่น ๆ</option>
                </select>
            </div>
        </div>

        <!-- ที่อยู่ -->
        <div>
            <label class="block font-semibold text-gray-700">ที่อยู่</label>
            <textarea name="address" rows="3" class="w-full border rounded px-3 py-2"><?= htmlspecialchars($member['Address']) ?></textarea>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <input name="subdistrict" placeholder="ตำบล" value="<?= htmlspecialchars($member['SubDistrict']) ?>" class="border rounded px-3 py-2" />
            <input name="district" placeholder="อำเภอ" value="<?= htmlspecialchars($member['District']) ?>" class="border rounded px-3 py-2" />
            <input name="province" placeholder="จังหวัด" value="<?= htmlspecialchars($member['Province']) ?>" class="border rounded px-3 py-2" />
            <input name="zipcode" placeholder="รหัสไปรษณีย์" value="<?= htmlspecialchars($member['ZipCode']) ?>" class="border rounded px-3 py-2" />
        </div>

        <!-- รูป -->
        <div>
            <label class="block font-semibold text-gray-700">รูปโปรไฟล์</label>
            <?php if ($member['ProfileImage']): ?>
                <img src="../<?= htmlspecialchars($member['ProfileImage']) ?>" class="w-24 h-24 rounded-full object-cover mb-2 border" />
            <?php endif; ?>
            <input type="file" name="profileImage" class="w-full" />
            <input type="hidden" name="oldImage" value="<?= htmlspecialchars($member['ProfileImage']) ?>" />
        </div>

        <div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded">บันทึก</button>
        </div>
    </form>

    <p class="mt-6">
        <a href="profile.php" class="text-green-600 hover:underline">← กลับไปหน้าข้อมูลส่วนตัว</a>
    </p>
</main>

<?php if (isset($_GET['error'])): ?>
<script>
    const error = "<?= $_GET['error'] ?>";
    let message = "เกิดข้อผิดพลาด";
    switch (error) {
        case "username": message = "ชื่อผู้ใช้นี้ถูกใช้ไปแล้ว"; break;
        case "filetype": message = "ต้องเป็นไฟล์ JPG, PNG หรือ GIF เท่านั้น"; break;
        case "filesize": message = "ขนาดไฟล์ต้องไม่เกิน 2MB"; break;
        case "uploadfail": message = "อัปโหลดรูปภาพล้มเหลว"; break;
        case "update": message = "อัปเดตข้อมูลไม่สำเร็จ"; break;
    }
    Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: message });
</script>
<?php endif; ?>

</body>
</html>
