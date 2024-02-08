<template>
  <div>
    <div
      class="modal fade"
      id="submiteditauthorizeddatemodal"
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
              <!-- {{ transferredData.branchName }} -->
            </h1>
          </div>
          <div class="modal-body">
            <form>
              <div class="row">
                <div class="col-12 pull-left">
                  <fg-input
                    label="Assigned Personnel for Pull-Out/Promodiser/Accredited Service Representative"
                    v-model="transferTransactionPromoName"
                  ></fg-input>
                </div>
                <div class="col-6">
                  <fg-input label="Date Started">
                    <el-date-picker
                      v-model="dateStarted"
                      type="date"
                      placeholder="Select a Day"
                      :picker-options="pickerOptions"
                      @change="changeDateStart()"
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
                      @change="changeDateEnd()"
                    >
                    </el-date-picker>
                  </fg-input>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer px-5">
            <!-- <button
              class="btn btn-secondary"
              data-bs-target="#submiteditauthorizeddatemodal"
              data-bs-toggle="modal"
            >
              Close
            </button> -->
            <button
              :disabled="isDisabled"
              type="button"
              class="btn btn-success"
              data-bs-target="#submiteditauthorizeddatemodal"
              data-bs-dismiss="modal"
              @click="generateLetter()"
            >
              Generate Letter
            </button>
          </div>
        </div>
      </div>
    </div>
    <TransactionReceiptModal
      :transferTransactionID="transferTransactionID"
      :savingCounter="saving_counter"
      :transferTransactionPromoName="transferTransactionPromoName"
      :dateStarted="dateStarted"
      :dateEnded="dateEnded"
      :transferTransactionPromoEmail="transferTransactionPromoEmail"
      :status="status"
      :transactionReceipt="transactionReceipt"
    ></TransactionReceiptModal>
  </div>
</template>
<script>
import Vue from "vue";
import { DatePicker, Table, TableColumn, Select, Option } from "element-ui";
import TransactionReceiptModal from "./TransactionReceiptModal.vue";
import axiosClient from "../../../../../../axios";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
Vue.use(DatePicker);
export default {
  props: [
    "transferTransactionID",
    "transferTransactionPromoName",
    "transferTransactionPromoEmail",
    "savingCounter",
    "dateStart",
    "dateEnd",
    "transactionDetails",
    "tempRemoveItems",
    "toSubmit",
    "isDraft",
  ],
  components: {
    TransactionReceiptModal,
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
      DateStart: false,
      DateEnd: false,
      transactionReceipt: [],
    };
  },
  watch: {
    transferTransactionPromoName: function (val, oldVal) {
      this.validateGenerate();
    },
    dateStart: {
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
      this.dateStarted = this.dateStart;
      this.dateEnded = this.dateEnd;
    },
    async generateLetter() {
      if (this.DateStart) {
        const yearStart = this.dateStarted.getFullYear();
        const monthStart = String(this.dateStarted.getMonth() + 1).padStart(2, "0"); // Months are zero-based, so add 1
        const dayStart = String(this.dateStarted.getDate()).padStart(2, "0");

        this.dateStarted = `${yearStart}-${monthStart}-${dayStart}`;
      }
      if (this.DateEnd) {
        const yearEnd = this.dateEnded.getFullYear();
        const monthEnd = String(this.dateEnded.getMonth() + 1).padStart(2, "0"); // Months are zero-based, so add 1
        const dayEnd = String(this.dateEnded.getDate()).padStart(2, "0");

        this.dateEnded = `${yearEnd}-${monthEnd}-${dayEnd}`;
      }

      const status =
        sessionStorage.getItem("Position") == "Reviewer" ? "endorsement" : "approved";
      this.transferTransactionPromoEmail = sessionStorage.getItem("Email");
      console.log("Transaction Details 2:", this.transactionDetails);
      console.log("Transaction Items:", Array.isArray(this.transactionDetails.items));
      console.log("Transaction Boxes:", Array.isArray(this.transactionDetails.boxes));
      console.log("Transaction Promo Name:", this.transferTransactionPromoName);
      console.log("Transaction Promo Email:", this.transferTransactionPromoEmail);
      console.log("Transaction Date Started:", this.dateStarted);
      console.log("Transaction Date Ended:", this.dateEnded);

      if (this.toSubmit == "save") {
        console.log("Save at Authorized Date Modal");
        var response = await axiosClient.post("/savePullOutBranchRequest", {
          chainCode: this.transactionDetails.chainCode,
          companyType: this.transactionDetails.companyType,
          branchName: this.transactionDetails.branchName,
          transactionType: this.transactionDetails.transactionType,
          boxes: this.transactionDetails.boxes,
          items: this.transactionDetails.items,
          email: sessionStorage.getItem("Email"),
          status: status,
        });
        this.transferTransactionID = response.data;
        const letterDates = await axiosClient.post("/postDatesLetter", {
          id: this.transferTransactionID,
          authorizedPersonnel: this.transferTransactionPromoName,
          company: sessionStorage.getItem("Company"),
          dateStarted: this.dateStarted,
          dateEnded: this.dateEnded,
          status: status,
        });
        this.transactionReceipt = {
          transferTransactionPromoName: this.transferTransactionPromoName,
          transferTransactionID: this.transferTransactionID,
          dateStarted: this.dateStarted,
          dateEnded: this.dateEnded,
          transferTransactionPromoEmail: this.transferTransactionPromoEmail,
          status: status,
        };
        console.log("ID:", this.transferTransactionID);
        const transactionModal = new bootstrap.Modal("#transactionReceipt");
        transactionModal.show();
      } else if (this.toSubmit == "update") {
        console.log("Update at Authorized Date Modal");
        var isStatus = await axiosClient.get("/fetchBranchStatus", {
          params: {
            id: this.transferTransactionID,
            company: sessionStorage.getItem("Company"),
          },
        });
        console.log("Status:", isStatus.data);
        const updateBranch = await axiosClient.post("/updateBranchStatus", {
          userID: sessionStorage.getItem("UserID"),
          company: sessionStorage.getItem("Company"),
          status: status,
          id: this.transferTransactionID,
          email: sessionStorage.getItem("Email"),
          transactionData: this.transactionDetails,
          removedItems: this.tempRemoveItems,
          editStatus: true,
        });
        const letterDates = await axiosClient.post("/postDatesLetter", {
          id: this.transferTransactionID,
          authorizedPersonnel: this.transferTransactionPromoName,
          company: sessionStorage.getItem("Company"),
          dateStarted: this.dateStarted,
          dateEnded: this.dateEnded,
          status: status,
        });
        if (isStatus.data != "draft") {
          console.log("Approve");
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
              this.transferTransactionPromoName +
              "&" +
              holderplID +
              "=" +
              this.transferTransactionID +
              "&" +
              holderDateStart +
              "=" +
              this.dateStarted +
              "&" +
              holderDateEnd +
              "=" +
              this.dateEnded +
              "&" +
              holderEmail +
              "=" +
              this.transferTransactionPromoEmail +
              "&" +
              holderUserID +
              "=" +
              sessionStorage.getItem("UserID") +
              "&" +
              holderStatus +
              "=" +
              status +
              "&" +
              holderCompany +
              "=" +
              sessionStorage.getItem("Company") +
              "&" +
              holderRegenerate +
              "=generate",
            "_blank"
          );
          setTimeout(() => {
            const submiteditauthorizeddatemodal = new bootstrap.Modal(
              "#submiteditauthorizeddatemodal"
            );
            // submiteditauthorizeddatemodal.hide();
            this.$router.push({
              path: "/pull-out/requests",
            });
          }, 2000); // Reload after 3 seconds
        } else {
          this.transactionReceipt = {
            transferTransactionPromoName: this.transferTransactionPromoName,
            transferTransactionID: this.transferTransactionID,
            dateStarted: this.dateStarted,
            dateEnded: this.dateEnded,
            transferTransactionPromoEmail: this.transferTransactionPromoEmail,
            status: status,
          };

          console.log("Transaction Date Modal:", this.transactionReceipt);

          const transactionModal = new bootstrap.Modal("#transactionReceipt");
          transactionModal.show();
        }
      }
    },
    validateGenerate() {
      if (this.transferTransactionPromoName && this.dateStarted && this.dateEnded)
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
    changeDateStart() {
      this.DateStart = true;
      console.log("Change Date Start", this.dateStarted);
    },
    changeDateEnd() {
      this.DateEnd = true;
      console.log("Change Date End", this.dateEnded);
    },
  },
};
</script>

<style>
.form-group {
  text-align: left !important;
}
</style>
