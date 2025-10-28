<template>
  <div class="registration-container">
    <!-- Background Elements -->
    <div class="background-elements">
      <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
      </div>
      <div class="grid-pattern"></div>
      <div class="gradient-spotlight"></div>
    </div>

    <!-- Main Registration Card -->
    <div class="registration-card">
      <!-- Header Section -->
      <div class="registration-header">
        <div class="brand-section">
          <div class="brand-logo">
            <i class="fas fa-crown"></i>
          </div>
          <h1 class="brand-title">AutoElite</h1>
          <p class="brand-tagline">Premium Automotive Management</p>
        </div>
        
        <div class="registration-title">
          <h2>Create Account</h2>
          <p>Join our exclusive automotive network</p>
        </div>
      </div>

      <!-- Registration Form -->
      <form @submit.prevent="handleRegister" class="registration-form">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Name</label>
            <div class="input-wrapper">
              <i class="input-icon fas fa-user"></i>
              <input 
                v-model="name" 
                type="text" 
                class="form-input" 
                placeholder="Enter your name"
                required
              />
              <div class="input-focus-line"></div>
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Email Address</label>
            <div class="input-wrapper">
              <i class="input-icon fas fa-envelope"></i>
              <input 
                v-model="email" 
                type="email" 
                class="form-input" 
                placeholder="Enter your email"
                required
              />
              <div class="input-focus-line"></div>
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Password</label>
            <div class="input-wrapper">
              <i class="input-icon fas fa-lock"></i>
              <input 
                v-model="password" 
                type="password" 
                class="form-input" 
                placeholder="Create a strong password"
                required
              />
              <div class="password-strength" :class="getPasswordStrengthClass">
                <div class="strength-bar" :style="getPasswordStrengthStyle"></div>
              </div>
            </div>
            <div class="password-requirements">
              <span class="requirement" :class="{ met: hasMinLength }">
                <i class="fas" :class="hasMinLength ? 'fa-check' : 'fa-circle'"></i>
                8+ characters
              </span>
              <span class="requirement" :class="{ met: hasUpperCase }">
                <i class="fas" :class="hasUpperCase ? 'fa-check' : 'fa-circle'"></i>
                Uppercase letter
              </span>
              <span class="requirement" :class="{ met: hasNumber }">
                <i class="fas" :class="hasNumber ? 'fa-check' : 'fa-circle'"></i>
                Number
              </span>
            </div>
          </div>
        </div>

        <div class="form-options">
          <label class="checkbox-wrapper">
            <input type="checkbox" v-model="agreeTerms" />
            <span class="checkmark"></span>
            <span class="checkbox-label">
              I agree to the <a href="#" class="link">Terms of Service</a> and <a href="#" class="link">Privacy Policy</a>
            </span>
          </label>
        </div>

        <button 
          type="submit" 
          class="register-button"
          :class="{ loading: isLoading, disabled: !agreeTerms }"
          :disabled="isLoading || !agreeTerms"
        >
          <span class="button-content">
            <i class="fas fa-user-plus"></i>
            <span class="button-text">
              {{ isLoading ? 'Creating Account...' : 'Create Elite Account' }}
            </span>
          </span>
          <div class="button-loader" v-if="isLoading">
            <div class="loader-spinner"></div>
          </div>
        </button>

        <div class="login-redirect">
          <p>Already have an account? <a href="#" @click.prevent="$router.push('/login')" class="login-link">Sign In</a></p>
        </div>
      </form>

      <!-- Success/Error Message -->
      <div class="message-container" v-if="message">
        <div class="message" :class="messageType">
          <i class="message-icon" :class="getMessageIcon"></i>
          <span class="message-text">{{ message }}</span>
          <button @click="message = ''" class="message-close">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Features Side Panel -->
    <div class="features-panel">
      <div class="panel-content">
        <div class="panel-header">
          <h3>Why Join AutoElite?</h3>
          <p>Experience the future of automotive management</p>
        </div>
        
        <div class="features-grid">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-car"></i>
            </div>
            <h4>Vehicle Management</h4>
            <p>Manage your entire luxury fleet with advanced tools and analytics</p>
          </div>
          
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-chart-line"></i>
            </div>
            <h4>Sales Analytics</h4>
            <p>Track performance with real-time data and insights</p>
          </div>
          
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-calendar-alt"></i>
            </div>
            <h4>Appointment System</h4>
            <p>Streamline client bookings and service scheduling</p>
          </div>
          
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-shield-alt"></i>
            </div>
            <h4>Secure Platform</h4>
            <p>Enterprise-grade security for your business data</p>
          </div>
        </div>
        
        <div class="testimonial">
          <div class="testimonial-content">
            <i class="fas fa-quote-left quote-icon"></i>
            <p>"AutoElite transformed our dealership operations. The platform is as premium as the cars we sell."</p>
            <div class="testimonial-author">
              <strong>Sarah Chen</strong>
              <span>CEO, Prestige Motors</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RegistrationComponent',
  data() {
    return {
      name: '',
      email: '',
      password: '',
      agreeTerms: false,
      message: '',
      isLoading: false
    };
  },
  computed: {
    hasMinLength() {
      return this.password.length >= 8;
    },
    hasUpperCase() {
      return /[A-Z]/.test(this.password);
    },
    hasNumber() {
      return /[0-9]/.test(this.password);
    },
    getPasswordStrengthClass() {
      if (this.password.length === 0) return '';
      const strength = [this.hasMinLength, this.hasUpperCase, this.hasNumber].filter(Boolean).length;
      return `strength-${strength}`;
    },
    getPasswordStrengthStyle() {
      if (this.password.length === 0) return { width: '0%' };
      const strength = [this.hasMinLength, this.hasUpperCase, this.hasNumber].filter(Boolean).length;
      const width = (strength / 3) * 100;
      return { width: `${width}%` };
    },
    getMessageIcon() {
      return this.messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    },
    messageType() {
      return this.message.includes('successful') ? 'success' : 'error';
    }
  },
  methods: {
    async handleRegister() {
      this.isLoading = true;
      this.message = '';

      try {
        // Simulate API call
        await new Promise(resolve => setTimeout(resolve, 2000));
        
        const response = await fetch('http://localhost:8000/api/users/register', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            name: this.name,
            email: this.email,
            password: this.password
          })
        });

        const data = await response.json();
        console.log(data);

        if (data.status === 'succcess') {
          this.message = 'Registration successful! Welcome to AutoElite. Redirecting to login...';
          setTimeout(() => this.$router.push('/login'), 2000);
        } else {
          this.message = data.message || 'Registration failed. Please try again.';
        }
      } catch (err) {
        this.message = 'Registration failed. Please check your connection and try again.';
      } finally {
        this.isLoading = false;
      }
    }
  }
};
</script>

<style scoped>
/* Base Styles */
.registration-container {
  min-height: 100vh;
  display: flex;
  background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #2a1a1a 100%);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  position: relative;
  overflow: hidden;
}

/* Background Elements */
.background-elements {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1;
}

.floating-shapes {
  position: absolute;
  width: 100%;
  height: 100%;
}

.shape {
  position: absolute;
  border: 1px solid rgba(229, 57, 53, 0.1);
  border-radius: 8px;
  animation: float 6s ease-in-out infinite;
}

.shape-1 {
  width: 120px;
  height: 120px;
  top: 10%;
  left: 5%;
  animation-delay: 0s;
}

.shape-2 {
  width: 80px;
  height: 80px;
  top: 70%;
  left: 10%;
  animation-delay: -2s;
}

.shape-3 {
  width: 60px;
  height: 60px;
  top: 30%;
  right: 15%;
  animation-delay: -4s;
}

.grid-pattern {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: 
    linear-gradient(rgba(229, 57, 53, 0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(229, 57, 53, 0.03) 1px, transparent 1px);
  background-size: 50px 50px;
}

.gradient-spotlight {
  position: absolute;
  top: -50%;
  right: -50%;
  width: 100%;
  height: 100%;
  background: radial-gradient(circle, rgba(229, 57, 53, 0.05) 0%, transparent 70%);
  filter: blur(40px);
}

/* Registration Card */
.registration-card {
  flex: 0 0 500px;
  background: rgba(18, 18, 18, 0.95);
  backdrop-filter: blur(20px);
  border-right: 1px solid rgba(255, 255, 255, 0.1);
  padding: 60px 50px;
  display: flex;
  flex-direction: column;
  position: relative;
  z-index: 2;
  box-shadow: 
    0 0 100px rgba(229, 57, 53, 0.1),
    inset 0 1px 0 rgba(255, 255, 255, 0.05);
}

/* Header Section */
.registration-header {
  margin-bottom: 50px;
}

.brand-section {
  text-align: center;
  margin-bottom: 40px;
}

.brand-logo {
  width: 60px;
  height: 60px;
  background: linear-gradient(45deg, #e53935, #d32f2f);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 20px;
  font-size: 1.5rem;
  color: white;
  box-shadow: 0 8px 25px rgba(229, 57, 53, 0.3);
}

.brand-title {
  font-size: 2.5rem;
  font-weight: 700;
  background: linear-gradient(45deg, #fff, #e53935);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin-bottom: 8px;
}

.brand-tagline {
  color: rgba(255, 255, 255, 0.6);
  font-size: 1rem;
  font-weight: 300;
  letter-spacing: 0.5px;
}

.registration-title h2 {
  font-size: 2rem;
  font-weight: 600;
  color: #fff;
  margin-bottom: 8px;
}

.registration-title p {
  color: rgba(255, 255, 255, 0.6);
  font-size: 1rem;
}

/* Form Styles */
.registration-form {
  display: flex;
  flex-direction: column;
  gap: 25px;
}

.form-row {
  display: flex;
  gap: 20px;
}

.form-group {
  flex: 1;
  position: relative;
}

.form-label {
  display: block;
  color: rgba(255, 255, 255, 0.8);
  font-size: 0.9rem;
  font-weight: 500;
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.input-wrapper {
  position: relative;
}

.input-icon {
  position: absolute;
  left: 16px;
  top: 50%;
  transform: translateY(-50%);
  color: rgba(255, 255, 255, 0.5);
  font-size: 1rem;
  z-index: 2;
  transition: all 0.3s ease;
}

.form-input {
  width: 100%;
  padding: 16px 16px 16px 48px;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 8px;
  color: #fff;
  font-size: 1rem;
  transition: all 0.3s ease;
  backdrop-filter: blur(10px);
}

.form-input::placeholder {
  color: rgba(255, 255, 255, 0.4);
}

.form-input:focus {
  outline: none;
  border-color: rgba(229, 57, 53, 0.5);
  background: rgba(255, 255, 255, 0.08);
}

.form-input:focus + .input-focus-line {
  transform: scaleX(1);
}

.input-focus-line {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 2px;
  background: linear-gradient(90deg, #e53935, #ff6b6b);
  transform: scaleX(0);
  transition: transform 0.3s ease;
  border-radius: 0 0 8px 8px;
}

/* Password Strength */
.password-strength {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 3px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 0 0 8px 8px;
  overflow: hidden;
}

.strength-bar {
  height: 100%;
  background: #e53935;
  transition: all 0.3s ease;
  width: 0%;
}

.password-strength.strength-1 .strength-bar {
  background: #ef4444;
  width: 33%;
}

.password-strength.strength-2 .strength-bar {
  background: #f59e0b;
  width: 66%;
}

.password-strength.strength-3 .strength-bar {
  background: #10b981;
  width: 100%;
}

.password-requirements {
  display: flex;
  gap: 15px;
  margin-top: 8px;
  flex-wrap: wrap;
}

.requirement {
  display: flex;
  align-items: center;
  gap: 6px;
  color: rgba(255, 255, 255, 0.5);
  font-size: 0.8rem;
  transition: all 0.3s ease;
}

.requirement.met {
  color: #10b981;
}

.requirement i {
  font-size: 0.7rem;
}

/* Form Options */
.form-options {
  margin: 10px 0;
}

.checkbox-wrapper {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.9rem;
  cursor: pointer;
  line-height: 1.4;
}

.checkbox-wrapper input {
  display: none;
}

.checkmark {
  width: 18px;
  height: 18px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-radius: 4px;
  position: relative;
  transition: all 0.3s ease;
  flex-shrink: 0;
  margin-top: 2px;
}

.checkbox-wrapper input:checked + .checkmark {
  background: #e53935;
  border-color: #e53935;
}

.checkbox-wrapper input:checked + .checkmark::after {
  content: '✓';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: white;
  font-size: 12px;
  font-weight: bold;
}

.link {
  color: #e53935;
  text-decoration: none;
  transition: color 0.3s ease;
}

.link:hover {
  color: #ff6b6b;
}

/* Register Button */
.register-button {
  position: relative;
  padding: 18px 30px;
  background: linear-gradient(45deg, #e53935, #d32f2f);
  border: none;
  border-radius: 8px;
  color: white;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  overflow: hidden;
  margin-top: 10px;
}

.register-button::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s ease;
}

.register-button:hover::before {
  left: 100%;
}

.register-button:hover:not(.disabled) {
  transform: translateY(-2px);
  box-shadow: 
    0 10px 25px rgba(229, 57, 53, 0.4),
    0 0 0 1px rgba(255, 255, 255, 0.1);
}

.register-button:active:not(.disabled) {
  transform: translateY(0);
}

.register-button.loading {
  pointer-events: none;
  opacity: 0.8;
}

.register-button.disabled {
  background: rgba(255, 255, 255, 0.1);
  color: rgba(255, 255, 255, 0.4);
  cursor: not-allowed;
}

.button-content {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.button-loader {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.loader-spinner {
  width: 20px;
  height: 20px;
  border: 2px solid transparent;
  border-top: 2px solid white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

/* Login Redirect */
.login-redirect {
  text-align: center;
  margin-top: 20px;
}

.login-redirect p {
  color: rgba(255, 255, 255, 0.6);
  font-size: 0.9rem;
}

.login-link {
  color: #e53935;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s ease;
}

.login-link:hover {
  color: #ff6b6b;
}

/* Message Container */
.message-container {
  margin-top: 20px;
}

.message {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px 20px;
  border-radius: 8px;
  backdrop-filter: blur(10px);
  animation: slideInUp 0.3s ease;
}

.message.success {
  background: rgba(16, 185, 129, 0.1);
  border: 1px solid rgba(16, 185, 129, 0.3);
  color: #10b981;
}

.message.error {
  background: rgba(239, 68, 68, 0.1);
  border: 1px solid rgba(239, 68, 68, 0.3);
  color: #ef4444;
}

.message-icon {
  font-size: 1.2rem;
  flex-shrink: 0;
}

.message-text {
  flex: 1;
  font-weight: 500;
}

.message-close {
  background: none;
  border: none;
  color: inherit;
  cursor: pointer;
  padding: 4px;
  border-radius: 4px;
  transition: background 0.3s ease;
  flex-shrink: 0;
}

.message-close:hover {
  background: rgba(255, 255, 255, 0.1);
}

/* Features Panel */
.features-panel {
  flex: 1;
  background: linear-gradient(135deg, rgba(229, 57, 53, 0.02) 0%, transparent 100%);
  backdrop-filter: blur(10px);
  padding: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  z-index: 2;
}

.panel-content {
  max-width: 600px;
  width: 100%;
}

.panel-header {
  text-align: center;
  margin-bottom: 50px;
}

.panel-header h3 {
  font-size: 2.2rem;
  font-weight: 600;
  color: #fff;
  margin-bottom: 12px;
}

.panel-header p {
  color: rgba(255, 255, 255, 0.6);
  font-size: 1.1rem;
}

.features-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 25px;
  margin-bottom: 50px;
}

.feature-card {
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.05);
  border-radius: 12px;
  padding: 25px;
  transition: all 0.3s ease;
  backdrop-filter: blur(10px);
}

.feature-card:hover {
  background: rgba(255, 255, 255, 0.05);
  transform: translateY(-5px);
  border-color: rgba(229, 57, 53, 0.2);
}

.feature-icon {
  width: 50px;
  height: 50px;
  background: linear-gradient(45deg, #e53935, #d32f2f);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
  font-size: 1.2rem;
  color: white;
}

.feature-card h4 {
  color: #fff;
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 10px;
}

.feature-card p {
  color: rgba(255, 255, 255, 0.6);
  font-size: 0.9rem;
  line-height: 1.5;
}

.testimonial {
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.05);
  border-radius: 12px;
  padding: 30px;
  backdrop-filter: blur(10px);
}

.testimonial-content {
  position: relative;
}

.quote-icon {
  position: absolute;
  top: -10px;
  left: -10px;
  font-size: 2rem;
  color: rgba(229, 57, 53, 0.3);
}

.testimonial-content p {
  color: rgba(255, 255, 255, 0.8);
  font-style: italic;
  line-height: 1.6;
  margin-bottom: 20px;
  padding-left: 30px;
}

.testimonial-author strong {
  color: #fff;
  display: block;
  margin-bottom: 4px;
}

.testimonial-author span {
  color: rgba(255, 255, 255, 0.6);
  font-size: 0.9rem;
}

/* Animations */
@keyframes float {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-20px) rotate(2deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive Design */
@media (max-width: 1024px) {
  .features-panel {
    display: none;
  }
  
  .registration-card {
    flex: 1;
    max-width: 100%;
    margin: 0 auto;
  }
}

@media (max-width: 768px) {
  .registration-container {
    padding: 20px;
  }
  
  .registration-card {
    padding: 40px 30px;
  }
  
  .form-row {
    flex-direction: column;
    gap: 15px;
  }
  
  .features-grid {
    grid-template-columns: 1fr;
  }
  
  .brand-title {
    font-size: 2rem;
  }
}

@media (max-width: 480px) {
  .registration-card {
    padding: 30px 20px;
  }
  
  .password-requirements {
    flex-direction: column;
    gap: 8px;
  }
  
  .brand-title {
    font-size: 1.8rem;
  }
}
</style>