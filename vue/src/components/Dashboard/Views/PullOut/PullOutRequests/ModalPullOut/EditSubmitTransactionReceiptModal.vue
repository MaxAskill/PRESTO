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
        <div class="modal-body text-center">
          <p>
            The transaction has been reviewed successfully.<br /><br />Transaction
            Number<br /><span class="tr_num">{{ transferTransactionID }}</span
            ><br /><br />The transaction will now proceed for approval.
          </p>
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <!-- <div class="spinner-border text-dark" role="status" v-if="counter">
            <span class="sr-only">Loading...</span>
          </div> -->
          <!-- <div v-else> -->
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
          <!-- </div> -->
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Vue from "vue";
import axiosClient from "../../../../../../axios";
import axios from "axios";

export default {
  props: ["transferTransactionID", "savingCounter"],
  components: {},
  watch: {
    savingCounter: "counterSaving",
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
    };
  },
  mounted() {},
  methods: {
    reloadPage() {
      // window.location.href = "http://192.168.0.7:4040/#/pull-out/pullout-transaction";

      if (
        sessionStorage.getItem("Position") == "Agent" ||
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
      // foreach(v);
      console.log("Counter: ", this.counter);
      setTimeout(() => {
        this.counter = false;
      }, this.savingCounter * 1000);
      console.log("Counter after: ", this.counter);
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
