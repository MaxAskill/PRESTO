<template>
  <div
    class="modal fade"
    id="editbrandmodal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="editbrandmodalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="editbrandmodal">
            EDIT BRAND
          </h1>
        </div>
        <div class="modal-body">
          <form>
            <div class="row">
              <div class="mx-auto col-12">
                <fg-input
                  label="Brand Name"
                  :placeholder="brandDetail.brandNames"
                  v-model.trim="brandNames"
                  @input="convertToUppercase"
                  @blur="handleBlur('brandNames')"
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
  props: ["brandDetail"],

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
      brandNames: "",
      isEmpty: false,
    };
  },
  mounted() {
    this.fetchChainCode();
  },
  watch: {},
  methods: {
    handleBlur(inputName) {
      console.log("Clicked outside the specific input box", inputName);
      if (this[inputName] === "") {
        this[inputName] = this.brandDetail[inputName];
      }
    },
    checking() {
      this.brandNames === "" ? (this.brandNames = this.brandDetail.brandNames) : "";
    },
    convertToUppercase() {
      this.brandNames = this.brandNames.toUpperCase();
    },
    submit() {
      console.log("before", this.brandNames);
      this.checking();
      console.log("after", this.brandNames);
      axiosClient.post("/addNewBrand", {
        brandName: this.brandNames,
      });
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
      this.brandNames = "";
    },
    fetchChainCode() {
      axiosClient
        .get("/fetchChainCodeAdmin", {
          params: {
            company: this.company,
          },
        })
        .then((response) => {
          this.chainCodeList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
      axiosClient
        .get("/fetchCompany")
        .then((response) => {
          this.companyList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>

<style>
.form-group {
  text-align: left !important;
}
</style>
