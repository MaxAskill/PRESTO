<template>
  <div
    class="modal fade"
    id="approvedPending"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="approvedPendingLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="approvedPendingLabel">
            {{ transferredData.branchName }}
          </h1>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12 pull-left">
              <fg-input
                label="Assigned Personnel for Pull-Out/Promodiser/Accredited Service Representative"
                v-model="authorizedPersonnel"
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
          <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <el-button
            :disabled="isDisabled"
            type="button"
            class="btn btn-success"
            data-bs-dismiss="modal"
            @click="generateLetter"
          >
            Generate Letter
          </el-button>
        </div>
      </div>
    </div>
    <ApprovalModal></ApprovalModal>
  </div>
</template>
<script>
import Vue from "vue";
import { DatePicker, Table, TableColumn, Select, Option } from "element-ui";
import axiosClient from "../../../../../../../axios";
import ApprovalModal from "../ApprovalModal.vue";
import { Loading } from "element-ui";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
Vue.use(DatePicker);
export default {
  props: ["transferredData", "itemData", "dateData"],
  components: {
    ApprovalModal,
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
    async generateLetter() {
      const status =
        sessionStorage.getItem("Position") == "Reviewer" ? "endorsement" : "approved";
      console.log("Personnel:", this.authorizedPersonnel);
      var dates = await axiosClient.post("/postDatesLetter", {
        id: this.transferredData.plID,
        authorizedPersonnel: this.authorizedPersonnel,
        company: sessionStorage.getItem("Company"),
        dateStarted: this.dateStarted,
        dateEnded: this.dateEnded,
        status: status,
      });

      console.log("Dates", dates);

      await axiosClient
        .post("/updateBranchStatus", {
          company: sessionStorage.getItem("Company"),
          id: this.transferredData.plID,
          status: "approved",
          userID: sessionStorage.getItem("UserID"),
          editStatus: false,
        })
        .then((response) => {})
        .catch((error) => {
          console.error(error);
        });

      var holderName = this.convertToAlphanumeric("name");
      var holderplID = this.convertToAlphanumeric("plID");
      var holderDateStart = this.convertToAlphanumeric("dateStart");
      var holderDateEnd = this.convertToAlphanumeric("dateEnd");
      var holderEmail = this.convertToAlphanumeric("email");
      var holderUserID = this.convertToAlphanumeric("userID");
      var holderStatus = this.convertToAlphanumeric("status");
      var holderCompany = this.convertToAlphanumeric("company");
      var holderRegenerate = this.convertToAlphanumeric("regenerate");

      await window.open(
        "http://192.168.0.7:90/api/generatePDF?" +
          holderName +
          "=" +
          this.authorizedPersonnel +
          "&" +
          holderplID +
          "=" +
          this.transferredData.plID +
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
          this.transferredData.promoEmail +
          "&" +
          holderUserID +
          "=" +
          sessionStorage.getItem("UserID") +
          "&" +
          holderStatus +
          "=approved" +
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
        this.$router.push({
          path: "/pull-out/requests",
        });
      }, 3000);
      // setTimeout(() => {
      //   this.$router.push({
      //     path: "/pull-out/requests",
      //   });
      // }, 3000); // Reload after 3 seconds
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
