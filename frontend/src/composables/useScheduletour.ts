import { ref, reactive } from "vue";
import { api } from "../services/api";

interface Dog {
  id: number;
  nome: string;
}

interface Walker {
  id: number;
  nome: string;
}

interface ScheduleTourForm {
  dog_id: string | number;
  date: string;
  hour: string;
  duration: string;
  location: string;
  value: string | number;
  walker_id: string | number;
}

export function useScheduletour() {
  const dogs = ref<Dog[]>([]);
  const walkers = ref<Walker[]>([]);
  const form = reactive<ScheduleTourForm>({
    dog_id: "",
    date: "",
    hour: "",
    duration: "",
    location: "",
    value: "",
    walker_id: "",
  });

  async function loadDogs(): Promise<void> {
    try {
      const res = await api.get("/dogs/my");
      dogs.value = res.data;
    } catch (error: any) {
      console.log("erro ao buscar dogs:", error?.response?.data || error);
    }
  }

  async function loadWalkers(): Promise<void> {
    try {
      const res = await api.get("/walkers");
      walkers.value = res.data.map((walker: any) => ({
        id: walker.id,
        nome: walker.nome ?? walker.name,
      }));
    } catch (error: any) {
      console.log(
        "erro ao buscar passeadores:",
        error?.response?.data || error,
      );
    }
  }

  function setWalker(id: string | number | null): void {
    form.walker_id = id ? Number(id) : "";
  }

  function clearWalker(): void {
    form.walker_id = "";
  }

  async function requestTour() {
    return await api.post("/tours", {
      dog_id: form.dog_id,
      data: form.date,
      hora: form.hour,
      duracao: form.duration,
      local: form.location,
      valor: form.value,
      passeador_id: form.walker_id || null,
    });
  }

  function clearTour(): void {
    Object.assign(form, {
      dog_id: "",
      date: "",
      hour: "",
      duration: "",
      location: "",
      value: "",
      walker_id: "",
    });
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
    clearTour,
  };
}