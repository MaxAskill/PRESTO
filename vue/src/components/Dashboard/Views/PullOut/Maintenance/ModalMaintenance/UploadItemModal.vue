<template>
  <div
    class="modal fade"
    id="uploaditemmodal"
    tabindex="-1"
    aria-labelledby="uploaditemmodalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="uploaditemmodalLabel">
            UPLOAD ITEM
          </h1>
        </div>
        <div class="modal-body">
          <div class="upload-style">
            <input type="file" accept=".csv" />
          </div>
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
          <button type="button" class="btn btn-primary">Save</button>
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
    // return {
    //   newBranch: {
    //     branchCode: "",
    //     chainCode: "",
    //     branchName: "",
    //   },
    //   chainCodeList: [
    //     { id: 1, code: "0001" },
    //     { id: 2, code: "0002" },
    //     { id: 3, code: "0003" },
    //   ],
    // };
  },
  methods: {
    closeModal() {
      // this.newBranch.branchCode = "";
      // this.newBranch.chainCode = "";
      // this.newBranch.branchName = "";
    },
  },
};
</script>

<style lang="scss">
.form-group {
  text-align: left !important;
}
.upload-style {
  border-style: dashed;
  border-color: #9ca3af;
  border-radius: 0.5rem;
  border-width: 2px;
  padding-top: 10vh;
  padding-left: 15vw;
  padding-right: 10vw;
  padding-bottom: 10vh;
}
</style>
