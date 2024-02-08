<template>
  <div
    class="modal fade"
    id="promoAssignBranchByAgentModal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="promoAssignBranchByAgentModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">
            Assigning of Temporary Branch for promo: <b>{{ promoData.name }}</b>
          </h6>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-6">
              <fg-input label="Company">
                <el-select
                  class="select-default"
                  size="large"
                  placeholder="Select Company"
                  v-model="form.Company"
                  @change="fetchChainCode"
                >
                  <el-option
                    v-for="option in companyList"
                    class="select-default"
                    :value="option.shortName"
                    :label="option.name + ' (' + option.shortName + ') '"
                    :key="option.id"
                  >
                  </el-option>
                </el-select>
              </fg-input>
            </div>
            <div class="col-sm-6">
              <fg-input label="Chain Name">
                <el-select
                  class="select-default"
                  size="large"
                  placeholder="Select Chain Name"
                  v-model="form.ChainCode"
                  @change="fetchChainName"
                >
                  <el-option
                    v-for="option in chainCodeList"
                    class="select-default"
                    :value="option.chainCode"
                    :label="option.chainCode"
                    :key="option.chainCode"
                  >
                  </el-option>
                </el-select>
              </fg-input>
            </div>
            <div class="col-sm-6">
              <fg-input label="Branch Name">
                <el-select
                  class="select-default"
                  size="large"
                  placeholder="Select Branch Name"
                  v-model="form.BranchName"
                >
                  <el-option
                    v-for="option in branchNameList"
                    class="select-default"
                    :value="option.branchName"
                    :label="option.branchName"
                    :key="option.branchName"
                  >
                  </el-option>
                </el-select>
              </fg-input>
            </div>
            <div class="col-sm-6 row">
              <div class="col-sm-6">
                <label class="date-label">Start Date</label>
                <div class="form-group">
                  <el-date-picker
                    v-model="form.StartDate"
                    type="date"
                    placeholder="Pick a date"
                  >
                  </el-date-picker>
                </div>
              </div>
              <div class="col-sm-6">
                <label class="date-label">End Date</label>
                <div class="form-group">
                  <el-date-picker
                    v-model="form.EndDate"
                    type="date"
                    placeholder="Pick a date"
                  >
                  </el-date-picker>
                </div>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-center">
            <button
              class="btn btn-success"
              @click="addBranchInput"
              :disabled="disableAddBranch"
            >
              Add This Branch
            </button>
          </div>
          <div class="d-flex justify-content-center">
            <div style="overflow-x: auto; min-width: 350px">
              <el-table
                class="table-striped p-0"
                :data="assignBranch"
                border
                max-height="350"
                style="width: 100%"
              >
                <el-table-column prop="branch" label="Branch" min-width="300" />
                <el-table-column prop="date_start" label="Start Date" width="150" />
                <el-table-column prop="date_end" label="End Date" width="150" />
                <el-table-column label="Actions" width="100">
                  <template slot-scope="props">
                    <p-button
                      v-if="props.$index != editIndex"
                      type="warning"
                      size="sm"
                      icon
                      @click="editBranchInput(props.$index, props.row)"
                    >
                      <i class="fa fa-edit"></i>
                    </p-button>
                    <p-button
                      v-if="props.$index == editIndex"
                      class="ml-1"
                      type="default"
                      size="sm"
                      icon
                      @click="cancelEditBranchInput()"
                    >
                      <i class="fa fa-times"></i>
                    </p-button>
                    <p-button
                      class="ml-1"
                      type="danger"
                      size="sm"
                      icon
                      @click="removeBranchInput(props.$index)"
                    >
                      <i class="fa fa-trash"></i>
                    </p-button>
                  </template>
                </el-table-column>
              </el-table>
            </div>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button
            class="btn btn-secondary"
            @click="CancelRequest"
            data-bs-dismiss="modal"
          >
            Cancel
          </button>
          <button
            class="btn btn-primary"
            @click="SendRequestConfirmation"
            :disabled="disableSendRequest"
          >
            Proceed
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axiosClient from "../../../../axios";
import { Message, MessageBox } from "element-ui";

export default {
  components: {},
  props: ["promoData", "promoDetails"],
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
      assignBranch: [],
      companyList: [],
      chainCodeList: [],
      branchNameList: [],
      form: {
        Company: "",
        ChainCode: "",
        BranchName: "",
        StartDate: "",
        EndDate: "",
      },
      editBranchMode: false,
      editIndex: null,
      disableAddBranch: true,
      disableSendRequest: true,
    };
  },
  watch: {
    form: {
      handler() {
        this.validateAddBranch();
      },
      deep: true,
    },
    assignBranch: {
      handler() {
        this.validateAddBranch();
      },
      deep: true,
    },
  },
  mounted() {
    if (
      sessionStorage.getItem("Position") == "Reviewer" ||
      sessionStorage.getItem("Position") == "Approver"
    )
      this.fetchCompanyByRA();
    else this.fetchCompany();
  },
  methods: {
    fetchCompany() {
      axiosClient
        .get("/fetchCompany")
        .then((response) => {
          this.companyList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
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
            company: this.form.Company,
          },
        })
        .then((response) => {
          this.chainCodeList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    fetchChainName() {
      axiosClient
        .get("/fetchChainName", {
          params: {
            chainCode: this.form.ChainCode,
            company: this.form.Company,
          },
        })
        .then((response) => {
          this.branchNameList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    validateAddBranch() {
      if (
        this.form.BranchName &&
        this.form.ChainCode &&
        this.form.Company &&
        this.form.StartDate &&
        this.form.EndDate
      )
        this.disableAddBranch = false;
      else this.disableAddBranch = true;

      //Limit to 3 branch
      // if (this.assignBranch.length >= 3)
      //   if (!this.editBranchMode) this.disableAddBranch = true;

      this.validateSendRequest();
    },
    addBranchInput() {
      // Function to pad a single digit with a leading zero
      function padZero(number) {
        return number.toString().padStart(2, "0");
      }
      // Convert StartDate
      const startDate = new Date(this.form.StartDate);

      // Extract year, month, and day components
      const year = startDate.getFullYear();
      const month = padZero(startDate.getMonth() + 1); // Months are zero-based, so we add 1
      const day = padZero(startDate.getDate());

      // Format the date as "yyyy-MM-dd"
      this.form.StartDate = `${year}-${month}-${day}`;

      // Convert EndDate
      const endDate = new Date(this.form.EndDate);
      const yearE = endDate.getFullYear();
      const monthE = padZero(endDate.getMonth() + 1); // Months are zero-based, so we add 1
      const dayE = padZero(endDate.getDate());

      // Format the date as "yyyy-MM-dd"
      this.form.EndDate = `${yearE}-${monthE}-${dayE}`;

      var isStartDate = new Date(this.form.StartDate); //dd-mm-YYYY
      var isEndDate = new Date(this.form.EndDate); //dd-mm-YYYY
      var today = new Date();
      today.setHours(0, 0, 0, 0);

      if (
        this.promoDetails.some(
          (branch) => branch.branchName === this.form.BranchName && !branch.expired
        )
      ) {
        Message.error(
          "Selected Branch was already assigned for this Promo. Kindly choose another one."
        );
      } else if (isStartDate < today) {
        Message.error("Start Date must be today or any date later than today's date.");
      } else if (isEndDate < isStartDate) {
        Message.error("End Date must be equal or any date later than Start date.");
      } else {
        let temp = false;
        if (this.assignBranch.length == 0) temp = true;
        for (let i = 0; i < this.assignBranch.length; i++) {
          if (this.editBranchMode) {
            if (
              i == this.editIndex &&
              this.form.BranchName != this.assignBranch[i].branch
            ) {
              this.assignBranch[i].company = this.form.Company;
              this.assignBranch[i].chainCode = this.form.ChainCode;
              this.assignBranch[i].branch = this.form.BranchName;
              this.assignBranch[i].date_start = this.form.StartDate;
              this.assignBranch[i].date_end = this.form.EndDate;
              this.assignBranch[i].permanent = false;
              break;
            }
          } else {
            if (this.form.BranchName != this.assignBranch[i].branch) temp = true;
            else {
              temp = false;
              break;
            }
          }
        }
        if (temp)
          this.assignBranch.push({
            company: this.form.Company,
            chainCode: this.form.ChainCode,
            branch: this.form.BranchName,
            date_start: this.form.StartDate,
            date_end: this.form.EndDate,
          });
        this.form.Company = "";
        this.form.ChainCode = "";
        this.form.BranchName = "";
        this.form.StartDate = "";
        this.form.EndDate = "";
        this.chainCodeList = [];
        this.branchList = [];
        this.editBranchMode = false;
        this.editIndex = null;
      }
    },
    editBranchInput(index, row) {
      this.editBranchMode = true;
      this.editIndex = index;
      this.form.Company = this.assignBranch[index].company;
      this.form.ChainCode = this.assignBranch[index].chainCode;
      this.form.BranchName = this.assignBranch[index].branch;
      this.form.StartDate = this.assignBranch[index].date_start;
      this.form.EndDate = this.assignBranch[index].date_end;
      this.fetchChainCode();
      this.fetchChainName();
    },
    cancelEditBranchInput() {
      this.editBranchMode = false;
      this.editIndex = null;
      this.form.Company = "";
      this.form.ChainCode = "";
      this.form.BranchName = "";
      this.form.StartDate = "";
      this.form.EndDate = "";
    },
    removeBranchInput(index) {
      this.assignBranch.splice(index, 1);
    },
    validateSendRequest() {
      if (this.assignBranch.length != 0) this.disableSendRequest = false;
      else this.disableSendRequest = true;
    },
    CancelRequest() {
      this.editBranchMode = false;
      this.editIndex = null;
      this.form.Company = "";
      this.form.ChainCode = "";
      this.form.BranchName = "";
      this.form.StartDate = "";
      this.form.EndDate = "";
    },
    SendRequestConfirmation() {
      MessageBox.confirm("Do you want to continue?", "Assigning of Temporary Branch", {
        confirmButtonText: "CONFIRM",
        cancelButtonText: "CANCEL",
        center: true,
        showClose: false,
        closeOnClickModal: false,
        closeOnPressEscape: false,
      })
        .then(() => {
          this.SendRequest();
          // setTimeout(location.reload(), 500);
        })
        .catch(() => {
          Message({
            type: "info",
            message: "Assigning of Branch canceled.",
          });
        });
    },
    SendRequest() {
      this.disableSendRequest = true;
      const requests = this.assignBranch.map((ctr) => {
        return axiosClient.post("/postPromoUserBranch", {
          userID: this.promoData.id,
          company: ctr.company,
          chainCode: ctr.chainCode,
          branchName: ctr.branch,
          date_start: ctr.date_start,
          date_end: ctr.date_end,
          req: null,
        });
      });

      // Use Promise.all to wait for all requests to complete
      Promise.all(requests)
        .then(() => {
          Message({
            message: "Assigning of Temporary Branch completed.",
            type: "success",
          });

          // Reload the page after all requests have completed
          setTimeout(() => {
            location.reload();
          }, 500);
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>

<style lang="scss">
.date-label {
  font-size: 12px;
  color: gray;
}
</style>
