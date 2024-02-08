<template>
  <div
    class="modal fade"
    id="editreasonmodal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="editreasonmodalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="editreasonmodal">
            EDIT REASON 
          </h1>
        </div>
        <div class="modal-body">
          <form>
            <div class="row">
              <div class="mx-auto col-2">
                <fg-input label="Company">
                  <el-select
                    class="select-default"
                    size="large"
                    :placeholder="reasonDetail.company"
                    v-model="company"
                    @blur="handleBlur('company')"
                  >
                    <el-option
                      v-for="option in companyList"
                      class="select-default"
                      :value="option.company"
                      :label="option.company"
                      :key="option.id"
                    >
                    </el-option>
                  </el-select>
                </fg-input>
              </div>
              <div class="mx-auto col-10">
                <fg-input
                  label="Company"
                  :placeholder="reasonDetail.reasonLabel"
                  v-model.trim="reasonLabel"
                  @input="convertToUppercase"
                  @blur="handleBlur('reasonLabel')"
                ></fg-input>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer px-5">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
            @click.prevent="closeModal()"
          >
            Close
          </button>
          <button
            type="button"
            class="btn btn-primary"
            :disabled="isEmpty"
            @click.prevent="submit"
          >
            Save
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- </div> -->
</template>
<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";
import axiosClient from "../../../../../../../axios";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  props: ["reasonDetail"],

  watch: {},
  computed: {
    /***
     * Searches through table data and returns a paginated array.
     * Note that this should not be used for table with a lot of data as it might be slow!
     * Do the search and the pagination on the server and display the data retrieved from server instead.
     * @returns {computed.pagedData}
     */
  },
  data() {
    return {
      newBranch: {
        branchCode: "",
        chainCode: "",
        branchName: "",
        company: "",
      },
      chainCodeList: [],
      company: "",
      reasonLabel: "",
      isEmpty: false,
      companyList: [
        {
          id: 1,
          company: "NBFI",
        },
        {
          id: 2,
          company: "EPC",
        },
      ],
    };
  },
  mounted() {
    this.fetchChainCode();
  },
  watch: {
    // brandNames: {
    //   handler() {
    //     this.checking();
    //   },
    //   deep: true,
    // },
  },
  methods: {
    handleBlur(inputName) {
      console.log("Clicked outside the specific input box", inputName);
      if (this[inputName] === "") {
        this[inputName] = this.reasonDetail[inputName];
      }
    },
    checking() {
      this.company === "" ? (this.company = this.reasonDetail.company) : "";
      this.reasonLabel === "" ? (this.reasonLabel = this.reasonDetail.reasonLabel) : "";
    },
    convertToUppercase() {
      this.company = this.company.toUpperCase();
      this.reasonLabel = this.reasonLabel.toUpperCase();
    },
    submit() {
      console.log("before", this.company);
      this.checking();
      console.log("after", this.company);
      
      console.log("before", this.reasonLabel);
      this.checking();
      console.log("after", this.reasonLabel);
      // axiosClient
      //   .post("/addNewBranch", {
      //     branchCode: this.newBranch.branchCode,
      //     chainCode: this.newBranch.chainCode,
      //     branchName: this.newBranch.branchName,
      //     userID: sessionStorage.getItem("UserID"),
      //     companyType: this.newBranch.company,
      //     company: this.company,
      //   })
      //   .then((response) => {
      //     // console.log("Response:", response.data);
      //     setTimeout(() => {
      //       window.location.reload();
      //     }, 3000); // Reload after 3 seconds
      //   })
      //   .catch((error) => {
      //     console.error(error);
      //   });
    },
    closeModal() {
      this.company = "";
      this.reasonLabel = "";
    },
    fetchChainCode() {
      // axiosClient
      //   .get("/fetchChainCodeAdmin", {
      //     params: {
      //       company: this.company,
      //     },
      //   })
      //   .then((response) => {
      //     this.chainCodeList = response.data;
      //   })
      //   .catch((error) => {
      //     console.error(error);
      //   });
      // axiosClient
      //   .get("/fetchCompany")
      //   .then((response) => {
      //     this.companyList = response.data;
      //   })
      //   .catch((error) => {
      //     console.error(error);
      //   });
    },
  },
};
</script>

<style>
.form-group {
  text-align: left !important;
}
</style>
