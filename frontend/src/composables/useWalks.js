import { ref } from "vue"
import { api } from "../services/api"

export function useWalks() {

  const passeios = ref([])

  async function loadPasseios() {
    const response = await api.get("/tours")
    passeios.value = response.data
  }

  async function aceitarPasseio(id) {
    await api.put(`/tours/${id}/accept`)
  }

  async function recusarPasseio(id) {
    await api.patch(`/tours/${id}/reject`)
  }

  return {
    passeios,
    loadPasseios,
    aceitarPasseio,
    recusarPasseio
  }
}