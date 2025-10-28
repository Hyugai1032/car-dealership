<template>
  <div class="activity-feed" :class="theme">
    <div class="feed-header">
      <h3 class="feed-title">
        <i class="fas fa-history"></i>
        Recent Activity
      </h3>
      <button class="view-all-btn">
        View All
        <i class="fas fa-arrow-right"></i>
      </button>
    </div>
    
    <div class="activity-list">
      <div 
        v-for="(activity, index) in activities" 
        :key="index"
        class="activity-item"
        :class="getActivityType(activity)"
      >
        <div class="activity-icon">
          <i :class="getActivityIcon(activity)"></i>
        </div>
        
        <div class="activity-content">
          <p class="activity-text">{{ activity }}</p>
          <span class="activity-time">{{ getRandomTime() }}</span>
        </div>
        
        <div class="activity-badge" :class="getActivityBadge(activity)">
          {{ getActivityBadgeText(activity) }}
        </div>
      </div>
    </div>
    
    <div class="feed-footer">
      <div class="loading-indicator">
        <div class="loading-dot"></div>
        <div class="loading-dot"></div>
        <div class="loading-dot"></div>
      </div>
      <span class="loading-text">Live updates enabled</span>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'

export default {
  name: 'ActivityFeed',
  props: {
    activities: {
      type: Array,
      default: () => []
    },
    theme: {
      type: String,
      default: 'dark'
    }
  },
  setup() {
    const getActivityType = (activity) => {
      if (activity.includes('added') || activity.includes('New')) return 'addition'
      if (activity.includes('confirmed') || activity.includes('scheduled')) return 'confirmation'
      if (activity.includes('revenue') || activity.includes('target')) return 'success'
      if (activity.includes('partnership') || activity.includes('established')) return 'partnership'
      if (activity.includes('maintenance') || activity.includes('completed')) return 'maintenance'
      return 'default'
    }

    const getActivityIcon = (activity) => {
      const type = getActivityType(activity)
      const icons = {
        addition: 'fas fa-plus-circle',
        confirmation: 'fas fa-check-circle',
        success: 'fas fa-chart-line',
        partnership: 'fas fa-handshake',
        maintenance: 'fas fa-cogs',
        default: 'fas fa-info-circle'
      }
      return icons[type]
    }

    const getActivityBadge = (activity) => {
      const type = getActivityType(activity)
      const badges = {
        addition: 'success',
        confirmation: 'primary',
        success: 'accent',
        partnership: 'info',
        maintenance: 'neutral',
        default: 'default'
      }
      return badges[type]
    }

    const getActivityBadgeText = (activity) => {
      const type = getActivityType(activity)
      const texts = {
        addition: 'New',
        confirmation: 'Confirmed',
        success: 'Success',
        partnership: 'Partnership',
        maintenance: 'System',
        default: 'Info'
      }
      return texts[type]
    }

    const getRandomTime = () => {
      const times = ['2 min ago', '5 min ago', '10 min ago', '1 hour ago', '2 hours ago']
      return times[Math.floor(Math.random() * times.length)]
    }

    return {
      getActivityType,
      getActivityIcon,
      getActivityBadge,
      getActivityBadgeText,
      getRandomTime
    }
  }
}
</script>

<style scoped>
.activity-feed {
  background: var(--bg-secondary);
  border: 1px solid var(--border-primary);
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
  height: fit-content;
}

.feed-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}

.feed-title {
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--text-primary);
  display: flex;
  align-items: center;
  gap: 10px;
}

.feed-title i {
  color: var(--text-accent);
}

.view-all-btn {
  background: transparent;
  border: 1px solid var(--border-primary);
  color: var(--text-secondary);
  padding: 8px 16px;
  border-radius: 10px;
  font-size: 0.9rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
}

.view-all-btn:hover {
  color: var(--text-accent);
  border-color: var(--text-accent);
  transform: translateX(5px);
}

.activity-list {
  gap: 15px;
}

.activity-item {
  display: flex;
  align-items: flex-start;
  gap: 15px;
  padding: 20px;
  border-radius: 15px;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.activity-item::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 3px;
  background: var(--border-primary);
  transition: all 0.3s ease;
}

.activity-item:hover::before {
  background: var(--text-accent);
}

.activity-item:hover {
  background: var(--bg-tertiary);
  transform: translateX(5px);
}

.activity-item.addition:hover::before {
  background: #10b981;
}

.activity-item.confirmation:hover::before {
  background: #3b82f6;
}

.activity-item.success:hover::before {
  background: #d40000;
}

.activity-item.partnership:hover::before {
  background: #8b5cf6;
}

.activity-item.maintenance:hover::before {
  background: #6b7280;
}

.activity-icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  background: var(--bg-tertiary);
  color: var(--text-secondary);
  transition: all 0.3s ease;
}

.activity-item:hover .activity-icon {
  transform: scale(1.1);
}

.activity-item.addition .activity-icon {
  background: rgba(16, 185, 129, 0.1);
  color: #10b981;
}

.activity-item.confirmation .activity-icon {
  background: rgba(59, 130, 246, 0.1);
  color: #3b82f6;
}

.activity-item.success .activity-icon {
  background: rgba(212, 0, 0, 0.1);
  color: #d40000;
}

.activity-item.partnership .activity-icon {
  background: rgba(139, 92, 246, 0.1);
  color: #8b5cf6;
}

.activity-item.maintenance .activity-icon {
  background: rgba(107, 114, 128, 0.1);
  color: #6b7280;
}

.activity-content {
  flex: 1;
}

.activity-text {
  color: var(--text-primary);
  font-weight: 500;
  line-height: 1.5;
  margin-bottom: 5px;
}

.activity-time {
  font-size: 0.8rem;
  color: var(--text-secondary);
  font-weight: 500;
}

.activity-badge {
  padding: 4px 10px;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  flex-shrink: 0;
  align-self: flex-start;
}

.activity-badge.success {
  background: rgba(16, 185, 129, 0.1);
  color: #10b981;
  border: 1px solid rgba(16, 185, 129, 0.2);
}

.activity-badge.primary {
  background: rgba(59, 130, 246, 0.1);
  color: #3b82f6;
  border: 1px solid rgba(59, 130, 246, 0.2);
}

.activity-badge.accent {
  background: rgba(212, 0, 0, 0.1);
  color: #d40000;
  border: 1px solid rgba(212, 0, 0, 0.2);
}

.activity-badge.info {
  background: rgba(139, 92, 246, 0.1);
  color: #8b5cf6;
  border: 1px solid rgba(139, 92, 246, 0.2);
}

.activity-badge.neutral {
  background: rgba(107, 114, 128, 0.1);
  color: #6b7280;
  border: 1px solid rgba(107, 114, 128, 0.2);
}

.activity-badge.default {
  background: var(--bg-tertiary);
  color: var(--text-secondary);
  border: 1px solid var(--border-primary);
}

.feed-footer {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 20px;
  padding-top: 20px;
  border-top: 1px solid var(--border-primary);
}

.loading-indicator {
  display: flex;
  gap: 4px;
}

.loading-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--text-accent);
  animation: pulse 1.5s ease-in-out infinite;
}

.loading-dot:nth-child(2) {
  animation-delay: 0.2s;
}

.loading-dot:nth-child(3) {
  animation-delay: 0.4s;
}

@keyframes pulse {
  0%, 100% { opacity: 0.3; transform: scale(0.8); }
  50% { opacity: 1; transform: scale(1.2); }
}

.loading-text {
  font-size: 0.8rem;
  color: var(--text-secondary);
  font-weight: 500;
}

/* Responsive Design */
@media (max-width: 768px) {
  .activity-feed {
    padding: 20px;
  }
  
  .feed-header {
    flex-direction: column;
    gap: 15px;
    align-items: flex-start;
  }
  
  .activity-item {
    padding: 15px;
  }
}
</style>