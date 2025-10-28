<template>
  <aside class="sidebar" :class="theme">
    <div class="sidebar-header">
      <div class="logo">
        <div class="logo-icon">
          <i class="fas fa-car"></i>
        </div>
        <span class="logo-text">LuxuryAuto</span>
      </div>
    </div>
    
    <nav class="sidebar-nav">
      <div class="nav-section">
        <h3 class="nav-section-title">MAIN</h3>
        <ul class="nav-links">
          <li class="nav-item active">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
            <div class="active-indicator"></div>
          </li>
          <li class="nav-item">
            <i class="fas fa-chart-line"></i>
            <span>Analytics</span>
          </li>
          <li class="nav-item">
            <i class="fas fa-car"></i>
            <span>Vehicle Inventory</span>
          </li>
          <li class="nav-item">
            <i class="fas fa-store"></i>
            <span>Dealers</span>
          </li>
        </ul>
      </div>
      
      <div class="nav-section">
        <h3 class="nav-section-title">MANAGEMENT</h3>
          <ul class="nav-links">
            <li class="nav-item">
              <i class="fas fa-calendar-check"></i>
              <span>Appointments</span>
              <span class="badge">23</span>
            </li>
            <li class="nav-item">
              <i class="fas fa-car-side"></i>
              <span>Cars Management</span>
            </li>
            <li class="nav-item">
              <i class="fas fa-users"></i>
              <span>Customers</span>
            </li>
            <li class="nav-item">
              <i class="fas fa-file-invoice-dollar"></i>
              <span>Financing</span>
            </li>
            <li class="nav-item">
              <i class="fas fa-cogs"></i>
              <span>Settings</span>
            </li>
          </ul>
      </div>
    </nav>
    
    <div class="sidebar-footer">
      <div class="user-profile">
        <div class="avatar">
          <i class="fas fa-user"></i>
        </div>
        <div class="user-info">
          <span class="user-name">Admin User</span>
          <span class="user-role">Administrator</span>
        </div>
      </div>
      <button @click="$emit('toggle-theme')" class="theme-toggle">
        <i class="fas fa-palette"></i>
      </button>
    </div>
  </aside>
</template>

<script>
export default {
  name: 'Sidebar',
  props: {
    theme: {
      type: String,
      default: 'dark'
    }
  },
  emits: ['toggle-theme']
}
</script>

<style scoped>
.sidebar {
  width: 280px;
  height: 100vh;
  position: fixed;
  left: 0;
  top: 0;
  background: var(--bg-secondary);
  border-right: 1px solid var(--border-primary);
  display: flex;
  flex-direction: column;
  z-index: 1000;
  transition: all 0.3s ease;
  overflow: auto;
  scrollbar-width: none;
}

.sidebar::-webkit-scrollbar{
  display: none;
}

.sidebar-header {
  padding: 30px 25px;
  border-bottom: 1px solid var(--border-primary);
}

.logo {
  display: flex;
  align-items: center;
  gap: 12px;
}

.logo-icon {
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #d40000, #a80000);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.2rem;
  box-shadow: 0 4px 15px rgba(212, 0, 0, 0.3);
}

.logo-text {
  font-size: 1.5rem;
  font-weight: 800;
  background: linear-gradient(135deg, #d40000, #ffffff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.sidebar-nav {
  flex: 1;
  padding: 30px 0;
  overflow-y: auto;
}

.nav-section {
  margin-bottom: 30px;
}

.nav-section-title {
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: var(--text-secondary);
  padding: 0 25px 10px;
  margin-bottom: 10px;
  border-bottom: 1px solid var(--border-primary);
}

.nav-links {
  list-style: none;
  padding: 0;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 25px;
  color: var(--text-secondary);
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  border-left: 3px solid transparent;
}

.nav-item:hover {
  color: var(--text-primary);
  background: var(--bg-tertiary);
  border-left-color: var(--text-accent);
}

.nav-item.active {
  color: var(--text-accent);
  background: linear-gradient(90deg, rgba(212, 0, 0, 0.1), transparent);
  border-left-color: var(--text-accent);
}

.nav-item i {
  width: 20px;
  font-size: 1rem;
}

.nav-item span {
  flex: 1;
  font-weight: 500;
}

.badge {
  background: var(--text-accent);
  color: white;
  padding: 2px 8px;
  border-radius: 10px;
  font-size: 0.75rem;
  font-weight: 600;
}

.active-indicator {
  width: 6px;
  height: 6px;
  background: var(--text-accent);
  border-radius: 50%;
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}

.sidebar-footer {
  padding: 20px 25px;
  border-top: 1px solid var(--border-primary);
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 12px;
}

.avatar {
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #2d2d2d, #1a1a1a);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--text-secondary);
  border: 2px solid var(--border-primary);
}

.user-info {
  display: flex;
  flex-direction: column;
}

.user-name {
  font-weight: 600;
  color: var(--text-primary);
  font-size: 0.9rem;
}

.user-role {
  font-size: 0.75rem;
  color: var(--text-secondary);
}

.theme-toggle {
  width: 40px;
  height: 40px;
  border: none;
  background: var(--bg-tertiary);
  color: var(--text-secondary);
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.theme-toggle:hover {
  color: var(--text-accent);
  background: var(--bg-secondary);
  transform: rotate(15deg);
}

/* Responsive Design */
@media (max-width: 1200px) {
  .sidebar {
    transform: translateX(-100%);
  }
  
  .sidebar.mobile-open {
    transform: translateX(0);
  }
}
</style>