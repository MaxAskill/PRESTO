<template>
  <div>
    <div
      class="modal fade"
      id="promoDetailsModal"
      tabindex="-1"
      aria-labelledby="promoDetailsModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-xl">
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
            <div
              style="
                overflow-x: auto;
                min-width: 350px;
                overflow-y: auto;
                min-height: 150px;
              "
            >
              <table class="table table-borderless">
                <thead>
                  <tr>
                    <th scope="col" class="labelRows">Company</th>
                    <th scope="col" class="labelRows">Chain Name</th>
                    <th scope="col" class="labelRows">Branch Name</th>
                    <th scope="col" class="labelRows">Start Date</th>
                    <th scope="col" class="labelRows">Expiry Date</th>
                    <th scope="col" class="labelRows">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="details in promoDetails">
                    <td v-if="tempBranchEditID == details.id && details.permanent">
                      <el-select
                        style="width: 100px"
                        class="select-default"
                        size="small"
                        v-model="editCompany"
                        placeholder="Select Company"
                        @change="fetchChainCode(), (editChain = ''), (editBranch = '')"
                      >
                        <el-option
                          v-for="option in companyList"
                          class="select-default"
                          :value="option.shortName"
                          :label="option.shortName"
                          :key="option.id"
                        ></el-option>
                      </el-select>
                    </td>
                    <td
                      v-else
                      :class="
                        details.expired
                          ? details.expired == 'expired'
                            ? 'cell text-nowrap py-1 expiredCSS'
                            : 'cell text-nowrap py-1 removedCSS'
                          : 'cell text-nowrap py-1'
                      "
                    >
                      {{ details.company }}
                    </td>
                    <td v-if="tempBranchEditID == details.id && details.permanent">
                      <el-select
                        style="min-width: 100px"
                        class="select-default"
                        size="small"
                        v-model="editChain"
                        placeholder="Select Chain Name"
                        @change="fetchChainName(), (editBranch = '')"
                      >
                        <el-option
                          v-for="option in chainList"
                          class="select-default"
                          :value="option.chainCode"
                          :label="option.chainCode"
                          :key="option.chainCode"
                        >
                        </el-option>
                      </el-select>
                    </td>
                    <td
                      v-else
                      :class="
                        details.expired
                          ? details.expired == 'expired'
                            ? 'cell text-nowrap py-1 expiredCSS'
                            : 'cell text-nowrap py-1 removedCSS'
                          : 'cell text-nowrap py-1'
                      "
                    >
                      {{ details.chainCode }}
                    </td>
                    <td v-if="tempBranchEditID == details.id && details.permanent">
                      <el-select
                        class="select-default"
                        size="small"
                        v-model="editBranch"
                        placeholder="Select Branch Name"
                      >
                        <el-option
                          v-for="option in branchList"
                          class="select-default"
                          :value="option.branchName"
                          :label="option.branchName"
                          :key="option.branchName"
                        >
                        </el-option>
                      </el-select>
                    </td>
                    <td
                      v-else
                      :class="
                        details.expired
                          ? details.expired == 'expired'
                            ? 'cell text-nowrap py-1 expiredCSS'
                            : 'cell text-nowrap py-1 removedCSS'
                          : 'cell text-nowrap py-1'
                      "
                    >
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
                    <td
                      v-if="tempBranchEditID == details.id && !details.permanent"
                      style="width: 150px"
                    >
                      <el-date-picker
                        v-model="editStartDate"
                        style="width: 90%"
                        type="date"
                        placeholder="Pick a date"
                        :clearable="false"
                      >
                      </el-date-picker>
                    </td>
                    <td
                      v-else
                      :class="
                        details.expired
                          ? details.expired == 'expired'
                            ? 'cell text-nowrap py-1 expiredCSS'
                            : 'cell text-nowrap py-1 removedCSS'
                          : 'cell text-nowrap py-1'
                      "
                    >
                      {{ details.dateStart }}
                    </td>
                    <td
                      v-if="tempBranchEditID == details.id && !details.permanent"
                      style="width: 150px"
                    >
                      <el-date-picker
                        v-model="editEndDate"
                        style="width: 90%"
                        type="date"
                        placeholder="Pick a date"
                        :clearable="false"
                      >
                      </el-date-picker>
                    </td>
                    <td
                      v-else
                      :class="
                        details.expired
                          ? details.expired == 'expired'
                            ? 'cell text-nowrap py-1 expiredCSS'
                            : 'cell text-nowrap py-1 removedCSS'
                          : 'cell text-nowrap py-1'
                      "
                    >
                      {{ details.dateEnd }}
                    </td>
                    <td
                      v-if="!details.expired && tempBranchEditID != details.id"
                      class="cell text-nowrap py-1"
                    >
                      <p-button
                        type="success"
                        size="sm"
                        icon
                        @click="
                          editTempBranch(
                            details.id,
                            details.dateStart,
                            details.dateEnd,
                            details.permanent,
                            details.company,
                            details.chainCode,
                            details.branchName
                          )
                        "
                        :disabled="tempBranchEditID != null"
                      >
                        <i class="fa fa-edit"></i>
                      </p-button>
                      <el-popconfirm
                        v-if="!details.permanent"
                        width="280"
                        confirm-button-text="Confirm"
                        cancel-button-text="Cancel"
                        icon-color="#c45656"
                        title="Are you sure you want to remove this temporary branch?"
                        @confirm="handleRemove(details.id)"
                      >
                        <p-button
                          class="ml-1"
                          slot="reference"
                          type="danger"
                          size="sm"
                          icon
                          :disabled="tempBranchEditID != null"
                        >
                          <i class="fa fa-trash"></i>
                        </p-button>
                      </el-popconfirm>
                    </td>
                    <td
                      v-if="!details.expired && tempBranchEditID == details.id"
                      class="cell text-nowrap py-1"
                    >
                      <el-tooltip content="Save Changes" placement="bottom">
                        <el-popconfirm
                          width="280"
                          confirm-button-text="Confirm"
                          cancel-button-text="Cancel"
                          icon-color="#fbc658"
                          title="Are you sure you want to save changes for this temporary branch?"
                          @confirm="
                            details.permanent
                              ? handleSavePermBranch(details.id)
                              : handleSaveTempBranch(
                                  details.id,
                                  details.dateStart,
                                  details.dateEnd
                                )
                          "
                        >
                          <p-button slot="reference" type="warning" size="sm" icon>
                            <i class="fa fa-check"></i>
                          </p-button>
                        </el-popconfirm>
                      </el-tooltip>
                      <el-tooltip content="Discard Changes" placement="bottom">
                        <p-button
                          type="default"
                          size="sm"
                          class="ml-1"
                          icon
                          @click="tempBranchEditID = null"
                        >
                          <i class="fa fa-remove"></i>
                        </p-button>
                      </el-tooltip>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-center">
            <button
              class="btn btn-secondary"
              data-bs-dismiss="modal"
              @click="tempBranchEditID = null"
            >
              Close
            </button>
            <button
              class="btn btn-success"
              data-bs-toggle="modal"
              data-bs-target="#promoAssignBranchByAgentModal"
            >
              Add Temporary Branch
            </button>
          </div>
        </div>
      </div>
    </div>
    <PromoAssignBranchByAgentModal
      :promoData="promoData"
      :promoDetails="promoDetails"
    ></PromoAssignBranchByAgentModal>
  </div>
</template>
<script>
import Vue from "vue";
import axiosClient from "../../../../axios";
import { Tooltip, Popconfirm, Message } from "element-ui";
import PromoAssignBranchByAgentModal from "./PromoAssignBranchByAgentModal.vue";

Vue.use(Tooltip);
Vue.use(Popconfirm);

export default {
  components: { PromoAssignBranchByAgentModal },
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
    return {
      tempBranchEditID: null,
      editStartDate: "",
      editEndDate: "",
      editCompany: "",
      editChain: "",
      editBranch: "",
      companyList: [],
      chainList: [],
      branchList: [],
    };
  },
  mounted() {},
  methods: {
    fetchCompanyByRA() {
      axiosClient
        .get("/fetchCompanyByRA", {
          params: {
            company: sessionStorage.getItem("Company"),
          },
        })
        .then((response) => {
          this.companyList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    fetchChainCode() {
      axiosClient
        .get("/fetchChain", {
          params: {
            company: this.editCompany,
          },
        })
        .then((response) => {
          this.chainList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    fetchChainName() {
      axiosClient
        .get("/fetchChainName", {
          params: {
            chainCode: this.editChain,
            company: this.editCompany,
          },
        })
        .then((response) => {
          this.branchList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    editTempBranch(id, dateStart, dateEnd, permanent, company, chain, branch) {
      this.tempBranchEditID = id;
      if (!permanent) {
        this.editStartDate = this.convertDateFormat(dateStart);
        this.editEndDate = this.convertDateFormat(dateEnd);
      } else {
        this.editCompany = company;
        this.editChain = chain;
        this.editBranch = branch;
        this.fetchCompanyByRA();
        this.fetchChainCode();
        this.fetchChainName();
      }
    },
    convertDateFormat(date) {
      var inputDate = new Date(date);
      var year = inputDate.getFullYear();
      var month = (inputDate.getMonth() + 1).toString().padStart(2, "0"); // Months are zero-based
      var day = inputDate.getDate().toString().padStart(2, "0");

      return year + "-" + month + "-" + day;
    },
    handleRemove(id) {
      axiosClient
        .post("/removeTempBranch", {
          id: id,
        })
        .then((response) => {
          this.$emit("fetchPromoDetails", this.promoData.id);
          this.$emit("fetchData");
        })
        .catch((error) => {
          console.error(error);
        });

      Message({
        type: "success",
        message: "Temporary Branch has been removed.",
      });
    },
    handleSavePermBranch(id) {
      if (this.editBranch) {
        axiosClient
          .post("/updatePermBranch", {
            id: id,
            company: this.editCompany,
            chainCode: this.editChain,
            branchName: this.editBranch,
          })
          .then((response) => {
            this.$emit("fetchPromoDetails", this.promoData.id);
          })
          .catch((error) => {
            console.error(error);
          });

        Message({
          type: "success",
          message: "Temporary Branch has been updated.",
        });

        this.tempBranchEditID = null;
      } else
        Message({
          type: "error",
          message: "Kindly select a Branch to save changes.",
        });
    },
    handleSaveTempBranch(id, startDate, endDate) {
      if (
        this.convertDateFormat(this.editStartDate) != this.convertDateFormat(startDate) ||
        this.convertDateFormat(this.editEndDate) != this.convertDateFormat(endDate)
      ) {
        axiosClient
          .post("/updateTempBranch", {
            id: id,
            startDate: this.convertDateFormat(this.editStartDate),
            endDate: this.convertDateFormat(this.editEndDate),
          })
          .then((response) => {
            this.$emit("fetchPromoDetails", this.promoData.id);
          })
          .catch((error) => {
            console.error(error);
          });

        Message({
          type: "success",
          message: "Temporary Branch has been updated.",
        });
      }
      this.tempBranchEditID = null;
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

.expiredCSS {
  text-decoration: line-through;
  text-decoration-color: rgba(255, 0, 0, 0.7);
}
.expiredCSS::before {
  content: "This temporary branch has expired.";
  position: absolute;
  top: 0px; /* Adjust this value to position the tooltip as desired */
  left: 50%;
  transform: translateX(-50%);
  background: red;
  color: white;
  padding: 5px;
  border-radius: 5px;
  opacity: 0;
  transition: opacity 0.2s;
  z-index: 1;
}

.expiredCSS:hover::before {
  opacity: 1;
}

.removedCSS {
  text-decoration: line-through;
  text-decoration-color: rgba(255, 0, 0, 0.7);
}
.removedCSS::before {
  content: "This temporary branch has been removed.";
  position: absolute;
  top: 0px; /* Adjust this value to position the tooltip as desired */
  left: 50%;
  transform: translateX(-50%);
  background: red;
  color: white;
  padding: 5px;
  border-radius: 5px;
  opacity: 0;
  transition: opacity 0.2s;
  z-index: 1;
}

.removedCSS:hover::before {
  opacity: 1;
}
</style>
