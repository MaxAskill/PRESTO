<template>
  <div
    class="modal fade"
    id="edititemmodal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="edititemmodalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="edititemmodal">EDIT ITEM</h1>
          {{ itemDetail }}
        </div>
        <div class="modal-body">
          <form>
            <div class="row">
              <div class="mx-auto col-3">
                <fg-input
                  label="Item Code"
                  :placeholder="itemDetail.ItemNo"
                  v-model.trim="ItemNo"
                  @input="convertToUppercase"
                  @blur="handleBlur('ItemNo')"
                ></fg-input>
              </div>
              <div class="mx-auto col-6">
                <fg-input
                  label="Item Description"
                  :placeholder="itemDetail.ItemDescription"
                  v-model.trim="ItemDescription"
                  @input="convertToUppercase"
                  @blur="handleBlur('ItemDescription')"
                ></fg-input>
              </div>
              <div class="mx-auto col-3">
                <fg-input label="Brand Name">
                  <el-select
                    class="select-default"
                    size="large"
                    :placeholder="itemDetail.brandNames"
                    v-model="brandNames"
                    @blur="handleBlur('brandNames')"
                  >
                    <el-option
                      v-for="option in CodeList"
                      class="select-default"
                      :value="option.brand"
                      :label="option.brand"
                      :key="option.id"
                    >
                    </el-option>
                  </el-select>
                </fg-input>
              </div>
              <div class="mx-auto col-3">
                <fg-input
                  label="Department"
                  :placeholder="itemDetail.Department"
                  v-model.trim="Department"
                  @input="convertToUppercase"
                  @blur="handleBlur('Department')"
                ></fg-input>
              </div>
              <div class="mx-auto col-3">
                <fg-input
                  label="Sub-Department"
                  :placeholder="itemDetail.SubDepartment"
                  v-model.trim="SubDepartment"
                  @input="convertToUppercase"
                  @blur="handleBlur('SubDepartment')"
                ></fg-input>
              </div>
              <div class="mx-auto col-3">
                <fg-input
                  label="Category"
                  :placeholder="itemDetail.Category"
                  v-model.trim="Category"
                  @input="convertToUppercase"
                  @blur="handleBlur('Category')"
                ></fg-input>
              </div>
              <div class="mx-auto col-3">
                <fg-input
                  label="Sub-Category"
                  :placeholder="itemDetail.SubCategory"
                  v-model.trim="SubCategory"
                  @input="convertToUppercase"
                  @blur="handleBlur('SubCategory')"
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
          <button type="button" class="btn btn-primary" @click.prevent="submit">
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
  props: ["itemDetail"],

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
      CodeList: [
        {
          id: 1,
          brand: "BARBIZON",
        },
        {
          id: 2,
          brand: "SADSSA",
        },
        {
          id: 3,
          brand: "ISLAND HAZE",
        },
      ],
      chainCodeList: [],
      companyList: [],
      ItemNo: "",
      ItemDescription: "",
      brandNames: "",
      Department: "",
      SubDepartment: "",
      Category: "",
      SubCategory: "",
      isEmpty: true,
    };
  },
  mounted() {
    this.fetchChainCode();
  },
  watch: {},
  methods: {
    checking() {
      this.ItemNo === "" ? (this.ItemNo = this.itemDetail.ItemNo) : "";
      this.ItemDescription === ""
        ? (this.ItemDescription = this.itemDetail.ItemDescription)
        : "";
      this.brandNames === "" ? (this.brandNames = this.itemDetail.brandNames) : "";
      this.Department === "" ? (this.Department = this.itemDetail.Department) : "";
      this.SubDepartment === ""
        ? (this.SubDepartment = this.itemDetail.SubDepartment)
        : "";
      this.Category === "" ? (this.Category = this.itemDetail.Category) : "";
      this.SubCategory === "" ? (this.SubCategory = this.itemDetail.SubCategory) : "";
    },
    handleBlur(inputName) {
      console.log("Clicked outside the specific input box", inputName);
      if (this[inputName] === "") {
        this[inputName] = this.itemDetail[inputName];
      }
    },
    convertToUppercase() {
      this.newBrandName = this.newBrandName.toUpperCase();
    },
    submit() {
      console.log("before", this.ItemNo);
      this.checking();
      console.log("after", this.ItemNo);

      console.log("before", this.ItemDescription);
      this.checking();
      console.log("after", this.ItemDescription);

      console.log("before", this.brandNames);
      this.checking();
      console.log("after", this.brandNames);

      console.log("before", this.Department);
      this.checking();
      console.log("after", this.Department);

      console.log("before", this.SubDepartment);
      this.checking();
      console.log("after", this.SubDepartment);

      console.log("before", this.Category);
      this.checking();
      console.log("after", this.Category);

      console.log("before", this.SubCategory);
      this.checking();
      console.log("after", this.SubCategory);
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
      this.ItemNo = "";
      this.ItemDescription = "";
      this.brandNames = "";
      this.Department = "";
      this.SubDepartment = "";
      this.Category = "";
      this.SubCategory = "";
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
