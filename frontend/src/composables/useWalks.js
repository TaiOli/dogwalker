import { ref } from "vue"
import { api } from "../services/api"

export function useWalks() {

  const passeios = ref([])

  async function loadPasseios() {
    const response = await api.get("/passeios")
    passeios.value = response.data
  }

  async function aceitarPasseio(id) {
    await api.put(`/passeios/${id}/aceitar`)
  }

  async function recusarPasseio(id) {
    await api.patch(`/passeios/${id}/recusar`)
  }

  return {
    passeios,
    loadPasseios,
    aceitarPasseio,
    recusarPasseio
  }
}