<template>
  <div
    class="modal fade"
    id="editbranchmodal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="editbranchmodalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="editbranchmodalLabel">
            EDIT BRANCH
          </h1>
        </div>
        <div class="modal-body">
          <form>
            <div class="row">
              <div class="col-4 pull-left">
                <fg-input label="Brand Name">
                  <el-select
                    class="select-default"
                    size="large"
                    :placeholder="branchDetail.chainCode"
                    v-model="chainCode"
                    @blur="handleBlur('chainCode')"
                  >
                    <el-option
                      v-for="option in chainCodeList"
                      class="select-default"
                      :value="option.chain"
                      :label="option.chain"
                      :key="option.id"
                    >
                    </el-option>
                  </el-select>
                </fg-input>
              </div>
              <div class="col-4">
                <fg-input
                  label="Branch Code"
                  :placeholder="branchDetail.branchCode"
                  v-model.trim="branchCode"
                  @input="convertToUppercase"
                  @blur="handleBlur('branchCode')"
                ></fg-input>
              </div>
              <div class="col-4">
                <fg-input
                  label="Branch Name"
                  :placeholder="branchDetail.branchName"
                  v-model.trim="branchName"
                  @input="convertToUppercase"
                  @blur="handleBlur('branchName')"
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

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  props: ["branchDetail"],
  // props: ["branchDetail"],

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
      companyList: [],
      chainCode: "",
      branchCode: "",
      branchName: "",
      isEmpty: false,
      chainCodeList: [
        {
          id: 1,
          chain: "ALL HOME",
        },
        {
          id: 2,
          chain: "METRO",
        },
        {
          id: 3,
          chain: "WDS",
        },
      ],
    };
  },
  mounted() {
    this.fetchChainCode();
  },
  watch: {
    // branchCode: {
    //   handler() {
    //     this.checking();
    //   },
    //   deep: true,
    // },
    // branchName: {
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
        this[inputName] = this.branchDetail[inputName];
      }
    },
    checking() {
      // if (this.newBranchCode === "" || this.newBranchName === "") {
      //   this.isEmpty = true;
      // } else {
      //   this.isEmpty = false;
      // }

      this.chainCode === "" ? (this.chainCode = this.branchDetail.chainCode) : "";
      this.branchCode === "" ? (this.branchCode = this.branchDetail.branchCode) : "";
      this.branchName === "" ? (this.branchName = this.branchDetail.branchName) : "";
    },
    convertToUppercase() {
      this.chainCode = this.chainCode.toUpperCase();
      this.branchCode = this.branchCode.toUpperCase();
      this.branchName = this.branchName.toUpperCase();
    },
    submit() {
      console.log("before", this.chainCode);
      this.checking();
      console.log("after", this.chainCode);

      console.log("before", this.branchCode);
      this.checking();
      console.log("after", this.branchCode);

      console.log("before", this.branchName);
      this.checking();
      console.log("after", this.branchName);
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
      this.chainCode = "";
      this.branchName = "";
      this.branchCode = "";
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
