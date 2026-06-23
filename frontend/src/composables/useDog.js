import { reactive } from "vue";
import { getRandomDogImage } from "../services/dogApi";
import { api } from "../services/api";

export function useDog() {

  const formDog = reactive({
    nome: "",
    idade: "",
    porte: "",
    raca: "",
    observacoes: "",
    foto: ""
  });

  async function cadastrarDog() {

    let foto = formDog.foto;

    // Se não informou foto, busca uma aleatória
    if (!foto || foto.trim() === "") {
      foto = await getRandomDogImage();
    }

    return await api.post("/dogs", {
      ...formDog,
      foto
    });
  }

  function clearDog() {
    formDog.nome = "";
    formDog.idade = "";
    formDog.porte = "";
    formDog.raca = "";
    formDog.observacoes = "";
    formDog.foto = "";
  }

  return {
    formDog,
    cadastrarDog,
    clearDog
  };
}