import { ref, reactive } from "vue"
import { api } from "../services/api"

export function useScheduletour() {

  const dogs = ref([])
  const walkers = ref([])

  const form = reactive({
    dog_id: "",
    date: "",
    hour: "",
    duration: "",
    location: "",
    value: "",
    passeador_id: ""
  })

  async function loadDogs() {
    try {
      const res = await api.get("/dogs/my")
      dogs.value = res.data
    } catch (error) {
      console.log("erro ao buscar dogs:", error.response?.data || error)
    }
  }

  async function loadWalkers() {
    try {
      const res = await api.get("/walkers")
      walkers.value = res.data
    } catch (error) {
      console.log("erro ao buscar passeadores:", error.response?.data || error)
    }
  }

  function setWalker(id) {
    form.passeador_id = id ? Number(id) : ""
  }

  function clearWalker() {
    form.passeador_id = ""
  }

  async function requestTour() {
    try {
      const res = await api.post("/tours", {
        dog_id: form.dog_id,
        data: form.date,
        hora: form.hour,
        duracao: form.duration,
        local: form.location,
        valor: form.value,
        passeador_id: form.passeador_id || null
      })

      return res
    } catch (err) {
      console.log(err.response?.status)
      console.log(err.response?.data)
      throw err
    }
  }

  function clearTour() {
    Object.assign(form, {
      dog_id: "",
      date: "",
      hour: "",
      duration: "",
      location: "",
      value: "",
      passeador_id: ""
    })
  }

  return {
    form,
    dogs,
    walkers,
    loadDogs,
    loadWalkers,
    setWalker,
    clearWalker,
    requestTour,
    clearTour
  }
}