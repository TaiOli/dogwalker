import { reactive } from "vue";
import { getRandomDogImage } from "../services/dogApi";
import { api } from "../services/api";

export function useDog() {

  const formDog = reactive({
    id: null,
    name: "",
    age: "",
    size: "",
    breed: "",
    observations: "",
    photo: ""
  });

  async function buildFormData() {
    const formData = new FormData();

    formData.append("nome", formDog.name);
    formData.append("idade", formDog.age ? Number(formDog.age) : "");
    formData.append("porte", formDog.size);
    formData.append("raca", formDog.breed);
    formData.append("observacoes", formDog.observations ?? "");

    if (!formDog.photo) {
      const url = await getRandomDogImage();
      formData.append("foto", url);
    } else {
      formData.append("foto", formDog.photo);
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

  // recebe o dog vindo da API (nome, idade, raca...) e preenche o form
  function setDog(dog) {
    formDog.id = dog.id;
    formDog.name = dog.nome ?? "";
    formDog.age = dog.idade ?? "";
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
    formDog.photo = "";
  }

  return {
    formDog,
    registerDog,
    updateDog,
    setDog,
    clearDog
  };
}