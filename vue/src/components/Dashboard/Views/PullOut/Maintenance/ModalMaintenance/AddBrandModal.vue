<template>
  <div
    class="modal fade"
    id="addbrandmodal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="addbrandmodalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="addbrandmodalLabel">
            ADD BRAND
          </h1>
        </div>
        <div class="modal-body">
          <form>
            <div class="col-12">
              <fg-input
                label="Brand Name"
                placeholder="Brand Name"
                v-model="newBrand.brandName"
              ></fg-input>
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
          <button type="button" class="btn btn-primary" @click="submit">Save</button>
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
  props: ["transferredData"],
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
      newBrand: {
        brandName: "",
      },
    };
  },
  methods: {
    closeModal() {
      this.newBrand.brandName = "";
    },
    submit() {
      axiosClient
        .post("/addNewBrand", {
          brandName: this.newBrand.brandName,
          company: this.transferredData,
          userID: sessionStorage.getItem("UserID"),
        })
        .then((response) => {
          // console.log("Success Add Brand:", response.data);
          setTimeout(() => {
            window.location.reload();
          }, 3000); // Reload after 3 seconds
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
