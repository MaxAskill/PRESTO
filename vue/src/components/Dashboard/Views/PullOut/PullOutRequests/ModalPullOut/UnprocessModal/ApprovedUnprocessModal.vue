<template>
  <div
    class="modal fade"
    id="approvedunprocess"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="approvedunprocessLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="approvedunprocessLabel">
            BRANCH NAME
          </h1>
        </div>
        <div class="modal-body">
          <form>
            <div class="row">
              <div class="col-12 pull-left">
                <fg-input
                  label="Name of Sales Representative / Promodiser"
                  v-model="promodiser"
                ></fg-input>
              </div>
              <div class="col-6">
                <fg-input label="Date Started">
                  <el-date-picker
                    v-model="dateStarted"
                    type="date"
                    placeholder="Select a Day"
                    :picker-options="pickerOptions"
                  >
                  </el-date-picker>
                </fg-input>
              </div>
              <div class="col-6">
                <fg-input label="Date Ended">
                  <el-date-picker
                    v-model="dateEnded"
                    type="date"
                    placeholder="Select a Day"
                    :picker-options="pickerOptions"
                  >
                  </el-date-picker>
                </fg-input>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer px-5">
          <button
            class="btn btn-secondary"
            data-bs-target="#unprocessModal"
            data-bs-toggle="modal"
            @click.prevent="closeModal()"
          >
            Close
          </button>
          <button type="button" class="btn btn-primary">Generate Letter</button>
        </div>
      </div>
    </div>
  </div>
  <UnprocessModal></UnprocessModal>
</template>
<script>
import Vue from "vue";
import { DatePicker, Table, TableColumn, Select, Option } from "element-ui";
import axiosClient from "../../../../../../../axios";
import UnprocessModal from "../UnprocessModal.vue";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
Vue.use(DatePicker);
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
      promodiser: "",
      pickerOptions: {
        shortcuts: [
          {
            text: "Today",
            onClick(picker) {
              picker.$emit("pick", new Date());
            },
          },
        ],
      },
      dateStarted: "",
      dateEnded: "",
    };
  },
  methods: {
    closeModal() {
      this.promodiser = "";
    },
  },
};
</script>

<style>
.form-group {
  text-align: left !important;
}
</style>
