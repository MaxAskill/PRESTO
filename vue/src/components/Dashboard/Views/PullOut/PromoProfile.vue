<template>
  <div>
    <div class="row">
      <!-- <div class="col-lg-4 col-md-5">
       <user-card>

      </user-card>
      <members-card>

      </members-card>
    </div> -->
      <!-- <div class="col-lg-8 col-md-7"> -->
      <div class="col-lg-9 col-md-10 mx-auto">
        <div class="card">
          <!-- <div class="card-header d-flex">
          <i
            data-bs-toggle="tooltip"
            class="nc-icon nc-alert-circle-i font-weight-bold"
          ></i>
          <h4 class="title">My Profile</h4>
        </div> -->
          <div class="card-body">
            <form>
              <div class="row">
                <div class="col-sm-5">
                  <fg-input
                    label="Name"
                    type="text"
                    placeholder="Paper dashboard"
                    v-model.trim="user.name"
                    readonly
                  >
                  </fg-input>
                </div>
                <div class="col-sm-5">
                  <fg-input
                    label="Email"
                    type="text"
                    placeholder="Username"
                    v-model.trim="user.email"
                    readonly
                  >
                  </fg-input>
                </div>
                <div class="col-sm-2">
                  <fg-input
                    label="Date Registered"
                    type="text"
                    placeholder="Date Registered"
                    v-model="user.date"
                    readonly
                  >
                  </fg-input>
                </div>
              </div>
              <!-- <div class="row labelRows" v-show="!editMode">
                <span class="col-sm-3">Company</span>
                <span class="col-sm-3">Chain Name</span>
                <span class="col-sm-6">Branch Name</span>
              </div> -->
              <div class="row" v-show="!editMode">
                <div class="col-sm-3">
                  <fg-input
                    label="Company"
                    type="text"
                    placeholder="Company"
                    v-model="user.company"
                    readonly
                  >
                  </fg-input>
                </div>
                <div class="col-sm-2">
                  <fg-input
                    label="Chain Name"
                    type="text"
                    placeholder="Chain Name"
                    v-model="user.chainCode"
                    readonly
                  >
                  </fg-input>
                </div>
                <div class="col-sm-7">
                  <fg-input
                    label="Branch Name"
                    type="text"
                    placeholder="Branch Name"
                    v-model="user.branchName"
                    readonly
                  >
                  </fg-input>
                </div>
              </div>

              <div class="row mx-1" v-show="!editMode">
                <el-table
                  class="p-0"
                  :data="tableData"
                  border
                  max-height="350"
                  :header-cell-style="headerCellStyle"
                  :cell-style="cellStyle"
                  style="width: 100%"
                >
                  <!-- Index Column -->
                  <el-table-column header-align="center">
                    <template slot="header" slot-scope="scope">
                      Temporary Branch
                    </template>
                    <el-table-column label="" class="el-table-mod" width="40">
                      <template slot-scope="scope">
                        <span>{{ scope.$index + 1 }}</span>
                      </template>
                    </el-table-column>

                    <el-table-column
                      v-for="column in tableColumns"
                      :key="column.label"
                      :prop="column.prop"
                      :label="column.label"
                      :min-width="column.minWidth"
                      header-align="center"
                    >
                    </el-table-column>
                    <el-table-column
                      label="Request Status"
                      width="130"
                      header-align="center"
                      align="center"
                    >
                      <template slot-scope="scope">
                        <el-tooltip
                          v-if="scope.row.request == 'remove'"
                          content="Pending request to remove this branch."
                          placement="top"
                        >
                          <el-tag class="m-0" type="danger">For Removal</el-tag>
                        </el-tooltip>
                        <el-tooltip
                          v-else-if="scope.row.request == 'additional'"
                          content="Pending request to add this branch."
                          placement="top"
                        >
                          <el-tag class="m-0" color="white" type="warning"
                            >New Branch</el-tag
                          >
                        </el-tooltip>
                        <el-tooltip
                          v-else
                          content="Your request to add this branch is Approved by the authorized Officer."
                          placement="top"
                        >
                          <el-tag class="m-0" color="white" type="success"
                            >Approved</el-tag
                          >
                        </el-tooltip>
                      </template>
                    </el-table-column>
                    <el-table-column
                      prop="start_date"
                      label="Start Date"
                      min-width="130"
                      header-align="center"
                    ></el-table-column>
                    <el-table-column
                      prop="end_date"
                      label=" Expiry Date"
                      min-width="130"
                      header-align="center"
                    ></el-table-column>
                  </el-table-column>
                </el-table>
              </div>

              <div class="row" v-show="editMode">
                <div class="col-md-6">
                  <fg-input label="Company">
                    <el-select
                      class="select-default"
                      size="large"
                      placeholder="Select Company"
                      v-model="edit.Company"
                      @change="fetchChainCode()"
                    >
                      <el-option
                        v-for="company in companyList"
                        class="select-default"
                        :value="company.shortName"
                        :label="company.name + ' (' + company.shortName + ') '"
                      >
                      </el-option>
                    </el-select>
                  </fg-input>
                </div>
                <div class="col-md-6">
                  <fg-input label="Chain Name">
                    <el-select
                      class="select-default"
                      size="large"
                      placeholder="Select Chain Name"
                      v-model="edit.ChainCode"
                      @change="fetchChainName()"
                    >
                      <el-option
                        v-for="chain in chainCodeList"
                        class="select-default"
                        :value="chain.chainCode"
                        :label="chain.chainCode"
                      >
                      </el-option>
                    </el-select>
                  </fg-input>
                </div>
              </div>

              <div class="row" v-show="editMode">
                <span class="headerBranch">Branch Name</span>
                <div class="col-sm-11 mb-10px">
                  <el-select
                    class="select-default selectBranch"
                    size="large"
                    placeholder="Select Branch Name"
                    v-model="edit.BranchName"
                  >
                    <el-option
                      v-for="branch in branchList"
                      class="select-default"
                      :value="branch.branchName"
                      :label="branch.branchName"
                    >
                    </el-option>
                  </el-select>
                </div>
                <div class="col-sm-1 mb-10px selectBtnBranch">
                  <p-button
                    type="success"
                    class="m-0"
                    @click="addBranchInput"
                    icon
                    :disabled="disableAddBranch"
                  >
                    <i class="nc-icon nc-check-2"></i>
                  </p-button>
                </div>
              </div>

              <div
                v-if="editMode"
                class="row"
                v-for="(brM, index) in branchMultiple"
                :key="index"
              >
                <span class="headerBranch" v-if="brM.permanent">Permanent Branch</span>
                <span class="headerBranch" v-else-if="!brM.permanent && index == 1"
                  >Temporary Branch</span
                >
                <div class="col-sm-12" v-if="brM.permanent">
                  <fg-input
                    type="text"
                    placeholder="Select Branch Name"
                    v-model="brM.branch"
                    disabled
                  >
                  </fg-input>
                </div>
                <div class="col-sm-10" v-else>
                  <fg-input
                    type="text"
                    placeholder="Select Branch Name"
                    v-model="brM.branch"
                    disabled
                  >
                  </fg-input>
                </div>

                <!-- <div v-if="index >= 1" class="col-1 removeBtnBranch">
                <p-button
                  type="danger"
                  class="m-0"
                  @click="removeBranchInput(index)"
                  icon
                >
                  <i class="nc-icon nc-simple-delete"></i>
                </p-button>
              </div> -->
                <div v-if="!brM.permanent" class="col-sm-2 gap-2 inputBtnBranch">
                  <p-button
                    v-if="index != editIndex"
                    type="warning"
                    class="m-0"
                    @click="editBranchInput(index)"
                    icon
                    :disabled="editBranchMode"
                  >
                    <!-- <i class="nc-icon nc-simple-add"></i> -->
                    <i class="nc-icon nc-ruler-pencil"></i>
                    <!-- <i class="fa fa-heart"></i> -->
                  </p-button>
                  <p-button
                    v-if="index == editIndex"
                    type="default"
                    class="m-0"
                    @click="cancelEditBranchInput"
                    icon
                  >
                    <i class="nc-icon nc-simple-remove"></i>
                  </p-button>
                  <p-button
                    v-if="index == 0"
                    type="danger"
                    class="m-0"
                    @click="removeBranchInput(index)"
                    icon
                    :disabled="editBranchMode || branchMultiple.length == 1"
                  >
                    <i class="nc-icon nc-simple-delete"></i>
                  </p-button>
                  <p-button
                    v-else
                    type="danger"
                    class="m-0"
                    @click="removeBranchInput(index)"
                    icon
                    :disabled="editBranchMode"
                  >
                    <i class="nc-icon nc-simple-delete"></i>
                  </p-button>
                </div>
              </div>

              <div class="clearfix"></div>
            </form>
          </div>
          <div class="card-footer">
            <div class="gap-2 d-flex justify-content-center">
              <span
                id="btnRequest"
                class="d-inline-block"
                tabindex="0"
                data-bs-toggle="tooltip"
                data-bs-placement="bottom"
              >
                <button
                  v-show="!editMode"
                  class="btn btn-info btn-fill btn-wd"
                  @click.prevent="editProfile"
                  :disabled="user.withRequest"
                >
                  Request to Update Temporary Branch
                </button>
              </span>
              <!-- <button
                v-show="!editMode"
                class="btn btn-success btn-fill btn-wd"
                data-bs-toggle="modal"
                data-bs-target="#promoViewPendingRequestModal"
                :disabled="!user.withRequest"
                @click="fetchDataPromoRequest"
              >
                View Pending Request
              </button> -->
              <button
                v-show="editMode"
                class="btn btn-danger btn-fill btn-wd"
                @click.prevent="cancelProfile"
              >
                Cancel
              </button>
              <button
                v-show="editMode"
                @click="updateProfile"
                class="btn btn-success btn-fill btn-wd"
                :disabled="disableUpdateProfile"
              >
                Save Changes
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <PromoRequestBranchConfirmationModal
      :remove="removeBranch"
      :additional="additionalBranch"
      @confirm="updateProfileWithRequest($event)"
    ></PromoRequestBranchConfirmationModal>
    <PromoViewPendingRequestModal
      :userData="userMultiple"
      :userRequest="userRequest"
      :dateRequested="dateCreated"
      @refetchUserData="fetchData"
    >
    </PromoViewPendingRequestModal>
    <PromoRequestBranchNotification></PromoRequestBranchNotification>
  </div>
</template>
<script>
import axiosClient from "../../../../axios";
import linkName from "../../../../linkName";
import { Select, Option, Table, TableColumn, Tooltip, Tag } from "element-ui";
import PromoRequestBranchConfirmationModal from "./PromoRequestBranchConfirmationModal.vue";
import PromoViewPendingRequestModal from "./PromoViewPendingRequest.vue";
import PromoRequestBranchNotification from "./PromoRequestBranchNotification.vue";
import axios from "axios";

export default {
  components: {
    [Option.name]: Option,
    [Select.name]: Select,
    [Table.name]: Table,
    [TableColumn.name]: TableColumn,
    [Tooltip.name]: Tooltip,
    [Tag.name]: Tag,
    PromoRequestBranchConfirmationModal,
    PromoViewPendingRequestModal,
    PromoRequestBranchNotification,
  },
  data() {
    return {
      user: {
        name: "",
        email: "",
        date: "",
        status: "",
        withRequest: null,
        company: "",
        chainCode: "",
        branchName: "",
      },
      userRequest: [],
      userMultiple: [],
      edit: {
        Name: "",
        Email: "",
        Company: "",
        ChainCode: "",
        BranchName: "",
      },
      dateCreated: "",
      branchMultiple: [],
      branchList: [],
      companyList: [],
      chainCodeList: [],
      editMode: false,
      disableUpdateProfile: true,
      disableAddBranch: true,
      editBranchMode: false,
      editIndex: null,
      isBranchChanged: true,
      removeBranch: [],
      additionalBranch: [],

      tableColumns: [
        {
          prop: "company",
          label: "Company",
          minWidth: 130,
        },
        {
          prop: "chainCode",
          label: "Chain Name",
          minWidth: 120,
        },
        {
          prop: "branchName",
          label: "Branch Name",
          minWidth: 220,
        },
        // {
        //   prop: "request",
        //   label: "Request Status",
        //   minWidth: 130,
        // },
        // {
        //   prop: "start_date",
        //   label: "Start Date",
        //   minWidth: 150,
        // },
        // {
        //   prop: "end_date",
        //   label: "End Date",
        //   minWidth: 120,
        // },
      ],
      headerCellStyle: {
        fontSize: "10px",
      },
      cellStyle: {
        fontSize: "12px !important",
      },
      tableData: [],
    };
  },
  watch: {
    edit: {
      handler() {
        this.validateAddBranch();
        // this.validateUpdateProfile();
      },
      deep: true,
    },
    branchMultiple: "validateAddBranch",
  },
  mounted() {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(
      (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
    );
    this.fetchData();
    if (window.resolveRouteChange) {
      window.resolveRouteChange();
      window.resolveRouteChange = null;
    }
    linkName.val = "My Profile";
  },
  methods: {
    fetchChainCode() {
      axiosClient
        .get("/fetchChain", {
          params: {
            company: this.edit.Company,
          },
        })
        .then((response) => {
          this.chainCodeList = response.data;
        })
        .catch((error) => {});
      this.isChainCode = false;
    },
    fetchChainName() {
      axiosClient
        .get("/fetchChainName", {
          params: {
            chainCode: this.edit.ChainCode,
            company: this.edit.Company,
          },
        })
        .then((response) => {
          this.branchList = response.data;
        })
        .catch((error) => {});
      this.isBranchName = false;
    },
    editProfile() {
      this.edit.Name = this.user.name;
      this.edit.Email = this.user.email;
      this.userMultiple.forEach((userM) => {
        var company1 = userM.company.split("(")[1];
        var company = company1.split(")")[0];
        this.branchMultiple.push({
          id: userM.id,
          company: company,
          chainCode: userM.chainCode,
          branch: userM.branchName,
          permanent: userM.permanent,
        });
      });

      this.editMode = true;
      this.chainCodeList = [];
      this.branchList = [];

      axiosClient.get("/fetchCompany").then((response) => {
        this.companyList = response.data;
      });
    },
    cancelProfile() {
      this.editMode = false;
      this.edit = {
        Company: "",
        ChainCode: "",
        BranchName: "",
      };
      this.branchMultiple = [];
      console.log("Edit: ", this.edit);
    },
    addBranchInput() {
      let temp = false;
      for (let i = 0; i < this.branchMultiple.length; i++) {
        if (this.editBranchMode) {
          if (
            i == this.editIndex &&
            this.edit.BranchName != this.branchMultiple[i].branch
          ) {
            this.branchMultiple[i].company = this.edit.Company;
            this.branchMultiple[i].chainCode = this.edit.ChainCode;
            this.branchMultiple[i].branch = this.edit.BranchName;
            this.branchMultiple[i].permanent = false;
            break;
          }
        } else {
          if (this.edit.BranchName != this.branchMultiple[i].branch) temp = true;
          else {
            temp = false;
            break;
          }
        }
      }
      if (temp)
        this.branchMultiple.push({
          company: this.edit.Company,
          chainCode: this.edit.ChainCode,
          branch: this.edit.BranchName,
        });

      this.edit.Company = "";
      this.edit.ChainCode = "";
      this.edit.BranchName = "";
      this.chainCodeList = [];
      this.branchList = [];
      this.editBranchMode = false;
    },
    removeBranchInput(index) {
      this.branchMultiple.splice(index, 1);
    },
    editBranchInput(index) {
      this.editBranchMode = true;
      this.editIndex = index;
      this.edit.Company = this.branchMultiple[index].company;
      this.edit.ChainCode = this.branchMultiple[index].chainCode;
      this.edit.BranchName = this.branchMultiple[index].branch;
    },
    cancelEditBranchInput() {
      this.editBranchMode = false;
      this.editIndex = null;
      this.edit.Company = "";
      this.edit.ChainCode = "";
      this.edit.BranchName = "";
    },
    fetchData() {
      axiosClient
        .get("/usersProfile", {
          params: {
            userID: sessionStorage.getItem("UserID"),
          },
        })
        .then((response) => {
          this.user.name = response.data[0].name;
          this.user.email = response.data[0].email;
          this.user.date = response.data[0].date;
          this.user.status = response.data[0].status;
          this.user.withRequest = response.data[0].withRequest;
          if (response.data[0].position == "User") {
            this.userMultiple = response.data;
          }
          this.tableData = [];
          let ctr = 0;
          for (let temp of response.data) {
            if (!temp.permanent) this.tableData.push(temp);
            else {
              this.user.company = temp.company;
              this.user.chainCode = temp.chainCode;
              this.user.branchName = temp.branchName;
            }
            if (temp.request == "remove" || temp.request == "additional") ctr++;
          }
          if (ctr > 0) {
            this.user.withRequest = true;
            let tooltipReq = bootstrap.Tooltip.getInstance("#btnRequest");
            tooltipReq._config.title =
              "You still have a pending request. Kindly wait for the approval by the authorized Officer.";
            tooltipReq.update();
          } else {
            this.user.withRequest = false;
            let tooltip = bootstrap.Tooltip.getInstance("#btnRequest");
            tooltip.disable();
          }
        })
        .catch((error) => {
          console.error(error);
        });
    },
    fetchDataPromoRequest() {
      axiosClient
        .get("/usersMaintenanceRequestBranch", {
          params: {
            userID: sessionStorage.getItem("UserID"),
          },
        })
        .then((response) => {
          this.userRequest = response.data;
          this.dateCreated = response.data[0].dateCreated;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    updateProfileWithRequest(confirm) {
      if (confirm) {
        this.removeBranch.forEach((ctr) => {
          axiosClient
            .post("/postPromoUserBranch", {
              userID: sessionStorage.getItem("UserID"),
              company: ctr.company,
              chainCode: ctr.chainCode,
              branchName: ctr.branch,
              req: "remove",
              id: ctr.id,
            })
            .then((response) => {
              this.cancelProfile();
              this.fetchData();
            })
            .catch((error) => {
              console.error(error);
            });
        });
        this.additionalBranch.forEach((ctr) => {
          axiosClient
            .post("/postPromoUserBranch", {
              userID: sessionStorage.getItem("UserID"),
              company: ctr.company,
              chainCode: ctr.chainCode,
              branchName: ctr.branch,
              req: "additional",
            })
            .then((response) => {
              this.cancelProfile();
              this.fetchData();
            })
            .catch((error) => {
              console.error(error);
            });
        });
        const requestBranchNotificationModal = new bootstrap.Modal(
          "#requestBranchNotification"
        );
        requestBranchNotificationModal.show();
      }
      //this.branchMultiple
    },
    updateProfile() {
      console.log(this.userMultiple, this.branchMultiple, this.isBranchChanged);
      if (
        this.branchMultiple.length != this.userMultiple.length ||
        this.isBranchChanged
      ) {
        let requestBranchModal = new bootstrap.Modal("#requestBranchConfirmation");
        requestBranchModal.show();

        this.removeBranch = [];
        this.userMultiple.forEach((temp) => {
          var removeB = true;
          for (let i = 1; i < this.branchMultiple.length; i++) {
            if (temp.branchName == this.branchMultiple[i].branch) {
              removeB = false;
              break;
            }
          }
          if (!temp.permanent && removeB) {
            this.removeBranch.push({
              id: temp.id,
              company: temp.company,
              chainCode: temp.chainCode,
              branch: temp.branchName,
            });
          }
        });

        this.additionalBranch = [];
        this.branchMultiple.forEach((req) => {
          var additionalB = true;
          for (let i = 1; i < this.userMultiple.length; i++) {
            if (req.branch == this.userMultiple[i].branchName) {
              additionalB = false;
              break;
            }
          }
          if (!req.permanent && additionalB) {
            req.request = "additional";
            this.additionalBranch.push({
              company: req.company,
              chainCode: req.chainCode,
              branch: req.branch,
            });
          }
        });
      } else console.log("POST || update only name & email");
    },
    validateAddBranch() {
      if (this.edit.Company && this.edit.ChainCode && this.edit.BranchName)
        this.disableAddBranch = false;
      else this.disableAddBranch = true;

      if (this.branchMultiple.length >= 3)
        if (!this.editBranchMode) this.disableAddBranch = true;

      this.validateUpdateProfile();
    },
    validateUpdateProfile() {
      if (this.userMultiple.length == this.branchMultiple.length) {
        let temp = false;
        for (let i = 0; i < this.userMultiple.length; i++) {
          for (let j = 0; j < this.branchMultiple.length; j++)
            if (this.userMultiple[i].branchName == this.branchMultiple[j].branch) {
              temp = true;
              break;
            } else temp = false;
        }
        if (temp) this.isBranchChanged = false;
        else this.isBranchChanged = true;
      }
      if (
        this.user.name === this.edit.Name &&
        this.user.email === this.edit.Email &&
        this.branchMultiple.length == this.userMultiple.length &&
        !this.isBranchChanged
      )
        this.disableUpdateProfile = true;
      else this.disableUpdateProfile = false;
    },
  },
};
</script>
<style lang="scss">
.headerBranch {
  font-size: 0.8571em;
  color: #9a9a9a;
  margin-bottom: 5px;
}
.mb-10px {
  margin-bottom: 10px;
}
.selectBranch {
  width: 100%;
}
.selectBtnBranch {
  display: flex;
  justify-content: end;
  padding-left: 12px;
  padding-right: 12px;
}
.inputBtnBranch {
  display: flex;
  justify-content: end;
  padding-left: 0px;
  padding-right: 12px;
}
.pl-24px {
  padding-left: 12px;
  padding-right: 24px;
}
.form-control-plaintext {
  margin: 4px;
  border: solid;
  border-width: 1px;
  border-radius: 5px;
}
.tagStyle {
  border-radius: 0px !important;
}
</style>
