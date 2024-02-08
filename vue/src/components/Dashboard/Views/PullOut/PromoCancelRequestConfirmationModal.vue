<template>
  <div>
    <div
      class="modal fade"
      id="promoCancelRequestConfirmation"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="promoCancelRequestConfirmationLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body text-center">
            <p class="m-0">
              Are you sure you want to cancel your request for updating assigned branch?
            </p>
          </div>
          <div class="modal-footer d-flex justify-content-center">
            <button
              class="btn btn-secondary"
              data-bs-toggle="modal"
              data-bs-target="#promoViewPendingRequestModal"
              @click="confirm(false)"
            >
              Back
            </button>
            <button
              class="btn btn-primary"
              data-bs-dismiss="modal"
              @click="confirm(true)"
            >
              Yes, Cancel My Request
            </button>
          </div>
        </div>
      </div>
    </div>
    <PromoViewPendingRequestModal></PromoViewPendingRequestModal>
  </div>
</template>
<script>
import PromoViewPendingRequestModal from "./PromoViewPendingRequest.vue";

export default {
  components: {
    PromoViewPendingRequestModal,
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
