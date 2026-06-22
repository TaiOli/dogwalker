import { reactive } from "vue";
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
    return await api.post("/dogs", formDog);
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