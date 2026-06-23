import { ref } from "vue"
import { api } from "../services/api"

export function useWalks() {

  const passeios = ref([])

  async function loadPasseios() {

    try {
      const response = await api.get("/passeios")
      passeios.value = response.data
    } catch (error) {
      console.log("erro API:", error)
    }
  }

  async function aceitarPasseio(id) {
    return await api.put(`/passeios/${id}/aceitar`)
  }

  async function recusarPasseio(id) {
    return await api.patch(`/passeios/${id}/recusar`)
  }

  async function removerPasseio(id) {
    const confirmar = confirm("Deseja realmente excluir este passeio?")

    if (!confirmar) return

    await api.delete(`/passeios/${id}`)
  }

  return {
    passeios,
    loadPasseios,
    aceitarPasseio,
    recusarPasseio,
    removerPasseio
  }
}