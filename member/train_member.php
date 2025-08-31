<?php
ob_start(); // เริ่ม buffer output
session_start();
require_once '../config/db.php';

// Debug: ตรวจสอบพาธของ fpdf.php
$fpdfPath = realpath('../fpdf.php');
if ($fpdfPath === false) {
    die('Cannot find fpdf.php at ' . __DIR__ . '/../fpdf.php. Please check the file location.');
}
require_once $fpdfPath;

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php?error=unauthorized");
    exit();
}

$memberID = $_SESSION['user_id'];

// ดึงข้อมูลผู้ใช้ (first_name, last_name)
$userSql = "SELECT Firstname, Lastname FROM members WHERE id = ?";
$userStmt = $conn->prepare($userSql);
$userStmt->bind_param("i", $memberID);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();
// แก้ไขคีย์ให้ตรงกับฐานข้อมูล
$firstName = $user['Firstname'] ?? 'Unknown';
$lastName = $user['Lastname'] ?? 'Unknown';

// ดึงข้อมูลโครงการที่สมาชิกลงทะเบียนไว้ รวมถึงภาพ
$sql = "SELECT 
            pr.id AS registration_id,
            tp.title AS project_title,
            tp.date AS training_date,
            tp.location,
            pr.created_at,
            tp.project_status AS project_status,
            tp.image AS project_image,
            tp.image_certificate AS certificate_image
        FROM project_registrations pr
        JOIN training_projects tp ON pr.project_id = tp.id
        WHERE pr.member_id = ?
        ORDER BY pr.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $memberID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>โครงการที่คุณลงทะเบียน</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Style for the grid cards */
        .grid-item {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
            cursor: pointer;
        }
        .grid-item:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .grid-item img {
            width: 100%;
            height: 128px;
            object-fit: cover;
            border-radius: 4px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: auto;
        }
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 800px;
            min-height: 60vh;
            position: relative;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
        }
        .close {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            color: #4b5563;
            cursor: pointer;
            z-index: 10;
        }
        .close:hover {
            color: #ef4444;
        }
        .image-section img {
            width: 100%;
            max-height: 300px;
            object-fit: contain;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .text-section {
            padding-left: 10px;
        }
        .text-section h2 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #10b981;
        }
        .text-section p {
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 8px;
        }
        .download-btn {
            background-color: #10b981;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .download-btn:hover {
            background-color: #059669;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="flex-1 p-6">
        <h1 class="text-2xl font-bold text-green-600 mb-6">โครงการที่คุณลงทะเบียนไว้</h1>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4">
                <?php
                $i = 1;
                while ($row = $result->fetch_assoc()) {
                    $projectImagePath = $row['project_image'] ? "../uploads/projects/{$row['project_image']}" : "https://via.placeholder.com/150";
                    echo "<div class='grid-item' onclick=\"openModal({$row['registration_id']}, '{$row['project_title']}', '{$row['training_date']}', '{$row['location']}', '{$row['project_status']}', '{$row['created_at']}', '{$row['project_image']}', '{$row['certificate_image']}')\">";
                    echo "<img src=\"{$projectImagePath}\" alt=\"{$row['project_title']}\" class=\"w-full h-32 object-cover rounded-t-lg mb-2\">";
                    // echo "<p class='text-sm text-gray-600 mb-1'>#{$i}</p>";
                    echo "<h3 class='text-md font-semibold mb-1 line-clamp-2'>{$row['project_title']}</h3>";
                    echo "<p class='text-xs text-gray-500 mb-1'>วันที่: " . ($row['training_date'] ? date("d/m/Y", strtotime($row['training_date'])) : '-') . "</p>";
                    echo "<p class='text-xs text-gray-500 mb-1'>สถานะ: {$row['project_status']}</p>";
                    echo "</div>";
                    $i++;
                }

                if ($i === 1) {
                    echo "<div class='col-span-4 text-center text-gray-500 py-6'>ยังไม่มีการลงทะเบียนโครงการ</div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="projectModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="image-section">
            <img id="modalProjectImage" src="" alt="Project Image">
            <img id="modalCertificateImage" src="" alt="Certificate Image">
        </div>
        <div class="text-section">
            <h2 id="modalTitle"></h2>
            <p id="modalDate"></p>
            <p id="modalLocation"></p>
            <p id="modalStatus"></p>
            <p id="modalRegistered"></p>
            <button class="download-btn" onclick="downloadCertificate()">ดาวน์โหลดเกียรติบัตร</button>
        </div>
    </div>
</div>

<?php
if (isset($_POST['download_pdf'])) {
    $fpdfPath = realpath('../fpdf.php');
    if ($fpdfPath === false) {
        die('Cannot find fpdf.php at ' . __DIR__ . '/../fpdf.php. Please check the file location.');
    }
    require_once $fpdfPath;

    $title = $_POST['title'];
    $certificateImage = $_POST['certificate_image'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];

    // ใช้กระดาษ A4 แนวนอน (297 × 210 mm)
    $pdf = new FPDF('L', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont('Helvetica', 'B', 16);

    // ใส่ภาพเต็มหน้ากระดาษเลย
    if ($certificateImage) {
        $pdf->Image($certificateImage, 0, 0, 297, 210); 
    }

    // เพิ่มข้อความ (ปรับตำแหน่งเองตาม layout ของเกียรติบัตร)
    $pdf->SetXY(0, 95); 
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(297, 10, $firstName . ' ' . $lastName, 0, 1, 'C');

    $pdf->SetXY(0, 80);
    $pdf->Cell(297, 10, $title, 0, 1, 'C');

    ob_clean();
    $pdf->Output('D', 'certificate_' . time() . '.pdf');
    exit;
}
?>




<script>
    function openModal(registrationId, title, trainingDate, location, status, createdAt, projectImage, certificateImage) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalDate').textContent = 'วันที่: ' + (trainingDate ? new Date(trainingDate).toLocaleDateString('th-TH', { day: 'numeric', month: 'short', year: 'numeric' }) : '-');
        document.getElementById('modalLocation').textContent = 'สถานที่: ' + (location || '-');
        document.getElementById('modalStatus').textContent = 'สถานะ: ' + status;
        document.getElementById('modalRegistered').textContent = 'ลงทะเบียน: ' + new Date(createdAt).toLocaleString('th-TH', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });

        const projectImg = document.getElementById('modalProjectImage');
        if (projectImage) {
            projectImg.src = '../uploads/projects/' + projectImage;
            projectImg.style.display = 'block';
        } else {
            projectImg.style.display = 'none';
        }

        const certificateImg = document.getElementById('modalCertificateImage');
        if (certificateImage) {
            certificateImg.src = '../uploads/certificates/' + certificateImage;
            certificateImg.style.display = 'block';
        } else {
            certificateImg.style.display = 'none';
        }

        document.getElementById('projectModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('projectModal').style.display = 'none';
    }

    // ปิด modal เมื่อคลิกที่ overlay
    window.onclick = function(event) {
        const modal = document.getElementById('projectModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    function downloadCertificate() {
        const title = document.getElementById('modalTitle').textContent;
        const certificateImage = document.getElementById('modalCertificateImage').src;
        const firstName = '<?php echo addslashes($firstName); ?>';
        const lastName = '<?php echo addslashes($lastName); ?>';

        if (certificateImage) {
            // ส่งข้อมูลไปยังเซิร์ฟเวอร์ผ่าน Form submission
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = ''; // ใช้หน้าเดียวกัน
            form.style.display = 'none';

            const titleInput = document.createElement('input');
            titleInput.type = 'hidden';
            titleInput.name = 'title';
            titleInput.value = title;
            form.appendChild(titleInput);

            const imageInput = document.createElement('input');
            imageInput.type = 'hidden';
            imageInput.name = 'certificate_image';
            imageInput.value = certificateImage;
            form.appendChild(imageInput);

            const firstNameInput = document.createElement('input');
            firstNameInput.type = 'hidden';
            firstNameInput.name = 'first_name';
            firstNameInput.value = firstName;
            form.appendChild(firstNameInput);

            const lastNameInput = document.createElement('input');
            lastNameInput.type = 'hidden';
            lastNameInput.name = 'last_name';
            lastNameInput.value = lastName;
            form.appendChild(lastNameInput);

            const downloadInput = document.createElement('input');
            downloadInput.type = 'hidden';
            downloadInput.name = 'download_pdf';
            downloadInput.value = '1';
            form.appendChild(downloadInput);

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        } else {
            alert('ไม่มีเกียรติบัตรสำหรับดาวน์โหลด');
        }
    }
</script>

</body>
</html>