<template>
  <div
    class="modal fade"
    id="requestBranchConfirmation"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="requestBranchConfirmationLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">Request for Changing of Branch</h6>
        </div>
        <div class="modal-body px-sm-5 py-sm-3 px-3 py-2 text-center">
          <span class="spanLabel" v-if="remove.length != 0">
            You want to <b>remove</b> this as your Temporary Branch:
          </span>
          <div class="divBranch" v-for="req in remove" v-if="remove.length != 0">
            <span>{{ req.branch }}</span>
          </div>
          <div class="mt-3">
            <span class="spanLabel" v-if="additional.length != 0"
              >You want to <b>add</b> this as your Temporary Branch:
            </span>
          </div>
          <div class="divBranch" v-for="req in additional" v-if="additional.length != 0">
            <span>{{ req.branch }}</span>
          </div>
          <p class="m-0 mb-2 mt-4">
            Do you want to submit this request to change your assigned branch?
          </p>
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button
            class="btn btn-secondary"
            data-bs-dismiss="modal"
            @click="confirm(false)"
          >
            Cancel
          </button>
          <button class="btn btn-primary" data-bs-dismiss="modal" @click="confirm(true)">
            Confirm
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["remove", "additional"],
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
.spanLabel {
  font-size: 0.8571em;
  color: #9a9a9a;
}
.divBranch {
  font-size: 13px;
  font-weight: 600;
}
</style>
