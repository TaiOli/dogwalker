import { reactive } from "vue";
import { getRandomDogImage } from "../services/dogApi";
import { api } from "../services/api";

export function useDog() {

  const formDog = reactive({
    name: "",
    age: "",
    size: "",
    breed: "",
    observations: "",
    photo: ""
  });

  async function registerDog() {
    let photo = formDog.photo;

    if (!photo) {
      photo = await getRandomDogImage();
    }

    return await api.post("/dogs", {
      nome: formDog.name,
      idade: formDog.age ? Number(formDog.age) : null,
      porte: formDog.size,
      raca: formDog.breed,
      observacoes: formDog.observations,
      foto: photo
    });
}

  function clearDog() {
    formDog.name = "";
    formDog.age = "";
    formDog.size = "";
    formDog.breed = "";
    formDog.observations = "";
    formDog.photo = "";
  }

  return {
    formDog,
    registerDog,
    clearDog
  };
}