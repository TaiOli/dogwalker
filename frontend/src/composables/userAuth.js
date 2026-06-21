import { reactive, ref } from "vue";
import { api } from "../services/api";

export function useAuth() {

  const user = ref(JSON.parse(localStorage.getItem("user")));

  const formLogin = reactive({
    email: "",
    password: ""
  });

  async function login() {
    const res = await api.post("/login", formLogin);

    user.value = res.data.user;
    localStorage.setItem("user", JSON.stringify(res.data.user));

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
    formCadastro.telefone = "";
    formCadastro.tipo_usuario = "tutor"; 
    formCadastro.foto = "";
  }

  function obterUser() {
    return user.value;
  }

  function obterFuncao() {
    return user.value?.tipo_usuario;
  }

  function tutor() {
    return obterFuncao() === "tutor";
  }

  function walker() {
    return obterFuncao() === "passeador";
  }

  function logout() {
    user.value = null;
    localStorage.removeItem("user");
  }

  return {
    formLogin,
    login,
    clearLogin,
    formCadastro,
    cadastrar,
    clearCadastro,
    user,
    obterUser,
    obterFuncao,
    tutor,
    walker,
    logout
  };
}