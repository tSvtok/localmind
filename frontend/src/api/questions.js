import api from './axios'

export default {
  async getAll(search = '') {
    const params = search ? { search } : {}
    const response = await api.get('/questions', { params })
    return response.data
  },

  async getOne(id) {
    const response = await api.get(`/questions/${id}`)
    return response.data
  },

  async create(titre, description, city) {
    const response = await api.post('/questions', { titre, description, city })
    return response.data
  },

  async update(id, titre, description, city) {
    const response = await api.put(`/questions/${id}`, { titre, description, city })
    return response.data
  },

  async delete(id) {
    const response = await api.delete(`/questions/${id}`)
    return response.data
  },

  async addReponse(questionId, content) {
    const response = await api.post(`/questions/${questionId}/reponses`, { content })
    return response.data
  },

  async toggleFavori(questionId) {
    const response = await api.post(`/questions/${questionId}/favoris`)
    return response.data
  },
}
