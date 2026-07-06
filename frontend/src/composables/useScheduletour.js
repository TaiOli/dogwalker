import { ref, reactive } from "vue"
import { api } from "../services/api"

export function useScheduletour() {

  const dogs = ref([])

  const form = reactive({
    dog_id: "",
    date: "",
    hour: "",
    duration: "",
    location: "",
    value: ""
  })

  async function loadDogs() {
    try {

      const res = await api.get("/dogs/my")

      dogs.value = res.data
    } catch (error) {
      console.log("erro ao buscar dogs:", error.response?.data || error)
    }
  }

  async function requestTour() {
  try {
    const res = await api.post("/tours", {
      dog_id: form.dog_id,
      data: form.date,
      hora: form.hour,
      duracao: form.duration,
      local: form.location,
      valor: form.value
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
      value: ""
    })
  }

  return {
    form,
    dogs,
    loadDogs,
    requestTour,
    clearTour
  }
}