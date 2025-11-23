<template>
  <header class="header" :class="theme">
    <div class="header-content">
      <div class="header-left">
        <button class="menu-toggle" @click="$emit('toggle-sidebar')">
          <i class="fas fa-bars"></i>
        </button>
        <div class="breadcrumb">
          <span class="breadcrumb-item">Dashboard</span>
          <span class="breadcrumb-separator">/</span>
          <span class="breadcrumb-item active">Overview</span>
        </div>
      </div>
      
      <div class="header-right">
        <div class="header-actions">
          <button class="action-btn">
            <i class="fas fa-bell"></i>
            <span class="notification-dot"></span>
          </button>
          <button class="action-btn">
            <i class="fas fa-envelope"></i>
          </button>
          <button class="action-btn">
            <i class="fas fa-cog"></i>
          </button>
        </div>
        
        <div class="user-menu">
          <div class="user-avatar">
            <i class="fas fa-user"></i>
          </div>
          <div class="user-info">
            <span class="user-name">Admin User</span>
            <span class="user-status">Online</span>
          </div>
          <i class="fas fa-chevron-down dropdown-icon"></i>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: 'Header',
  props: {
    theme: {
      type: String,
      default: 'dark'
    }
  },
  emits: ['toggle-sidebar']
}
</script>

<style scoped>
.header {
  background: var(--bg-secondary);
  border-bottom: 1px solid var(--border-primary);
  padding: 0 30px;
  height: 80px;
  display: flex;
  align-items: center;
  position: sticky;
  top: 0;
  z-index: 999;
  backdrop-filter: blur(10px);
}

.header-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 20px;
}

.menu-toggle {
  width: 40px;
  height: 40px;
  border: none;
  background: var(--bg-tertiary);
  color: var(--text-secondary);
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.3s ease;
  display: none;
  align-items: center;
  justify-content: center;
}

.menu-toggle:hover {
  color: var(--text-accent);
  background: var(--bg-secondary);
}

.breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.9rem;
}

.breadcrumb-item {
  color: var(--text-secondary);
  transition: color 0.3s ease;
}

.breadcrumb-item.active {
  color: var(--text-accent);
  font-weight: 600;
}

.breadcrumb-separator {
  color: var(--text-secondary);
}

.header-right {
  display: flex;
  align-items: center;
  gap: 20px;
}

.header-actions {
  display: flex;
  gap: 10px;
}

.action-btn {
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
  position: relative;
}

.action-btn:hover {
  color: var(--text-accent);
  background: var(--bg-secondary);
  transform: translateY(-2px);
}

.notification-dot {
  position: absolute;
  top: 8px;
  right: 8px;
  width: 8px;
  height: 8px;
  background: var(--text-accent);
  border-radius: 50%;
  animation: pulse 2s infinite;
}

.user-menu {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 8px 15px;
  background: var(--bg-tertiary);
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
  border: 1px solid transparent;
}

.user-menu:hover {
  background: var(--bg-secondary);
  border-color: var(--text-accent);
}

.user-avatar {
  width: 36px;
  height: 36px;
  background: linear-gradient(135deg, #d40000, #a80000);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 0.9rem;
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

.user-status {
  font-size: 0.75rem;
  color: var(--text-accent);
}

.dropdown-icon {
  color: var(--text-secondary);
  font-size: 0.8rem;
  transition: transform 0.3s ease;
}

.user-menu:hover .dropdown-icon {
  transform: rotate(180deg);
}

/* Responsive Design */
@media (max-width: 1200px) {
  .menu-toggle {
    display: flex;
  }
  
  .header {
    padding: 0 20px;
  }
}

@media (max-width: 768px) {
  .user-info {
    display: none;
  }
  
  .dropdown-icon {
    display: none;
  }
  
  .header-actions {
    gap: 5px;
  }
}
</style>