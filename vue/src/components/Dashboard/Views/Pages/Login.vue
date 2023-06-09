<template>
  <div class="login-page">
    <app-navbar></app-navbar>
    <div class="wrapper wrapper-full-page">
      <div class="full-page login-page section-image">
        <!--   you can change the color of the filter page using: data-color="blue | azure | green | orange | red | purple" -->
        <div class="content">
          <div class="container">
            <div class="col-lg-4 col-md-6 ml-auto mr-auto">
              <form @submit.prevent="login">
                <card type="login">
                  <div
                    v-if="errorMsg"
                    class="alert alert-danger alert-dismissible fade show"
                  >
                    <button
                      type="button"
                      aria-hidden="true"
                      class="close"
                      data-dismiss="alert"
                      aria-label="Close"
                      @click="errorMsg = ''"
                    >
                      <i class="nc-icon nc-simple-remove"></i>
                    </button>
                    <span>{{ errorMsg }}</span>
                  </div>
                  <h3 slot="header" class="header text-center">Login</h3>

                  <fg-input
                    v-model="form.username"
                    addon-left-icon="nc-icon nc-single-02"
                    placeholder="First Name..."
                  ></fg-input>

                  <fg-input
                    v-model="form.password"
                    addon-left-icon="nc-icon nc-key-25"
                    placeholder="Password"
                    type="password"
                  ></fg-input>

                  <br />

                  <p-checkbox> Subscribe to newsletter </p-checkbox>

                  <p-button
                    native-type="submit"
                    slot="footer"
                    type="warning"
                    round
                    block
                    class="mb-3"
                    >Get started</p-button
                  >
                </card>
              </form>
            </div>
          </div>
        </div>
        <!-- <app-footer></app-footer> -->
        <div
          class="full-page-background"
          style="background-image: url(static/img/background/background-2.jpg)"
        ></div>
      </div>
    </div>
  </div>
</template>
<script>
import { Card, Checkbox, Button } from "src/components/UIComponents";
import AppNavbar from "./Layout/AppNavbar";
// import AppFooter from './Layout/AppFooter'

import axiosClient from "../../../../axios";

export default {
  components: {
    Card,
    AppNavbar,
    // AppFooter,
    [Checkbox.name]: Checkbox,
    [Button.name]: Button,
  },
  methods: {
    toggleNavbar() {
      document.body.classList.toggle("nav-open");
    },
    closeMenu() {
      document.body.classList.remove("nav-open");
      document.body.classList.remove("off-canvas-sidebar");
    },
    login() {
      axiosClient
        .post("/login", {
          email: this.form.username,
          password: this.form.password,
        })
        .then((response) => {
          if (response.data.user.status == "Active") {
            console.log("Response", response.data);
            sessionStorage.setItem("UserID", response.data.user.id);
            sessionStorage.setItem("Token", response.data.token);
            sessionStorage.setItem("Position", response.data.user.position);
            sessionStorage.setItem("Company", response.data.company[0].shortName);
            sessionStorage.setItem("Email", response.data.user.email);
            this.$router.push({ name: "Overview" });
          } else {
            this.errorMsg =
              "Your account is temporarily unavailable. Kindly contact your Administrator.";
          }
        })
        .catch((err) => {
          this.errorMsg = err.response.data.message;
          console.log(err.response.data.message);
          console.error(err.response.data.message);
        });
    },
  },
  data() {
    return {
      form: {
        username: "",
        password: "",
      },
      errorMsg: "",
    };
  },
  beforeDestroy() {
    this.closeMenu();
  },
};
</script>
<style></style>
