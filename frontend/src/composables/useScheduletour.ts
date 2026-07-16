import { ref, reactive } from "vue"
import { api } from "../services/api"

interface Dog {
  id: number
  nome: string
  [key: string]: unknown
}

interface Walker {
  id: number
  nome?: string
  name?: string
  [key: string]: unknown
}

interface ScheduleTourForm {
  dog_id: string | number
  date: string
  hour: string
  duration: string
  location: string
  value: string | number
  walker_id: string | number
}

export function useScheduletour() {
  const dogs = ref<Dog[]>([])
  const walkers = ref<Walker[]>([])
  const form = reactive<ScheduleTourForm>({
    dog_id: "",
    date: "",
    hour: "",
    duration: "",
    location: "",
    value: "",
    walker_id: ""
  })
  async function loadDogs() {
    try {
      const res = await api.get("/dogs/my")
      dogs.value = res.data
    } catch (error: any) {
      console.log("erro ao buscar dogs:", error?.response?.data || error)
    }
  }
  async function loadWalkers() {
    try {
      const res = await api.get("/walkers")
      walkers.value = res.data
    } catch (error: any) {
      console.log("erro ao buscar passeadores:", error?.response?.data || error)
    }
  }
  function setWalker(id: string | number | null): void {
    form.walker_id = id ? Number(id) : ""
  }
  function clearWalker() {
    form.walker_id = ""
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
        passeador_id: form.walker_id || null
      })
      return res
    } catch (err: any) {
      console.log(err?.response?.status)
      console.log(err?.response?.data)
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
      walker_id: ""
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