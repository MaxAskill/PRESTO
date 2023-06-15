<template>
  <div class="register-page">
    <app-navbar></app-navbar>
    <div class="wrapper wrapper-full-page">
      <div class="full-page register-page section-image" filter-color="black">
        <div class="content">
          <div class="container">
            <div class="col-lg-4 col-md-6 ml-auto mr-auto">
              <form @submit.prevent="register">
                <card type="signup" class="text-center">
                  <template slot="header">
                    <h4 class="card-title">Register</h4>
                  </template>

                  <fg-input
                    v-model="form.fullName"
                    addon-left-icon="nc-icon nc-single-02"
                    placeholder="Full Name..."
                  ></fg-input>
                  <fg-input
                    v-model="form.email"
                    @input="validateEmail"
                    addon-left-icon="nc-icon nc-email-85"
                    placeholder="Email..."
                  ></fg-input>
                  <fg-input>
                    <el-select
                      :disabled="isUser"
                      id="company"
                      class="select-default"
                      size="large"
                      placeholder="Select Company"
                      v-model="form.company"
                    >
                      <el-option value="" hidden></el-option>
                      <el-option :value="NBFI">NBFI</el-option>
                      <el-option :value="ASC">ASC</el-option>
                      <el-option :value="CMC">CMC</el-option>
                      <el-option :value="EPC">EPC</el-option>
                      <el-option :value="AHLC">AHLC</el-option>
                    </el-select>
                  </fg-input>
                  <fg-input
                    v-model="form.password"
                    addon-left-icon="nc-icon nc-key-25"
                    placeholder="Password"
                    type="password"
                  ></fg-input>
                  <fg-input
                    v-model="form.password_confirmation"
                    addon-left-icon="nc-icon nc-key-25"
                    placeholder="Confirm Password"
                    type="password"
                  ></fg-input>

                  <a slot="footer" class="linkLogin" @click="directToLogin"
                    >Already Registered?
                  </a>
                  <p-button native-type="submit" slot="footer" type="info" round
                    >Register
                  </p-button>
                </card>
              </form>
            </div>
          </div>
        </div>
        <!-- <app-footer></app-footer> -->
        <div
          class="full-page-background"
          style="background-image: url(static/img/background/jan-sendereks.jpg)"
        ></div>
      </div>
    </div>
  </div>
</template>
<script>
import AppNavbar from "./Layout/AppNavbar";
// import AppFooter from './Layout/AppFooter'
import { Card, Checkbox, Button, InfoSection } from "src/components/UIComponents";
import { Select, Option } from "element-ui";
import axiosClient from "../../../../axios";

export default {
  components: {
    Card,
    AppNavbar,
    // AppFooter,
    InfoSection,
    [Checkbox.name]: Checkbox,
    [Button.name]: Button,
    [Option.name]: Option,
    [Select.name]: Select,
  },
  data() {
    return {
      form: {
        fullName: "",
        email: "",
        company: "",
        password: "",
        password_confirmation: "",
        position: "",
      },
      isUser: true,
    };
  },
  methods: {
    directToLogin() {
      this.$router.push({ name: "Login" });
    },
    toggleNavbar() {
      document.body.classList.toggle("nav-open");
    },
    closeMenu() {
      document.body.classList.remove("nav-open");
      document.body.classList.remove("off-canvas-sidebar");
    },
    register() {
      switch (this.form.company) {
        case "NBFI":
          this.form.company = 1;
          break;
        case "ASC":
          this.form.company = 2;
          break;
        case "CMC":
          this.form.company = 3;
          break;
        case "EPC":
          this.form.company = 4;
          break;
        case "AHLC":
          this.form.company = 5;
          break;

        default:
          break;
      }
      axiosClient
        .get("/sendVerificationCode", {
          params: {
            email: this.form.email,
          },
        })
        .then((response) => {
          // sessionStorage.setItem("UserID", response.data.user.id);
          // sessionStorage.setItem("Token", response.data.token);
          // sessionStorage.setItem("Position", response.data.user.position);
          console.log("HASH CODE:", response.data);
          sessionStorage.setItem("Name", this.form.fullName);
          sessionStorage.setItem("Email", this.form.email);
          sessionStorage.setItem("Company", this.form.company);
          sessionStorage.setItem("Password", this.form.password);
          sessionStorage.setItem("Position", this.form.position);
          sessionStorage.setItem("HashCode", response.data);
          this.$router.push({ name: "OTPForm" });
        })
        .catch((error) => {
          console.error(error);
        });
    },
    validateEmail() {
      this.isUser = true;
      this.form.company = "";

      if (/^[\w.-]+@[a-zA-Z_-]+?\.[a-zA-Z]{2,3}$/.test(this.form.email)) {
        var domain = this.form.email.split("@")[1];
        var companyDomains = ["barbizonfashion.com", "everydayproductscorp.com"];

        if (companyDomains.includes(domain)) {
          this.form.position = "Agent";
          if (domain == "barbizonfashion.com") {
            this.form.company = "NBFI";
          } else {
            this.form.company = "EPC";
          }
        } else {
          this.isUser = false;
          this.form.position = "User";
        }
      }
    },
  },
  beforeDestroy() {
    this.closeMenu();
  },
};
</script>
<style lang="scss">
.linkLogin {
  text-decoration-line: underline !important;
  margin-right: 10px;
  color: gray !important;
}
.linkLogin:hover {
  color: black !important;
  cursor: pointer;
}
#company:hover {
  background-color: white !important;
  cursor: not-allowed !important;
}
</style>
