import { reactive } from "vue";
import { getRandomDogImage } from "../services/dogApi";
import { api } from "../services/api";

interface Dog {
  id: number;
  nome: string;
  idade?: number | string;
  porte?: string;
  raca?: string;
  observacoes?: string | null;
  foto?: string;
}

interface DogForm {
  id: number | null;
  name: string;
  age: string;
  size: string;
  breed: string;
  observations: string;
  photo: File | string | null;
}

export function useDog() {
  const formDog = reactive<DogForm>({
    id: null,
    name: "",
    age: "",
    size: "",
    breed: "",
    observations: "",
    photo: null,
  });

  async function buildFormData() {
    const formData = new FormData();

    formData.append("nome", formDog.name);
    formData.append("idade", formDog.age);
    formData.append("porte", formDog.size);
    formData.append("raca", formDog.breed);
    formData.append("observacoes", formDog.observations);

    if (formDog.photo instanceof File) {
      formData.append("foto", formDog.photo);
    } else if (typeof formDog.photo === "string" && formDog.photo) {
      formData.append("foto", formDog.photo);
    } else {
      const url = await getRandomDogImage();
      formData.append("foto", url);
    }

    return formData;
  }

  async function registerDog() {
    const formData = await buildFormData();
    return await api.post("/dogs", formData);
  }

  async function updateDog() {
    const formData = await buildFormData();
    formData.append("_method", "PUT");
    return await api.post(`/dogs/${formDog.id}`, formData);
  }

  function setDog(dog: Dog): void {
    formDog.id = dog.id;
    formDog.name = dog.nome ?? "";
    formDog.age = String(dog.idade ?? "");
    formDog.size = dog.porte ?? "";
    formDog.breed = dog.raca ?? "";
    formDog.observations = dog.observacoes ?? "";
    formDog.photo = dog.foto ?? "";
  }

  function clearDog() {
    formDog.id = null;
    formDog.name = "";
    formDog.age = "";
    formDog.size = "";
    formDog.breed = "";
    formDog.observations = "";
    formDog.photo = null;
  }

  return {
    formDog,
    registerDog,
    updateDog,
    setDog,
    clearDog,
  };
}