<?php
require_once '../config/db.php';
require_once '../functions/admin/manage_user.php';
// ดึงข้อมูลสมาชิกที่ไม่ใช่แอดมิน
$sql = "SELECT id, FullName, Username, Email, role_id FROM members WHERE role_id != 1";
$result = $conn->query($sql);

// แปลง role_id เป็นชื่อ
$roleNames = [
    2 => 'Expert',
    3 => 'User'
];
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }



        /* Main Content */
        .main-content {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            margin: 1rem;
        }

        /* Header */
        .header-gradient {
            /* background: linear-gradient(135deg, #1e293b 0%, #334155 100%); */
            background-color: #ff6b35;
            /* color: #ff6b35; */
            border-radius: 20px 20px 0 0;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        /* Table Styles */
        .table-container {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .table-header {
            /* background: linear-gradient(135deg, #1e293b 0%, #334155 100%); */
            background-color: #ff6b35;

            color: white;
        }

        .table-row {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-row:hover {
            background: linear-gradient(90deg, #f8fafc, #f1f5f9);
            transform: scale(1.001);
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
        }

        .btn-info {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
        }

        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(6, 182, 212, 0.4);
        }

        /* Dropdown */
        .dropdown {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .dropdown:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        /* Modal */
        .modal-content {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }

        .form-input {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Status Badge */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-expert {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .status-user {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
        }

        /* Profile Image */
        .profile-img {
            border: 3px solid white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* User Details Modal Styles */
        .info-modal {
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .profile-section {
            text-align: center;
            padding: 2rem 0;
            border-bottom: 2px solid #f1f5f9;
            margin-bottom: 2rem;
        }

        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            border: 4px solid #3b82f6;
            box-shadow: 0 8px 30px rgba(59, 130, 246, 0.3);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .info-item {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 12px;
            border-left: 4px solid #3b82f6;
        }

        .info-item label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .info-item .value {
            color: #1f2937;
            font-size: 1.1rem;
            word-wrap: break-word;
        }

        /* Loading Animation */
        .loading-spinner {
            border: 4px solid #f3f4f6;
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 2rem auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="flex min-h-screen">
        <!-- Sidebar -->

        <?php include '../includes/sidebarAdmin.php'; ?>
        <!-- Main Content -->
        <div class="flex-1">
            <div class="main-content animate-fade-in-up">
                <!-- Header -->
                <div class="header-gradient p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold text-white mb-2">
                                <i class="fas fa-users mr-3"></i>
                                User Management
                            </h1>
                            <p class="text-white">จัดการข้อมูลผู้ใช้ในระบบ</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-white font-medium">Admin Name</span>
                            <img src="https://via.placeholder.com/40" class="rounded-full w-12 h-12 profile-img" alt="Profile">
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <!-- Filter Section -->
                    <div class="card p-6 mb-8">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-filter text-blue-500 mr-2"></i>
                            <h3 class="text-lg font-semibold text-gray-800">ตัวกรองข้อมูล</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="userType" class="block font-medium mb-2 text-gray-700">เลือกประเภทผู้ใช้</label>
                                <select id="userType" class="dropdown w-full">
                                    <option value="All">ทั้งหมด</option>
                                    <option value="User">User</option>
                                    <option value="Expert">Expert</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- User Table -->
                    <div class="table-container">
                        <div class="table-header p-4">
                            <h2 class="text-xl font-semibold flex items-center">
                                <i class="fas fa-table mr-2"></i>
                                ข้อมูลสมาชิก
                            </h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="table-header">
                                    <tr>
                                        <th class="px-6 py-4 text-left font-semibold">
                                            <i class="fas fa-hashtag mr-2"></i>ID
                                        </th>
                                        <th class="px-6 py-4 text-left font-semibold">
                                            <i class="fas fa-user mr-2"></i>ชื่อ-นามสกุล
                                        </th>
                                        <th class="px-6 py-4 text-left font-semibold">
                                            <i class="fas fa-at mr-2"></i>Username
                                        </th>
                                        <th class="px-6 py-4 text-left font-semibold">
                                            <i class="fas fa-envelope mr-2"></i>Email
                                        </th>
                                        <th class="px-6 py-4 text-left font-semibold">
                                            <i class="fas fa-shield-alt mr-2"></i>สถานะ
                                        </th>
                                        <th class="px-6 py-4 text-left font-semibold">
                                            <i class="fas fa-tools mr-2"></i>จัดการ
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="userTableBody" class="bg-white">
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr class="table-row" data-role="<?= $roleNames[$row['role_id']] ?>">
                                            <td class="px-6 py-4 font-mono text-sm">
                                                <span class="bg-gray-100 px-2 py-1 rounded-lg"><?= $row['id'] ?></span>
                                            </td>
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                <?= htmlspecialchars($row['FullName']) ?>
                                            </td>
                                            <td class="px-6 py-4 text-gray-600">
                                                <?= htmlspecialchars($row['Username']) ?>
                                            </td>
                                            <td class="px-6 py-4 text-gray-600">
                                                <a href="mailto:<?= htmlspecialchars($row['Email']) ?>" class="text-blue-600 hover:underline">
                                                    <?= htmlspecialchars($row['Email']) ?>
                                                </a>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="status-badge <?= $row['role_id'] == 2 ? 'status-expert' : 'status-user' ?>">
                                                    <?= $row['role_id'] == 2 ? ' Expert' : ' User' ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex space-x-2">
                                                    <button onclick="viewUserDetails(<?= $row['id'] ?>)" class="btn-info text-sm">
                                                        <i class="fas fa-eye mr-1"></i>ดูข้อมูล
                                                    </button>
                                                    <button onclick="editUser(<?= $row['id'] ?>, '<?= htmlspecialchars($row['FullName']) ?>', '<?= htmlspecialchars($row['Username']) ?>', '<?= htmlspecialchars($row['Email']) ?>', <?= $row['role_id'] ?>)"
                                                        class="btn-primary text-sm">
                                                        <i class="fas fa-edit mr-1"></i>แก้ไข
                                                    </button>
                                                    <button onclick="deleteUser(<?= $row['id'] ?>)" class="btn-danger text-sm">
                                                        <i class="fas fa-trash mr-1"></i>ลบ
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal แก้ไข -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-60 hidden justify-center items-center z-50 backdrop-blur-sm">
        <div class="modal-content p-8 w-full max-w-md mx-4">
            <div class="flex items-center mb-6">
                <i class="fas fa-user-edit text-blue-500 text-2xl mr-3"></i>
                <h2 class="text-2xl font-bold text-gray-800">แก้ไขข้อมูลสมาชิก</h2>
            </div>
            <form id="editForm" class="space-y-4">
                <input type="hidden" name="id" id="editId">
                <div>
                    <label class="block font-medium mb-2 text-gray-700">
                        <i class="fas fa-user mr-2"></i>ชื่อ-นามสกุล
                    </label>
                    <input type="text" name="FullName" id="editFullName" class="form-input w-full">
                </div>
                <div>
                    <label class="block font-medium mb-2 text-gray-700">
                        <i class="fas fa-at mr-2"></i>Username
                    </label>
                    <input type="text" name="Username" id="editUsername" class="form-input w-full">
                </div>
                <div>
                    <label class="block font-medium mb-2 text-gray-700">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </label>
                    <input type="email" name="Email" id="editEmail" class="form-input w-full">
                </div>
                <div>
                    <label class="block font-medium mb-2 text-gray-700">
                        <i class="fas fa-shield-alt mr-2"></i>สถานะ
                    </label>
                    <select name="role_id" id="editRole" class="form-input w-full">
                        <option value="2">Expert</option>
                        <option value="3">User</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium mb-2 text-gray-700">
                        <i class="fas fa-lock mr-2"></i>รหัสผ่านใหม่ (ถ้าต้องการเปลี่ยน)
                    </label>
                    <input type="password" name="Password" id="editPassword" class="form-input w-full"
                        placeholder="กรุณากรอกรหัสผ่านอย่างน้อย 8 ตัวอักษร">
                </div>

                <div class="flex justify-end gap-3 pt-4">

                    <button type="button" onclick="closeModal()" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all duration-300">
                        <i class="fas fa-times mr-2"></i>ยกเลิก
                    </button>
                    <button type="submit" class="btn-primary px-6 py-3">
                        <i class="fas fa-save mr-2"></i>บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal ดูข้อมูลทั้งหมด -->
    <!-- Modal ดูข้อมูลทั้งหมด -->
    <div id="userDetailsModal" class="fixed inset-0 bg-black bg-opacity-60 hidden justify-center items-center z-50 backdrop-blur-sm">
        <div class="modal-content info-modal p-8 w-full mx-4">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <i class="fas fa-user-circle text-blue-500 text-3xl mr-3"></i>
                    <h2 class="text-2xl font-bold text-gray-800">ข้อมูลสมาชิกทั้งหมด</h2>
                </div>
                <button onclick="closeUserDetailsModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" style="display:none; text-align:center; margin-bottom:20px;">
                <div class="spinner-border" role="status" style="width: 3rem; height: 3rem; border-width: 0.3rem; color: #3b82f6;">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <div id="userDetailsContent">
                <!-- Profile Section -->
                <div id="profileSection" class="profile-section" style="display:none;">
                    <img id="userProfileImage" src="https://via.placeholder.com/100" alt="Profile" class="profile-image">
                    <h3 id="userProfileName" class="text-xl font-bold text-gray-800 mb-2"></h3>
                    <span id="userProfileRole" class="status-badge"></span>
                </div>

                <!-- User Details Grid -->
                <div id="userDetailsGrid" class="info-grid" style="display:none;">
                    <div class="info-item">
                        <label><i class="fas fa-hashtag mr-2"></i>Member ID</label>
                        <div class="value" id="detailMemberID"></div>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-user mr-2"></i>ชื่อ-นามสกุล</label>
                        <div class="value" id="detailFullName"></div>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-at mr-2"></i>Username</label>
                        <div class="value" id="detailUsername"></div>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-envelope mr-2"></i>Email</label>
                        <div class="value" id="detailEmail"></div>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-phone mr-2"></i>เบอร์โทรศัพท์</label>
                        <div class="value" id="detailTelephone"></div>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-birthday-cake mr-2"></i>วันเกิด</label>
                        <div class="value" id="detailBirthDate"></div>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-briefcase mr-2"></i>อาชีพ</label>
                        <div class="value" id="detailOccupation"></div>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-venus-mars mr-2"></i>เพศ</label>
                        <div class="value" id="detailGender"></div>
                    </div>
                    <div class="info-item" style="grid-column: 1 / -1;">
                        <label><i class="fas fa-map-marker-alt mr-2"></i>ที่อยู่</label>
                        <div class="value" id="detailAddress"></div>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-map mr-2"></i>ตำบล</label>
                        <div class="value" id="detailSubDistrict"></div>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-map-signs mr-2"></i>อำเภอ</label>
                        <div class="value" id="detailDistrict"></div>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-globe mr-2"></i>จังหวัด</label>
                        <div class="value" id="detailProvince"></div>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-mail-bulk mr-2"></i>รหัสไปรษณีย์</label>
                        <div class="value" id="detailZipCode"></div>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div id="modalActions" class="flex justify-end mt-6" style="display:none;">
                    <button onclick="closeUserDetailsModal()" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all duration-300">
                        <i class="fas fa-times mr-2"></i>ปิด
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteUser(userId) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: 'คุณต้องการลบผู้ใช้นี้ใช่หรือไม่',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-trash mr-2"></i>ใช่, ลบเลย!',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>ยกเลิก',
                background: '#ffffff',
                backdrop: 'rgba(0,0,0,0.8)'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`../functions/admin/manage_user.php?action=delete&id=${userId}`)

                        .then(response => response.text())
                        .then(data => {
                            Swal.fire({
                                title: 'ลบแล้ว!',
                                text: data,
                                icon: 'success',
                                confirmButtonColor: '#10b981'
                            }).then(() => {
                                location.reload();
                            });
                        })
                        .catch(err => {
                            Swal.fire({
                                title: 'เกิดข้อผิดพลาด!',
                                text: 'ไม่สามารถลบผู้ใช้ได้',
                                icon: 'error',
                                confirmButtonColor: '#ef4444'
                            });
                            console.error(err);
                        });
                }
            });
        }

        // function editUser(id, fullName, username, email, roleId) {
        //     document.getElementById('editId').value = id;
        //     document.getElementById('editFullName').value = fullName;
        //     document.getElementById('editUsername').value = username;
        //     document.getElementById('editEmail').value = email;
        //     document.getElementById('editRole').value = roleId;
        //     document.getElementById('editModal').classList.remove('hidden');
        //     document.getElementById('editModal').classList.add('flex');
        // }
        function editUser(id, fullName, username, email, roleId) {
            document.getElementById('editId').value = id;
            document.getElementById('editFullName').value = fullName;
            document.getElementById('editUsername').value = username;
            document.getElementById('editEmail').value = email;
            document.getElementById('editRole').value = roleId;

            // ล้างรหัสผ่านใหม่ทุกครั้งที่เปิด modal
            document.getElementById('editPassword').value = '';

            // แสดง modal
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }


        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }

        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('../functions/admin/manage_user.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.text())
                .then(data => {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: data,
                        icon: 'success',
                        confirmButtonColor: '#10b981'
                    }).then(() => location.reload());
                })
                .catch(err => {
                    Swal.fire({
                        title: 'ผิดพลาด!',
                        text: 'ไม่สามารถอัปเดตข้อมูลได้',
                        icon: 'error',
                        confirmButtonColor: '#ef4444'
                    });
                });
        });

        // กรองผู้ใช้
        document.getElementById('userType').addEventListener('change', function() {
            const selected = this.value;
            document.querySelectorAll('#userTableBody tr').forEach(tr => {
                const role = tr.getAttribute('data-role');
                tr.style.display = (selected === 'All' || role === selected) ? '' : 'none';
            });
        });

        // เพิ่ม animation เมื่อโหลดหน้า
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.table-row');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 50);
            });
        });


        // modal
        function viewUserDetails(userId) {
            console.log('viewUserDetails called with userId:', userId);
            const modal = document.getElementById('userDetailsModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.getElementById('loadingSpinner').style.display = 'block';
            document.getElementById('profileSection').style.display = 'none';
            document.getElementById('userDetailsGrid').style.display = 'none';
            document.getElementById('modalActions').style.display = 'none';

            fetch(`../functions/admin/manage_user.php?id=${encodeURIComponent(userId)}`)
                .then(response => {
                    console.log('Fetch status:', response.status);
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    console.log('Data from API:', data);

                    document.getElementById('loadingSpinner').style.display = 'none';

                    if (data.success) {
                        const user = data.user;

                        document.getElementById('userProfileName').textContent = user.FullName || '-';

                        let roleText = 'User';
                        let roleClass = 'status-user';
                        if (user.role_id == 2) {
                            roleText = 'Expert';
                            roleClass = 'status-expert';
                        } else if (user.role_id == 1) {
                            roleText = 'Admin';
                            roleClass = 'status-admin';
                        }
                        const roleElem = document.getElementById('userProfileRole');
                        roleElem.textContent = roleText;
                        roleElem.className = `status-badge ${roleClass}`;

                        const profileImage = document.getElementById('userProfileImage');
                        if (user.ProfileImage && user.ProfileImage.trim() !== '') {
                            profileImage.src = `../uploads/profiles/${user.ProfileImage}`;
                        } else {
                            profileImage.src = 'https://via.placeholder.com/120?text=No+Image';
                        }

                        document.getElementById('detailMemberID').textContent = user.MemberID || user.id || '-';
                        document.getElementById('detailFullName').textContent = user.FullName || '-';
                        document.getElementById('detailUsername').textContent = user.Username || '-';
                        document.getElementById('detailEmail').textContent = user.Email || '-';
                        document.getElementById('detailTelephone').textContent = user.Telephone || '-';
                        document.getElementById('detailBirthDate').textContent = user.BirthDate || '-';
                        document.getElementById('detailOccupation').textContent = user.Occupation || '-';
                        document.getElementById('detailGender').textContent = user.Gender || '-';
                        document.getElementById('detailAddress').textContent = user.Address || '-';
                        document.getElementById('detailSubDistrict').textContent = user.SubDistrict || '-';
                        document.getElementById('detailDistrict').textContent = user.District || '-';
                        document.getElementById('detailProvince').textContent = user.Province || '-';
                        document.getElementById('detailZipCode').textContent = user.ZipCode || '-';

                        // แสดงส่วนต่าง ๆ ของ modal หลังโหลดข้อมูล
                        document.getElementById('profileSection').style.display = 'block';
                        document.getElementById('userDetailsGrid').style.display = 'grid';
                        document.getElementById('modalActions').style.display = 'flex';

                    } else {
                        alert(data.message || 'ไม่สามารถดึงข้อมูลผู้ใช้ได้');
                        closeUserDetailsModal();
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    document.getElementById('loadingSpinner').style.display = 'none';
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
                    closeUserDetailsModal();
                });
        }

        function closeUserDetailsModal() {
            const modal = document.getElementById('userDetailsModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }



        // ตัวอย่างฟังก์ชันแปลงวันเกิด ให้อยู่ในรูปแบบ dd/mm/yyyy
        function formatDate(dateString) {
            const date = new Date(dateString);
            if (isNaN(date)) return '-';
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        // ตัวอย่างฟังก์ชันแปลงค่าเพศจากคำย่อ/ข้อความ
        function formatGender(gender) {
            const g = gender.toLowerCase();
            if (g === 'm' || g === 'male' || g === 'ชาย') return 'ชาย';
            if (g === 'f' || g === 'female' || g === 'หญิง') return 'หญิง';
            return gender; // คืนค่าเดิมถ้าไม่ตรงเงื่อนไข
        }
    </script>
</body>

</html>