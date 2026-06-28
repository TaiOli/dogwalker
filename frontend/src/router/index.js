import { createRouter, createWebHistory } from "vue-router";
import User from "../views/User.vue";
import Login from "../views/Login.vue";
import Dashboard from "../views/Dashboard.vue";
import CadastrarCachorro from "../views/RegisterDog.vue";
import SolicitarPasseio from "../views/Scheduletour.vue";
import Walks from "../views/Walks.vue"
import WalkerProfile from "../views/WalkerProfile.vue";
import MeuPerfil from "../views/MeuPerfil.vue"
import TutorProfile from "../views/TutorProfile.vue";

const routes = [
  {
    path: "/",
    component: Login
  },
  {
    path: "/cadastro-usuario",
    component: User
  },
  {
    path: "/home",
    component: Dashboard
  },
  {
    path: "/dogs",
    component: CadastrarCachorro
  },
  { 
    path: "/scheduletour", 
    component: SolicitarPasseio 
  },
  {
    path: "/walks",
    component: Walks
  },
  {
    path: "/walkers/:id",
    component: WalkerProfile
  },
  {
    path: '/tutors/:id',
    component: TutorProfile
  },
  {
    path: "/meu-perfil",
    component: MeuPerfil
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;