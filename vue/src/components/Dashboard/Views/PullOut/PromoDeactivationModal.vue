<template>
  <div
    class="modal fade"
    id="promoDeactivationModal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="promoDeactivationModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">Deactivation of Promodiser's Account</h6>
        </div>
        <div class="modal-body text-center">
          <p>Are you sure you want to deactivate <b>{{ promoData.name }}</b>'s account?</p>
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >
            Cancel
          </button>
          <button class="btn btn-primary" data-bs-dismiss="modal" @click="deactivate()">
            Proceed
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axiosClient from '../../../../axios';


export default {
  components: {},
  watch: {},
  props: ["promoData"],
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
    deactivate() {
      axiosClient.post("/updateUserBranch", {
        userID: this.promoData.id,
        status: "Deactivated"
      })
      .then((response) => {
        this.$emit("fetchUsers");
      })
      .catch((error) => {
        console.error(error);
      });
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
