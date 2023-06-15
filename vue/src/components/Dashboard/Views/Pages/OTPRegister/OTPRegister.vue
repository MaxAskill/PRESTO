<template>
  <div class="login-page">
    <div class="wrapper wrapper-full-page">
      <div class="full-page login-page section-image">
        <!--   you can change the color of the filter page using: data-color="blue | azure | green | orange | red | purple" -->
        <div class="content">
          <div class="container">
            <div class="col-lg-4 col-md-6 ml-auto mr-auto">
              <form @submit.prevent="verify">
                <card type="login">
                  <p class="text-center font-size">
                    Kindly check your email for the sent OTP Verification Code.
                  </p>

                  <fg-input label="OTP Verification" v-model="OTP"></fg-input>

                  <p-button native-type="submit" slot="footer" type="warning" round
                    >Verify
                  </p-button>
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
// import AppFooter from './Layout/AppFooter'

import axiosClient from "../../../../../axios";

export default {
  components: {
    Card,
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
    verify() {
      console.log("HashCode:", sessionStorage.getItem("HashCode"));
      console.log("OTP:", this.OTP);

      axiosClient
        .get("/confirmCode", {
          params: {
            verCode: this.OTP,
            hashCode: sessionStorage.getItem("HashCode"),
          },
        })
        .then((response) => {
          console.log("Success Equal Response:", response.data);
          axiosClient
            .post("/register", {
              name: sessionStorage.getItem("Name"),
              email: sessionStorage.getItem("Email"),
              password: sessionStorage.getItem("Password"),
              position: sessionStorage.getItem("Position"),
              company: sessionStorage.getItem("Company"),
            })
            .then((response) => {
              console.log(response.data);
              sessionStorage.clear();
              this.$router.push({ name: "Login" });
            })
            .catch((error) => {
              console.error(error);
            });
        })
        .catch((err) => {
          console.error(err);
        });
    },
  },
  data() {
    return {
      OTP: "",
    };
  },
  beforeDestroy() {
    this.closeMenu();
  },
};
</script>
<style lang="scss">
.font-size {
  font-size: 12px;
}
</style>
