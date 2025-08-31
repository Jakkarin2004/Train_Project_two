<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>แหล่งการเรียนรู้</title>
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

    /* Sidebar Styles */

    /* Main Content */
    .main-content {
      background: white;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
      margin: 1rem;
    }

    /* Header */
    .header-gradient {
      background-color: #ff6b35;
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

    .video-card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      border: 1px solid #f1f5f9;
      overflow: hidden;
      transition: all 0.3s ease;
    }

    .video-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    /* Form Styles */
    .form-input,
    .form-textarea {
      border: 2px solid #e2e8f0;
      border-radius: 10px;
      padding: 0.75rem;
      transition: all 0.3s ease;
      width: 100%;
    }

    .form-input:focus,
    .form-textarea:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
      outline: none;
    }

    /* Buttons */
    .btn-primary {
      /* background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); */
      background-color: #ff6b35;
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

    .btn-success {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
      border: none;
      border-radius: 10px;
      padding: 0.75rem 1.5rem;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
      cursor: pointer;
      font-weight: 500;
    }

    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    }

    .btn-edit {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      color: white;
      border: none;
      border-radius: 8px;
      padding: 0.5rem 1rem;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
      cursor: pointer;
      font-weight: 500;
      font-size: 0.875rem;
    }

    .btn-edit:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
    }

    .btn-danger {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
      border: none;
      border-radius: 8px;
      padding: 0.5rem 1rem;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
      cursor: pointer;
      font-weight: 500;
      font-size: 0.875rem;
    }

    .btn-danger:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
    }

    /* Profile Image */
    .profile-img {
      border: 3px solid white;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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

    @keyframes slideInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .animate-slide-in {
      animation: slideInUp 0.4s ease-out;
    }

    /* Video iframe styles */
    .video-container {
      position: relative;
      width: 100%;
      height: 200px;
      background: #f1f5f9;
      border-radius: 12px 12px 0 0;
      overflow: hidden;
    }

    .video-container iframe {
      width: 100%;
      height: 100%;
      border: none;
    }

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(4px);
    }

    .modal-content {
      background: white;
      margin: 5% auto;
      padding: 0;
      border-radius: 16px;
      width: 90%;
      max-width: 600px;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
      /* background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); */
      background-color: #ff6b35;
      color: white;
      padding: 1.5rem;
      border-radius: 16px 16px 0 0;
    }

    .close {
      color: white;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
      line-height: 1;
    }

    .close:hover {
      opacity: 0.7;
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
                <i class="fas fa-book-open mr-3"></i>
                แหล่งการเรียนรู้
              </h1>
              <p class="text-white">จัดการวิดีโอและเนื้อหาการเรียนรู้สำหรับผู้ใช้งาน</p>
            </div>
            <div class="flex items-center space-x-4">
              <span class="text-white font-medium">Admin Name</span>
              <img src="https://via.placeholder.com/40" class="rounded-full w-12 h-12 profile-img" alt="Profile">
            </div>
          </div>
        </div>

        <div class="p-8">
          <!-- Action Button -->
          <div class="flex justify-between items-center mb-8">
            <div class="flex items-center">
              <i class="fas fa-video text-blue-500 text-2xl mr-3"></i>
              <h2 class="text-2xl font-bold text-gray-800">วิดีโอการเรียนรู้ทั้งหมด</h2>
            </div>
            <button onclick="openAddModal()" class="btn-success">
              <i class="fas fa-plus mr-2"></i>เพิ่มวิดีโอใหม่
            </button>
          </div>

          <!-- Learning Resources Grid -->
          <!-- <div id="videoGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
           
            <div class="video-card animate-slide-in">
              <div class="video-container">
                <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" allowfullscreen></iframe>
              </div>
              <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-2">การพัฒนาเว็บเบื้องต้น</h3>
                <p class="text-gray-600 mb-4 text-sm leading-relaxed">เรียนรู้พื้นฐาน HTML, CSS และ JavaScript เพื่อสร้างเว็บไซต์ที่สวยงามและใช้งานได้จริง</p>
                <div class="flex justify-end gap-2">
                  <button onclick="editVideo(1)" class="btn-edit">
                    <i class="fas fa-edit mr-1"></i>แก้ไข
                  </button>
                  <button onclick="deleteVideo(1)" class="btn-danger">
                    <i class="fas fa-trash mr-1"></i>ลบ
                  </button>
                </div>
              </div>
            </div>


          </div> -->
          <div id="videoGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- วิดีโอจะถูกเพิ่มที่นี่โดย JS -->
          </div>

          <!-- Empty State (hidden by default) -->
          <div id="emptyState" class="text-center py-16 hidden">
            <i class="fas fa-video text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">ยังไม่มีวิดีโอในระบบ</h3>
            <p class="text-gray-500 mb-4">เพิ่มวิดีโอการเรียนรู้เพื่อให้ผู้ใช้เข้าถึงเนื้อหาที่มีคุณภาพ</p>
            <button onclick="openAddModal()" class="btn-primary">
              <i class="fas fa-plus mr-2"></i>เพิ่มวิดีโอแรก
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Add/Edit Video Modal -->
  <!-- <div id="videoModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 class="text-xl font-bold">
          <i class="fas fa-video mr-2"></i>
          <span id="modalTitle">เพิ่มวิดีโอใหม่</span>
        </h2>
      </div>
      <form class="p-6 space-y-4" onsubmit="submitVideo(event)">
        <div>
          <label class="block font-medium mb-2 text-gray-700">
            <i class="fas fa-heading mr-2"></i>ชื่อวิดีโอ
          </label>
          <input type="text" id="videoTitle" class="form-input" placeholder="พิมพ์ชื่อวิดีโอ" required>
        </div>
        
        <div>
          <label class="block font-medium mb-2 text-gray-700">
            <i class="fas fa-align-left mr-2"></i>คำอธิบาย
          </label>
          <textarea id="videoDescription" class="form-textarea" rows="3" placeholder="พิมพ์คำอธิบายวิดีโอ" required></textarea>
        </div>
        
        <div>
          <label class="block font-medium mb-2 text-gray-700">
            <i class="fas fa-link mr-2"></i>URL วิดีโอ YouTube
          </label>
          <input type="url" id="videoUrl" class="form-input" placeholder="https://www.youtube.com/watch?v=..." required>
          <p class="text-sm text-gray-500 mt-1">วางลิงค์ YouTube ของวิดีโอที่ต้องการเพิ่ม</p>
        </div>
        
        <div class="flex justify-end gap-3 pt-4">
          <button type="button" onclick="closeModal()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            ยกเลิก
          </button>
          <button type="submit" class="btn-primary">
            <i class="fas fa-save mr-2"></i>
            <span id="submitButtonText">บันทึก</span>
          </button>
        </div>
      </form>
    </div>
  </div> -->
  <div id="videoModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 class="text-xl font-bold">
          <i class="fas fa-video mr-2"></i>
          <span id="modalTitle">เพิ่มวิดีโอใหม่</span>
        </h2>
      </div>

      <form class="p-6 space-y-4" onsubmit="submitVideo(event)">
        <div>
          <label class="block font-medium mb-2 text-gray-700">
            <i class="fas fa-heading mr-2"></i>ชื่อวิดีโอ
          </label>
          <input type="text" id="videoTitle" class="form-input" placeholder="พิมพ์ชื่อวิดีโอ" required>
        </div>

        <div>
          <label class="block font-medium mb-2 text-gray-700">
            <i class="fas fa-align-left mr-2"></i>คำอธิบาย
          </label>
          <textarea id="videoDescription" class="form-textarea" rows="3" placeholder="พิมพ์คำอธิบายวิดีโอ" required></textarea>
        </div>

        <!-- Video Type Selection -->
        <div>
          <label class="block font-medium mb-3 text-gray-700">
            <i class="fas fa-video mr-2"></i>ประเภทวิดีโอ
          </label>
          <div class="flex gap-3 mb-4">
            <label class="flex items-center space-x-2 cursor-pointer p-3 border rounded-lg hover:bg-gray-50 transition-colors video-type-option" onclick="selectVideoType('upload')">
              <input type="radio" name="videoType" value="upload" class="video-type-radio" checked>
              <i class="fas fa-upload text-blue-500"></i>
              <span class="font-medium">อัปโหลดไฟล์วิดีโอ</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer p-3 border rounded-lg hover:bg-gray-50 transition-colors video-type-option" onclick="selectVideoType('youtube')">
              <input type="radio" name="videoType" value="youtube" class="video-type-radio">
              <i class="fab fa-youtube text-red-500"></i>
              <span class="font-medium">ลิงค์ YouTube</span>
            </label>
          </div>
        </div>

        <!-- Upload Video Section -->
        <div id="uploadSection" class="video-section">
          <label class="block font-medium mb-2 text-gray-700">
            <i class="fas fa-cloud-upload-alt mr-2"></i>อัปโหลดไฟล์วิดีโอ
          </label>
          <div class="upload-area border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors cursor-pointer" onclick="document.getElementById('videoFile').click()">
            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
            <p class="text-gray-600 mb-2">คลิกเพื่อเลือกไฟล์วิดีโอ หรือลากไฟล์มาวางที่นี่</p>
            <p class="text-sm text-gray-500">รองรับ MP4, AVI, MOV, WMV (ขนาดไม่เกิน 100MB)</p>
            <input type="file" id="videoFile" accept="video/*" class="hidden" onchange="handleFileSelect(this)" required>
          </div>
          <div id="selectedFile" class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg hidden">
            <i class="fas fa-file-video text-blue-500 mr-2"></i>
            <span id="fileName"></span>
            <span id="fileSize" class="text-sm text-gray-500 ml-2"></span>
          </div>
        </div>

        <!-- YouTube URL Section -->
        <div id="youtubeSection" class="video-section hidden">
          <label class="block font-medium mb-2 text-gray-700">
            <i class="fab fa-youtube mr-2 text-red-500"></i>URL วิดีโอ YouTube
          </label>
          <div class="relative">
            <!-- <i class="fab fa-youtube absolute left-3 top-1/2 transform -translate-y-1/2 text-red-500"></i> -->
            <input type="url" id="videoUrl" class="form-input pl-10" placeholder="https://www.youtube.com/watch?v=..." onchange="validateYouTubeUrl(this)">
          </div>
          <p class="text-sm text-gray-500 mt-1">วางลิงค์ YouTube ของวิดีโอที่ต้องการเพิ่ม</p>
        </div>

        <div class="flex justify-end gap-3 pt-4">
          <button type="button" onclick="closeModal()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            ยกเลิก
          </button>
          <button type="submit" class="btn-primary">
            <i class="fas fa-save mr-2"></i>
            <span id="submitButtonText">บันทึก</span>
          </button>
        </div>
      </form>
    </div>
  </div>


  
  <script>
    let currentVideoType = 'upload';
    let editingVideoId = null;


    async function loadVideos() {
      try {
        const res = await fetch('../functions/admin/leaning.php'); // เปลี่ยนเป็น URL จริงของ PHP ดึงข้อมูล
        if (!res.ok) throw new Error('ไม่สามารถดึงข้อมูลวิดีโอได้');
        const videos = await res.json();

        const videoGrid = document.getElementById('videoGrid');
        videoGrid.innerHTML = ''; // ล้างของเก่า

        videos.forEach(video => {
          let videoEmbed = '';

          if (video.video_type === 'youtube' && video.youtube_url) {
            // แปลง url YouTube ให้เป็น embed link
            const videoId = extractYouTubeID(video.youtube_url);
            if (videoId) {
              videoEmbed = `<iframe src="https://www.youtube.com/embed/${videoId}" allowfullscreen class="w-full h-48"></iframe>`;
            }
          } else if (video.video_type === 'upload' && video.file_path) {
            // แสดง video player
            videoEmbed = `<video controls class="w-full h-48">
                        <source src="${video.file_path}" type="video/mp4">
                        เบราว์เซอร์ของคุณไม่รองรับการเล่นวิดีโอ
                      </video>`;
          }

          const cardHtml = `
        <div class="video-card animate-slide-in border rounded-lg overflow-hidden shadow-md">
          <div class="video-container">
            ${videoEmbed}
          </div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-2">${escapeHtml(video.title)}</h3>
            <p class="text-gray-600 mb-4 text-sm leading-relaxed">${escapeHtml(video.description)}</p>
            <div class="flex justify-end gap-2">
              <button onclick="editVideo(${video.id})" class="btn-edit">
                <i class="fas fa-edit mr-1"></i>แก้ไข
              </button>
              <button onclick="deleteVideo(${video.id})" class="btn-danger">
                <i class="fas fa-trash mr-1"></i>ลบ
              </button>
            </div>
          </div>
        </div>`;

          videoGrid.insertAdjacentHTML('beforeend', cardHtml);
        });
      } catch (error) {
        console.error(error);
      }
    }

    // ฟังก์ชันช่วยดึง videoId จาก URL YouTube
    function extractYouTubeID(url) {
      const regExp = /^.*(?:youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
      const match = url.match(regExp);
      return (match && match[1].length === 11) ? match[1] : null;
    }

    // ฟังก์ชันช่วยป้องกัน XSS เล็กน้อย
    function escapeHtml(text) {
      const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;',
      };
      return text.replace(/[&<>"']/g, m => map[m]);
    }

    // เรียกโหลดวิดีโอเมื่อโหลดหน้าเว็บ
    window.addEventListener('DOMContentLoaded', loadVideos);
    // Open add modal
    function openAddModal() {
      // รีเซ็ตฟอร์ม
      document.getElementById('modalTitle').textContent = 'เพิ่มวิดีโอใหม่';
      document.getElementById('submitButtonText').textContent = 'บันทึก';
      document.getElementById('videoTitle').value = '';
      document.getElementById('videoDescription').value = '';
      document.getElementById('videoUrl').value = '';
      document.getElementById('videoFile').value = '';
      document.getElementById('selectedFile').classList.add('hidden');

      // ตั้งค่าประเภทวิดีโอเป็นอัปโหลด
      currentVideoType = 'upload';
      document.querySelector('input[name="videoType"][value="upload"]').checked = true;
      selectVideoType('upload');

      editingVideoId = null;

      // แสดง modal
      document.getElementById('videoModal').style.display = 'block';
    }

    // Open edit modal
    // function editVideo(id) {
    //   document.getElementById('modalTitle').textContent = 'แก้ไขวิดีโอ';
    //   document.getElementById('submitButtonText').textContent = 'อัปเดต';

    //   // In real application, fetch video data by id
    //   // For demo, using sample data
    //   const sampleData = {
    //     // 1: { title: 'การพัฒนาเว็บเบื้องต้น', description: 'เรียนรู้พื้นฐาน HTML, CSS และ JavaScript', url: 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' },
    //     // 2: { title: 'การออกแบบ UI/UX', description: 'หลักการออกแบบส่วนติดต่อผู้ใช้และประสบการณ์ผู้ใช้', url: 'https://www.youtube.com/watch?v=ScMzIvxBSi4' }
    //   };

    //   const video = sampleData[id] || {
    //     title: '',
    //     description: '',
    //     url: ''
    //   };

    //   document.getElementById('videoTitle').value = video.title;
    //   document.getElementById('videoDescription').value = video.description;
    //   document.getElementById('videoUrl').value = video.url;

    //   // Set to YouTube mode for editing
    //   currentVideoType = 'youtube';
    //   document.querySelector('input[name="videoType"][value="youtube"]').checked = true;
    //   selectVideoType('youtube');

    //   editingVideoId = id;
    //   document.getElementById('videoModal').style.display = 'block';
    // }
async function editVideo(id) {
  document.getElementById('modalTitle').textContent = 'แก้ไขวิดีโอ';
  document.getElementById('submitButtonText').textContent = 'อัปเดต';

  try {
    const res = await fetch(`../functions/admin/leaning.php?id=${id}`);
    if (!res.ok) throw new Error('ไม่พบข้อมูลวิดีโอ');

    const video = await res.json();

    document.getElementById('videoTitle').value = video.title || '';
    document.getElementById('videoDescription').value = video.description || '';

    if (video.video_type === 'youtube') {
      document.getElementById('videoUrl').value = video.youtube_url || '';
      document.querySelector('input[name="videoType"][value="youtube"]').checked = true;
      selectVideoType('youtube');
    } else {
      // ถ้าเป็นอัปโหลด ให้ซ่อน YouTube input
      document.getElementById('videoUrl').value = '';
      document.querySelector('input[name="videoType"][value="upload"]').checked = true;
      selectVideoType('upload');

      // แสดงชื่อไฟล์เดิม (optional)
      if (video.file_path) {
        const fileNameSpan = document.getElementById('fileName');
        fileNameSpan.textContent = video.file_path.split('/').pop();
        document.getElementById('selectedFile').classList.remove('hidden');
      }
    }

    editingVideoId = id;
    document.getElementById('videoModal').style.display = 'block';
  } catch (error) {
    alert('โหลดข้อมูลไม่สำเร็จ');
  }
}

    // Close modal
    function closeModal() {
      document.getElementById('videoModal').style.display = 'none';
    }

    function selectVideoType(type) {
      currentVideoType = type;

      const uploadSection = document.getElementById('uploadSection');
      const youtubeSection = document.getElementById('youtubeSection');
      const videoFile = document.getElementById('videoFile');
      const videoUrl = document.getElementById('videoUrl');

      if (type === 'upload') {
        uploadSection.classList.remove('hidden');
        youtubeSection.classList.add('hidden');
        videoFile.setAttribute('required', '');
        videoUrl.removeAttribute('required');
      } else {
        uploadSection.classList.add('hidden');
        youtubeSection.classList.remove('hidden');
        videoFile.removeAttribute('required');
        videoUrl.setAttribute('required', '');
      }
    }

    function handleFileSelect(input) {
      const file = input.files[0];
      const selectedFileDiv = document.getElementById('selectedFile');
      const fileName = document.getElementById('fileName');
      const fileSize = document.getElementById('fileSize');

      if (file) {
        const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
        fileName.textContent = file.name;
        fileSize.textContent = `(${fileSizeMB} MB)`;
        selectedFileDiv.classList.remove('hidden');

        // Check file size
        if (file.size > 100 * 1024 * 1024) {
          alert('ขนาดไฟล์เกิน 100MB กรุณาเลือกไฟล์ที่มีขนาดเล็กกว่า');
          input.value = '';
          selectedFileDiv.classList.add('hidden');
        }
      } else {
        selectedFileDiv.classList.add('hidden');
      }
    }

    function validateYouTubeUrl(input) {
      const url = input.value;
      const embedUrl = convertToEmbedUrl(url);

      if (url && !embedUrl) {
        input.style.borderColor = '#ef4444';
        // Show error message
        let errorMsg = input.parentNode.parentNode.querySelector('.error-message');
        if (!errorMsg) {
          errorMsg = document.createElement('p');
          errorMsg.className = 'text-sm text-red-500 mt-1 error-message';
          input.parentNode.parentNode.appendChild(errorMsg);
        }
        errorMsg.textContent = 'กรุณาใส่ URL ของ YouTube ที่ถูกต้อง';
      } else {
        input.style.borderColor = '';
        const errorMsg = input.parentNode.parentNode.querySelector('.error-message');
        if (errorMsg) {
          errorMsg.remove();
        }
      }
    }

    // YouTube URL validation and conversion
    function convertToEmbedUrl(url) {
      const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
      const match = url.match(regExp);
      return (match && match[2].length === 11) ? `https://www.youtube.com/embed/${match[2]}` : null;
    }

    // Drag and drop functionality
    const uploadArea = document.querySelector('.upload-area');

    uploadArea?.addEventListener('dragover', (e) => {
      e.preventDefault();
      uploadArea.classList.add('dragover');
    });

    uploadArea?.addEventListener('dragleave', () => {
      uploadArea.classList.remove('dragover');
    });

    uploadArea?.addEventListener('drop', (e) => {
      e.preventDefault();
      uploadArea.classList.remove('dragover');

      const files = e.dataTransfer.files;
      if (files.length > 0 && currentVideoType === 'upload') {
        const fileInput = document.getElementById('videoFile');
        fileInput.files = files;
        handleFileSelect(fileInput);
      }
    });

    // Submit video form
  
    async function submitVideo(event) {
      event.preventDefault();

      const title = document.getElementById('videoTitle').value.trim();
      const description = document.getElementById('videoDescription').value.trim();

      if (!title) {
        alert('กรุณากรอกชื่อวิดีโอ');
        return;
      }

      if (currentVideoType === 'upload') {
        const fileInput = document.getElementById('videoFile');
        if (!fileInput.files[0]) {
          alert('กรุณาเลือกไฟล์วิดีโอ');
          return;
        }
      } else {
        const youtubeUrl = document.getElementById('videoUrl').value.trim();
        if (!youtubeUrl) {
          alert('กรุณากรอก URL ของ YouTube');
          return;
        }

        // Validate YouTube URL
        if (!convertToEmbedUrl(youtubeUrl)) {
          alert('กรุณากรอก URL ของ YouTube ที่ถูกต้อง');
          return;
        }
      }

      // Show loading
      const submitBtn = event.target.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>กำลังบันทึก...';
      submitBtn.disabled = true;

      try {
        const formData = new FormData();
        formData.append('videoTitle', title);
        formData.append('videoDescription', description);
        formData.append('videoType', currentVideoType);

        if (currentVideoType === 'upload') {
          const fileInput = document.getElementById('videoFile');
          formData.append('videoFile', fileInput.files[0]);
        } else {
          const youtubeUrl = document.getElementById('videoUrl').value.trim();
          formData.append('videoUrl', youtubeUrl);
        }

        if (editingVideoId) {
            formData.append('id', editingVideoId); // ✅ ให้ตรงกับ PHP
        }

        const response = await fetch('../functions/admin/leaning.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.text();

        if (response.ok) {
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              title: editingVideoId ? 'อัปเดตสำเร็จ!' : 'เพิ่มสำเร็จ!',
              text: result,
              icon: 'success',
              confirmButtonColor: '#10b981'
            }).then(() => {
              closeModal();
              location.reload(); // โหลดข้อมูลใหม่หลังบันทึก
            });
          } else {
            alert(result);
            closeModal();
            location.reload();
          }
        } else {
          throw new Error(result);
        }
      } catch (error) {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            title: 'เกิดข้อผิดพลาด!',
            text: error.message || 'ไม่สามารถบันทึกข้อมูลได้',
            icon: 'error',
            confirmButtonColor: '#ef4444'
          });
        } else {
          alert('เกิดข้อผิดพลาด: ' + (error.message || 'ไม่สามารถบันทึกข้อมูลได้'));
        }
      } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      }
    }


  

    // Delete video
async function deleteVideo(id) {
  let result;

  if (typeof Swal !== 'undefined') {
    result = await Swal.fire({
      title: 'แน่ใจหรือไม่?',
      text: 'ต้องการลบวิดีโอนี้ใช่หรือไม่? การกระทำนี้ไม่สามารถย้อนกลับได้',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#ef4444',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'ใช่, ลบเลย!',
      cancelButtonText: 'ยกเลิก'
    });

    if (!result.isConfirmed) return; // ถ้าไม่ยืนยันก็เลิกทำ
  } else {
    if (!confirm('ต้องการลบวิดีโอนี้ใช่หรือไม่? การกระทำนี้ไม่สามารถย้อนกลับได้')) {
      return;
    }
  }

  try {
    // เรียก API ลบวิดีโอจริง
    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('id', id);

    const response = await fetch('../functions/admin/leaning.php', {
      method: 'POST',
      body: formData
    });

    const textResult = await response.text();

    if (!response.ok) {
      throw new Error(textResult);
    }

    if (typeof Swal !== 'undefined') {
      await Swal.fire({
        title: 'ลบแล้ว!',
        text: textResult,
        icon: 'success',
        confirmButtonColor: '#10b981'
      });
    } else {
      alert('ลบวิดีโอเรียบร้อยแล้ว!');
    }

    // หลังลบแล้ว รีโหลดหน้า หรือถ้าต้องการลบ DOM เองก็เขียนตรงนี้
    location.reload();

  } catch (error) {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'เกิดข้อผิดพลาด!',
        text: error.message || 'ไม่สามารถลบวิดีโอได้',
        icon: 'error',
        confirmButtonColor: '#ef4444'
      });
    } else {
      alert('เกิดข้อผิดพลาด: ' + (error.message || 'ไม่สามารถลบวิดีโอได้'));
    }
  }
}


    // Close modal when clicking outside
    window.onclick = function(event) {
      const modal = document.getElementById('videoModal');
      if (event.target === modal) {
        closeModal();
      }
    }

    // Add animation delay to video cards on load
    document.addEventListener('DOMContentLoaded', function() {
      const videoCards = document.querySelectorAll('.video-card');
      videoCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
      });

      // Handle YouTube URL validation on blur
      const videoUrlInput = document.getElementById('videoUrl');
      if (videoUrlInput) {
        videoUrlInput.addEventListener('blur', function() {
          validateYouTubeUrl(this);
        });
      }
    });
  </script>
</body>

</html>