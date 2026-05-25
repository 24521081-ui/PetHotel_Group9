<footer class="site-footer" id="site-footer">
  <div class="footer-inner">
    <div class="footer-column">
      <h2 class="footer-col-title">Liên hệ</h2>

      <div class="footer-contact-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
          <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" />
          <circle cx="12" cy="9" r="2.5" />
        </svg>
        <span>123 Đường Lê Lợi, Quận 1, TP.HCM</span>
      </div>

      <div class="footer-contact-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
          <path
            d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.5 19.79 19.79 0 01.1 4.9 2 2 0 012.1 2.72h3a2 2 0 012 1.72c.12.96.36 1.9.7 2.81a2 2 0 01-.45 2.11L6.24 10.4a16 16 0 006.36 6.36l1.27-1.27a2 2 0 012.11-.45c.9.34 1.85.58 2.81.7A2 2 0 0122 16.92z" />
        </svg>
        <span>Hotline: 1900 1234</span>
      </div>

      <div class="footer-contact-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
          <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
          <polyline points="22,6 12,13 2,6" />
        </svg>
        <span>contact@pethotel.com</span>
      </div>

      <div class="footer-social" aria-label="Mạng xã hội">
        <button class="footer-social-btn" type="button" title="Facebook" aria-label="Facebook">
          <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
          </svg>
        </button>

        <button class="footer-social-btn" type="button" title="Instagram" aria-label="Instagram">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
            <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" />
            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
          </svg>
        </button>

        <button class="footer-social-btn" type="button" title="YouTube" aria-label="YouTube">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path
              d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 001.46 6.42 29 29 0 001 12a29 29 0 00.46 5.58A2.78 2.78 0 003.41 19.6C5.12 20.06 12 20.06 12 20.06s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58z" />
            <polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" />
          </svg>
        </button>
      </div>
    </div>

    <div class="footer-column">
      <h2 class="footer-col-title">Đối tác</h2>
      <a href="https://www.pedigree.com/" class="footer-partner-link" target="_blank"
        rel="noopener noreferrer">Pedigree</a>
      <a href="https://www.royalcanin.com/vn" class="footer-partner-link" target="_blank"
        rel="noopener noreferrer">Royal Canin</a>
      <a href="https://www.whiskas.com.vn" class="footer-partner-link" target="_blank"
        rel="noopener noreferrer">Whiskas</a>
    </div>

    <div class="footer-column">
      <h2 class="footer-col-title">Chính sách</h2>
      <a href="{{ url('/policies#quy-dinh-chung') }}" class="footer-policy-link">Chính sách đặt phòng</a>
      <a href="{{ url('/policies#hoan-tien') }}" class="footer-policy-link">Chính sách huỷ phòng</a>
      <a href="{{ url('/policies#quy-dinh-chung') }}" class="footer-policy-link">Yêu cầu sức khoẻ</a>
      <a href="{{ url('/policies#bao-mat') }}" class="footer-policy-link">Chính sách bảo mật</a>
      <a href="{{ url('/policies') }}" class="footer-policy-link">Điều khoản sử dụng</a>
    </div>

    <div class="footer-column footer-column-newsletter">
      <h2 class="footer-col-title">Nhận ưu đãi</h2>
      <p class="footer-newsletter-text">Đăng ký email để nhận thông tin về ưu đãi và chương trình khuyến mãi</p>

      <form class="footer-newsletter-form" action="#" method="POST">
        @csrf
        <input class="footer-newsletter-input" type="email" name="email" placeholder="Nhập email của bạn" required>
        <button type="submit" class="footer-newsletter-btn">Đăng ký</button>
      </form>
    </div>
  </div>

  <div class="footer-bottom">© {{ date('Y') }} Pet Hotel. All rights reserved.</div>
</footer>