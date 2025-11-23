<template>
  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="brand">
          <div class="logo">
            <svg viewBox="0 0 100 100" class="logo-icon"><path d="M50 15 L70 35 L70 65 L50 85 L30 65 L30 35 Z" fill="url(#grad)"/></svg>
            <span class="logo-text">LUXAUTO</span>
          </div>
          <p class="tagline">Drive the Future. Today.</p>
          <div class="social">
            <a href="#" class="social-link"><svg viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg></a>
            <a href="#" class="social-link"><svg viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg></a>
            <a href="#" class="social-link"><svg viewBox="0 0 24 24"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.3 4.3 0 001.88-2.38 8.6 8.6 0 01-2.72 1.04 4.27 4.27 0 00-7.28 3.9A12.1 12.1 0 011 4.79a4.27 4.27 0 001.32 5.7 4.2 4.2 0 01-1.94-.54v.05a4.27 4.27 0 003.43 4.18 4.2 4.2 0 01-1.93.07 4.27 4.27 0 003.98 2.96A8.56 8.56 0 012 19.54a12.1 12.1 0 006.56 1.92c7.87 0 12.18-6.53 12.18-12.18 0-.19-.01-.37-.02-.56A8.7 8.7 0 0024 5.56a8.5 8.5 0 01-2.54.7z"/></svg></a>
          </div>
        </div>

        <div class="links">
          <h4>Explore</h4>
          <a href="#">New Arrivals</a>
          <a href="#">Electric</a>
          <a href="#">Luxury</a>
          <a href="#">Performance</a>
        </div>

        <div class="links">
          <h4>Services</h4>
          <a href="#">Finance</a>
          <a href="#">Insurance</a>
          <a href="#">Test Drive</a>
          <a href="#">Trade-in</a>
        </div>

        <div class="links">
          <h4>Company</h4>
          <a href="#">About</a>
          <a href="#">Careers</a>
          <a href="#">Press</a>
          <a href="#">Contact</a>
        </div>
      </div>

      <div class="bottom">
        <p>&copy; 2025 LUXAUTO. All rights reserved.</p>
        <div class="legal">
          <a href="#">Privacy</a>
          <a href="#">Terms</a>
          <a href="#">Cookies</a>
        </div>
      </div>
    </div>
  </footer>
</template>

<style scoped>
.footer {
  background: #0f172a;
  color: #e2e8f0;
  padding: 4rem 0 2rem;
}

.container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 2rem;
}

.footer-grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr;
  gap: 3rem;
  margin-bottom: 3rem;
}

.brand {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.logo {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.logo-icon {
  width: 2.5rem;
  height: 2.5rem;
}

.logo-text {
  font-size: 1.5rem;
  font-weight: 800;
  background: linear-gradient(to right, #a78bfa, #c4b5fd);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
}

.tagline {
  font-size: 0.9rem;
  color: #94a3b8;
}

.social {
  display: flex;
  gap: 1rem;
}

.social-link {
  width: 2.5rem;
  height: 2.5rem;
  background: rgba(255,255,255,0.1);
  border-radius: 9999px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.social-link:hover {
  background: #4f46e5;
  transform: translateY(-2px);
}

.social-link svg {
  width: 1.25rem;
  height: 1.25rem;
  fill: #e2e8f0;
}

.links h4 {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 1rem;
  color: white;
}

.links a {
  display: block;
  color: #94a3b8;
  text-decoration: none;
  margin-bottom: 0.5rem;
  transition: color 0.3s ease;
}

.links a:hover {
  color: #c4b5fd;
}

.bottom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 2rem;
  border-top: 1px solid rgba(255,255,255,0.1);
  font-size: 0.875rem;
  color: #64748b;
}

.legal a {
  color: #94a3b8;
  text-decoration: none;
  margin-left: 1rem;
}

.legal a:hover {
  color: #c4b5fd;
}
</style>