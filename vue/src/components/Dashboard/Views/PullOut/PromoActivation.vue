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
            aria-readonly="true"
            addon-left-icon="nc-icon nc-single-02"
            placeholder="Full Name"
          ></fg-input>
          <fg-input
            v-model="promoData.email"
            disabled
            addon-left-icon="nc-icon nc-email-85"
            placeholder="Email"
          ></fg-input>
          <div class="input-group">
            <div class="input-group-prepend" style="width: 100%">
              <span class="input-group-text">
                <i class="nc-icon nc-bank"></i>
              </span>
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
          </div>
          <div class="input-group d-flex">
            <div class="input-group-prepend" style="width: 100%">
              <span class="input-group-text">
                <i class="nc-icon nc-basket"></i>
              </span>
              <el-select
                suffix-icon="nc-icon nc-key-25"
                class="select-default flex-grow-1 sign-select"
                size="large"
                placeholder="Select Chain Name"
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
          </div>
          <div class="input-group d-flex">
            <div class="input-group-prepend" style="width: 100%">
              <span class="input-group-text">
                <i class="nc-icon nc-shop"></i>
              </span>
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
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button class="btn btn-secondary" data-bs-dismiss="modal" @click="cancel">
            Cancel
          </button>
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
import { DatePicker, Select, Option } from "element-ui";
import axiosClient from "../../../../axios";
import Vue from "vue";

Vue.use(DatePicker);
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
        dateEnd: "",
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
    if (
      sessionStorage.getItem("Position") == "Reviewer" ||
      sessionStorage.getItem("Position") == "Approver"
    )
      this.fetchCompanyByRA();
    else this.fetchCompany();
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
          this.companyList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    fetchCompanyByRA() {
      axiosClient
        .get("/fetchCompanyByRA", {
          params: {
            company: sessionStorage.getItem("Company"),
          },
        })
        .then((response) => {
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
          this.branchNameList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    register() {
      axiosClient
        .post("/postUserBranch", {
          userID: this.promoData.id,
          chainCode: this.form.chainCode,
          branchName: this.form.branchName,
          user: sessionStorage.getItem("UserID"),
          company: this.form.company,
        })
        .then((response) => {
          this.$emit("fetchUsers");
        })
        .catch((error) => {
          console.error(error);
        });
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
    cancel() {
      (this.form.company = ""), (this.form.chainCode = ""), (this.form.branchName = "");
    },
  },
};
</script>
<style lang="scss">
.sign-select .el-input__inner {
  border-left: none !important;
  border-top-left-radius: 0px !important;
  border-bottom-left-radius: 0px !important;
}
</style>
