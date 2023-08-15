<template>
  <div class="register-page">
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
                  <div class="input-group d-flex" v-if="showCompany">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="nc-icon nc-bank"></i>
                      </span>
                    </div>
                    <el-select
                      suffix-icon="nc-icon nc-key-25"
                      class="select-default flex-grow-1 sign-select"
                      size="large"
                      placeholder="Select Company"
                      v-model="form.company"
                      :disabled="true"
                    >
                      <el-option
                        v-for="option in companyList"
                        class="select-default"
                        :value="option.shortName"
                        :label="option.name + ' (' + option.shortName + ') '"
                        :key="option.id"
                      >
                      </el-option>
                    </el-select>
                  </div>
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

                  <div class="card-footer my-2">
                    <a class="linkLogin" @click="directToLogin">Already Registered? </a>
                    <p-button native-type="submit" type="info" round>Register </p-button>
                  </div>
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
import { Card, Checkbox, Button, InfoSection } from "src/components/UIComponents";
import { Select, Option } from "element-ui";
import axiosClient from "../../../../axios";

export default {
  components: {
    Card,
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
        company: null,
        password: "",
        password_confirmation: "",
        position: "",
      },
      companyList: "",
      chainCodeList: "",
      branchNameList: "",
      showCompany: false,
    };
  },
  mounted() {
    this.fetchCompany();
  },
  methods: {
    fetchCompany() {
      axiosClient
        .get("/fetchCompany")
        .then((response) => {
          console.log("Company Response:", response.data);
          this.companyList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    fetchChainCode() {
      axiosClient
        .get("/fetchChainCode")
        .then((response) => {
          console.log("Company Response:", response.data);
          this.companyList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    directToLogin() {
      this.$router.push({ name: "Login" });
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
      this.showCompany = false;
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
          this.showCompany = true;
        } else {
          this.form.position = "User";
          this.showCompany = false;
        }
      }
    },
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
.sign-select .el-input__inner {
  border-left: none !important;
  border-top-left-radius: 0px !important;
  border-bottom-left-radius: 0px !important;
}
</style>
