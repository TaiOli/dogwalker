import { createRouter, createWebHistory } from "vue-router";
import User from "../views/User.vue";
import Login from "../views/Login.vue";
import Dashboard from "../views/Dashboard.vue";
import RegisterDog from "../views/RegisterDog.vue";
import ScheduleTour from "../views/Scheduletour.vue";
import Walks from "../views/Walks.vue"
import WalkerProfile from "../views/WalkerProfile.vue";
import MyProfile from "../views/MyProfile.vue"
import TutorProfile from "../views/TutorProfile.vue";

const routes = [
  {
    path: "/",
    component: Login
  },
  {
    path: "/register-user",
    component: User
  },
  {
    path: "/home",
    component: Dashboard
  },
  {
    path: "/dogs",
    component: RegisterDog
  },
  { 
    path: "/scheduletour", 
    component: ScheduleTour 
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
    path: "/my-profile",
    component: MyProfile
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;