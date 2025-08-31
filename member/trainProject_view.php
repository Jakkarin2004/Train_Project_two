<?php
require_once '../config/db.php';

// ดึงข้อมูลโครงการอบรม
$sql = "SELECT id, title, description, date AS training_date, location, status AS project_status, display_order, image AS image_path 
        FROM training_projects 
        ORDER BY 
            CASE 
                WHEN display_order IS NULL OR display_order = 0 THEN 999 
                ELSE display_order 
            END ASC, 
            id DESC";
$result = $conn->query($sql);

$projects = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}

function getStatusText($status)
{
    $statusMap = [
        'open' => 'เปิดรับสมัคร',
        'closed' => 'ปิดรับสมัคร',
        'in_progress' => 'กำลังดำเนินการ',
        'completed' => 'เสร็จสิ้นแล้ว'
    ];
    return $statusMap[$status] ?? $status;
}

function formatDateForDisplay($dateString)
{
    if (!$dateString) return '';
    $date = new DateTime($dateString);
    $thaiMonths = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
    $day = $date->format('j');
    $month = $thaiMonths[$date->format('n') - 1];
    $year = $date->format('Y');
    return "{$day} {$month} {$year}";
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ข้อมูลโครงการอบรม</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <style>
        html,
        body {
            height: 100%;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body class="bg-green-50 min-h-screen font-sans flex overflow-y-auto">

    <!-- Sidebar -->
    <?php include("sidebar.php"); ?>

    <!-- Main Content -->
    <main class="flex-1 p-8 max-w-7xl mx-auto overflow-auto">
        <h1 class="text-3xl font-bold mb-10 text-green-800 flex items-center">
            รายการโครงการอบรม
        </h1>

        <?php if (count($projects) === 0): ?>
            <p class="text-center text-gray-600 mt-16">ไม่พบข้อมูลโครงการอบรม</p>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($projects as $project):
                    $imageFullPath = __DIR__ . '/../uploads/projects/' . $project['image_path'];
                ?>
                    <div class="bg-white rounded-lg shadow-md border border-green-200 flex flex-col overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="h-44 overflow-hidden rounded-t-lg">
                            <?php if (!empty($project['image_path']) && file_exists($imageFullPath)): ?>
                                <img src="../uploads/projects/<?= htmlspecialchars($project['image_path']) ?>" alt="รูปโครงการ" class="w-full h-full object-cover" />
                            <?php else: ?>
                                <div class="w-full h-full bg-green-100 flex items-center justify-center text-green-400 text-6xl font-bold rounded-t-lg">
                                    <?= strtoupper(substr($project['title'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="p-4 flex flex-col flex-grow">
                            <h2 class="text-lg font-semibold text-green-800 mb-2"><?= htmlspecialchars($project['title']) ?></h2>
                            <p class="text-green-700 text-sm line-clamp-3 flex-grow"><?= nl2br(htmlspecialchars($project['description'])) ?></p>

                            <div class="mt-4 space-y-1 text-xs text-green-600 font-medium">
                                <p><span class="font-semibold">วันที่อบรม:</span> <?= formatDateForDisplay($project['training_date']) ?></p>
                                <p><span class="font-semibold">สถานที่:</span> <?= htmlspecialchars($project['location']) ?></p>
                                <p>
                                    <span class="inline-block px-3 py-1 rounded-full font-semibold
                                    <?= $project['project_status'] === 'open' ? 'bg-green-200 text-green-800' : '' ?>
                                    <?= $project['project_status'] === 'closed' ? 'bg-red-200 text-red-800' : '' ?>
                                    <?= $project['project_status'] === 'in_progress' ? 'bg-yellow-200 text-yellow-800' : '' ?>
                                    <?= $project['project_status'] === 'completed' ? 'bg-gray-200 text-gray-700' : '' ?>">
                                        <?= getStatusText($project['project_status']) ?>
                                    </span>
                                </p>
                            </div>

                            <div class="mt-4 space-y-2">
                                <?php if ($project['project_status'] === 'open'): ?>
                                    <a href="formTrain.php?id=<?= urlencode($project['id']) ?>" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition">
                                        ลงทะเบียน
                                    </a>
                                <?php else: ?>
                                    <button disabled class="block w-full text-center bg-gray-300 text-gray-600 font-semibold py-2 rounded-lg cursor-not-allowed">
                                        ไม่สามารถลงทะเบียนได้
                                    </button>
                                <?php endif; ?>

                                <button
                                    onclick="openModal(<?= htmlspecialchars(json_encode($project)) ?>)"
                                    class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
                                    ดูข้อมูล
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Modal -->
    <!-- Modal -->
    <div id="projectModal" class="fixed inset-0 hidden z-50 overflow-y-auto bg-black/50">
        <div class="flex min-h-screen items-start justify-center p-6">
            <div class="bg-white rounded-lg max-w-3xl w-full p-6 overflow-y-auto max-h-[90vh] shadow-lg relative">
                <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-xl font-bold">&times;</button>
                <div id="modalContent"></div>
            </div>
        </div>
    </div>


    <script>
        const modal = document.getElementById('projectModal');
        const modalContent = document.getElementById('modalContent');

        function openModal(project) {
            const thaiMonths = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
            const formatDate = (dateStr) => {
                if (!dateStr) return '';
                const d = new Date(dateStr);
                return d.getDate() + ' ' + thaiMonths[d.getMonth()] + ' ' + d.getFullYear();
            };

            const statusColors = {
                open: 'bg-green-200 text-green-800',
                closed: 'bg-red-200 text-red-800',
                in_progress: 'bg-yellow-200 text-yellow-800',
                completed: 'bg-gray-200 text-gray-700'
            };

            let imageHtml = '';
            if (project.image_path) {
                imageHtml = `<img src="../uploads/projects/${project.image_path}" alt="รูปโครงการ" class="w-full h-auto object-cover rounded mb-4" />`;
            } else {
                imageHtml = `<div class="w-full h-auto bg-green-100 flex items-center justify-center text-green-400 text-8xl font-bold rounded mb-4">${project.title.charAt(0).toUpperCase()}</div>`;
            }

            let registerButton = project.project_status === 'open' ?
                `<a href="formTrain.php?id=${encodeURIComponent(project.id)}" class="mt-3 block w-full text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition">ลงทะเบียน</a>` :
                `<button disabled class="mt-3 block w-full text-center bg-gray-300 text-gray-600 font-semibold py-2 rounded-lg cursor-not-allowed">ไม่สามารถลงทะเบียนได้</button>`;

            modalContent.innerHTML = `
            ${imageHtml}
            <h2 class="text-2xl font-bold mb-2 text-red-800">${project.title}</h2>
            <p class="mb-4 whitespace-pre-line text-green-700">${project.description}</p>
            <p><span class="font-semibold">วันที่อบรม:</span> ${formatDate(project.training_date)}</p>
            <p><span class="font-semibold">สถานที่:</span> ${project.location}</p>
            <p class="mt-2 inline-block px-3 py-1 rounded-full text-xs font-semibold ${statusColors[project.project_status] || ''}">
                ${project.project_status.replace('_', ' ')}
            </p>
            ${registerButton}
        `;

            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modalContent.innerHTML = '';
        }

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });
    </script>

</body>

</html>