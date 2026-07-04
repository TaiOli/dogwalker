import { ref, reactive } from "vue"
import { api } from "../services/api"

export function useScheduletour() {

  const dogs = ref([])

  const form = reactive({
    dog_id: "",
    data: "",
    hora: "",
    duracao: "",
    local: "",
    valor: ""
  })

  async function loadDogs() {
    try {

      const res = await api.get("/dogs/my")

      dogs.value = res.data
    } catch (error) {
      console.log("erro ao buscar dogs:", error.response?.data || error)
    }
  }

  async function solicitarPasseio() {
  try {
    const res = await api.post("/tours", form)
    return res
  } catch (err) {
    console.log(err.response?.status)
    console.log(err.response?.data)
    throw err
  }
}

  function clearPasseio() {
    Object.assign(form, {
      dog_id: "",
      data: "",
      hora: "",
      duracao: "",
      local: "",
      valor: ""
    })
  }

  return {
    form,
    dogs,
    loadDogs,
    solicitarPasseio,
    clearPasseio
  }
}