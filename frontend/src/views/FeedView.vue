<template>
  <div class="app-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
      <a href="#" class="brand">
        <div class="brand-icon">
          <i class="fa-solid fa-street-view"></i>
        </div>
        <span class="brand-text">LOCALmind</span>
      </a>

      <nav style="flex: 1">
        <button @click="currentSection = 'discover'" class="nav-btn" :class="{ active: currentSection === 'discover' }">
          <i class="fa-solid fa-house"></i> Decouvrir
        </button>
        <button @click="loadFavoris" class="nav-btn" :class="{ active: currentSection === 'favoris' }">
          <i class="fa-solid fa-bookmark"></i> Mes Favoris
        </button>
      </nav>

      <div class="nav-footer">
        <div class="user-mini">
          <div class="mini-avatar">{{ userInitial }}</div>
          <div style="flex: 1; overflow: hidden;">
            <div style="font-weight: 700; font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
              {{ user?.fullname }}
            </div>
            <div style="font-size: 12px; color: #64748b;">Membre</div>
          </div>
        </div>
        <a href="#" @click.prevent="handleLogout" class="logout-link">
          <i class="fa-solid fa-arrow-right-from-bracket"></i> Deconnexion
        </a>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Header -->
      <header class="header">
        <div>
          <h1 class="page-title">{{ currentSection === 'discover' ? 'Decouvrir' : 'Mes Favoris' }}</h1>
          <p style="color: #64748b; margin-top: 4px;">
            {{ currentSection === 'discover' ? 'Explorez les questions de votre communaute.' : 'Vos questions sauvegardees.' }}
          </p>
        </div>
        <button v-if="currentSection === 'discover'" @click="showPostModal = true" class="btn-primary">
          <i class="fa-solid fa-plus"></i>
          <span>Poser une question</span>
        </button>
      </header>

      <!-- Search -->
      <div v-if="currentSection === 'discover'" style="margin-bottom: 32px;">
        <div class="search-bar">
          <i class="fa-solid fa-magnifying-glass search-icon"></i>
          <input v-model="searchQuery" @input="handleSearch" type="text" placeholder="Rechercher une ville, un sujet..." class="search-input">
        </div>
      </div>

      <!-- Question Feed -->
      <div v-if="currentSection === 'discover'">
        <article v-for="question in questions" :key="question.id" class="feed-card">
          <!-- Card Header -->
          <div class="card-header">
            <div class="author-box">
              <div class="author-avatar">
                <i class="fa-solid fa-user"></i>
              </div>
              <div class="author-info">
                <h4>{{ question.user?.fullname }}</h4>
                <span>{{ timeAgo(question.created_at) }}</span>
              </div>
            </div>

            <div v-if="question.user_id === user?.id" class="card-actions-top">
              <button @click="openEditModal(question)" class="btn-icon">
                <i class="fa-solid fa-pen"></i>
              </button>
              <button @click="deleteQuestion(question.id)" class="btn-icon delete">
                <i class="fa-solid fa-trash"></i>
              </button>
            </div>
          </div>

          <!-- Content -->
          <div class="badge-loc">
            <i class="fa-solid fa-location-dot"></i> {{ question.city }}
          </div>
          <h2 class="card-title">{{ question.titre }}</h2>
          <p class="card-body">{{ question.description }}</p>

          <!-- Actions -->
          <div class="card-footer">
            <button class="action-btn" @click="toggleComments(question.id)">
              <i class="fa-regular fa-comment-dots"></i>
              {{ question.reponses?.length || 0 }} Reponses
            </button>

            <button @click="toggleFavori(question.id)" class="action-btn">
              <i class="fa-regular fa-bookmark"></i> Sauvegarder
            </button>
          </div>

          <!-- Comments -->
          <div v-if="expandedComments[question.id]" class="comments-section">
            <div v-for="reply in question.reponses" :key="reply.id" class="comment">
              <div class="comment-avatar">{{ reply.user?.fullname?.charAt(0) }}</div>
              <div class="comment-bubble">
                <div class="comment-author">{{ reply.user?.fullname }}</div>
                <div class="comment-text">{{ reply.content }}</div>
              </div>
            </div>

            <form v-if="question.user_id !== user?.id" @submit.prevent="addReponse(question.id)" class="reply-form">
              <input v-model="replyTexts[question.id]" type="text" class="reply-input" placeholder="Ecrire une reponse..." required>
              <button type="submit" class="reply-btn">
                <i class="fa-solid fa-paper-plane"></i>
              </button>
            </form>
          </div>
        </article>

        <p v-if="questions.length === 0" style="text-align: center; color: #64748b; padding: 40px;">
          Aucune question trouvee.
        </p>
      </div>

      <!-- Favorites Section -->
      <div v-if="currentSection === 'favoris'">
        <article v-for="fav in favoris" :key="fav.id" class="feed-card" style="border-left: 4px solid #f59e0b;">
          <div class="card-header">
            <h2 class="card-title" style="margin:0">{{ fav.question?.titre }}</h2>
            <button @click="removeFavori(fav.id)" class="btn-icon delete">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>
          <p class="card-body">{{ fav.question?.description?.substring(0, 150) }}</p>
          <div class="badge-loc">
            <i class="fa-solid fa-location-dot"></i> {{ fav.question?.city }}
          </div>
        </article>

        <p v-if="favoris.length === 0" style="text-align: center; color: #64748b; padding: 40px;">
          Aucun favori pour le moment.
        </p>
      </div>
    </main>

    <!-- Post Modal -->
    <div v-if="showPostModal" class="modal-overlay" @click.self="showPostModal = false">
      <div class="modal-card">
        <h2 class="modal-title">Nouvelle Question</h2>
        <form @submit.prevent="createQuestion">
          <div class="form-group">
            <label class="form-label">Sujet de votre question</label>
            <input v-model="postForm.titre" type="text" class="form-control" placeholder="Ex: Recherche plombier..." required>
          </div>
          <div class="form-group">
            <label class="form-label">Ville concernee</label>
            <input v-model="postForm.city" type="text" class="form-control" placeholder="Ex: Paris 15" required>
          </div>
          <div class="form-group">
            <label class="form-label">Details</label>
            <textarea v-model="postForm.description" rows="4" class="form-control" placeholder="Decrivez votre demande en detail..." required></textarea>
          </div>
          <div class="modal-actions">
            <button type="submit" class="btn-primary" style="flex: 2; justify-content: center">Publier</button>
            <button type="button" @click="showPostModal = false" class="btn-cancel">Annuler</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="modal-overlay" @click.self="showEditModal = false">
      <div class="modal-card">
        <h2 class="modal-title">Modifier la question</h2>
        <form @submit.prevent="updateQuestion">
          <div class="form-group">
            <label class="form-label">Sujet</label>
            <input v-model="editForm.titre" type="text" class="form-control" required>
          </div>
          <div class="form-group">
            <label class="form-label">Ville</label>
            <input v-model="editForm.city" type="text" class="form-control" required>
          </div>
          <div class="form-group">
            <label class="form-label">Description</label>
            <textarea v-model="editForm.description" rows="4" class="form-control" required></textarea>
          </div>
          <div class="modal-actions">
            <button type="submit" class="btn-primary" style="flex: 2; justify-content: center">Enregistrer</button>
            <button type="button" @click="showEditModal = false" class="btn-cancel">Annuler</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import questionsApi from '../api/questions'
import favorisApi from '../api/favoris'
import authApi from '../api/auth'

export default {
  name: 'FeedView',
  data() {
    return {
      user: JSON.parse(localStorage.getItem('user') || 'null'),
      questions: [],
      favoris: [],
      currentSection: 'discover',
      searchQuery: '',
      showPostModal: false,
      showEditModal: false,
      expandedComments: {},
      replyTexts: {},
      postForm: { titre: '', description: '', city: '' },
      editForm: { id: null, titre: '', description: '', city: '' },
    }
  },
  computed: {
    userInitial() {
      return this.user?.fullname?.charAt(0) || '?'
    },
  },
  async mounted() {
    await this.loadQuestions()
  },
  methods: {
    async loadQuestions() {
      try {
        this.questions = await questionsApi.getAll(this.searchQuery)
      } catch (err) {
        console.error('Error loading questions:', err)
      }
    },

    async loadFavoris() {
      this.currentSection = 'favoris'
      try {
        this.favoris = await favorisApi.getAll()
      } catch (err) {
        console.error('Error loading favoris:', err)
      }
    },

    handleSearch() {
      clearTimeout(this._searchTimeout)
      this._searchTimeout = setTimeout(() => {
        this.loadQuestions()
      }, 300)
    },

    async createQuestion() {
      try {
        await questionsApi.create(this.postForm.titre, this.postForm.description, this.postForm.city)
        this.showPostModal = false
        this.postForm = { titre: '', description: '', city: '' }
        await this.loadQuestions()
      } catch (err) {
        console.error('Error creating question:', err)
      }
    },

    openEditModal(question) {
      this.editForm = {
        id: question.id,
        titre: question.titre,
        description: question.description,
        city: question.city,
      }
      this.showEditModal = true
    },

    async updateQuestion() {
      try {
        await questionsApi.update(this.editForm.id, this.editForm.titre, this.editForm.description, this.editForm.city)
        this.showEditModal = false
        await this.loadQuestions()
      } catch (err) {
        console.error('Error updating question:', err)
      }
    },

    async deleteQuestion(id) {
      if (!confirm('Supprimer cette question ?')) return
      try {
        await questionsApi.delete(id)
        await this.loadQuestions()
      } catch (err) {
        console.error('Error deleting question:', err)
      }
    },

    async addReponse(questionId) {
      const content = this.replyTexts[questionId]
      if (!content) return
      try {
        await questionsApi.addReponse(questionId, content)
        this.replyTexts[questionId] = ''
        await this.loadQuestions()
      } catch (err) {
        console.error('Error adding reponse:', err)
      }
    },

    async toggleFavori(questionId) {
      try {
        await questionsApi.toggleFavori(questionId)
      } catch (err) {
        console.error('Error toggling favori:', err)
      }
    },

    async removeFavori(id) {
      try {
        await favorisApi.remove(id)
        await this.loadFavoris()
      } catch (err) {
        console.error('Error removing favori:', err)
      }
    },

    toggleComments(questionId) {
      this.expandedComments[questionId] = !this.expandedComments[questionId]
    },

    async handleLogout() {
      try {
        await authApi.logout()
      } catch (err) {
        // Ignore errors on logout
      }
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      this.$router.push('/login')
    },

    timeAgo(date) {
      if (!date) return ''
      const now = new Date()
      const past = new Date(date)
      const diffMs = now - past
      const diffMins = Math.floor(diffMs / 60000)
      const diffHours = Math.floor(diffMs / 3600000)
      const diffDays = Math.floor(diffMs / 86400000)

      if (diffMins < 1) return "a l'instant"
      if (diffMins < 60) return `il y a ${diffMins} min`
      if (diffHours < 24) return `il y a ${diffHours}h`
      if (diffDays < 30) return `il y a ${diffDays}j`
      return past.toLocaleDateString('fr-FR')
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

.nav-btn {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 14px 16px;
  color: #64748b;
  background: transparent;
  border: none;
  width: 100%;
  cursor: pointer;
  border-radius: 16px;
  font-weight: 600;
  font-size: 14px;
  font-family: inherit;
  transition: all 0.2s ease;
  text-align: left;
  margin-bottom: 8px;
}

.nav-btn:hover {
  background: #f8fafc;
  color: #6366f1;
  transform: translateX(4px);
}

.nav-btn.active {
  background: #6366f1;
  color: white;
  box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
}

.nav-btn.active:hover { transform: none; }

.nav-footer {
  margin-top: auto;
  border-top: 1px solid #e2e8f0;
  padding-top: 24px;
}

.user-mini {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px;
  background: #f8fafc;
  border-radius: 16px;
  margin-bottom: 12px;
}

.mini-avatar {
  width: 36px;
  height: 36px;
  background: #e0e7ff;
  color: #6366f1;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 14px;
}

.logout-link {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #ef4444;
  text-decoration: none;
  font-size: 13px;
  font-weight: 600;
  padding: 8px 12px;
  border-radius: 12px;
  transition: background 0.2s;
}
.logout-link:hover { background: #fee2e2; }

/* Main Content */
.main-content {
  margin-left: 280px;
  flex: 1;
  padding: 40px;
  max-width: 1000px;
  margin-right: auto;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 32px;
}

.page-title {
  font-size: 28px;
  font-weight: 800;
  color: #0f172a;
  letter-spacing: -1px;
}

.btn-primary {
  background: #6366f1;
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 14px;
  font-weight: 700;
  font-family: inherit;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
  box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
}

.btn-primary:hover {
  background: #4338ca;
  transform: translateY(-2px);
  box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
}

.search-bar {
  width: 100%;
  max-width: 400px;
  position: relative;
}

.search-input {
  width: 100%;
  padding: 14px 20px 14px 48px;
  border-radius: 16px;
  border: 1px solid #e2e8f0;
  font-family: inherit;
  background: white;
  box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
  transition: all 0.2s;
  box-sizing: border-box;
}

.search-input:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 0 3px #e0e7ff;
}

.search-icon {
  position: absolute;
  left: 20px;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
}

/* Feed Card */
.feed-card {
  background: #ffffff;
  border-radius: 24px;
  padding: 32px;
  margin-bottom: 24px;
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
  border: 1px solid #e2e8f0;
  animation: slideUp 0.4s ease forwards;
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 20px;
}

.author-box { display: flex; align-items: center; gap: 12px; }

.author-avatar {
  width: 44px;
  height: 44px;
  background: linear-gradient(135deg, #e2e8f0, #f1f5f9);
  color: #64748b;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.author-info h4 { font-size: 15px; font-weight: 700; color: #0f172a; }
.author-info span { font-size: 12px; color: #64748b; }

.card-actions-top { display: flex; gap: 8px; }

.btn-icon {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  border: 1px solid #e2e8f0;
  background: white;
  color: #64748b;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-icon:hover { background: #f8fafc; color: #0f172a; }
.btn-icon.delete:hover { background: #fee2e2; color: #ef4444; border-color: #fee2e2; }

.badge-loc {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  background: #e0e7ff;
  color: #6366f1;
  border-radius: 99px;
  font-size: 12px;
  font-weight: 700;
  margin-bottom: 12px;
}

.card-title {
  font-size: 20px;
  font-weight: 800;
  color: #0f172a;
  margin-bottom: 12px;
  line-height: 1.4;
}

.card-body {
  color: #64748b;
  line-height: 1.6;
  margin-bottom: 24px;
}

.card-footer {
  border-top: 1px solid #e2e8f0;
  padding-top: 20px;
  display: flex;
  gap: 16px;
}

.action-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  border-radius: 12px;
  background: transparent;
  border: none;
  color: #64748b;
  font-weight: 600;
  font-family: inherit;
  cursor: pointer;
  transition: all 0.2s;
}

.action-btn:hover { background: #f8fafc; color: #0f172a; }

/* Comments Section */
.comments-section {
  background: #f8fafc;
  border-radius: 16px;
  padding: 20px;
  margin-top: 20px;
}

.comment {
  display: flex;
  gap: 12px;
  margin-bottom: 16px;
}

.comment-avatar {
  width: 32px;
  height: 32px;
  background: white;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
  font-weight: 700;
  box-shadow: 0 1px 2px rgba(0,0,0,0.05);
  flex-shrink: 0;
}

.comment-bubble {
  background: white;
  padding: 12px 16px;
  border-radius: 0 16px 16px 16px;
  box-shadow: 0 1px 2px rgba(0,0,0,0.05);
  flex: 1;
}

.comment-author { font-size: 13px; font-weight: 700; margin-bottom: 4px; color: #0f172a; }
.comment-text { font-size: 14px; color: #64748b; line-height: 1.5; }

.reply-form {
  display: flex;
  gap: 10px;
  margin-top: 16px;
}

.reply-input {
  flex: 1;
  padding: 10px 16px;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  font-family: inherit;
  font-size: 14px;
  box-sizing: border-box;
}

.reply-btn {
  width: 40px;
  height: 40px;
  background: #6366f1;
  color: white;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.1s;
  flex-shrink: 0;
}
.reply-btn:hover { transform: scale(1.05); }

/* Modals */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.6);
  backdrop-filter: blur(4px);
  z-index: 100;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-card {
  background: white;
  width: 90%;
  max-width: 550px;
  border-radius: 24px;
  padding: 32px;
  animation: modalPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes modalPop {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}

.modal-title { font-size: 24px; font-weight: 800; margin-bottom: 24px; color: #0f172a; }

.form-group { margin-bottom: 20px; }
.form-label { display: block; font-size: 13px; font-weight: 700; margin-bottom: 8px; color: #0f172a; }
.form-control {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  font-family: inherit;
  font-size: 14px;
  background: #f8fafc;
  transition: all 0.2s;
  box-sizing: border-box;
}
.form-control:focus { outline: none; border-color: #6366f1; background: white; }

.modal-actions { display: flex; gap: 12px; margin-top: 32px; }

.btn-cancel {
  flex: 1;
  padding: 12px;
  border-radius: 14px;
  border: 1px solid #e2e8f0;
  background: white;
  font-weight: 700;
  cursor: pointer;
  color: #64748b;
  font-family: inherit;
}
.btn-cancel:hover { background: #f8fafc; }

@media (max-width: 768px) {
  .sidebar { display: none; }
  .main-content { margin-left: 0; padding: 20px; }
  .header { flex-direction: column; gap: 16px; align-items: stretch; }
  .search-bar { max-width: 100%; }
}
</style>
