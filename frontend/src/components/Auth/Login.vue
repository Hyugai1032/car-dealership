<template>
  <div class="login-container">
    <!-- Background Elements -->
    <div class="background-elements">
      <div class="floating-car"></div>
      <div class="luxury-pattern"></div>
      <div class="gradient-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
      </div>
    </div>

    <!-- Main Login Card -->
    <div class="login-card">
      <!-- Header -->
      <div class="login-header">
        <div class="brand-logo">
          <i class="fas fa-crown"></i>
          <span class="brand-text">RideZone</span>
        </div>
        <h1 class="welcome-text">Welcome Back</h1>
        <p class="subtitle">Access your luxury automotive dashboard</p>
      </div>

      <!-- Email/Password Form -->
      <form @submit.prevent="handleLogin" class="login-form">
        <div class="form-group">
          <div class="input-container">
            <i class="input-icon fas fa-user"></i>
            <input 
              v-model="email" 
              type="text" 
              class="form-input" 
              placeholder="Email or Username" 
              required 
            />
            <div class="input-underline"></div>
          </div>
        </div>

        <div class="form-group">
          <div class="input-container">
            <i class="input-icon fas fa-lock"></i>
            <input 
              v-model="password" 
              type="password" 
              class="form-input" 
              placeholder="Enter your password" 
              required 
            />
            <div class="input-underline"></div>
          </div>
        </div>

        <div class="form-options">
          <label class="checkbox-container">
            <input type="checkbox" v-model="rememberMe" />
            <span class="checkmark"></span>
            Remember me
          </label>
          <a href="#" class="forgot-password">Forgot Password?</a>
        </div>

        <button type="submit" class="login-button" :disabled="isLoading">
          <span class="button-text">
            <i class="fas fa-key"></i>
            {{ isLoading ? 'Authenticating...' : 'Access Dashboard' }}
          </span>
          <div class="button-loader" v-if="isLoading">
            <div class="loader-spinner"></div>
          </div>
        </button>
      </form>

      <!-- GOOGLE SIGN-IN BUTTON -->
      <div class="divider">
        <span>or</span>
      </div>
      <div class="google-login">
        <div id="g_id_signin" class="g_id_signin" data-type="standard"></div>
      </div>

      <!-- Registration Link -->
      <div class="registration-section">
        <p>Don't have an account? <router-link to="/register" class="login-link">Sign Up</router-link></p>
      </div>

      <!-- Error Message -->
      <div class="error-message" v-if="errorMessage" @click="errorMessage = ''">
        <i class="fas fa-exclamation-circle"></i>
        {{ errorMessage }}
        <i class="fas fa-times close-error"></i>
      </div>

      <!-- Success Message (optional) -->
      <div class="success-message" v-if="successMessage">
        <i class="fas fa-check-circle"></i> {{ successMessage }}
      </div>
    </div>

    <!-- Decorative Panel (same as before) -->
    <div class="decorative-panel">
      <div class="panel-content">
        <div class="luxury-badge"><i class="fas fa-gem"></i></div>
        <h3 class="panel-title">Exclusive Access</h3>
        <p class="panel-description">
          Manage luxury vehicles, client appointments, and dealership operations with our premium dashboard.
        </p>
        <div class="feature-list">
          <div class="feature-item"><i class="fas fa-car"></i><span>Vehicle Management</span></div>
          <div class="feature-item"><i class="fas fa-calendar-alt"></i><span>Appointment Scheduling</span></div>
          <div class="feature-item"><i class="fas fa-chart-line"></i><span>Sales Analytics</span></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LoginComponent',
  data() {
    return {
      email: '',
      password: '',
      rememberMe: false,
      errorMessage: '',
      successMessage: '',
      isLoading: false
    }
  },

  mounted() {
    this.loadGoogleSignIn()
  },

  methods: {
    // ===================== GOOGLE SIGN IN =====================
    loadGoogleSignIn() {
      if (window.google?.accounts?.id) {
        this.renderGoogleButton()
        return
      }

      const script = document.createElement('script')
      script.src = 'https://accounts.google.com/gsi/client'
      script.async = true
      script.defer = true
      script.onload = () => this.renderGoogleButton()
      document.head.appendChild(script)
    },

    renderGoogleButton() {
      window.google.accounts.id.initialize({
        client_id: '1084979266133-d1bvpmpb5devqn5cl0pscuv9k01l9p9t.apps.googleusercontent.com',
        callback: (response) => {
          this.processGoogleLogin(response.credential)
        }
      })

      window.google.accounts.id.renderButton(
        document.getElementById('g_id_signin'),
        {
          theme: 'outline',
          size: 'large',
          width: 350,
          text: 'signin_with',
          shape: 'rectangular',
          logo_alignment: 'left'
        }
      )
    },

async processGoogleLogin(token) {
  const res = await fetch('http://localhost:8000/auth/google', {
    method: 'POST',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ credential: token })
  });

  const data = await res.json();

  if (data.success) {
    localStorage.setItem('user', JSON.stringify(data.user));
    localStorage.setItem('logged_in', 'true');
    this.$router.push('/dashboard');
  } else {
    alert('Login failed: ' + data.error);
  }
},

    // ===================== EMAIL/PASSWORD LOGIN =====================
        async handleLogin() {
      if (!this.email || !this.password) {
        this.errorMessage = 'Please fill in all fields';
        return;
      }

      this.isLoading = true;
      this.errorMessage = '';

      try {
        const response = await fetch('http://localhost:8000/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
          body: JSON.stringify({ email: this.email, password: this.password })
        });

        const data = await response.json();

        if (response.ok && data.status === 'success') {
          localStorage.setItem('user', JSON.stringify(data.user));
          localStorage.setItem('logged_in', 'true');

          // Emit event so sidebar updates immediately
          window.dispatchEvent(new CustomEvent('user-logged-in', { detail: data.user }));

          this.successMessage = 'Login successful! Redirecting...';
          setTimeout(() => this.$router.push('/dashboard'), 800);
        } else {
          this.errorMessage = data.message || 'Invalid email/username or password';
        }
      } catch (err) {
        console.error(err);
        this.errorMessage = 'Server not responding. Check if backend is running.';
      } finally {
        this.isLoading = false;
      }
    }

  }
}
</script>

<style scoped>
/* LAHAT NG STYLE MO DITO — HINDI KO GINALAW */
.divider {
  text-align: center;
  margin: 25px 0;
  position: relative;
  color: rgba(255,255,255,0.5);
  font-size: 0.9rem;
}
.divider::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  height: 1px;
  background: rgba(255,255,255,0.1);
}
.divider span {
  background: #1a1a1a;
  padding: 0 15px;
}
.success-message {
  background: rgba(76, 175, 80, 0.15);
  border: 1px solid rgba(76, 175, 80, 0.4);
  color: #a5d6a7;
  padding: 15px;
  border-radius: 10px;
  text-align: center;
  margin-top: 20px;
}
.login-container { min-height: 100vh; display: flex; background: linear-gradient(135deg, #0c0c0c 0%, #1a1a1a 50%, #2d1b1b 100%); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; position: relative; overflow: hidden; }
.background-elements { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; }
.floating-car { position: absolute; top: 20%; right: 10%; width: 500px; height: 250px; background: linear-gradient(45deg, transparent 30%, rgba(229, 57, 53, 0.1) 50%, transparent 70%); border-radius: 20px; animation: float 6s ease-in-out infinite; filter: blur(1px); }
.luxury-pattern { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: radial-gradient(circle at 20% 80%, rgba(229, 57, 53, 0.05) 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(229, 57, 53, 0.03) 0%, transparent 50%); }
.gradient-orbs { position: absolute; width: 100%; height: 100%; }
.orb { position: absolute; border-radius: 50%; filter: blur(40px); animation: orbFloat 8s ease-in-out infinite; }
.orb-1 { width: 300px; height: 300px; background: radial-gradient(circle, rgba(229, 57, 53, 0.15) 0%, transparent 70%); top: 10%; left: 5%; }
.orb-2 { width: 200px; height: 200px; background: radial-gradient(circle, rgba(229, 57, 53, 0.1) 0%, transparent 70%); bottom: 20%; right: 10%; animation-delay: -2s; }
.orb-3 { width: 150px; height: 150px; background: radial-gradient(circle, rgba(229, 57, 53, 0.08) 0%, transparent 70%); top: 50%; left: 20%; animation-delay: -4s; }
.login-card { flex: 1; max-width: 480px; background: rgba(255,255,255,0.02); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); padding: 60px 50px; display: flex; flex-direction: column; justify-content: center; position: relative; z-index: 2; box-shadow: 0 25px 50px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.1); }
.login-header { text-align: center; margin-bottom: 50px; }
.brand-logo { display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 30px; }
.brand-logo i { font-size: 2rem; color: #e53935; filter: drop-shadow(0 0 10px rgba(229,57,53,0.5)); }
.brand-text { font-size: 2rem; font-weight: 700; background: linear-gradient(45deg, #fff, #e53935); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.welcome-text { font-size: 2.5rem; font-weight: 300; color: #fff; margin-bottom: 10px; letter-spacing: 1px; }
.subtitle { color: rgba(255,255,255,0.6); font-size: 1.1rem; font-weight: 300; }
.google-login { display: flex; justify-content: center; margin: 30px 0; }
.g_id_signin { width: 350px !important; }
.registration-section { margin-top: 20px; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.1); }
.login-redirect { text-align: center; margin-top: 2px; }
.login-redirect p { color: rgba(255,255,255,0.6); font-size: 0.95rem; }
.login-link { color: #e53935; text-decoration: none; font-weight: 600; border-bottom: 1px solid transparent; transition: all 0.3s ease; }
.login-link:hover { color: #ff6b6b; border-bottom-color: #ff6b6b; }
.login-form { display: flex; flex-direction: column; gap: 25px; }
.form-group { position: relative; }
.input-container { position: relative; }
.input-icon { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,0.5); font-size: 1.1rem; z-index: 2; }
.form-input { width: 100%; padding: 18px 20px 18px 55px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; color: #fff; font-size: 1rem; transition: all 0.3s ease; backdrop-filter: blur(10px); }
.form-input::placeholder { color: rgba(255,255,255,0.4); }
.form-input:focus { outline: none; border-color: rgba(229,57,53,0.5); background: rgba(255,255,255,0.08); box-shadow: 0 0 0 2px rgba(229,57,53,0.1); }
.input-underline { position: absolute; bottom: 0; left: 0; width: 100%; height: 2px; background: linear-gradient(90deg, #e53935, #ff6b6b); transform: scaleX(0); transition: transform 0.3s ease; }
.form-options { display: flex; justify-content: space-between; align-items: center; margin: 10px 0; }
.checkbox-container { display: flex; align-items: center; gap: 10px; color: rgba(255,255,255,0.7); font-size: 0.9rem; cursor: pointer; }
.checkbox-container input { display: none; }
.checkmark { width: 18px; height: 18px; border: 2px solid rgba(255,255,255,0.3); border-radius: 4px; position: relative; transition: all 0.3s ease; }
.checkbox-container input:checked + .checkmark { background: #e53935; border-color: #e53935; }
.checkbox-container input:checked + .checkmark::after { content: '✓'; position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); color: white; font-size: 12px; font-weight: bold; }
.forgot-password { color: rgba(255,255,255,0.6); text-decoration: none; font-size: 0.9rem; transition: color 0.3s ease; }
.forgot-password:hover { color: #e53935; }
.login-button { position: relative; padding: 18px 30px; background: linear-gradient(45deg, #e53935, #d32f2f); border: none; border-radius: 12px; color: white; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; overflow: hidden; margin-top: 5px; }
.login-button:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(229,57,53,0.4), 0 0 0 1px rgba(255,255,255,0.1); }
.login-button.loading { pointer-events: none; opacity: 0.8; }
.button-loader { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); }
.loader-spinner { width: 20px; height: 20px; border: 2px solid transparent; border-top: 2px solid white; border-radius: 50%; animation: spin 1s linear infinite; }
.error-message { background: rgba(229,57,53,0.1); border: 1px solid rgba(229,57,53,0.3); border-radius: 10px; padding: 15px 20px; color: #ff6b6b; display: flex; align-items: center; gap: 12px; margin-top: 20px; cursor: pointer; backdrop-filter: blur(10px); }
.close-error { margin-left: auto; opacity: 0.7; }
.login-footer { margin-top: 5px; text-align: center; }
.footer-text { color: rgba(255,255,255,0.4); font-size: 0.9rem; }
.version { color: rgba(229,57,53,0.6); font-weight: 600; }
.decorative-panel { flex: 1; background: linear-gradient(135deg, rgba(229,57,53,0.05) 0%, transparent 100%); backdrop-filter: blur(10px); border-left: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; padding: 60px; position: relative; z-index: 2; }
.panel-content { max-width: 500px; text-align: center; }
.luxury-badge { width: 80px; height: 80px; background: linear-gradient(45deg, #e53935, #d32f2f); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; font-size: 2rem; color: white; box-shadow: 0 10px 30px rgba(229,57,53,0.3); }
.panel-title { font-size: 2.2rem; font-weight: 300; color: #fff; margin-bottom: 20px; letter-spacing: 1px; }
.panel-description { color: rgba(255,255,255,0.7); font-size: 1.1rem; line-height: 1.6; margin-bottom: 40px; }
.feature-list { display: flex; flex-direction: column; gap: 20px; }
.feature-item { display: flex; align-items: center; gap: 15px; color: rgba(255,255,255,0.8); font-size: 1rem; padding: 15px 25px; background: rgba(255,255,255,0.03); border-radius: 10px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s ease; }
.feature-item:hover { background: rgba(255,255,255,0.05); transform: translateX(10px); }
.feature-item i { color: #e53935; font-size: 1.2rem; width: 20px; }
@keyframes float { 0%,100% { transform: translateY(0px) rotate(0deg); } 50% { transform: translateY(-20px) rotate(1deg); } }
@keyframes orbFloat { 0%,100% { transform: translateY(0px) scale(1); } 50% { transform: translateY(-20px) scale(1.1); } }
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
@media (max-width: 1024px) { .decorative-panel { display: none; } .login-card { max-width: 100%; margin: 0 auto; } }
@media (max-width: 768px) { .login-card { padding: 40px 30px; } .welcome-text { font-size: 2rem; } .brand-text { font-size: 1.8rem; } }
@media (max-width: 480px) { .login-card { padding: 30px 20px; } .form-options { flex-direction: column; gap: 15px; align-items: flex-start; } }
</style>