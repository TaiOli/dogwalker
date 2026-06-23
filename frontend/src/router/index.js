import { createRouter, createWebHistory } from "vue-router";
import User from "../views/User.vue";
import Login from "../views/Login.vue";
import Dashboard from "../views/Dashboard.vue";
import CadastrarCachorro from "../views/RegisterDog.vue";
import SolicitarPasseio from "../views/Scheduletour.vue";
import Walks from "../views/Walks.vue"

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
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;