<template>
  <div>
    <div
      class="modal fade"
      id="confirmationDeleteBoxLabel"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="confirmationDeleteBoxLabelLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title">
              You are Deleting the Box No. {{ boxInfo.boxNumber }} of
              {{ boxInfo.boxLength }} ( {{ boxInfo.boxLabel }} )
            </h6>
          </div>
          <div class="modal-body text-center">
            <p>Are you sure you want to delete this box? This cannot be undo.</p>
          </div>
          <div class="modal-footer d-flex justify-content-center">
            <button
              class="btn btn-secondary"
              data-bs-toggle="modal"
              data-bs-target="#editboxlabelModal"
              @click="confirm(false)"
            >
              Cancel
            </button>
            <button
              class="btn btn-danger"
              data-bs-toggle="modal"
              data-bs-target="#editboxlabelModal"
              @click="confirm(true)"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>
    <EditBoxLabelModal></EditBoxLabelModal>
  </div>
</template>
<script>
import EditBoxLabelModal from "./EditBoxLabelModal.vue";

export default {
  components: {
    EditBoxLabelModal,
  },
  props: ["BoxLabelInfo"],
  watch: {
    BoxLabelInfo: {
      handler(val) {
        this.transferInfo();
      },
      deep: true,
    },
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
      boxInfo: {
        boxNumber: 0,
        boxLength: 0,
        boxLabel: "",
      },
    };
  },
  mounted() {},
  methods: {
    confirm(x) {
      this.$emit("confirm", x);
    },
    transferInfo() {
      this.boxInfo.boxNumber = this.BoxLabelInfo.boxNumber;
      this.boxInfo.boxLength = this.BoxLabelInfo.boxLength;
      this.boxInfo.boxLabel = this.BoxLabelInfo.boxLabel;
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
