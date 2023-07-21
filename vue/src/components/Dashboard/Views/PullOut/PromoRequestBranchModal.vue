<template>
  <div>
    <div
      class="modal fade"
      id="promoRequestBranchModal"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="promoRequestBranchModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header py-3">
            <span style="font-size: 16px"
              >Promo Name:
              <b style="font-size: 16px; text-transform: uppercase">{{
                promoData.name
              }}</b></span
            ><br />
            <span
              >Email: <b>{{ promoData.email }}</b></span
            >
          </div>
          <div class="modal-body row pt-2 pb-3 px-4">
            <span class="text-center mb-2" style="font-size: 13px">
              <b>{{ promoDetails.length }} Assigned Branch:</b>
            </span>
            <div class="d-flex">
              <span class="labelRows col-3">Company</span>
              <span class="labelRows col-3">Chain Code</span>
              <span class="labelRows col-6">Branch Name</span>
            </div>
            <div class="d-flex mt-1" v-for="details in promoDetails">
              <span class="col-3">{{ details.company }}</span>
              <span class="col-3">{{ details.chainCode }}</span>
              <span class="col-6">{{ details.branchName }}</span>
            </div>
            <div class="d-flex mt-3 text-center">
              <span class="col-12 mb-2" style="font-size: 13px"
                ><b>This Promo wants to Update the Assigned Branch to: </b></span
              >
            </div>
            <div class="d-flex mt-1">
              <span class="labelRows col-3">Company</span>
              <span class="labelRows col-3">Chain Code</span>
              <span class="labelRows col-6">Branch Name</span>
            </div>
            <div class="d-flex mt-1" v-for="(request, key) in tempPromoRequest">
              <span class="col-3">{{ request.company }}</span>
              <span class="col-3">{{ request.chainCode }}</span>
              <span class="col-6">{{ request.branchName }}</span>
              <input
                v-show="rejectMode"
                class="align-self-start"
                type="checkbox"
                v-model="checkedBranch"
                :value="key"
              />
            </div>
            <div class="mt-3">
              <fg-input label="Date End Promo" class="col-12 text-center mb-0"
                ><el-date-picker
                  v-model="dateEnd"
                  type="date"
                  placeholder="Select a Day"
                  :disabled="rejectMode"
                >
                </el-date-picker
              ></fg-input>
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-center">
            <button v-show="!rejectMode" class="btn btn-default" data-bs-dismiss="modal">
              Cancel
            </button>
            <button
              v-show="rejectMode"
              class="btn btn-default"
              @click="(rejectMode = false), (checkedBranch = [])"
            >
              Back
            </button>
            <button
              v-show="!rejectMode"
              class="btn btn-danger"
              @click="rejectMode = true"
            >
              Reject
            </button>
            <button v-show="rejectMode" class="btn btn-danger" @click="rejectBranch">
              Reject Selected Branch
            </button>
            <button
              v-show="!rejectMode"
              class="btn btn-primary"
              data-bs-toggle="modal"
              data-bs-target="#requestBranchApproveConfirmation"
              @click="approveRequest()"
              :disabled="disableApprove"
            >
              Approve this Request
            </button>
          </div>
        </div>
      </div>
    </div>
    <PromoRequestBranchApproveConfirmationModal
      :promoData="promoData"
      @confirm="approveRequest($event)"
    ></PromoRequestBranchApproveConfirmationModal>
  </div>
</template>
<script>
import axiosClient from "../../../../axios";
import PromoRequestBranchApproveConfirmationModal from "./PromoRequestBranchApproveConfirmationModal.vue";

export default {
  components: {
    PromoRequestBranchApproveConfirmationModal,
  },
  watch: {
    dateEnd: "validateApprove",
    promoRequest: {
      handler() {
        this.promoDetailsTransfer();
      },
      deep: true,
    },
  },
  props: ["promoData", "promoDetails", "promoRequest"],
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
      dateEnd: null,
      disableApprove: true,
      checkedBranch: [],
      rejectMode: false,
      tempPromoRequest: [],
    };
  },
  mounted() {
    console.log("DSAFSAF");
  },
  methods: {
    approveRequest(confirm) {
      if (confirm)
        axiosClient
          .post("/updateUserBranchByRequest", {
            userID: this.promoData.id,
            dateEnd: this.dateEnd,
          })
          .then((response) => {
            this.$emit("fetchUsers");
          })
          .catch((error) => {
            console.error(error);
          });
    },
    rejectBranch() {
      let selectedBranchReject = this.checkedBranch.map((i) => this.tempPromoRequest[i]);
      this.tempPromoRequest = this.tempPromoRequest.filter(
        (_, index) => !this.checkedBranch.includes(index)
      );
      selectedBranchReject.forEach((reject) => {
        axiosClient
          .post("/deleteUserBranch", {
            userID: this.promoData.id,
            userType: "Agent",
            company: reject.company,
            chainCode: reject.chainCode,
            branchName: reject.branchName,
          })
          .then((response) => {})
          .catch((error) => {
            console.error(error);
          });
      });
      if (this.tempPromoRequest.length <= 0) {
        window.location.reload();
      }
      this.rejectMode = false;
      this.checkedBranch = [];
      this.$emit("fetchUsers");
    },
    promoDetailsTransfer() {
      this.tempPromoRequest = [...this.promoRequest];
    },
    validateApprove() {
      console.log(this.dateEnd);
      if (this.dateEnd) this.disableApprove = false;
      else this.disableApprove = true;
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
