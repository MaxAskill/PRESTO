<template>
  <div
    class="modal fade"
    id="transactionReceipt"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="transactionReceiptLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"></div>
        <div class="modal-body text-center" v-if="isSubmit">
          <p>
            Your transaction has been processed successfully.<br /><br />Transaction
            Number<br /><span class="tr_num">{{ transferTransactionID }}</span
            ><br /><br />Please take a photo or screenshot the transaction number before
            closing this window.
          </p>
        </div>
        <div class="modal-body text-center" v-else>
          <p>
            The transaction has been reviewed successfully.<br /><br />Transaction
            Number<br /><span class="tr_num">{{ transferTransactionID }}</span
            ><br /><br />The transaction will now proceed for approval.
          </p>
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button
            class="btn btn-secondary"
            data-bs-dismiss="modal"
            @click="reloadPage()"
            :disabled="counter"
          >
            <span
              class="spinner-border spinner-border-sm"
              role="status"
              aria-hidden="true"
              v-if="counter"
            ></span>
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axiosClient from "../../../../../../axios";

export default {
  props: [
    "transferTransactionID",
    "savingCounter",
    "transferTransactionPromoName",
    "dateStarted",
    "dateEnded",
    "transferTransactionPromoEmail",
    "status",
    "transactionReceipt",
  ],
  components: {},
  watch: {
    savingCounter: "counterSaving",
  },
  transactionReceipt: {
    handler(val) {
      this.transferTransactionReceipt();
    },
    deep: true,
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
      counter: true,
      isSubmit: true,
      transactionData: [],
    };
  },
  mounted() {
    this.isEditParagraph();
  },
  methods: {
    transferTransactionReceipt() {
      this.transactionData = this.transactionReceipt;

      console.log("Transaction Data:", this.transactionData);
    },
    isEditParagraph() {
      if (
        sessionStorage.getItem("Position") == "Reviewer" ||
        sessionStorage.getItem("Position") == "Approver"
      ) {
        this.isSubmit = false;
      }
    },
    async reloadPage() {
      // const submitdatemodal = new bootstrap.Modal("#submiteditauthorizeddatemodal");
      // submitdatemodal.show();

      var holderName = this.convertToAlphanumeric("name");
      var holderplID = this.convertToAlphanumeric("plID");
      var holderDateStart = this.convertToAlphanumeric("dateStart");
      var holderDateEnd = this.convertToAlphanumeric("dateEnd");
      var holderEmail = this.convertToAlphanumeric("email");
      var holderUserID = this.convertToAlphanumeric("userID");
      var holderStatus = this.convertToAlphanumeric("status");
      var holderCompany = this.convertToAlphanumeric("company");
      var holderRegenerate = this.convertToAlphanumeric("regenerate");

      console.log("Transaction ID:", this.transferTransactionID);
      console.log("Transaction Promo Name:", this.transferTransactionPromoName);
      console.log("Transaction Company:", sessionStorage.getItem("Company"));

      const letterData = await axiosClient.get("/fetchGenerateLetter", {
        params: {
          company: sessionStorage.getItem("Company"),
          id: this.transferTransactionID,
        },
      });

      console.log("Letter Data:", letterData.data);
      console.log("Promo Name:", letterData.data[0].authorizedPersonnel);
      console.log("Date Start:", letterData.data[0].dateStart);
      console.log("Date End:", letterData.data[0].dateEnd);
      console.log("Promo Email:", letterData.data[0].promoEmail);
      console.log("Status:", letterData.data[0].status);
      setTimeout(() => {
        window.open(
          "http://192.168.0.7:90/api/generatePDF?" +
            holderName +
            "=" +
            letterData.data[0].authorizedPersonnel +
            "&" +
            holderplID +
            "=" +
            this.transferTransactionID +
            "&" +
            holderDateStart +
            "=" +
            letterData.data[0].dateStart +
            "&" +
            holderDateEnd +
            "=" +
            letterData.data[0].dateEnd +
            "&" +
            holderEmail +
            "=" +
            letterData.data[0].promoEmail +
            "&" +
            holderUserID +
            "=" +
            sessionStorage.getItem("UserID") +
            "&" +
            holderStatus +
            "=" +
            letterData.data[0].status +
            "&" +
            holderCompany +
            "=" +
            sessionStorage.getItem("Company") +
            "&" +
            holderRegenerate +
            "=generate",
          "_blank"
        );
      }, 500);

      if (
        sessionStorage.getItem("Position") == "Reviewer" ||
        sessionStorage.getItem("Position") == "Approver"
      )
        this.$router.push({
          path: "/pull-out/requests",
        });
      else
        this.$router.push({
          path: "/pull-out/pullout-transaction",
        });
    },
    counterSaving() {
      setTimeout(() => {
        this.counter = false;
      }, this.savingCounter * 1000);
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

<style lang="scss">
.tr_num {
  font-size: 35px;
  font-weight: 700;
}
</style>
