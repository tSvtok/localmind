import api from './axios'

export default {
  async getAll() {
    const response = await api.get('/favoris')
    return response.data
  },

  async remove(id) {
    const response = await api.delete(`/favoris/${id}`)
    return response.data
  },
}
