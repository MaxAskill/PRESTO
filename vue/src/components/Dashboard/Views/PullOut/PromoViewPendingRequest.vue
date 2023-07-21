<template>
  <div>
    <div
      class="modal fade"
      id="promoViewPendingRequestModal"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="promoViewPendingRequestModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header py-2">
            <span style="font-size: 16px"
              ><b>Pending Request to Update Assigned Branch</b></span
            >
          </div>
          <div class="modal-body row pt-2 pb-3 px-4">
            <span class="text-center mb-2" style="font-size: 13px">
              <b>Current Assigned Branch [ {{ userData.length }} ]</b>
            </span>
            <div class="d-flex">
              <span class="labelRows col-3">Company</span>
              <span class="labelRows col-3">Chain Code</span>
              <span class="labelRows col-6">Branch Name</span>
            </div>
            <div class="d-flex mt-1" v-for="user in userData">
              <span class="col-3">{{ user.company }}</span>
              <span class="col-3">{{ user.chainCode }}</span>
              <span class="col-6">{{ user.branchName }}</span>
            </div>
            <div class="d-flex mt-3">
              <span class="col-12 text-center" style="font-size: 13px"
                ><b>You wants to Update your Assigned Branch to: </b></span
              >
            </div>
            <div class="col-12 my-1 text-center">
              <span class="labelRows">Date Requested: &nbsp;&nbsp;</span>
              <span style="font-size: 13px"
                ><b>{{ dateRequested }}</b></span
              >
            </div>
            <div class="d-flex mt-1">
              <span class="labelRows col-3">Company</span>
              <span class="labelRows col-3">Chain Code</span>
              <span class="labelRows col-6">Branch Name</span>
            </div>
            <div class="d-flex mt-1" v-for="request in userRequest">
              <span class="col-3">{{ request.company }}</span>
              <span class="col-3">{{ request.chainCode }}</span>
              <span class="col-6">{{ request.branchName }}</span>
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-center">
            <button class="btn btn-default" data-bs-dismiss="modal">Close</button>
            <button
              class="btn btn-danger"
              data-bs-toggle="modal"
              data-bs-target="#promoCancelRequestConfirmation"
            >
              Cancel Request
            </button>
            <!-- <button
              class="btn btn-primary"
              data-bs-toggle="modal"
              data-bs-target="#requestBranchApproveConfirmation"
              @click="approveRequest()"
            >
              Approve this Request
            </button> -->
          </div>
        </div>
      </div>
    </div>
    <PromoCancelRequestConfirmationModal
      @confirm="cancelRequest($event)"
    ></PromoCancelRequestConfirmationModal>
  </div>
</template>
<script>
import axiosClient from "../../../../axios";
import PromoCancelRequestConfirmationModal from "./PromoCancelRequestConfirmationModal.vue";

export default {
  components: {
    PromoCancelRequestConfirmationModal,
  },
  watch: {},
  props: ["userData", "userRequest", "dateRequested"],
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
    cancelRequest(confirm) {
      if (confirm)
        axiosClient
          .post("/deleteUserBranch", {
            userID: sessionStorage.getItem("UserID"),
            userType: "Promo",
          })
          .then((response) => {
            this.$emit("refetchUserData");
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
.labelRows {
  font-size: 0.8571em;
  color: #9a9a9a;
  // margin-bottom: 5px;
}
</style>
