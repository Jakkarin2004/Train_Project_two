<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    /* Sidebar Styles */
    .sidebar {
        background: #ff6b35;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
    }

    .sidebar-link {
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 0.75rem 1rem;
        border-radius: 12px;
        margin-bottom: 0.5rem;
        position: relative;
        overflow: hidden;
    }

    .sidebar-link:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 0;
        background: #ffffff;
        transition: width 0.3s ease;
        z-index: -1;
    }

    .sidebar-link:hover {
        color: #ff6b35;
        transform: translateX(8px);
        box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
    }

    .sidebar-link:hover:before {
        width: 100%;
    }

    .sidebar-link.active {
        color: #ff6b35;
        background: #ffffff;
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
    }

    .sidebar-link i {
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }
</style>

<aside class="w-64 sidebar p-6 space-y-2 sticky top-0 h-screen">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-white mb-2">
            <i class="fas fa-crown mr-2 text-yellow-300"></i>
            Admin Panel
        </h2>
        <div class="w-full h-1 bg-white rounded-full"></div>
    </div>
    
    <ul class="space-y-1">
        <li>
            <a href="#" class="sidebar-link" data-page="admin_dashbord">
                <i class="fas fa-chart-pie"></i>
                Dashboard
            </a>
        </li>
        <li>
            <a href="../index.php" class="sidebar-link" data-page="index">
                <i class="fas fa-home"></i>
                Home
            </a>
        </li>
        <li>
            <a href="manage_users.php" class="sidebar-link" data-page="manage_users">
                <i class="fas fa-users"></i>
                User Data
            </a>
        </li>
        <li>
            <a href="training_project.php" class="sidebar-link" data-page="training_project">
                <i class="fas fa-graduation-cap"></i>
                ข้อมูลโครงการอบรม
            </a>
        </li>
        <li>
            <a href="leaning.php" class="sidebar-link" data-page="leaning">
                <i class="fas fa-book-open"></i>
                แหล่งการเรียนรู้
            </a>
        </li>
        <li>
            <a href="#" class="sidebar-link" data-page="performance">
                <i class="fas fa-chart-line"></i>
                ผลงานและบริการ
            </a>
        </li>
        <li>
            <a href="#" class="sidebar-link" data-page="consultation">
                <i class="fas fa-comments"></i>
                การให้คำปรึกษา
            </a>
        </li>
        <li>
            <a href="#" class="sidebar-link" data-page="contact">
                <i class="fas fa-address-book"></i>
                ข้อมูลการติดต่อ
            </a>
        </li>
        <li>
            <a href="account_setting.php" class="sidebar-link" data-page="account_setting">
                <i class="fas fa-user-cog"></i>
                การตั้งค่าบัญชี
            </a>
        </li>
        <li>
            <a href="setting.php" class="sidebar-link" data-page="setting">
                <i class="fas fa-cog"></i>
                การตั้งค่า
            </a>
        </li>
    </ul>
</aside>

<script>
    // ไฮไลท์เมนูปัจจุบัน
    document.addEventListener('DOMContentLoaded', function() {
        const currentPage = window.location.pathname.split('/').pop().replace('.php', '');
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        
        sidebarLinks.forEach(link => {
            const page = link.getAttribute('data-page');
            if (page === currentPage) {
                link.classList.add('active');
            }
        });
    });
</script>