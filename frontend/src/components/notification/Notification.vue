<template>
  <div class="notification-bar" :class="type" @click="$emit('close')">
    <div class="notification-content">
      <i :class="iconClass" class="notification-icon"></i>
      <span class="notification-message">{{ message }}</span>
    </div>
    <button @click="$emit('close')" class="notification-close">
      <i class="fas fa-times"></i>
    </button>
  </div>
</template>

<script>
import { computed } from 'vue'

export default {
  name: 'NotificationBar',
  props: {
    message: {
      type: String,
      required: true
    },
    type: {
      type: String,
      default: 'success',
      validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
    }
  },
  emits: ['close'],
  setup(props) {
    const iconClass = computed(() => {
      const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
      }
      return icons[props.type]
    })

    return {
      iconClass
    }
  }
}
</script>

<style scoped>
.notification-bar {
  position: fixed;
  top: 20px;
  right: 20px;
  background: #fff;
  padding: 15px 20px;
  border-radius: 8px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  display: flex;
  align-items: center;
  justify-content: space-between;
  min-width: 300px;
  max-width: 400px;
  z-index: 1001;
  animation: slideInRight 0.3s ease;
  border-left: 4px solid;
  cursor: pointer;
  transition: all 0.3s ease;
}

@keyframes slideInRight {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.notification-bar:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}

.notification-bar.success {
  border-left-color: #10b981;
}

.notification-bar.error {
  border-left-color: #ef4444;
}

.notification-bar.warning {
  border-left-color: #f59e0b;
}

.notification-bar.info {
  border-left-color: #3b82f6;
}

.notification-content {
  display: flex;
  align-items: center;
  gap: 12px;
  flex: 1;
}

.notification-icon {
  font-size: 1.2rem;
  flex-shrink: 0;
}

.notification-bar.success .notification-icon {
  color: #10b981;
}

.notification-bar.error .notification-icon {
  color: #ef4444;
}

.notification-bar.warning .notification-icon {
  color: #f59e0b;
}

.notification-bar.info .notification-icon {
  color: #3b82f6;
}

.notification-message {
  color: #1e1e1e;
  font-weight: 500;
  line-height: 1.4;
}

.notification-close {
  background: none;
  border: none;
  color: #b0b0b0;
  cursor: pointer;
  padding: 5px;
  border-radius: 4px;
  transition: all 0.3s ease;
  flex-shrink: 0;
  margin-left: 10px;
}

.notification-close:hover {
  background: #f5f5f5;
  color: #e53935;
}

/* Responsive Design */
@media (max-width: 768px) {
  .notification-bar {
    top: 10px;
    right: 10px;
    left: 10px;
    max-width: none;
    min-width: auto;
  }
}
</style>