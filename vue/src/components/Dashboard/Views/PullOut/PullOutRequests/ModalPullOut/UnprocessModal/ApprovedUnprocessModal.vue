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
            {{ transferredData.branchName }}
          </h1>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12 pull-left">
              <fg-input
                label="Name of Sales Representative / Promodiser"
                v-model="transferredData.createdBy"
              ></fg-input>
            </div>
            <div class="col-6">
              <fg-input label="Pull-Out Date Started">
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
              <fg-input label="Pull-Out Date Ended">
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
        </div>
        <div class="modal-footer px-5">
          <button
            class="btn btn-secondary"
            data-bs-target="#unprocessModal"
            data-bs-toggle="modal"
          >
            Cancel
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
    <UnprocessModal></UnprocessModal>
  </div>
</template>
<script>
import Vue from "vue";
import { DatePicker, Table, TableColumn, Select, Option } from "element-ui";
import axiosClient from "../../../../../../../axios";
import UnprocessModal from "../UnprocessModal.vue";
import axios from "axios";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
Vue.use(DatePicker);
export default {
  props: ["transferredData", "itemData"],
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
    };
  },
  watch: {
    "transferredData.createdBy": function (val, oldVal) {
      this.validateGenerate();
    },
    dateStarted: "validateGenerate",
    dateEnded: "validateGenerate",
  },
  methods: {
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

      // console.log("Date Start:", this.dateStarted);
      // console.log("Date End:", this.dateEnded);
      axiosClient
        .post("/postDatesLetter", {
          id: this.transferredData.plID,
          dateStarted: this.dateStarted,
          dateEnded: this.dateEnded,
          authorizedPersonnel: this.transferredData.createdBy,
          company: sessionStorage.getItem("Company"),
          userID: sessionStorage.getItem("UserID"),
        })
        .then((response) => {
          console.log("Success Post Dates Letter", response.data);
        })
        .catch((error) => {
          console.error(error);
        });

      for (var x = 0; x < this.itemData.length; x++) {
        axiosClient
          .post("/updateItemQuantity", {
            company: sessionStorage.getItem("Company"),
            id: this.itemData[x].id,
            boxNumber: this.itemData[x].boxNumber,
            boxLabel: this.itemData[x].boxLabel,
            quantity: this.itemData[x].quantity,
            userID: sessionStorage.getItem("UserID"),
          })
          .then((response) => {
            // console.log("Success Save", response.data);
          })
          .catch((error) => {
            console.error(error);
          });
      }
      axiosClient
        .post("/updateBranchStatus", {
          company: sessionStorage.getItem("Company"),
          id: this.transferredData.plID,
          // name: this.userName,
          status: "endorsement",
          userID: sessionStorage.getItem("UserID"),
        })
        .then((response) => {
          // console.log("Success:", response.data);
          const baseUrl = "http://192.168.0.7:40/api/generatePDF";
          const queryParams = new URLSearchParams({
            name: this.transferredData.createdBy,
            plID: this.transferredData.plID,
            dateStart: tempDateStart,
            dateEnd: tempDateEnd,
            email: this.transferredData.promoEmail,
            userID: sessionStorage.getItem("UserID"),
            status: "endorsement",
            company: sessionStorage.getItem("Company"),
            regenerate: "generate",
          });

          const fullUrl = `${baseUrl}?${queryParams}`;

          window.open(fullUrl, "_blank");
          setTimeout(() => {
            window.location.reload();
          }, 3000); // Reload after 3 seconds
        })
        .catch((error) => {
          console.error(error);
        });
    },
    validateGenerate() {
      if (this.transferredData.createdBy && this.dateStarted && this.dateEnded)
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
