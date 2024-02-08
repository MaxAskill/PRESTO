<template>
  <div
    class="modal fade"
    id="editpersonnelmodal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="editpersonnelmodalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="editpersonnelmodal">
            EDIT PERSONNEL DETAIL 
          </h1>
        </div>
        <div class="modal-body">
          <form>
            <div class="row">
              <div class="mx-auto col-6">
                <fg-input
                  label="Brand Name"
                  :placeholder="personnelDetail.name"
                  v-model.trim="name"
                  @input="convertToUppercase"
                  @blur="handleBlur('name')"
                ></fg-input>
              </div>
              <div class="mx-auto col-6">
                <fg-input label="Brand Name">
                  <el-select
                    class="select-default"
                    size="large"
                    :placeholder="personnelDetail.position"
                    v-model="position"
                    @blur="handleBlur('position')"
                  >
                    <el-option
                      v-for="option in positionList"
                      class="select-default"
                      :value="option.position"
                      :label="option.position"
                      :key="option.id"
                    >
                    </el-option>
                  </el-select>
                </fg-input>
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
  props: ["personnelDetail"],

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
      name: "",
      position: "",
      isEmpty: false,
      positionList: [
        {
          id: 1,
          position: "DRIVER",
        },
        {
          id: 2,
          position: "DELIVERY HELPER",
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
        this[inputName] = this.personnelDetail[inputName];
      }
    },
    checking() {
      this.name === "" ? (this.name = this.personnelDetail.name) : "";
      this.position === "" ? (this.position = this.personnelDetail.position) : "";
    },
    convertToUppercase() {
      this.name = this.name.toUpperCase();
    },
    submit() {
      console.log("before", this.name);
      this.checking();
      console.log("after", this.name);
      
      console.log("before", this.position);
      this.checking();
      console.log("after", this.position);
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
      this.name = "";
      this.position = "";
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
