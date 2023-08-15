<template>
  <div
    class="modal fade"
    id="promoDetailsModal"
    tabindex="-1"
    aria-labelledby="promoDetailsModalLabel"
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
          <!-- <div class="d-flex mt-2 text-center">
            <span class="labelRows col-12">Date Branch Expiration</span>
          </div>
          <div class="d-flex mt-1 text-center">
            <span class="col-12">{{ dateBranchEnd }}</span>
          </div> -->

          <!-- <span class="text-center mb-3" style="font-size: 13px">
            <b>{{ promoDetails.length }} Assigned Branch:</b>
          </span>
          <div class="d-flex">
            <span class="labelRows col-3 text-nowrap">Company</span>
            <span class="labelRows col-3">Chain Code</span>
            <span class="labelRows col-6">Branch Name</span>
          </div>
          <div class="d-flex mt-1" v-for="details in promoDetails">
            <span class="col-3">{{ details.company }}</span>
            <span class="col-3">{{ details.chainCode }}</span>
            <span class="col-6">{{ details.branchName }}</span>
          </div>
          <div class="d-flex mt-3 text-center">
            <span class="labelRows col-12">Date Branch Expiration</span>
          </div>
          <div class="d-flex mt-1 text-center">
            <span class="col-12">{{ dateBranchEnd }}</span>
          </div> -->
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <!-- <button class="btn btn-primary" data-bs-dismiss="modal" @click="deactivate()">
            Proceed
          </button> -->
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Vue from "vue";
import axiosClient from "../../../../axios";
import { Tooltip } from "element-ui";

Vue.use(Tooltip);

export default {
  components: {},
  watch: {},
  props: ["promoData", "promoDetails", "dateBranchEnd"],
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
      axiosClient
        .post("/updateUserBranch", {
          userID: this.promoData.id,
          status: "Deactivated",
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
.labelRows {
  font-size: 0.8571em !important;
  color: #9a9a9a !important;
  // margin-bottom: 5px;
  white-space: nowrap !important;
  padding: 7px !important;
}
.p-icon {
  background-color: #6bd098;
  padding: 0px 3px;
  border-radius: 3px;
  color: white;
  font-size: 12px;
  font-weight: 700;
}
.t-icon {
  background-color: #31d2f2;
  padding: 0px 4px;
  border-radius: 3px;
  color: white;
  font-size: 12px;
  font-weight: 700;
}
</style>
