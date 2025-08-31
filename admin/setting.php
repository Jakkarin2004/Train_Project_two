<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>การตั้งค่า</title>
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
        background-color:  #ff6b35;
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

    /* Form Styles */
    .form-input {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem;
        transition: all 0.3s ease;
        width: 100%;
    }

    .form-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        cursor: pointer;
        font-weight: 500;
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
        cursor: pointer;
        font-weight: 500;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
    }

    /* Category Item */
    .category-item {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        transition: all 0.3s ease;
        margin-bottom: 0.5rem;
    }

    .category-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border-color: #cbd5e1;
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

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-slide-in {
        animation: slideInRight 0.4s ease-out;
    }

    /* Profile Image */
    .profile-img {
        border: 3px solid white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Success/Error Messages */
    .message {
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        font-weight: 500;
    }

    .message-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .message-error {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
  </style>
</head>
<body>
  <div class="flex min-h-screen">
    
    <!-- Sidebar -->
    <!-- <aside class="w-64 sidebar p-6 space-y-2 sticky top-0 h-screen">
      <div class="mb-8">
        <h2 class="text-2xl font-bold text-white mb-2">
          <i class="fas fa-crown mr-2 text-yellow-400"></i>
          Admin Panel
        </h2>
        <div class="w-full h-1 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full"></div>
      </div>
      <ul class="space-y-1">
        <li><a href="admin_dashbord.html" class="sidebar-link">
          <i class="fas fa-chart-pie"></i>Dashboard</a></li>
        <li><a href="../User/index.html" class="sidebar-link">
          <i class="fas fa-home"></i>Home</a></li>
        <li><a href="userdata.html" class="sidebar-link">
          <i class="fas fa-users"></i>User Data</a></li>
        <li><a href="training_project.html" class="sidebar-link">
          <i class="fas fa-graduation-cap"></i>ข้อมูลโครงการอบรม</a></li>
        <li><a href="leaning.html" class="sidebar-link">
          <i class="fas fa-book-open"></i>แหล่งการเรียนรู้</a></li>
        <li><a href="performance.html" class="sidebar-link">
          <i class="fas fa-chart-line"></i>ผลงานและบริการ</a></li>
        <li><a href="consultation.html" class="sidebar-link">
          <i class="fas fa-comments"></i>การให้คำปรึกษา</a></li>
        <li><a href="contact.html" class="sidebar-link">
          <i class="fas fa-address-book"></i>ข้อมูลการติดต่อ</a></li>
        <li><a href="account_setting.html" class="sidebar-link">
          <i class="fas fa-user-cog"></i>การตั้งค่าบัญชี</a></li>
        <li><a href="setting.html" class="sidebar-link">
          <i class="fas fa-cog"></i>การตั้งค่า</a></li>
      </ul>
    </aside> -->
                <?php include '../includes/sidebarAdmin.php'; ?>

    <!-- Main Content -->
    <div class="flex-1">
      <div class="main-content animate-fade-in-up">
        <!-- Header -->
        <div class="header-gradient p-6">
          <div class="flex justify-between items-center">
            <div>
              <h1 class="text-3xl font-bold text-white mb-2">
                <i class="fas fa-cog mr-3"></i>
                การตั้งค่าประเภทผลงานและบริการ
              </h1>
              <p class="text-white">จัดการประเภทผลงานและบริการต่างๆ ในระบบ</p>
            </div>
            <div class="flex items-center space-x-4">
              <span class="text-white font-medium">Admin Name</span>
              <img src="https://via.placeholder.com/40" class="rounded-full w-12 h-12 profile-img" alt="Profile">
            </div>
          </div>
        </div>

        <div class="p-8">
          <!-- Add Category Form -->
          <div class="card p-6 mb-8">
            <div class="flex items-center mb-6">
              <i class="fas fa-plus-circle text-blue-500 text-2xl mr-3"></i>
              <h2 class="text-2xl font-bold text-gray-800">เพิ่มประเภทใหม่</h2>
            </div>
            <form class="space-y-4" onsubmit="addCategory(event)">
              <div>
                <label class="block font-medium mb-2 text-gray-700">
                  <i class="fas fa-tag mr-2"></i>ชื่อประเภท
                </label>
                <input type="text" id="newCategory" class="form-input" placeholder="พิมพ์ชื่อประเภทที่ต้องการเพิ่ม">
              </div>
              <div class="flex justify-end">
                <button type="submit" class="btn-primary">
                  <i class="fas fa-plus mr-2"></i>เพิ่มประเภท
                </button>
              </div>
            </form>
          </div>

          <!-- Categories List -->
          <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center">
                <i class="fas fa-list text-green-500 text-2xl mr-3"></i>
                <h2 class="text-2xl font-bold text-gray-800">รายการประเภททั้งหมด</h2>
              </div>
              <!-- <div class="flex items-center text-gray-600">
                <i class="fas fa-info-circle mr-2"></i>
                <span id="categoryCount">3 รายการ</span>
              </div> -->
            </div>
            
            <div id="categoryList" class="space-y-3">
              <div class="category-item flex justify-between items-center">
                <div class="flex items-center">
                  
                  <span class="font-medium text-gray-800">แอปพลิเคชัน</span>
                </div>
                <button onclick="removeCategory(this)" class="btn-danger text-sm">
                  <i class="fas fa-trash mr-1"></i>ลบ
                </button>
              </div>
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="text-center py-12 hidden">
              <i class="fas fa-inbox text-gray-400 text-6xl mb-4"></i>
              <h3 class="text-xl font-semibold text-gray-600 mb-2">ไม่มีประเภทในระบบ</h3>
              <p class="text-gray-500">เพิ่มประเภทใหม่เพื่อเริ่มต้นใช้งาน</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>


   // โหลดรายการประเภทเมื่อโหลดหน้า
    document.addEventListener('DOMContentLoaded', loadCategories);

    async function loadCategories() {
      const res = await fetch('../functions/admin/setting.php?action=get');
      const data = await res.json();

      const categoryList = document.getElementById('categoryList');
      categoryList.innerHTML = '';

      if (data.success) {
        data.categories.forEach(cat => {
          const div = document.createElement('div');
          div.className = "category-item flex justify-between items-center bg-white p-3 rounded shadow";
          div.innerHTML = `
            <span class="text-gray-800 font-medium">${cat.category_name}</span>
            <button onclick="deleteCategory(${cat.id}, this)" class="bg-red-500 text-white px-3 py-1 text-sm rounded">ลบ</button>
          `;
          categoryList.appendChild(div);
        });
      }
    }

     // เพิ่มประเภท
    async function addCategory(event) {
      event.preventDefault();
      const input = document.getElementById('newCategory');
      const value = input.value.trim();

      if (value === '') {
        Swal.fire('กรุณากรอกชื่อประเภท', '', 'warning');
        return;
      }

      const formData = new FormData();
      formData.append('action', 'add');
      formData.append('categoryName', value);

      const res = await fetch('../functions/admin/setting.php', {
        method: 'POST',
        body: formData
      });

      const data = await res.json();

      if (data.success) {
        Swal.fire('เพิ่มสำเร็จ', '', 'success');
        input.value = '';
        loadCategories();
      } else {
        Swal.fire('ผิดพลาด', data.message || '', 'error');
      }
    }




 
    // ลบประเภท
    async function deleteCategory(id, btn) {
      const confirm = await Swal.fire({
        title: 'แน่ใจหรือไม่?',
        text: 'ต้องการลบประเภทนี้ใช่หรือไม่?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'ใช่, ลบเลย!',
        cancelButtonText: 'ยกเลิก'
      });

      if (!confirm.isConfirmed) return;

      const formData = new FormData();
      formData.append('action', 'delete');
      formData.append('id', id);

      const res = await fetch('../functions/admin/setting.php', {
        method: 'POST',
        body: formData
      });

      const data = await res.json();
      if (data.success) {
        Swal.fire('ลบแล้ว', '', 'success');
        btn.parentElement.remove();
      } else {
        Swal.fire('ผิดพลาด', data.message || '', 'error');
      }
    }



    // เพิ่ม animation เมื่อโหลดหน้า
    document.addEventListener('DOMContentLoaded', function() {
      const categories = document.querySelectorAll('.category-item');
      categories.forEach((category, index) => {
        category.style.opacity = '0';
        category.style.transform = 'translateY(20px)';
        setTimeout(() => {
          category.style.transition = 'all 0.3s ease';
          category.style.opacity = '1';
          category.style.transform = 'translateY(0)';
        }, index * 100);
      });
    });

    // เพิ่ม Enter key support
    document.getElementById('newCategory').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        addCategory(e);
      }
    });
  </script>
</body>
</html>