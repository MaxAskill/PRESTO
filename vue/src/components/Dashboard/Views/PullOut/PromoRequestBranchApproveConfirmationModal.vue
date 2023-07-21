<template>
  <div>
    <div
      class="modal fade"
      id="requestBranchApproveConfirmation"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="requestBranchApproveConfirmationLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title">Request of Updating of Branch of Promo</h6>
          </div>
          <div class="modal-body text-center">
            <p>Approve the request of this promo: {{ promoData.name }}?</p>
          </div>
          <div class="modal-footer d-flex justify-content-center">
            <button
              class="btn btn-secondary"
              data-bs-toggle="modal"
              data-bs-target="#promoRequestBranchModal"
              @click="confirm(false)"
            >
              Cancel
            </button>
            <button
              class="btn btn-primary"
              data-bs-dismiss="modal"
              @click="confirm(true)"
            >
              Confirm
            </button>
          </div>
        </div>
      </div>
    </div>
    <PromoRequestBranchModal></PromoRequestBranchModal>
  </div>
</template>
<script>
import Vue from "vue";
import axiosClient from "../../../../axios";
import PromoRequestBranchModal from "./PromoRequestBranchModal.vue";

export default {
  props: ["promoData"],
  components: {
    PromoRequestBranchModal,
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
    return {};
  },
  mounted() {},
  methods: {
    confirm(x) {
      this.$emit("confirm", x);
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
