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
                label="Assigned Personnel for Pull-Out/Promodiser/Accredited Service Representative"
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
import { Message, MessageBox, Loading } from "element-ui";

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
    validateDates() {
      var isStartDate = new Date(this.dateStarted); //dd-mm-YYYY
      var isEndDate = new Date(this.dateEnded); //dd-mm-YYYY
      var createdDate = new Date(this.transferredData.date);

      console.log("TD:: ", this.transferredData);
      if (isStartDate < createdDate) {
        Message.error(
          "Start Date must be any date later than the created date [ " +
            this.transferredData.date +
            " ]."
        );
        return false;
      } else if (isEndDate < isStartDate) {
        Message.error("End Date must be equal or any date later than Start date.");
        return false;
      } else return true;
    },
    generateLetter() {
      if (this.validateDates()) {
        const yearStart = this.dateStarted.getFullYear();
        const monthStart = String(this.dateStarted.getMonth() + 1).padStart(2, "0"); // Months are zero-based, so add 1
        const dayStart = String(this.dateStarted.getDate()).padStart(2, "0");

        const tempDateStart = `${yearStart}-${monthStart}-${dayStart}`;

        const yearEnd = this.dateEnded.getFullYear();
        const monthEnd = String(this.dateEnded.getMonth() + 1).padStart(2, "0"); // Months are zero-based, so add 1
        const dayEnd = String(this.dateEnded.getDate()).padStart(2, "0");

        const tempDateEnd = `${yearEnd}-${monthEnd}-${dayEnd}`;

        axiosClient
          .post("/postDatesLetter", {
            id: this.transferredData.plID,
            dateStarted: tempDateStart,
            dateEnded: tempDateEnd,
            authorizedPersonnel: this.transferredData.createdBy,
            company: sessionStorage.getItem("Company"),
            userID: sessionStorage.getItem("UserID"),
            status: "endorsement",
          })
          .then((response) => {
            console.log("Success Post Dates Letter", response.data);
          })
          .catch((error) => {
            console.error(error);
          });

        axiosClient
          .post("/updateBranchStatus", {
            company: sessionStorage.getItem("Company"),
            id: this.transferredData.plID,
            status: "endorsement",
            userID: sessionStorage.getItem("UserID"),
            editStatus: false,
          })
          .then((response) => {
            var holderName = this.convertToAlphanumeric("name");
            var holderplID = this.convertToAlphanumeric("plID");
            var holderDateStart = this.convertToAlphanumeric("dateStart");
            var holderDateEnd = this.convertToAlphanumeric("dateEnd");
            var holderEmail = this.convertToAlphanumeric("email");
            var holderUserID = this.convertToAlphanumeric("userID");
            var holderStatus = this.convertToAlphanumeric("status");
            var holderCompany = this.convertToAlphanumeric("company");
            var holderRegenerate = this.convertToAlphanumeric("regenerate");

            window.open(
              "http://192.168.0.7:90/api/generatePDF?" +
                holderName +
                "=" +
                this.transferredData.createdBy +
                "&" +
                holderplID +
                "=" +
                this.transferredData.plID +
                "&" +
                holderDateStart +
                "=" +
                tempDateStart +
                "&" +
                holderDateEnd +
                "=" +
                tempDateEnd +
                "&" +
                holderEmail +
                "=" +
                this.transferredData.promoEmail +
                "&" +
                holderUserID +
                "=" +
                sessionStorage.getItem("UserID") +
                "&" +
                holderStatus +
                "=endorsement" +
                "&" +
                holderCompany +
                "=" +
                sessionStorage.getItem("Company") +
                "&" +
                holderRegenerate +
                "=generate",
              "_blank"
            );
            let loadingInstance = Loading.service({
              fullscreen: true,
              lock: true,
              text: "Loading",
              spinner: "el-icon-loading",
              background: "rgba(0, 0, 0, 0.7)",
            });

            setTimeout(() => {
              loadingInstance.close();
              window.location.reload();
            }, 3000);
            // setTimeout(() => {
            //   window.location.reload();
            // }, 2000); // Reload after 3 seconds
          })
          .catch((error) => {
            console.error(error);
          });
      }
    },
    validateGenerate() {
      if (this.transferredData.createdBy && this.dateStarted && this.dateEnded)
        this.isDisabled = false;
      else this.isDisabled = true;
    },
    convertToAlphanumeric(input) {
      let result = "";

      for (let i = 0; i < input.length; i++) {
        const char = input[i];
        const charCode = char.charCodeAt(0);

        // Check if the character is alphanumeric
        if (
          (char >= "0" && char <= "9") ||
          (char >= "a" && char <= "z") ||
          (char >= "A" && char <= "Z")
        ) {
          // Convert the character code to a base-36 alphanumeric representation
          const alphanumericChar = charCode.toString(36);
          result += alphanumericChar;
        } else {
          // Non-alphanumeric characters are left unchanged
          result += char;
        }
      }

      return result;
    },
  },
};
</script>

<style>
.form-group {
  text-align: left !important;
}
</style>
