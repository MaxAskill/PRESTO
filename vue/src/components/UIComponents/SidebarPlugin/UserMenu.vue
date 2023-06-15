<template>
  <div class="user">
    <div class="photo">
      <img src="static/img/faces/face-2.jpg" alt="user avatar" />
    </div>
    <div class="info">
      <a data-toggle="collapse" :aria-expanded="!isClosed" @click="toggleMenu" href="#">
        <span>
          {{ name }}
          <b class="caret"></b>
        </span>
      </a>
      <div class="clearfix"></div>
      <div>
        <collapse-transition>
          <ul class="nav nav-menu" v-show="!isClosed">
            <li>
              <a href="#">
                <span class="sidebar-mini-icon">Mp</span>
                <span class="sidebar-normal">My Profile</span>
              </a>
            </li>
            <li>
              <a href="#">
                <span class="sidebar-mini-icon">Ep</span>
                <span class="sidebar-normal">Edit Profile</span>
              </a>
            </li>
            <li>
              <a href="#" @click="logout">
                <span class="sidebar-mini-icon">L</span>
                <span class="sidebar-normal">Logout</span>
              </a>
            </li>
          </ul>
        </collapse-transition>
      </div>
    </div>
  </div>
</template>
<script>
import { CollapseTransition } from "vue2-transitions";
import axiosClient from "../../../axios";

export default {
  components: {
    CollapseTransition,
  },
  data() {
    return {
      isClosed: true,
      name: "",
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    fetchData() {
      axiosClient
        .get("/users", {
          params: {
            email: sessionStorage.getItem("Email"),
          },
        })
        .then((response) => {
          // console.log("Success User Response:", response.data);
          this.name = response.data[0].name;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    toggleMenu() {
      this.isClosed = !this.isClosed;
    },
    logout() {
      axiosClient.post("/logout").then((response) => {
        console.log(response);
        sessionStorage.removeItem("UserID");
        sessionStorage.removeItem("Token");
        sessionStorage.removeItem("Position");
        sessionStorage.removeItem("Company");
        sessionStorage.removeItem("Email");
        this.$router.push({ name: "Login" });
      });
    },
  },
};
</script>
<style scoped>
.nav.nav-menu {
  margin-top: 0;
}
</style>
