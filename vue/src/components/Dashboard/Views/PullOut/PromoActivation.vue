<template>
  <div
    class="modal fade"
    id="promoActivation"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="promoActivationLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="promoActivationLabel">
            Promodiser's Account
          </h1>
        </div>
        <div class="text-center modal-body px-4">
          <fg-input
            v-model="promoData.name"
            disabled
            addon-left-icon="nc-icon nc-single-02"
            placeholder="Full Name"
          ></fg-input>
          <fg-input
            v-model="promoData.email"
            disabled
            addon-left-icon="nc-icon nc-email-85"
            placeholder="Email"
          ></fg-input>
          <div class="input-group d-flex">
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
              @change="fetchChainCode"
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
          <div class="input-group d-flex">
            <div class="input-group-prepend">
              <span class="input-group-text">
                <i class="nc-icon nc-bank"></i>
              </span>
            </div>
            <el-select
              suffix-icon="nc-icon nc-key-25"
              class="select-default flex-grow-1 sign-select"
              size="large"
              placeholder="Select Chain Code"
              v-model="form.chainCode"
              :disabled="disableChainCode"
              @change="fetchBranchName"
            >
              <el-option
                v-for="option in chainCodeList"
                class="select-default"
                :value="option.chainCode"
                :label="option.chainCode"
                :key="option.chainCode"
              >
              </el-option>
            </el-select>
          </div>
          <div class="input-group d-flex">
            <div class="input-group-prepend">
              <span class="input-group-text">
                <i class="nc-icon nc-bank"></i>
              </span>
            </div>
            <el-select
              suffix-icon="nc-icon nc-key-25"
              class="select-default flex-grow-1 sign-select"
              size="large"
              placeholder="Select Branch Name"
              v-model="form.branchName"
              :disabled="disableBranchName"
            >
              <el-option
                v-for="option in branchNameList"
                class="select-default"
                :value="option.branchName"
                :label="option.branchName"
                :key="option.branchName"
              >
              </el-option>
            </el-select>
          </div>

          <!-- <div class="card-footer my-2">
                <p-button native-type="submit" type="info" round>Register </p-button>
              </div> -->
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button
            :disabled="enableBtn"
            type="button"
            class="btn btn-primary"
            data-bs-dismiss="modal"
            @click="register"
          >
            Activate This Account
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { Card, Checkbox, Button, InfoSection } from "src/components/UIComponents";
import { Select, Option } from "element-ui";
import axiosClient from "../../../../axios";

export default {
  props: ["promoData"],
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
        company: "",
        chainCode: "",
        branchName: "",
        position: "",
      },
      companyList: "",
      disableChainCode: true,
      disableBranchName: true,
      enableBtn: true,
      chainCodeList: "",
      branchNameList: "",
    };
  },
  mounted() {
    this.fetchCompany();
  },
  watch: {
    form: {
      handler(val, oldVal) {
        this.formValidation();
      },
      deep: true,
    },
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
      this.disableChainCode = false;
      axiosClient
        .get("/fetchChainCode", {
          params: {
            company: this.form.company,
          },
        })
        .then((response) => {
          console.log("Chain Code Response:", response.data);
          this.chainCodeList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    fetchBranchName() {
      this.disableBranchName = false;
      axiosClient
        .get("/fetchChainName", {
          params: {
            company: this.form.company,
            chainCode: this.form.chainCode,
          },
        })
        .then((response) => {
          console.log("Chain Code Response:", response.data);
          this.branchNameList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    register() {
      // switch (this.form.company) {
      //   case "NBFI":
      //     this.form.company = 1;
      //     break;
      //   case "ASC":
      //     this.form.company = 2;
      //     break;
      //   case "CMC":z
      //     this.form.company = 3;
      //     break;
      //   case "EPC":
      //     this.form.company = 4;
      //     break;
      //   case "AHLC":
      //     this.form.company = 5;
      //     break;

      //   default:
      //     break;
      // }
      console.log("Promo Data: ", this.promoData);
      console.log("Form Data:", this.form);

      axiosClient
        .post("/postUserBranch", {
          userID: this.promoData.id,
          chainCode: this.form.chainCode,
          branchName: this.form.branchName,
          user: sessionStorage.getItem("UserID"),
          company: this.form.company,
        })
        .then((response) => {
          console.log("Success Activate Account", response.data);
        })
        .catch((error) => {
          console.error(error);
        });
      // axiosClient
      //   .get("/sendVerificationCode", {
      //     params: {
      //       email: this.form.email,
      //     },
      //   })
      //   .then((response) => {
      //     sessionStorage.setItem("Name", this.form.fullName);
      //     sessionStorage.setItem("Email", this.form.email);
      //     sessionStorage.setItem("Company", this.form.company);
      //     sessionStorage.setItem("Password", this.form.password);
      //     sessionStorage.setItem("Position", this.form.position);
      //     sessionStorage.setItem("HashCode", response.data);
      //     this.$router.push({ name: "OTPForm" });
      //   })
      //   .catch((error) => {
      //     console.error(error);
      //   });
    },
    formValidation() {
      if (this.form.company) this.disableChainCode = false;
      else this.disableChainCode = true;
      if (this.form.chainCode) this.disableBranchName = false;
      else this.disableBranchName;

      if (
        this.promoData.email &&
        this.promoData.name &&
        this.form.company &&
        this.form.chainCode &&
        this.form.branchName
      )
        this.enableBtn = false;
      else {
        this.enableBtn = true;
      }
    },
  },
};
</script>
<style lang="scss">
// .linkLogin {
//   text-decoration-line: underline !important;
//   margin-right: 10px;
//   color: gray !important;
// }
// .linkLogin:hover {
//   color: black !important;
//   cursor: pointer;
// }
.sign-select .el-input__inner {
  border-left: none !important;
  border-top-left-radius: 0px !important;
  border-bottom-left-radius: 0px !important;
}
</style>
