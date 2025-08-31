<?php
session_start();

// ล้างค่า session ทั้งหมด
$_SESSION = [];
session_unset();
session_destroy();

// ไปยังหน้า login หรือหน้าแรก
header("Location: ../../index.php?msg=logout_success");
exit();
