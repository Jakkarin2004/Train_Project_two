

<!-- เรียกใช้งาน Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>

<aside class="w-64 bg-white border-r shadow-sm p-6 space-y-6 flex-shrink-0">
  <h1 class="text-2xl font-bold text-green-600">สมาชิกศูนย์ฝึก</h1>
  <nav class="flex flex-col gap-4 text-gray-700">

    <a href="profile.php" class="flex items-center gap-2 hover:text-green-600">
      <i data-lucide="home" class="w-5 h-5"></i> ข้อมูลส่วนตัว
    </a>

    <a href="works_list.php" class="flex items-center gap-2 hover:text-green-600">
      <i data-lucide="folder-open" class="w-5 h-5"></i> ผลงานและบริการของฉัน
    </a>

    <a href="trainProject_view.php" class="flex items-center gap-2 hover:text-green-600">
      <i data-lucide="clipboard-list" class="w-5 h-5"></i> โครงการอบรม
    </a>

    <a href="train_member.php" class="flex items-center gap-2 hover:text-green-600">
      <i data-lucide="check-square" class="w-5 h-5"></i> โครงการที่ลงทะเบียน
    </a>

    <a href="LearnMember.php" class="flex items-center gap-2 hover:text-green-600">
      <i data-lucide="book-open" class="w-5 h-5"></i> แหล่งเรียนรู้เพิ่มเติม
    </a>

    <a href="#" class="flex items-center gap-2 hover:text-green-600">
      <i data-lucide="life-buoy" class="w-5 h-5"></i> คำปรึกษา
    </a>

    <a href="#" class="flex items-center gap-2 hover:text-green-600">
      <i data-lucide="message-circle" class="w-5 h-5"></i> แชทรวม
    </a>

    <a href="#" class="flex items-center gap-2 hover:text-green-600">
      <i data-lucide="phone-call" class="w-5 h-5"></i> ติดต่อเจ้าหน้าที่
    </a>

    <hr>

    <a href="../functions/auth/logout.php" class="flex items-center gap-2 hover:text-red-600 font-semibold text-red-500">
      <i data-lucide="log-out" class="w-5 h-5"></i> ออกจากระบบ
    </a>
  </nav>
</aside>

<script>
  lucide.createIcons();
</script>