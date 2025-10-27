<template>
  <div class="appointment-card" :class="`status-${appointment.status}`">
    <!-- Status Strip -->
    <div class="status-strip"></div>
    
    <div class="card-content">
      <!-- Header -->
      <div class="card-header">
        <div class="car-info">
          <i class="fas fa-car car-icon"></i>
          <h3 class="car-model">{{ appointment.car_model }}</h3>
        </div>
        <div class="status-badge" :class="appointment.status">
          {{ appointment.status }}
        </div>
      </div>

      <!-- Details -->
      <div class="card-details">
        <div class="detail-item">
          <i class="fas fa-calendar-alt detail-icon"></i>
          <span class="detail-text">{{ formatDate(appointment.appointment_at) }}</span>
        </div>
        <div class="detail-item">
          <i class="fas fa-clock detail-icon"></i>
          <span class="detail-text">{{ formatTime(appointment.appointment_at) }}</span>
        </div>
        <div class="detail-item">
          <i class="fas fa-building detail-icon"></i>
          <span class="detail-text">{{ appointment.dealer_name }}</span>
        </div>
        <div v-if="appointment.notes" class="detail-item notes">
          <i class="fas fa-sticky-note detail-icon"></i>
          <span class="detail-text">{{ appointment.notes }}</span>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="card-actions">
        <button @click="$emit('edit', appointment)" class="action-btn edit">
          <i class="fas fa-edit"></i>
          Edit
        </button>
        
        <button 
          v-if="!isAdmin && appointment.status === 'pending'"
          @click="$emit('cancel', appointment.id)" 
          class="action-btn cancel"
        >
          <i class="fas fa-times"></i>
          Cancel
        </button>

        <!-- Admin Actions -->
        <template v-if="isAdmin">
          <button 
            v-if="appointment.status === 'pending'"
            @click="$emit('status-change', appointment.id, 'confirmed')" 
            class="action-btn confirm"
          >
            <i class="fas fa-check"></i>
            Confirm
          </button>
          
          <button 
            v-if="appointment.status === 'confirmed'"
            @click="$emit('status-change', appointment.id, 'completed')" 
            class="action-btn complete"
          >
            <i class="fas fa-flag-checkered"></i>
            Complete
          </button>
          
          <button 
            @click="$emit('status-change', appointment.id, 'cancelled')" 
            class="action-btn cancel-admin"
          >
            <i class="fas fa-ban"></i>
            Cancel
          </button>
        </template>

        <button @click="$emit('view', appointment)" class="action-btn view">
          <i class="fas fa-eye"></i>
          Details
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AppointmentCard',
  props: {
    appointment: {
      type: Object,
      required: true
    },
    isAdmin: {
      type: Boolean,
      default: false
    }
  },
  emits: ['edit', 'cancel', 'view', 'status-change'],
  setup() {
    const formatDate = (dateString) => {
      const date = new Date(dateString)
      return date.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      })
    }

    const formatTime = (dateString) => {
      const date = new Date(dateString)
      return date.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit'
      })
    }

    return {
      formatDate,
      formatTime
    }
  }
}
</script>

<style scoped>
.appointment-card {
  background: var(--pure-white);
  border-radius: 12px;
  box-shadow: var(--shadow);
  overflow: hidden;
  transition: var(--transition);
  position: relative;
}

.appointment-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.status-strip {
  height: 6px;
  width: 100%;
  transition: var(--transition);
}

.status-pending .status-strip {
  background: var(--primary-red);
}

.status-confirmed .status-strip {
  background: var(--luxury-black);
}

.status-completed .status-strip {
  background: var(--dark-gray);
}

.status-cancelled .status-strip {
  background: var(--medium-gray);
}

.card-content {
  padding: 20px;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 15px;
}

.car-info {
  display: flex;
  align-items: center;
  gap: 10px;
}

.car-icon {
  color: var(--primary-red);
  font-size: 1.2rem;
}

.car-model {
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--luxury-black);
  margin: 0;
}

.status-badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: capitalize;
}

.status-badge.pending {
  background: rgba(212, 0, 0, 0.1);
  color: var(--primary-red);
  border: 1px solid var(--primary-red);
}

.status-badge.confirmed {
  background: rgba(26, 26, 26, 0.1);
  color: var(--luxury-black);
  border: 1px solid var(--luxury-black);
}

.status-badge.completed {
  background: rgba(102, 102, 102, 0.1);
  color: var(--dark-gray);
  border: 1px solid var(--dark-gray);
}

.status-badge.cancelled {
  background: rgba(224, 224, 224, 0.1);
  color: var(--dark-gray);
  border: 1px solid var(--medium-gray);
}

.card-details {
  margin-bottom: 20px;
}

.detail-item {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 8px;
  color: var(--dark-gray);
}

.detail-item.notes {
  padding: 10px;
  background: var(--light-gray);
  border-radius: 6px;
  border-left: 3px solid var(--primary-red);
}

.detail-icon {
  width: 16px;
  color: var(--primary-red);
}

.detail-text {
  font-size: 0.95rem;
}

.card-actions {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.action-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 8px 12px;
  border: none;
  border-radius: 6px;
  font-size: 0.85rem;
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
  flex: 1;
  justify-content: center;
  min-width: 80px;
}

.action-btn:hover {
  transform: translateY(-2px);
}

.action-btn.edit {
  background: var(--medium-gray);
  color: var(--luxury-black);
}

.action-btn.edit:hover {
  background: var(--dark-gray);
  color: var(--pure-white);
}

.action-btn.cancel {
  background: rgba(212, 0, 0, 0.1);
  color: var(--primary-red);
  border: 1px solid var(--primary-red);
}

.action-btn.cancel:hover {
  background: var(--primary-red);
  color: var(--pure-white);
}

.action-btn.confirm {
  background: rgba(26, 26, 26, 0.1);
  color: var(--luxury-black);
  border: 1px solid var(--luxury-black);
}

.action-btn.confirm:hover {
  background: var(--luxury-black);
  color: var(--pure-white);
}

.action-btn.complete {
  background: rgba(102, 102, 102, 0.1);
  color: var(--dark-gray);
  border: 1px solid var(--dark-gray);
}

.action-btn.complete:hover {
  background: var(--dark-gray);
  color: var(--pure-white);
}

.action-btn.cancel-admin {
  background: rgba(212, 0, 0, 0.1);
  color: var(--primary-red);
  border: 1px solid var(--primary-red);
}

.action-btn.cancel-admin:hover {
  background: var(--primary-red);
  color: var(--pure-white);
}

.action-btn.view {
  background: var(--luxury-black);
  color: var(--pure-white);
}

.action-btn.view:hover {
  background: var(--soft-black);
}

/* Responsive Design */
@media (max-width: 480px) {
  .card-header {
    flex-direction: column;
    gap: 10px;
    align-items: flex-start;
  }

  .card-actions {
    flex-direction: column;
  }

  .action-btn {
    flex: none;
  }
}
</style>