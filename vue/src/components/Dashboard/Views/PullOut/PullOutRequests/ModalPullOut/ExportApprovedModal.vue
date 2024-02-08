<template>
  <div
    class="modal fade"
    id="exportApproved"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="exportApprovedLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header padding-export">
          <h1 class="modal-title fs-5 text-weight-bold" id="exportApprovedLabel">
            EXPORT OPTIONS
          </h1>
        </div>
        <div class="modal-footer d-flex justify-content-center padding-export">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button
            type="button"
            class="btn btn-success"
            data-bs-dismiss="modal"
            @click="exportTransfer(true)"
          >
            Export All Entries
          </button>
          <button
            :disabled="disableBtn"
            type="button"
            class="btn btn-success"
            data-bs-dismiss="modal"
            @click="exportTransfer(false)"
          >
            Selected Entries Only
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  props: ["disableBtn"],
  components: {},
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
    return {};
  },
  mounted() {},
  methods: {
    exportTransfer(x) {
      this.$emit("exportTransfer", x);
    },
  },
};
</script>

<style lang="scss">
.padding-export {
  padding: 10px 0px;
}
</style>
