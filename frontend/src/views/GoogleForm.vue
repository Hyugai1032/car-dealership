<template>
  <div class="feedback-wrapper">
    <div class="header">
      <h1>We Value Your Feedback ❤️</h1>
      <p>Your insights help us improve and build a better experience.</p>
    </div>

    <div class="form-container">
      <iframe
        :src="formUrl"
        frameborder="0"
        marginheight="0"
        marginwidth="0"
        class="google-form"
        allowfullscreen
      >
        Loading…
      </iframe>
    </div>
  </div>
</template>

<script>
export default {
  name: "UserFeedbackForm",
  data() {
    return {
      formUrl: "https://docs.google.com/forms/d/e/1FAIpQLSf3qlrFTZdg5Ec53aJscTlBPbqXirqJyx648YVSUy-iYeNtIw/viewform?usp=header"
    };
  }
};
</script>

<style scoped>
.feedback-wrapper {
  min-height: 100vh;
  background: #ffffff;
  padding: 50px 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  animation: fadeIn 1s ease-in-out;
}

.header {
  text-align: center;
  margin-bottom: 30px;
}

.header h1 {
  font-size: 2.8rem;
  font-weight: 700;
  color: #cc0000; /* RED highlight */
  margin-bottom: 10px;
}

.header p {
  font-size: 1.2rem;
  color: #555;
  max-width: 600px;
}

.form-container {
  width: 100%;
  max-width: 900px;
  height: 85vh;

  /* ✨ GLASS EFFECT */
  background: rgba(255, 255, 255, 0.8);
  border-radius: 20px;
  padding: 20px;
  box-shadow: 0 20px 40px rgba(204, 0, 0, 0.15);
  border: 1px solid rgba(204, 0, 0, 0.2);
  backdrop-filter: blur(10px);
  animation: floatUp 1s ease-out;
}

.google-form {
  width: 100%;
  height: 100%;
  border-radius: 15px;
  border: none;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}

@keyframes floatUp {
  from { opacity: 0; transform: translateY(30px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>
