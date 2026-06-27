import { reactive, ref, computed } from "vue"
import { api } from "../services/api"

export function useAuth() {

  const user = ref(JSON.parse(localStorage.getItem("user") || "null"))

  const tutor = computed(() => user.value?.tipo_usuario === "tutor")
  const walker = computed(() => user.value?.tipo_usuario === "passeador")

  const formLogin = reactive({
    email: "",
    password: ""
  })

  async function login() {
    const res = await api.post("/login", formLogin)

    user.value = res.data.user

    localStorage.setItem("user", JSON.stringify(res.data.user))
    localStorage.setItem("token", res.data.token)

    return res.data
  }

  function clearLogin() {
    formLogin.email = ""
    formLogin.password = ""
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

    const formData = new FormData()

    formData.append("name", formCadastro.name)
    formData.append("nome", formCadastro.nome)
    formData.append("email", formCadastro.email)
    formData.append("password", formCadastro.password)
    formData.append("telefone", formCadastro.telefone || "")
    formData.append("tipo_usuario", formCadastro.tipo_usuario)

    if (formCadastro.foto) {
      formData.append("foto", formCadastro.foto)
    }

    const res = await api.post("/users", formData, {
      headers: {
        "Content-Type": "multipart/form-data"
      }
    })

    return res.data
  }

  function clearCadastro() {
    Object.assign(formCadastro, {
      name: "",
      nome: "",
      email: "",
      password: "",
      telefone: "",
      tipo_usuario: "tutor",
      foto: ""
    })
  }

  function logout() {
    user.value = null
    localStorage.removeItem("user")
    localStorage.removeItem("token")
  }

  return {
    user,
    tutor,
    walker,
    formLogin,
    login,
    clearLogin,
    formCadastro,
    cadastrar,
    clearCadastro,
    logout
  }
}