<template>
  <div class="login-page">
    <div class="wrapper wrapper-full-page">
      <div class="full-page login-page section-image">
        <!--   you can change the color of the filter page using: data-color="blue | azure | green | orange | red | purple" -->
        <div class="content">
          <div class="container">
            <div class="col-lg-4 col-md-6 ml-auto mr-auto">
              <form @submit.prevent="submit">
                <card type="login" class="m-0 p-0">
                  <p class="text-center font-size1">
                    Enter a new password for {{ email }}
                  </p>

                  <fg-input
                    type="password"
                    label="Password"
                    class="mb-1"
                    v-model="password"
                    @input="validatePassword"
                  >
                  </fg-input>
                  <p v-show="errorMsg" class="error-font mb-1">{{ errorMsg }}</p>
                  <fg-input
                    type="password"
                    :disabled="isConfirmPassword"
                    label="Confirm Password"
                    class="mb-0"
                    v-model="confirmPassword"
                    @input="matched"
                  >
                  </fg-input>

                  <div class="card-footer pt-10px p-0 d-flex justify-content-center">
                    <p-button
                      :disabled="isDisabled"
                      native-type="submit"
                      type="warning"
                      round
                      >Reset Password
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
import axiosClient from "../../../../../axios";

export default {
  components: {
    Card,
    [Checkbox.name]: Checkbox,
    [Button.name]: Button,
  },
  mounted() {
    this.fetchEmail();
  },
  methods: {
    fetchEmail() {
      this.email = sessionStorage.getItem("Email");
      console.log("Email", this.email);
    },
    submit() {
      //Reset User's Password

      axiosClient
        .post("/updatePasswordUser", {
          email: sessionStorage.getItem("Email"),
          password: this.password,
        })
        .then((response) => {
          console.log("Success Changed Password", response.data);
          this.$router.push({ name: "Login" });
        })
        .catch((error) => {
          console.error(error);
        });
    },
    validatePassword() {
      if (
        /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,}$/.test(
          this.password
        )
      ) {
        this.isConfirmPassword = false;
        this.errorMsg = "";
      } else {
        this.confirmPassword = "";
        this.isConfirmPassword = true;
        this.errorMsg =
          "Password must be at least 8 characters long which contain of at least a lowercase letter, an uppercase letter, a number, and a special character.";
      }
    },
    matched() {
      if (this.password == this.confirmPassword) {
        this.isDisabled = false;
        this.errorMsg = "";
      } else {
        this.isDisabled = true;
        this.errorMsg = "The password confirmation does not match.";
      }
    },
  },
  data() {
    return {
      password: "",
      confirmPassword: "",
      isConfirmPassword: true,
      errorMsg: "",
      isDisabled: true,
      email: "",
    };
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
.error-font {
  font-size: 12px !important;
  color: red !important;
  padding: 0px 6px;
}
</style>
