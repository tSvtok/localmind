<template>
  <div class="split-container">
    <!-- Visual Side -->
    <div class="visual-side">
      <div class="visual-content">
        <div class="visual-icon">
          <i class="fa-solid fa-street-view"></i>
        </div>
        <h1 class="visual-title">Connectez-vous a votre quartier.</h1>
        <p class="visual-text">
          Rejoignez LOCALmind pour echanger avec vos voisins, poser des questions et decouvrir ce qui se passe autour de vous. Une communaute bienveillante vous attend.
        </p>
      </div>
    </div>

    <!-- Form Side -->
    <div class="form-side">
      <div class="form-wrapper">

        <!-- Login Form -->
        <div v-if="!showRegister" class="fade-in">
          <div class="form-header">
            <h2 class="form-title">Bon retour !</h2>
            <p class="form-subtitle">Entrez vos identifiants pour acceder a votre compte.</p>
          </div>

          <form @submit.prevent="handleLogin">
            <div class="input-group">
              <label class="label">Adresse Email</label>
              <input v-model="loginForm.email" type="email" class="input-field" placeholder="exemple@email.com" required>
            </div>
            <div class="input-group">
              <label class="label">Mot de passe</label>
              <input v-model="loginForm.password" type="password" class="input-field" placeholder="********" required>
            </div>
            <p v-if="error" class="error-msg">{{ error }}</p>
            <button type="submit" class="btn-submit" :disabled="loading">
              {{ loading ? 'Connexion...' : 'Se connecter' }}
            </button>
          </form>

          <p class="switch-auth">
            Pas encore de compte ? <a class="switch-link" @click="showRegister = true">Creer un compte</a>
          </p>
        </div>

        <!-- Register Form -->
        <div v-else class="fade-in">
          <div class="form-header">
            <h2 class="form-title">Creer un compte</h2>
            <p class="form-subtitle">Rejoignez la communaute en quelques secondes.</p>
          </div>

          <form @submit.prevent="handleRegister">
            <div class="input-group">
              <label class="label">Nom complet</label>
              <input v-model="registerForm.fullname" type="text" class="input-field" placeholder="John Doe" required>
            </div>
            <div class="input-group">
              <label class="label">Ville de residence</label>
              <input v-model="registerForm.location" type="text" class="input-field" placeholder="Ex: Lyon" required>
            </div>
            <div class="input-group">
              <label class="label">Adresse Email</label>
              <input v-model="registerForm.email" type="email" class="input-field" placeholder="exemple@email.com" required>
            </div>
            <div class="input-group">
              <label class="label">Mot de passe</label>
              <input v-model="registerForm.password" type="password" class="input-field" placeholder="********" required>
            </div>
            <p v-if="error" class="error-msg">{{ error }}</p>
            <button type="submit" class="btn-submit" :disabled="loading">
              {{ loading ? 'Inscription...' : "S'inscrire" }}
            </button>
          </form>

          <p class="switch-auth">
            Deja inscrit ? <a class="switch-link" @click="showRegister = false">Me connecter</a>
          </p>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import authApi from '../api/auth'

export default {
  name: 'LoginView',
  data() {
    return {
      showRegister: false,
      loading: false,
      error: '',
      loginForm: {
        email: '',
        password: '',
      },
      registerForm: {
        fullname: '',
        email: '',
        password: '',
        location: '',
      },
    }
  },
  methods: {
    async handleLogin() {
      this.loading = true
      this.error = ''
      try {
        const data = await authApi.login(this.loginForm.email, this.loginForm.password)
        localStorage.setItem('token', data.token)
        localStorage.setItem('user', JSON.stringify(data.user))

        if (data.user.role === 'admin') {
          this.$router.push('/admin')
        } else {
          this.$router.push('/feed')
        }
      } catch (err) {
        this.error = err.response?.data?.message || 'Les identifiants sont incorrects.'
      } finally {
        this.loading = false
      }
    },

    async handleRegister() {
      this.loading = true
      this.error = ''
      try {
        const data = await authApi.register(
          this.registerForm.fullname,
          this.registerForm.email,
          this.registerForm.password,
          this.registerForm.location
        )
        localStorage.setItem('token', data.token)
        localStorage.setItem('user', JSON.stringify(data.user))
        this.$router.push('/feed')
      } catch (err) {
        this.error = err.response?.data?.message || "Erreur lors de l'inscription."
      } finally {
        this.loading = false
      }
    },
  },
}
</script>

<style scoped>
.split-container {
  display: flex;
  width: 100%;
  min-height: 100vh;
}

.visual-side {
  flex: 1;
  background: linear-gradient(135deg, #4f46e5 0%, #818cf8 100%);
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 60px;
  color: white;
  position: relative;
  overflow: hidden;
}

.visual-side::after {
  content: '';
  position: absolute;
  width: 150%;
  height: 150%;
  background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
  top: -25%;
  left: -25%;
}

.visual-content {
  position: relative;
  z-index: 10;
  max-width: 500px;
}

.visual-icon {
  font-size: 48px;
  margin-bottom: 32px;
  background: rgba(255,255,255,0.2);
  width: 80px;
  height: 80px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  backdrop-filter: blur(10px);
}

.visual-title {
  font-size: 48px;
  font-weight: 800;
  margin-bottom: 24px;
  line-height: 1.1;
}

.visual-text {
  font-size: 18px;
  line-height: 1.6;
  opacity: 0.9;
}

.form-side {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px;
  background: #ffffff;
}

.form-wrapper {
  width: 100%;
  max-width: 420px;
}

.fade-in {
  animation: fadeIn 0.4s ease forwards;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.form-header { text-align: center; margin-bottom: 40px; }
.form-title { font-size: 28px; font-weight: 800; margin-bottom: 8px; color: #0f172a; }
.form-subtitle { color: #64748b; font-size: 15px; }

.input-group { margin-bottom: 20px; }
.label { display: block; font-size: 13px; font-weight: 700; margin-bottom: 8px; color: #0f172a; }

.input-field {
  width: 100%;
  padding: 14px 16px;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  font-family: inherit;
  font-size: 14px;
  transition: all 0.2s;
  box-sizing: border-box;
}

.input-field:focus {
  outline: none;
  border-color: #6366f1;
  background: white;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.btn-submit {
  width: 100%;
  padding: 14px;
  background: #6366f1;
  color: white;
  border: none;
  border-radius: 12px;
  font-weight: 700;
  font-size: 15px;
  cursor: pointer;
  transition: all 0.2s;
  margin-top: 10px;
}

.btn-submit:hover { background: #4f46e5; transform: translateY(-1px); }
.btn-submit:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

.switch-auth {
  text-align: center;
  margin-top: 24px;
  font-size: 14px;
  color: #64748b;
}

.switch-link {
  color: #6366f1;
  font-weight: 700;
  text-decoration: none;
  cursor: pointer;
}

.switch-link:hover { text-decoration: underline; }

.error-msg {
  color: #ef4444;
  font-size: 13px;
  margin-bottom: 10px;
  text-align: center;
}

@media (max-width: 900px) {
  .split-container { flex-direction: column; }
  .visual-side { padding: 40px; min-height: 300px; flex: none; }
  .visual-title { font-size: 32px; }
  .form-side { flex: 1; padding: 40px 20px; }
}
</style>
