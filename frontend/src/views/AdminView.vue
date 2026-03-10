<template>
  <div class="app-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
      <a href="#" class="brand">
        <div class="brand-icon">
          <i class="fa-solid fa-layer-group"></i>
        </div>
        <span class="brand-text">Admin</span>
      </a>

      <nav class="nav-menu">
        <a href="#" class="nav-item active">
          <i class="fa-solid fa-chart-pie"></i>
          <span>Vue d'ensemble</span>
        </a>
        <a href="#questions" class="nav-item">
          <i class="fa-solid fa-message"></i>
          <span>Questions</span>
        </a>
        <a href="#responses" class="nav-item">
          <i class="fa-solid fa-comments"></i>
          <span>Reponses</span>
        </a>

        <a href="#" @click.prevent="handleLogout" class="nav-item logout-btn">
          <i class="fa-solid fa-power-off"></i>
          <span>Deconnexion</span>
        </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Top Bar -->
      <div class="top-bar">
        <div class="page-title">
          <h1>Tableau de bord</h1>
          <p>Bienvenue sur votre espace d'administration.</p>
        </div>

        <div class="user-profile">
          <div class="avatar">{{ userInitial }}</div>
          <div class="user-info">
            <span class="user-name">{{ user?.fullname }}</span>
            <span class="user-role">Super Admin</span>
          </div>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="stats-grid">
        <div class="stat-card stat-primary">
          <div class="stat-info">
            <h3>Total Questions</h3>
            <div class="stat-value">{{ stats.total_questions }}</div>
          </div>
          <div class="stat-icon">
            <i class="fa-solid fa-circle-question"></i>
          </div>
        </div>

        <div class="stat-card stat-success">
          <div class="stat-info">
            <h3>Total Reponses</h3>
            <div class="stat-value">{{ stats.total_reponses }}</div>
          </div>
          <div class="stat-icon">
            <i class="fa-solid fa-comment-dots"></i>
          </div>
        </div>

        <div class="stat-card stat-warning">
          <div class="stat-info">
            <h3>Citoyens Inscrits</h3>
            <div class="stat-value">{{ stats.total_users }}</div>
          </div>
          <div class="stat-icon">
            <i class="fa-solid fa-users"></i>
          </div>
        </div>
      </div>

      <!-- Recent Questions Table -->
      <div id="questions" class="section-header">
        <div class="section-title">
          <div class="section-icon"><i class="fa-solid fa-bolt"></i></div>
          Dernieres activites
        </div>
      </div>

      <div class="data-card">
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th style="width: 25%">Auteur</th>
                <th style="width: 35%">Titre du sujet</th>
                <th style="width: 20%">Localisation</th>
                <th style="width: 20%">Date</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="question in questions" :key="question.id">
                <td>
                  <div class="user-cell">
                    <div class="user-cell-avatar">{{ question.user?.fullname?.charAt(0) }}</div>
                    <span>{{ question.user?.fullname }}</span>
                  </div>
                </td>
                <td>{{ question.titre }}</td>
                <td>
                  <span class="badge badge-geo">
                    <i class="fa-solid fa-location-dot"></i> {{ question.city }}
                  </span>
                </td>
                <td>
                  <span class="badge badge-date">{{ formatDate(question.created_at) }}</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Recent Responses Table -->
      <div id="responses" class="section-header">
        <div class="section-title">
          <div class="section-icon"><i class="fa-solid fa-comments"></i></div>
          Dernieres reponses
        </div>
      </div>

      <div class="data-card">
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th style="width: 25%">Auteur</th>
                <th style="width: 35%">Question</th>
                <th style="width: 40%">Reponse</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="reponse in reponses" :key="reponse.id">
                <td>
                  <div class="user-cell">
                    <div class="user-cell-avatar">{{ reponse.user?.fullname?.charAt(0) }}</div>
                    <span>{{ reponse.user?.fullname }}</span>
                  </div>
                </td>
                <td>{{ reponse.question?.titre }}</td>
                <td>
                  <span class="reply-preview">{{ reponse.content }}</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import adminApi from '../api/admin'
import authApi from '../api/auth'

export default {
  name: 'AdminView',
  data() {
    return {
      user: JSON.parse(localStorage.getItem('user') || 'null'),
      stats: { total_questions: 0, total_reponses: 0, total_users: 0 },
      questions: [],
      reponses: [],
    }
  },
  computed: {
    userInitial() {
      return this.user?.fullname?.charAt(0) || 'A'
    },
  },
  async mounted() {
    await this.loadDashboard()
  },
  methods: {
    async loadDashboard() {
      try {
        const data = await adminApi.getDashboard()
        this.stats = data.stats
        this.questions = data.questions
        this.reponses = data.reponses
      } catch (err) {
        console.error('Error loading dashboard:', err)
      }
    },

    async handleLogout() {
      try {
        await authApi.logout()
      } catch (err) {
        // Ignore
      }
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      this.$router.push('/login')
    },

    formatDate(date) {
      if (!date) return ''
      return new Date(date).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
      })
    },
  },
}
</script>

<style scoped>
.app-layout {
  display: flex;
  min-height: 100vh;
  background-color: #f8fafc;
  background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
  background-size: 40px 40px;
}

/* Sidebar */
.sidebar {
  width: 280px;
  background: #ffffff;
  border-right: 1px solid #e2e8f0;
  height: 100vh;
  position: fixed;
  left: 0;
  top: 0;
  padding: 32px 24px;
  display: flex;
  flex-direction: column;
  z-index: 50;
}

.brand {
  display: flex;
  align-items: center;
  gap: 12px;
  text-decoration: none;
  color: #0f172a;
  margin-bottom: 48px;
  padding: 0 12px;
}

.brand-icon {
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #6366f1, #4f46e5);
  color: white;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}

.brand-text {
  font-size: 20px;
  font-weight: 800;
  letter-spacing: -0.5px;
  background: linear-gradient(135deg, #1e293b, #475569);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.nav-menu {
  display: flex;
  flex-direction: column;
  gap: 8px;
  flex: 1;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 14px 16px;
  color: #64748b;
  text-decoration: none;
  border-radius: 16px;
  font-weight: 600;
  font-size: 14px;
  transition: all 0.2s ease;
}

.nav-item:hover {
  background: #f8fafc;
  color: #6366f1;
  transform: translateX(4px);
}

.nav-item.active {
  background: #e0e7ff;
  color: #6366f1;
}

.nav-item i { width: 20px; text-align: center; }

.logout-btn {
  margin-top: auto;
  color: #ef4444;
  background: #fee2e2;
}
.logout-btn:hover { background: #fee2e2; color: #dc2626; }

/* Main Content */
.main-content {
  margin-left: 280px;
  flex: 1;
  padding: 40px;
  max-width: 1600px;
}

.top-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 40px;
}

.page-title h1 {
  font-size: 32px;
  font-weight: 800;
  color: #0f172a;
  letter-spacing: -1px;
  margin-bottom: 8px;
}

.page-title p {
  color: #64748b;
  font-size: 14px;
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 16px;
  background: #ffffff;
  padding: 8px 16px 8px 8px;
  border-radius: 50px;
  box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
  border: 1px solid #e2e8f0;
}

.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #e0e7ff;
  color: #6366f1;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 16px;
}

.user-info { display: flex; flex-direction: column; }
.user-name { font-weight: 700; font-size: 14px; color: #0f172a; }
.user-role { font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }

/* Stats Grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 24px;
  margin-bottom: 48px;
}

.stat-card {
  background: #ffffff;
  border-radius: 24px;
  padding: 32px;
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
  border: 1px solid rgba(226, 232, 240, 0.8);
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  transition: transform 0.2s, box-shadow 0.2s;
  position: relative;
  overflow: hidden;
}

.stat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
}

.stat-info h3 {
  font-size: 14px;
  font-weight: 600;
  color: #64748b;
  margin-bottom: 12px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.stat-value {
  font-size: 36px;
  font-weight: 800;
  color: #0f172a;
  letter-spacing: -1px;
  line-height: 1;
}

.stat-icon {
  width: 64px;
  height: 64px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
}

.stat-primary .stat-icon { background: #e0e7ff; color: #6366f1; }
.stat-success .stat-icon { background: #d1fae5; color: #10b981; }
.stat-warning .stat-icon { background: #fef3c7; color: #f59e0b; }

.stat-card::before {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 4px;
}

.stat-primary::before { background: #6366f1; }
.stat-success::before { background: #10b981; }
.stat-warning::before { background: #f59e0b; }

/* Content Sections */
.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.section-title {
  font-size: 20px;
  font-weight: 700;
  color: #0f172a;
  display: flex;
  align-items: center;
  gap: 12px;
}

.section-icon {
  width: 32px;
  height: 32px;
  background: #e0e7ff;
  color: #6366f1;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
}

.data-card {
  background: #ffffff;
  border-radius: 24px;
  padding: 8px;
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
  border: 1px solid #e2e8f0;
  margin-bottom: 40px;
  overflow: hidden;
}

.table-container { width: 100%; overflow-x: auto; }

table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}

th {
  text-align: left;
  padding: 20px 24px;
  color: #64748b;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  background: #f9fafb;
  border-bottom: 1px solid #e2e8f0;
}

th:first-child { border-top-left-radius: 16px; border-bottom-left-radius: 16px; }
th:last-child { border-top-right-radius: 16px; border-bottom-right-radius: 16px; }

td {
  padding: 20px 24px;
  color: #0f172a;
  font-size: 14px;
  font-weight: 500;
  border-bottom: 1px solid #e2e8f0;
  vertical-align: middle;
}

tr:last-child td { border-bottom: none; }
tr:hover td { background: #f8fafc; }

.user-cell { display: flex; align-items: center; gap: 12px; }

.user-cell-avatar {
  width: 32px;
  height: 32px;
  border-radius: 10px;
  background: #e0e7ff;
  color: #6366f1;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 12px;
  flex-shrink: 0;
}

.badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border-radius: 99px;
  font-size: 12px;
  font-weight: 600;
}

.badge-geo { background: #e0e7ff; color: #6366f1; }
.badge-date { background: #f1f5f9; color: #64748b; }

.reply-preview {
  color: #64748b;
  font-style: italic;
  background: #f8fafc;
  padding: 8px 12px;
  border-radius: 8px;
  border-left: 3px solid #e2e8f0;
  display: inline-block;
  max-width: 400px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

@media (max-width: 1024px) {
  .sidebar { width: 80px; padding: 20px 10px; }
  .brand-text, .nav-item span { display: none; }
  .main-content { margin-left: 80px; }
  .nav-item { justify-content: center; padding: 14px; }
  .nav-item i { width: auto; font-size: 18px; }
}
</style>
