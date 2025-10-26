// assets/js/car_showcase.js
(() => {
  // ---------- Featured cars (Unsplash realistic images)
  // Replace or extend these with actual images from your uploads later (via API)
  const featured = [
    {
      id: 1,
      name: "Tesla Model S",
      desc: "Electric luxury sedan — instant torque and silent glide.",
      image: "https://images.unsplash.com/photo-1549921296-3b1f5a1d9b2f?q=80&w=2000&auto=format&fit=crop&ixlib=rb-4.0.3&s=6b73be1b0cd2d0d6b9e9d89b0c9b7aef",
      bg: ["from-indigo-500", "to-purple-600"]
    },
    {
      id: 2,
      name: "Lamborghini Huracán",
      desc: "A roaring V10 and sculpted lines — pure adrenaline.",
      image: "https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?q=80&w=2000&auto=format&fit=crop&ixlib=rb-4.0.3&s=17b4b7f8a62e1b2bd3c1a9e1b14f8f6b",
      bg: ["from-yellow-400", "to-orange-600"]
    },
    {
      id: 3,
      name: "BMW M4",
      desc: "Performance coupe with precise handling and refined power.",
      image: "https://images.unsplash.com/photo-1519677100203-a0e668c92439?q=80&w=2000&auto=format&fit=crop&ixlib=rb-4.0.3&s=21b0feaa1f8b7b9e4b3c8a0e0f3e9c64",
      bg: ["from-blue-600", "to-cyan-400"]
    },
    {
      id: 4,
      name: "Porsche 911",
      desc: "Iconic rear-engine dynamics — a timeless driving experience.",
      image: "https://images.unsplash.com/photo-1511919884226-fd3cad34687c?q=80&w=2000&auto=format&fit=crop&ixlib=rb-4.0.3&s=5adf26c7b9f8e3f984fef7e2a3c1f9b1",
      bg: ["from-gray-700", "to-slate-900"]
    }
  ];

  // ---------- DOM refs
  const el = {
    carImage: document.getElementById('car-image'),
    carName: document.getElementById('car-name'),
    carDesc: document.getElementById('car-desc'),
    showcaseBg: document.getElementById('showcase-bg'),
    prevBtn: document.getElementById('prevBtn'),
    nextBtn: document.getElementById('nextBtn'),
    pauseBtn: document.getElementById('pauseBtn'),
    thumbs: document.getElementById('thumbs'),
    carShowcase: document.getElementById('car-showcase')
  };

  // ---------- state
  let idx = 0;
  let autoplay = true;
  let autoplayTimer = null;
  const AUTOPLAY_INTERVAL = 5000; // ms

  // ---------- helpers
  function applyBg(colors) {
    // colors array e.g. ["from-indigo-500", "to-purple-600"]
    // We'll convert tailwind class names to actual gradients via inline styles
    // Use a simple map -> hex fallback for consistent visuals
    const map = {
      'from-indigo-500': '#6366f1',
      'to-purple-600': '#7c3aed',
      'from-yellow-400': '#facc15',
      'to-orange-600': '#ea580c',
      'from-blue-600': '#2563eb',
      'to-cyan-400': '#2dd4bf',
      'from-gray-700': '#374151',
      'to-slate-900': '#0f172a'
    };
    const c1 = map[colors[0]] || '#374151';
    const c2 = map[colors[1]] || '#0f172a';
    el.showcaseBg.style.background = `linear-gradient(120deg, ${c1}, ${c2})`;
    el.showcaseBg.style.filter = 'saturate(1.05) blur(4px)';
    el.showcaseBg.style.opacity = '0.95';
    el.showcaseBg.style.transform = 'scale(1.05)';
  }

  function renderThumbs() {
    el.thumbs.innerHTML = featured.map((f, i) => {
      return `<button class="thumbBtn rounded-lg overflow-hidden border-2 border-transparent hover:border-white/40" data-i="${i}" style="width:88px;height:56px;flex:0 0 auto">
        <img src="${f.image}" alt="${f.name}" class="w-full h-full object-cover"/>
      </button>`;
    }).join('');
    document.querySelectorAll('.thumbBtn').forEach(b => b.addEventListener('click', (e) => {
      const i = Number(e.currentTarget.dataset.i);
      goTo(i);
      resetAutoplay();
    }));
  }

  // ---------- animate to index
  function goTo(i) {
    if (i < 0) i = featured.length - 1;
    if (i >= featured.length) i = 0;
    idx = i;
    const data = featured[idx];

    // GSAP animations: fade out -> swap -> fade in + slight pop
    const tl = gsap.timeline();
    tl.to(el.carImage, { duration: 0.45, opacity: 0, y: -20, ease: "power2.in" });
    tl.to(el.carName, { duration: 0.35, opacity: 0, y: -10, ease: "power2.in" }, "<");
    tl.call(() => {
      el.carImage.src = data.image;
      el.carName.textContent = data.name;
      el.carDesc.textContent = data.desc;
      applyBg(data.bg);
      highlightThumb(idx);
    });
    tl.to(el.carImage, { duration: 0.6, opacity: 1, y: 0, ease: "elastic.out(1, 0.6)" });
    tl.to(el.carName, { duration: 0.6, opacity: 1, y: 0, ease: "power4.out" }, "<0.05");
  }

  function highlightThumb(i) {
    document.querySelectorAll('.thumbBtn').forEach((b, j) => {
      b.style.boxShadow = j === i ? '0 8px 30px rgba(0,0,0,0.6)' : 'none';
      b.style.transform = j === i ? 'scale(1.04)' : 'scale(1)';
      b.style.borderColor = j === i ? 'rgba(255,255,255,0.35)' : 'transparent';
    });
  }

  // ---------- controls
  function next() {
    goTo(idx + 1);
  }
  function prev() {
    goTo(idx - 1);
  }
  function pause() {
    autoplay = false;
    el.pauseBtn.textContent = 'Resume';
    clearInterval(autoplayTimer);
  }
  function resume() {
    autoplay = true;
    el.pauseBtn.textContent = 'Pause';
    resetAutoplay();
  }
  function togglePause() {
    if (autoplay) pause(); else resume();
  }
  function resetAutoplay() {
    clearInterval(autoplayTimer);
    if (autoplay) autoplayTimer = setInterval(() => next(), AUTOPLAY_INTERVAL);
  }

  // ---------- theme toggle (auto + manual)
  const themeBtn = document.getElementById('themeToggle');
  const root = document.documentElement;
  function applyTheme(theme) {
    if (theme === 'dark') {
      root.classList.add('dark');
      localStorage.setItem('rz_theme', 'dark');
    } else {
      root.classList.remove('dark');
      localStorage.setItem('rz_theme', 'light');
    }
  }
  function initTheme() {
    const saved = localStorage.getItem('rz_theme');
    if (saved) applyTheme(saved);
    else {
      const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
      applyTheme(prefersDark ? 'dark' : 'light');
    }
  }
  themeBtn?.addEventListener('click', () => {
    const isDark = root.classList.contains('dark');
    applyTheme(isDark ? 'light' : 'dark');
  });

  // ---------- stats fetch and charts
  async function fetchStats() {
    try {
      const res = await fetch(API_STATS);
      const js = await res.json();
      document.getElementById('totalCars').textContent = js.totalCars ?? '--';
      document.getElementById('totalDealers').textContent = js.totalDealers ?? '--';
      document.getElementById('pendingAppointments').textContent = js.pendingAppointments ?? '--';
      // users mock (if you have users endpoint, replace)
      document.getElementById('totalUsers').textContent = '--';
      // recent activities (we'll fetch latest cars)
      await fetchRecent();
      // charts (mock data for now — can be replaced with real API)
      renderCharts();
    } catch (e) {
      console.error('Stats load error', e);
    }
  }

  async function fetchRecent() {
    try {
      const res = await fetch(API_FETCH + '&q=');
      const arr = await res.json();
      const recent = arr.slice(0, 6).map(a => `<div class="py-2 border-b border-slate-700"><strong>${escapeHtml(a.make)} ${escapeHtml(a.model)}</strong><div class="text-xs text-slate-400">${new Date(a.created_at).toLocaleString()}</div></div>`).join('');
      document.getElementById('recentActivities').innerHTML = recent || '<div class="text-slate-400">No recent activities</div>';
    } catch (e) {
      console.error('Recent load error', e);
    }
  }

  function renderCharts() {
    // Chart 1: types distribution (mock from featured)
    const typesCtx = document.getElementById('typesChart').getContext('2d');
    const typesData = {
      labels: featured.map(f => f.name),
      datasets: [{
        label: 'Featured Cars',
        data: featured.map((_, i) => Math.floor(Math.random() * 100) + 10),
        tension: 0.3,
        borderWidth: 0
      }]
    };
    new Chart(typesCtx, {
      type: 'doughnut',
      data: typesData,
      options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // Chart 2: monthly adds (mock)
    const addsCtx = document.getElementById('addsChart').getContext('2d');
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    new Chart(addsCtx, {
      type: 'bar',
      data: {
        labels: months,
        datasets: [{
          label: 'Cars Added',
          data: months.map(() => Math.floor(Math.random() * 20)),
          borderRadius: 6,
          barThickness: 18
        }]
      },
      options: { responsive: true, plugins: { legend: { display: false } } }
    });
  }

  // helper: escape
  function escapeHtml(s) {
    if (!s) return '';
    return String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
  }

  // ---------- wire up controls
  el.nextBtn?.addEventListener('click', () => { next(); resetAutoplay(); });
  el.prevBtn?.addEventListener('click', () => { prev(); resetAutoplay(); });
  el.pauseBtn?.addEventListener('click', togglePause);

  // ---------- init
  function init() {
    renderThumbs();
    initTheme();
    goTo(0);
    resetAutoplay();
    fetchStats();
    // subtle entrance animation for the showcase
    gsap.from('#car-showcase', { duration: 0.8, opacity: 0, y: 20, ease: "power3.out" });
  }

  document.addEventListener('DOMContentLoaded', init);
})();
