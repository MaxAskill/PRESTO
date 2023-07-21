<template>
  <div class="login-page">
    <div class="wrapper wrapper-full-page">
      <div class="full-page login-page section-image">
        <!--   you can change the color of the filter page using: data-color="blue | azure | green | orange | red | purple" -->
        <div class="content">
          <div class="container">
            <div class="col-lg-4 col-md-6 ml-auto mr-auto">
              <form @submit.prevent="login">
                <card type="login" class="m-0 p-0 pb-2">
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
                    v-model="form.email"
                    addon-left-icon="nc-icon nc-single-02"
                    placeholder="E-mail"
                  ></fg-input>

                  <fg-input
                    v-model="form.password"
                    addon-left-icon="nc-icon nc-key-25"
                    placeholder="Password"
                    type="password"
                  ></fg-input>

                  <br />

                  <div class="d-flex justify-content-between">
                    <p-checkbox v-model="form.remember"> Remember Me </p-checkbox>
                    <!-- <a slot="footer" class="forgotPass">Forgot Your Password? </a> -->
                    <router-link class="forgotPass" to="/login/forgot_password"
                      >Forgot Your Password?
                    </router-link>
                  </div>
                  <div class="card-footer pt-2 p-0 d-flex flex-column">
                    <p-button
                      native-type="submit"
                      type="warning"
                      round
                      block
                      class="mb-10px"
                      >Log In
                    </p-button>
                    <router-link class="registerBTN text-center" to="/register"
                      >Create an Account
                    </router-link>
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
import axios from "axios";
import axiosClient from "../../../../axios";

export default {
  components: {
    Card,
    [Checkbox.name]: Checkbox,
    [Button.name]: Button,
  },
  mounted() {
    this.fetchIpAddress();
  },
  methods: {
    fetchIpAddress() {
      const ipAxios = axios.create(); // Create a new Axios instance

      ipAxios
        .get("https://api.ipify.org?format=json")
        .then((response) => {
          this.ipAddress = response.data.ip;
          console.log("IP Adress:", this.ipAddress);
        })
        .catch((error) => {
          console.error(error);
        });
    },
    login() {
      axiosClient
        .post("/login", {
          email: this.form.email,
          password: this.form.password,
          remember: this.form.remember,
          ipAddress: this.ipAddress,
        })
        .then((response) => {
          if (response.data.user.status == "Active") {
            sessionStorage.setItem("UserID", response.data.user.id);
            sessionStorage.setItem("Token", response.data.token);
            sessionStorage.setItem("Position", response.data.user.position);
            sessionStorage.setItem("Company", response.data.company[0].shortName);
            sessionStorage.setItem("Email", response.data.user.email);
            if (response.data.user.position == "Admin") {
              this.$router.push({ name: "Overview" });
            } else if (
              response.data.user.position == "Agent" ||
              response.data.user.position == "Approver"
            ) {
              this.$router.push({ name: "Pull-Out Requests" });
            } else if (response.data.user.position == "User") {
              this.$router.push({ name: "Pull-Out Requisition Form" });
            }
          } else {
            this.errorMsg =
              "Your account is temporarily unavailable. Kindly contact your Administrator.";
          }
        })
        .catch((err) => {
          this.errorMsg = err.response.data.message;
          console.error(err.response.data.message);
        });
    },
  },
  data() {
    return {
      form: {
        email: "",
        password: "",
        remember: false,
      },
      errorMsg: "",
      ipAddress: "",
    };
  },
};
</script>
<style lang="scss">
.forgotPass {
  font-size: 11px !important;
  text-decoration-line: underline !important;
  margin-right: 10px;
  margin-top: 5px;
  color: gray !important;
}
.forgotPass:hover {
  color: black !important;
  cursor: pointer;
}
.registerBTN {
  font-size: 14px !important;
  text-decoration-line: none !important;
  color: gray !important;
}
.registerBTN:hover {
  color: black !important;
  text-decoration-line: underline !important;
  cursor: pointer;
}
.mb-10px {
  margin-bottom: 10px !important;
}
</style>
