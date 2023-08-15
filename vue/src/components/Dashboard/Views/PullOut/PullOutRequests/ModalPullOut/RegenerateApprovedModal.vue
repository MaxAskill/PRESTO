<template>
  <div
    class="modal fade"
    id="regenerateApproved"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="regenerateApprovedLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="regenerateApprovedLabel">
            {{ transferredData.branchName }}
          </h1>
        </div>
        <div class="modal-body">
          <form>
            <div class="row">
              <div class="col-12 pull-left">
                <fg-input
                  label="Name of Sales Representative / Promodiser"
                  v-model="transferredData.name"
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
            data-bs-target="#approvedModal"
            data-bs-toggle="modal"
          >
            Close
          </button>
          <button
            :disabled="isDisabled"
            type="button"
            class="btn btn-success"
            data-bs-dismiss="modal"
            @click="generateLetter"
          >
            Generate Letter
          </button>
        </div>
      </div>
    </div>
    <ApprovedModal></ApprovedModal>
  </div>
</template>
<script>
import Vue from "vue";
import { DatePicker, Table, TableColumn, Select, Option } from "element-ui";
import axiosClient from "../../../../../../axios";
import ApprovedModal from "./ApprovedModal.vue";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
Vue.use(DatePicker);
export default {
  props: ["transferredData", "itemData", "dateData"],
  components: {
    ApprovedModal,
  },
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
      isDisabled: true,
      authorizedPersonnel: "",
    };
  },
  watch: {
    "transferredData.name": function (val, oldVal) {
      this.validateGenerate();
    },
    dateData: {
      handler(val) {
        this.transferDate();
      },
      deep: true,
    },
    dateStarted: "validateGenerate",
    dateEnded: "validateGenerate",
  },
  methods: {
    transferDate() {
      console.log("Dates:", this.dateData);
      this.dateStarted = this.dateData[0].dateStart;
      this.dateEnded = this.dateData[0].dateEnd;
      this.authorizedPersonnel = this.dateData[0].authorizedPersonnel;
      console.log("Transfer Date:", this.dateStarted, this.dateEnded);
    },
    generateLetter() {
      var tempDateStart =
        this.dateStarted.toString().split(" ")[1] +
        " " +
        this.dateStarted.toString().split(" ")[2] +
        ", " +
        this.dateStarted.toString().split(" ")[3];

      var tempDateEnd =
        this.dateEnded.toString().split(" ")[1] +
        " " +
        this.dateEnded.toString().split(" ")[2] +
        ", " +
        this.dateEnded.toString().split(" ")[3];

      console.log("Date Start:", this.dateStarted, "Date End:", this.dateEnded);
      window.open(
        "http://192.168.0.7:40/api/generatePDF?name=" +
          this.transferredData.name +
          "&plID=" +
          this.transferredData.plID +
          "&dateStart=" +
          tempDateStart +
          "&dateEnd=" +
          tempDateEnd +
          "&email=" +
          this.transferredData.promoEmail +
          "&userID=" +
          sessionStorage.getItem("UserID") +
          "&company=" +
          sessionStorage.getItem("Company"),
        "_blank"
      );
      setTimeout(() => {
        window.location.reload();
      }, 3000); // Reload after 3 seconds
    },
    validateGenerate() {
      if (this.transferredData.name && this.dateStarted && this.dateEnded)
        this.isDisabled = false;
      else this.isDisabled = true;
    },
  },
};
</script>

<style>
.form-group {
  text-align: left !important;
}
</style>
