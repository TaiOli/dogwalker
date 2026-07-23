import { createRouter, createWebHistory } from "vue-router";
import User from "../views/User.vue";
import Login from "../views/Login.vue";
import Dashboard from "../views/Dashboard.vue";
import RegisterDog from "../views/RegisterDog.vue";
import ScheduleTour from "../views/Scheduletour.vue";
import Walks from "../views/Walks.vue";
import WalkerProfile from "../views/WalkerProfile.vue";
import MyProfile from "../views/MyProfile.vue";
import TutorProfile from "../views/TutorProfile.vue";

const routes = [
  {
    path: "/",
    redirect: "/login",
  },
  {
    path: "/login",
    component: Login,
  },
  {
    path: "/cadastro-usuario",
    component: User,
  },
  {
    path: "/usuario/editar",
    component: User,
  },
  {
    path: "/inicio",
    component: Dashboard,
  },
  {
    path: "/cadastro-cachorro",
    component: RegisterDog,
  },
  {
    path: "/solicitar-passeio",
    component: ScheduleTour,
  },
  {
    path: "/passeios",
    component: Walks,
  },
  {
    path: "/passeador-perfil/:id",
    component: WalkerProfile,
  },
  {
    path: "/tutores/:id",
    component: TutorProfile,
  },
  {
    path: "/meu-perfil",
    component: MyProfile,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;