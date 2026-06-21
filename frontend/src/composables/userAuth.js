import { reactive } from "vue";
import { api } from "../services/api";

export function useAuth() {

  const formLogin = reactive({
    email: "",
    password: ""
  });

  async function login() {
    const res = await api.post("/login", formLogin);
    return res.data;
  }

  function clearLogin() {
    formLogin.email = "";
    formLogin.password = "";
  }

  const formCadastro = reactive({
    name: "",
    nome: "",
    email: "",
    password: "",
    telefone: "",
    tipo_usuario: "tutor",
    foto: ""
  });

  async function cadastrar() {
    const res = await api.post("/users", formCadastro);
    return res.data;
  }

  function clearCadastro() {
    formCadastro.name = "";
    formCadastro.nome = "";
    formCadastro.email = "";
    formCadastro.password = "";
    formCadastro.tipo_usuario = "";
    formCadastro.telefone = "";
  }

  return {
    formLogin,
    login,
    clearLogin,

    formCadastro,
    cadastrar,
    clearCadastro
  };
}