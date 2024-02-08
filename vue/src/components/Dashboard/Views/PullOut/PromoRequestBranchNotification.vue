<template>
  <div
    class="modal fade"
    id="requestBranchNotification"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="requestBranchNotificationLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">Your Request was Submitted</h6>
        </div>
        <div class="modal-body text-center">
          <p class="m-0">
            You can contact your authorized Officer to immediately approve your request.
          </p>
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button class="btn btn-primary" data-bs-dismiss="modal">Okay</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  components: {},
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
  methods: {},
};
</script>

<style lang="scss">
.tr_num {
  font-size: 35px;
  font-weight: 700;
}
</style>
