<template>
  <div
    class="modal fade"
    id="addpersonnelmodal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="addpersonnelmodalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="addpersonnelmodalLabel">
            ADD PERSONNEL
          </h1>
        </div>
        <div class="modal-body">
          <form>
            <div class="row">
              <div class="col-sm-6 pull-left">
                <fg-input
                  label="Personnel Name"
                  placeholder="Personnel Name"
                  v-model="newPersonnel.name"
                ></fg-input>
              </div>
              <div class="col-sm-6">
                <fg-input
                  label="Position"
                  placeholder="Position"
                  v-model="newPersonnel.position"
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
import axios from "axios";

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
    return {
      newPersonnel: {
        name: "",
        position: "",
      },
    };
  },
  methods: {
    closeModal() {
      this.newPersonnel.name = "";
      this.newPersonnel.position = "";
    },
    submit() {
      axiosClient
        .post("/addNewDriver", {
          name: this.newPersonnel.name,
          position: this.newPersonnel.position,
          userID: sessionStorage.getItem("UserID"),
        })
        .then((response) => {
          console.log("Success Add Personnel", response.data);
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
