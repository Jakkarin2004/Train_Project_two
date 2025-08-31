   <style>
   /* Footer */
    .footer {
      background-color: #111;
      color: #f0f0f0;
      padding: 40px 20px 20px;
      margin-top: 60px;
    }

    .footer-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 30px;
      max-width: 1100px;
      margin: auto;
    }

    .footer h3 {
      margin-bottom: 10px;
      font-size: 20px;
      color: #fff;
    }

    .footer-left,
    .footer-center,
    .footer-right {
      flex: 1;
      min-width: 250px;
    }

    .fb-box-vertical {
      border-radius: 12px;
      overflow: hidden;
    }

    .quick-links,
    .contact-info {
      list-style: none;
      padding: 0;
    }

    .quick-links li,
    .contact-info li {
      margin-bottom: 10px;
      font-size: 14px;
    }

    .quick-links a {
      color: #ccc;
      text-decoration: none;
      transition: 0.3s;
    }

    .quick-links a:hover {
      text-decoration: underline;
      color: #fff;
    }

    .footer .icon {
      width: 16px;
      margin-right: 8px;
      vertical-align: middle;
      filter: brightness(0.9);
    }

    .footer-bottom {
      text-align: center;
      padding-top: 20px;
      font-size: 14px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    </style>

    <!-- Footer -->
  <footer class="footer">
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v22.0"></script>

    <div class="footer-container">
      <div class="footer-left">
        <h3>ติดตามเพจศูนย์ฝึก</h3>
        <div class="fb-box-vertical">
          <div class="fb-page" data-href="https://www.facebook.com/profile.php?id=61554638219599" data-tabs="timeline" data-width="250" data-height="400" data-small-header="false"
            data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
            <blockquote cite="https://www.facebook.com/profile.php?id=61554638219599" class="fb-xfbml-parse-ignore">
              <a href="https://www.facebook.com/profile.php?id=61554638219599">
                ศูนย์ฝึกวิชาชีพทางด้านวิทยาการคอมพิวเตอร์ มหาวิทยาลัยราชภัฏเลย
              </a>
            </blockquote>
          </div>
        </div>
      </div>

      <div class="footer-center">
        <h3>เมนูลัด</h3>
        <ul class="quick-links">
          <li><a href="login.php">เข้าสู่ระบบ</a></li>
          <li><a href="courses.php">คอร์สอบรม</a></li>
          <li><a href="services.php">แหล่งเรียนรู้</a></li>
          <li><a href="https://www.facebook.com/share/16o4QP2Vft/?mibextid=wwXIfr">ติดต่อเรา</a></li>
        </ul>
      </div>

      <div class="footer-right">
        <h3>ติดต่อเรา</h3>
        <ul class="contact-info">
          <li><img src="https://cdn-icons-png.flaticon.com/512/1384/1384053.png" alt="fb" class="icon" /> Facebook: LRU Digital Training</li>
          <li><img src="https://cdn-icons-png.flaticon.com/512/2111/2111392.png" alt="line" class="icon" /> Line: @lrucscenter</li>
          <li><img src="https://cdn-icons-png.flaticon.com/512/732/732200.png" alt="email" class="icon" /> Email: lrudigitalstartupteam.cs@gmail.com</li>
          <li><img src="https://cdn-icons-png.flaticon.com/512/724/724664.png" alt="phone" class="icon" /> โทร: 042-000-000</li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; 2025 ศูนย์ฝึกวิชาชีพทางด้านวิทยาการคอมพิวเตอร์ มหาวิทยาลัยราชภัฏเลย</p>
    </div>
  </footer>