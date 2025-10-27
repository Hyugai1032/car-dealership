import { createApp } from 'vue'
import App from './App.vue'
import Users from './Users.vue'

// Global CSS
const styles = `
:root {
  --primary-red: #d40000;
  --dark-red: #a80000;
  --luxury-black: #1a1a1a;
  --soft-black: #2d2d2d;
  --pure-white: #ffffff;
  --light-gray: #f5f5f5;
  --medium-gray: #e0e0e0;
  --dark-gray: #666666;
  --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  --transition: all 0.3s ease;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(135deg, var(--light-gray) 0%, var(--medium-gray) 100%);
  min-height: 100vh;
  color: var(--luxury-black);
  line-height: 1.6;
}

#app {
  min-height: 100vh;
}
`

// Inject global styles
const styleSheet = document.createElement('style')
styleSheet.textContent = styles
document.head.appendChild(styleSheet)

// Create and mount the app
createApp(App).mount('#app')
