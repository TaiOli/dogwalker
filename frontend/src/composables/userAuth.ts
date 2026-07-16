import { reactive, ref, computed } from "vue"
import { useRouter } from "vue-router"
import { api } from "../services/api"

interface User {
  id: number
  username?: string
  name?: string
  nome?: string
  email?: string
  telefone?: string
  tipo_usuario?: string
  foto?: string
}

interface RegisterForm {
  id: number | null
  username: string
  name: string
  email: string
  password: string
  phone: string
  type_user: string
  photo: string
}

const formRegister = reactive<RegisterForm>({
  id: null,
  username: "",
  name: "",
  email: "",
  password: "",
  phone: "",
  type_user: "tutor",
  photo: ""
});

export function useAuth() {
  const router = useRouter()
  let storedUser: User | null = null;
  try {
    storedUser = JSON.parse(localStorage.getItem("user") || "null");
  } catch {
    localStorage.removeItem("user");
    localStorage.removeItem("token");
  }
  const user = ref<User | null>(storedUser);
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
  async function updateRegister() {
    const data = {
      username: formRegister.username,
      nome: formRegister.name,
      email: formRegister.email,
      password: formRegister.password,
      telefone: formRegister.phone,
      tipo_usuario: formRegister.type_user,
      foto: formRegister.photo,
    };
    console.log(data);
    return await api.put(`/users/${formRegister.id}`, data);
  }
  function setRegister(user: User): void {
    formRegister.id = user.id;
    formRegister.username = user.username ?? "";
    formRegister.name = user.nome ?? "";
    formRegister.email = user.email ?? "";
    formRegister.password = "";
    formRegister.phone = user.telefone ?? "";
    formRegister.type_user = user.tipo_usuario ?? "tutor";
    formRegister.photo = user.foto ?? "";
  }
  async function register() {
    const formData = new FormData()
    formData.append("username", formRegister.username)
    formData.append("nome", formRegister.name)
    formData.append("email", formRegister.email)
    formData.append("password", formRegister.password)
    formData.append("telefone", formRegister.phone || "")
    formData.append("tipo_usuario", formRegister.type_user)
    if (formRegister.photo) {
      formData.append("foto", formRegister.photo)
    }
    const res = await api.post("/users", formData, {
      headers: {
        "Content-Type": "multipart/form-data"
      }
    })
    return res.data
  }
  function clearRegister() {
    Object.assign(formRegister, {
      id: null,
      username: "",
      name: "",
      email: "",
      password: "",
      phone: "",
      type_user: "tutor",
      photo: ""
    })
  }
  function logout() {
    user.value = null
    localStorage.removeItem("user")
    localStorage.removeItem("token")
    router.push("/login")
  }
  return {
    user,
    tutor,
    walker,
    formLogin,
    login,
    clearLogin,
    formRegister,
    updateRegister,
    setRegister,
    register,
    clearRegister,
    logout
  }
}