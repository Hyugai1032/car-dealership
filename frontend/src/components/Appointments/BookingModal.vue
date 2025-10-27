<template>
  <div class="modal-overlay" @click.self="$emit('close')">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">
          {{ editingAppointment ? 'Edit Appointment' : 'Book New Appointment' }}
          <span class="title-underline"></span>
        </h2>
        <button @click="$emit('close')" class="close-btn">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <form @submit.prevent="handleSubmit" class="booking-form">
        <div class="form-group">
          <label for="carModel" class="form-label">
            <i class="fas fa-car"></i>
            Car Model
          </label>
          <input
            type="text"
            id="carModel"
            v-model="formData.car_model"
            required
            class="form-input"
            placeholder="Enter car model..."
          >
        </div>

        <div class="form-group">
          <label for="dealerName" class="form-label">
            <i class="fas fa-building"></i>
            Dealer Name
          </label>
          <input
            type="text"
            id="dealerName"
            v-model="formData.dealer_name"
            required
            class="form-input"
            placeholder="Enter dealer name..."
          >
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="appointmentDate" class="form-label">
              <i class="fas fa-calendar"></i>
              Date
            </label>
            <input
              type="date"
              id="appointmentDate"
              v-model="formData.appointment_date"
              required
              class="form-input"
            >
          </div>

          <div class="form-group">
            <label for="appointmentTime" class="form-label">
              <i class="fas fa-clock"></i>
              Time
            </label>
            <input
              type="time"
              id="appointmentTime"
              v-model="formData.appointment_time"
              required
              class="form-input"
            >
          </div>
        </div>

        <div class="form-group">
          <label for="notes" class="form-label">
            <i class="fas fa-sticky-note"></i>
            Notes (Optional)
          </label>
          <textarea
            id="notes"
            v-model="formData.notes"
            rows="3"
            class="form-textarea"
            placeholder="Any additional notes or requirements..."
          ></textarea>
        </div>

        <div class="form-actions">
          <button type="button" @click="$emit('close')" class="btn btn-cancel">
            Cancel
          </button>
          <button type="submit" class="btn btn-confirm">
            <i class="fas fa-check"></i>
            {{ editingAppointment ? 'Update Appointment' : 'Book Appointment' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { ref, watch, onMounted } from 'vue'

export default {
  name: 'BookingModal',
  props: {
    editingAppointment: {
      type: Object,
      default: null
    }
  },
  emits: ['save', 'close'],
  setup(props, { emit }) {
    const formData = ref({
      car_model: '',
      dealer_name: '',
      appointment_date: '',
      appointment_time: '',
      notes: ''
    })

    // Initialize form when editing
    watch(() => props.editingAppointment, (newAppointment) => {
      if (newAppointment) {
        const dateTime = new Date(newAppointment.appointment_at)
        formData.value = {
          car_model: newAppointment.car_model,
          dealer_name: newAppointment.dealer_name,
          appointment_date: dateTime.toISOString().split('T')[0],
          appointment_time: dateTime.toTimeString().slice(0, 5),
          notes: newAppointment.notes || ''
        }
      } else {
        // Reset form for new appointment
        formData.value = {
          car_model: '',
          dealer_name: '',
          appointment_date: '',
          appointment_time: '',
          notes: ''
        }
      }
    }, { immediate: true })

    const handleSubmit = () => {
      const appointmentData = {
        ...formData.value,
        appointment_at: `${formData.value.appointment_date} ${formData.value.appointment_time}:00`,
        car_id: props.editingAppointment?.car_id || Math.floor(Math.random() * 100) + 1,
        dealer_id: props.editingAppointment?.dealer_id || Math.floor(Math.random() * 10) + 1
      }

      // Remove temporary fields
      delete appointmentData.appointment_date
      delete appointmentData.appointment_time

      emit('save', appointmentData)
    }

    return {
      formData,
      handleSubmit
    }
  }
}
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: flex-start;
  z-index: 1000;
  padding: 50px 20px;
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.modal-content {
  background: var(--pure-white);
  border-radius: 12px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  width: 100%;
  max-width: 500px;
  animation: slideDown 0.3s ease;
}

@keyframes slideDown {
  from { 
    transform: translateY(-50px);
    opacity: 0;
  }
  to { 
    transform: translateY(0);
    opacity: 1;
  }
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 25px 30px 0;
  margin-bottom: 20px;
}

.modal-title {
  color: var(--luxury-black);
  font-size: 1.5rem;
  font-weight: 700;
  position: relative;
}

.title-underline {
  position: absolute;
  bottom: -8px;
  left: 0;
  width: 50px;
  height: 3px;
  background: var(--primary-red);
  border-radius: 2px;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  color: var(--dark-gray);
  cursor: pointer;
  padding: 5px;
  border-radius: 50%;
  transition: var(--transition);
}

.close-btn:hover {
  background: var(--light-gray);
  color: var(--primary-red);
}

.booking-form {
  padding: 0 30px 30px;
}

.form-group {
  margin-bottom: 20px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

.form-label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 600;
  color: var(--luxury-black);
  margin-bottom: 8px;
  font-size: 0.9rem;
}

.form-label i {
  color: var(--primary-red);
  width: 16px;
}

.form-input, .form-textarea {
  width: 100%;
  padding: 12px 15px;
  border: 2px solid var(--medium-gray);
  border-radius: 6px;
  font-size: 1rem;
  transition: var(--transition);
  background: var(--pure-white);
}

.form-input:focus, .form-textarea:focus {
  outline: none;
  border-color: var(--primary-red);
  box-shadow: 0 0 0 3px rgba(212, 0, 0, 0.1);
}

.form-textarea {
  resize: vertical;
  min-height: 80px;
  font-family: inherit;
}

.form-actions {
  display: flex;
  gap: 15px;
  justify-content: flex-end;
  margin-top: 30px;
  padding-top: 20px;
  border-top: 1px solid var(--medium-gray);
}

.btn {
  padding: 12px 24px;
  border: none;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-cancel {
  background: var(--medium-gray);
  color: var(--dark-gray);
}

.btn-cancel:hover {
  background: var(--dark-gray);
  color: var(--pure-white);
}

.btn-confirm {
  background: var(--luxury-black);
  color: var(--pure-white);
}

.btn-confirm:hover {
  background: var(--primary-red);
  transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 768px) {
  .modal-overlay {
    padding: 20px 10px;
    align-items: flex-start;
  }

  .modal-content {
    margin-top: 20px;
  }

  .modal-header {
    padding: 20px 20px 0;
  }

  .booking-form {
    padding: 0 20px 20px;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .form-actions {
    flex-direction: column;
  }

  .btn {
    justify-content: center;
  }
}
</style>