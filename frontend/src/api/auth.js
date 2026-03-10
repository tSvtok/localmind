import api from './axios'

export default {
  async login(email, password) {
    const response = await api.post('/login', { email, password })
    return response.data
  },

  async register(fullname, email, password, location) {
    const response = await api.post('/register', { fullname, email, password, location })
    return response.data
  },

  async logout() {
    const response = await api.post('/logout')
    return response.data
  },

  async getUser() {
    const response = await api.get('/user')
    return response.data
  },
}
