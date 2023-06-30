<template>
  <div class="login-page">
    <div class="wrapper wrapper-full-page">
      <div class="full-page login-page section-image">
        <!--   you can change the color of the filter page using: data-color="blue | azure | green | orange | red | purple" -->
        <div class="content">
          <div class="container">
            <div class="col-lg-4 col-md-6 ml-auto mr-auto">
              <form @submit.prevent="verify">
                <card type="login" class="m-0 p-0">
                  <p class="text-center font-size">
                    Kindly check your email for the sent OTP Verification Code.
                  </p>

                  <fg-input
                    label="OTP Verification"
                    class="mb-0"
                    v-model="OTP"
                    placeholder="Enter 6-Digit Code"
                    @keypress="onlyNumber"
                  ></fg-input>
                  <div class="card-footer pt-10px p-0 d-flex flex-row-reverse">
                    <p-button native-type="submit" :disabled="isOTP" type="warning" round
                      >Verify
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
  watch: {
    OTP: "validateOTP",
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
      axiosClient
        .get("/confirmCode", {
          params: {
            verCode: this.OTP,
            hashCode: sessionStorage.getItem("HashCode"),
          },
        })
        .then((response) => {
          this.$router.push({ name: "ResetPassword" });
        })
        .catch((err) => {
          console.error(err);
        });
    },
    onlyNumber($event) {
      let keyCode = $event.keyCode ? $event.keyCode : $event.which;
      if (keyCode < 48 || keyCode > 57) {
        // 46 is dot
        $event.preventDefault();
      }
    },
    validateOTP() {
      if (this.OTP.length == 6) this.isOTP = false;
      else this.isOTP = true;
    },
  },
  data() {
    return {
      OTP: "",
      isOTP: true,
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
