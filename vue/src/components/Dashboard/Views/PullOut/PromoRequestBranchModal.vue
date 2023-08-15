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
            <div style="overflow-x: auto; min-width: 350px">
              <table class="table table-borderless">
                <thead>
                  <tr>
                    <th scope="col" class="labelRows">Company</th>
                    <th scope="col" class="labelRows">Chain Code</th>
                    <th scope="col" class="labelRows">Branch Name</th>
                    <th scope="col" class="labelRows">Start Date</th>
                    <th scope="col" class="labelRows">Expiry Date</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="details in promoDetails">
                    <td class="cell text-nowrap py-1">{{ details.company }}</td>
                    <td class="cell text-nowrap py-1">{{ details.chainCode }}</td>
                    <td class="cell text-nowrap py-1">
                      <el-tooltip
                        v-if="details.permanent"
                        effect="light"
                        content="Permanent Branch"
                        placement="top-start"
                      >
                        <span class="p-icon">P</span></el-tooltip
                      >
                      <el-tooltip
                        v-else-if="!details.permanent"
                        effect="light"
                        content="Temporary Branch"
                        placement="top-start"
                      >
                        <span class="t-icon">T</span>
                      </el-tooltip>
                      &nbsp;{{ details.branchName }}
                    </td>
                    <td class="cell text-nowrap py-1">{{ details.dateStart }}</td>
                    <td class="cell text-nowrap py-1">{{ details.dateEnd }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- <div class="d-flex">
              <span class="labelRows col-3">Company</span>
              <span class="labelRows col-3">Chain Code</span>
              <span class="labelRows col-6">Branch Name</span>
            </div>
            <div class="d-flex mt-1" v-for="details in promoDetails">
              <span class="col-3">{{ details.company }}</span>
              <span class="col-3">{{ details.chainCode }}</span>
              <span class="col-6">{{ details.branchName }}</span>
            </div> -->
            <div class="d-flex mt-2 text-center">
              <span class="col-12 mb-2" style="font-size: 13px"
                ><b>Promo's Request to Update the Temporary Branch</b>
              </span>
            </div>
            <div style="overflow-x: auto; min-width: 350px">
              <table class="table table-borderless">
                <thead>
                  <tr>
                    <th scope="col" class="labelRows">Company</th>
                    <th scope="col" class="labelRows">Chain Code</th>
                    <th scope="col" class="labelRows">Branch Name</th>
                    <th scope="col" class="labelRows text-center">Request</th>
                    <th scope="col" class="labelRows">Reject</th>
                    <th scope="col" class="labelRows">Start Date</th>
                    <th scope="col" class="labelRows">Expiry Date</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(request, key) in tempPromoRequest">
                    <td class="cell text-nowrap py-1">{{ request.company }}</td>
                    <td class="cell text-nowrap py-1">{{ request.chainCode }}</td>
                    <td class="cell text-nowrap py-1">{{ request.branchName }}</td>
                    <td class="cell text-nowrap py-1">
                      <el-tooltip
                        v-if="request.request == 'remove'"
                        content="Request to remove this branch."
                        placement="top"
                      >
                        <el-tag class="m-0" type="danger">For Removal</el-tag>
                      </el-tooltip>
                      <el-tooltip
                        v-else-if="request.request == 'additional'"
                        content="Request to add this branch."
                        placement="top"
                      >
                        <el-tag class="m-0" color="white" type="warning"
                          >New Branch</el-tag
                        >
                      </el-tooltip>
                    </td>
                    <td class="cell text-nowrap text-center py-1">
                      <button
                        class="btn btn-default py-1 px-2"
                        @click="rejectBranch(request)"
                      >
                        <i class="nc-icon nc-simple-remove"></i>
                      </button>
                    </td>
                    <td class="cell text-nowrap py-1">
                      <fg-input class="text-center mb-0" style="width: 150px"
                        ><el-date-picker
                          v-model="dateStart[key]"
                          type="date"
                          placeholder="Select a Day"
                        >
                        </el-date-picker
                      ></fg-input>
                    </td>
                    <td class="cell text-nowrap py-1">
                      <fg-input class="text-center mb-0" style="width: 150px"
                        ><el-date-picker
                          v-model="dateEnd[key]"
                          type="date"
                          placeholder="Select a Day"
                        >
                        </el-date-picker
                      ></fg-input>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- <div class="d-flex mt-1">
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
            </div> -->
            <!-- <div class="mt-3">
              <fg-input label="Date End Promo" class="col-12 text-center mb-0"
                ><el-date-picker
                  v-model="dateEnd"
                  type="date"
                  placeholder="Select a Day"
                  :disabled="rejectMode"
                >
                </el-date-picker
              ></fg-input>
            </div> -->
          </div>
          <div class="modal-footer d-flex justify-content-center">
            <button v-show="!rejectMode" class="btn btn-default" data-bs-dismiss="modal">
              Cancel
            </button>
            <!-- <button
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
            <button
              v-show="rejectMode"
              class="btn btn-danger"
              @click="rejectBranch"
              :disabled="rejected"
            >
              Reject Selected Branch
            </button> -->
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
    dateStart: "validateApprove",
    promoRequest: {
      handler() {
        this.promoDetailsTransfer();
      },
      deep: true,
    },
    checkedBranch: function () {
      if (this.checkedBranch.length == 0) this.rejected = true;
      else this.rejected = false;
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
      dateStart: [],
      dateEnd: [],
      disableApprove: true,
      checkedBranch: [],
      rejectMode: false,
      tempPromoRequest: [],
      rejected: true,
    };
  },
  mounted() {},
  methods: {
    approveRequest(confirm) {
      console.log("TP APP:", this.tempPromoRequest);
      if (confirm)
        for (let key in this.tempPromoRequest)
          axiosClient
            .post("/updateUserBranchByRequest", {
              id: this.tempPromoRequest[key].id,
              // userID: this.promoData.id,
              dateStart: this.dateStart[key],
              dateEnd: this.dateEnd[key],
            })
            .then((response) => {
              this.$emit("fetchUsers");
            })
            .catch((error) => {
              console.error(error);
            });
    },
    rejectBranch(reject) {
      // let selectedBranchReject = this.checkedBranch.map((i) => this.tempPromoRequest[i]);
      // this.tempPromoRequest = this.tempPromoRequest.filter(
      //   (_, index) => !this.checkedBranch.includes(index)
      // );
      // selectedBranchReject.forEach((reject) => {
      axiosClient
        .post("/deleteUserBranch", {
          userID: this.promoData.id,
          company: reject.company,
          chainCode: reject.chainCode,
          branchName: reject.branchName,
          req: reject.request,
        })
        .then((response) => {
          this.$emit("fetchPromoRequest", this.promoData.id);
        })
        .catch((error) => {
          console.error(error);
        });
      // });
      // if (this.tempPromoRequest.length <= 0) {
      //   window.location.reload();
      // }
      // this.rejectMode = false;
      // this.rejected = true;
      // this.checkedBranch = [];
      this.$emit("fetchUsers");
    },
    promoDetailsTransfer() {
      this.tempPromoRequest = [...this.promoRequest];
      console.log("Prom Req:::::::: ", this.promoRequest);
    },
    validateApprove() {
      if (
        this.dateEnd.length != this.tempPromoRequest.length &&
        this.dateStart.length != this.tempPromoRequest.length
      )
        this.disableApprove = true;
      else this.disableApprove = false;
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
