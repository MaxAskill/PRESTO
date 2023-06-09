<template>
  <div
    class="modal fade"
    id="deniedunprocess"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="deniedunprocessLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="deniedunprocessLabel">
            REASON
          </h1>
        </div>
        <div class="modal-body">
          <form>
            <div class="col-12 pull-left">
              <fg-input label="Reason" placeholder="Reason" v-model="reason"></fg-input>
            </div>
          </form>
        </div>
        <div class="modal-footer px-5">
          <button
            class="btn btn-secondary"
            data-bs-target="#unprocessModal"
            data-bs-toggle="modal"
            @click.prevent="cancelModal()"
          >
            Cancel
          </button>
          <button type="button" class="btn btn-primary">Send</button>
        </div>
      </div>
    </div>
  </div>
  <UnprocessModal></UnprocessModal>
</template>
<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";
import axiosClient from "../../../../../../../axios";
import UnprocessModal from "../UnprocessModal.vue";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  components: {
    UnprocessModal,
  },
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
      reason: "",
    };
  },
  methods: {
    cancelModal() {
      this.reason = "";
    },
  },
};
</script>

<style>
.form-group {
  text-align: left !important;
}
</style>
