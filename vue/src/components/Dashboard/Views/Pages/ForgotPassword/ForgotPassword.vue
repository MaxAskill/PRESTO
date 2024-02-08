<template>
  <div class="login-page">
    <div class="wrapper wrapper-full-page">
      <div class="full-page login-page section-image">
        <!--   you can change the color of the filter page using: data-color="blue | azure | green | orange | red | purple" -->
        <div class="content">
          <div class="container">
            <div class="col-lg-4 col-md-6 ml-auto mr-auto">
              <form @submit.prevent="sendOTP">
                <card type="login" class="m-0 p-0">
                  <p class="text-center font-size1">
                    Forgot your password? No problem. Just let us know your email address
                    and we will email you an OTP that will allow you to create a new one.
                  </p>

                  <fg-input
                    label="Email"
                    class="mb-0"
                    v-model="email"
                    @input="validateEmail"
                  ></fg-input>

                  <div class="card-footer pt-10px p-0 d-flex flex-row-reverse">
                    <p-button
                      :disabled="isEmail"
                      native-type="submit"
                      type="warning"
                      round
                      >Send OTP
                    </p-button>
                  </div>
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
    sendOTP() {
      //Send OTP to the user's email
      axiosClient
        .get("/sendForgotVerificationCode", {
          params: {
            email: this.email,
          },
        })
        .then((response) => {
          sessionStorage.setItem("HashCode", response.data);
          sessionStorage.setItem("Email", this.email);
          console.log("Response", response.data);

          if (response.data == 1) {
          } else {
            this.$router.push({ name: "LoginOTPForm" });
          }
        })
        .catch((error) => {
          console.error(error);
        });
    },
    validateEmail() {
      if (/^[\w.-]+@[a-zA-Z_-]+?\.[a-zA-Z]{2,3}$/.test(this.email)) {
        this.isEmail = false;
      } else this.isEmail = true;
    },
  },
  data() {
    return {
      email: "",
      isEmail: true,
    };
  },
  beforeDestroy() {
    this.closeMenu();
  },
};
</script>
<style lang="scss">
.font-size1 {
  font-size: 13px;
}
.pt-10px {
  padding-top: 10px !important;
}
</style>
