<template>
  <div class="appointment-page">
    <h2>Book an Appointment</h2>
    <form @submit.prevent="bookAppointment">
      <input v-model="form.name" type="text" placeholder="Full Name" required />
      <input v-model="form.email" type="email" placeholder="Email" required />
      <input v-model="form.phone" type="text" placeholder="Phone Number" required />
      <input v-model="form.time" type="datetime-local" required />
      <textarea v-model="form.notes" placeholder="Additional Notes"></textarea>
      <button type="submit">Book Appointment</button>
    </form>
  </div>
</template>

<script>
export default {
  props: ['carId'],
  data() {
    return {
      form: {
        name: '',
        email: '',
        phone: '',
        time: '',
        notes: ''
      }
    }
  },
  methods: {
    async bookAppointment() {
      try {
        const res = await fetch('http://localhost/createappointment', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            car_id: this.carId,
            name: this.form.name,
            email: this.form.email,
            phone: this.form.phone,
            appointment_at: this.form.time,
            notes: this.form.notes
          })
        });
        const data = await res.json();
        alert(data.message || 'Appointment booked successfully!');
      } catch (err) {
        console.error(err);
        alert('Failed to book appointment');
      }
    }
  }
}
</script>

<style scoped>
.appointment-page {
  max-width: 400px;
  margin: 30px auto;
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.appointment-page input,
.appointment-page textarea {
  width: 100%;
  margin-bottom: 10px;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 8px;
}
.appointment-page button {
  width: 100%;
  background: #007bff;
  color: white;
  padding: 10px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
}
</style>
