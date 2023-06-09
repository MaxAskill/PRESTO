<template>
  <div
    class="modal fade"
    id="addbranchmodal"
    tabindex="-1"
    aria-labelledby="addbranchmodalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="addbranchmodalLabel">
            ADD BRANCH
          </h1>
        </div>
        <div class="modal-body">
          <form>
            <div class="row">
              <div class="col-6 pull-left">
                <fg-input
                  label="Branch Code"
                  placeholder="Branch Code"
                  v-model="newBranch.branchCode"
                ></fg-input>
              </div>
              <div class="col-6">
                <fg-input label="Chain Code">
                  <el-select
                    class="select-default"
                    size="large"
                    placeholder="Select Chain Code"
                    v-model="newBranch.chainCode"
                  >
                    <el-option
                      v-for="option in chainCodeList"
                      class="select-default"
                      :value="option.chainCode"
                      :label="option.chainCode"
                      :key="option.id"
                    >
                    </el-option>
                  </el-select>
                  <!-- <label v-show="isValid.company" class="label-font"
                    >Company is required.</label
                  > -->
                </fg-input>
              </div>
              <div class="col-12">
                <fg-input
                  label="Branch Name"
                  placeholder="Branch Name"
                  v-model="newBranch.branchName"
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
import axiosClient from "../../../../../../axios";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  props: ["company", "chainCodeList"],

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
      },
      chainCodeList: [],
    };
  },
  mounted() {
    this.fetchChainCode();
  },
  methods: {
    submit() {
      console.log("Submit:", this.company);

      axiosClient
        .post("/addNewBranch", {
          branchCode: this.newBranch.branchCode,
          chainCode: this.newBranch.chainCode,
          branchName: this.newBranch.branchName,
          userID: sessionStorage.getItem("UserID"),
          company: sessionStorage.getItem("Company"),
        })
        .then((response) => {
          console.log("Response:", response.data);
          setTimeout(() => {
            window.location.reload();
          }, 3000); // Reload after 3 seconds
        })
        .catch((error) => {
          console.error(error);
        });
    },
    closeModal() {
      this.newBranch.branchCode = "";
      this.newBranch.chainCode = "";
      this.newBranch.branchName = "";
    },
    fetchChainCode() {
      axiosClient
        .get("/fetchChainCodeAdmin", {
          params: {
            company: this.company,
          },
        })
        .then((response) => {
          console.log("Success Chain Code Response:", response.data);
          this.chainCodeList = response.data;
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
