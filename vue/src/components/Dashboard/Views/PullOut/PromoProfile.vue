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
      <div class="col-lg-6 col-md-8 mx-auto">
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
                <div class="col-sm-6 form-floating">
                  <input
                    v-show="!editMode"
                    type="text"
                    class="form-control-plaintext"
                    id="floatingInputName"
                    placeholder="Paper dashboard"
                    v-model.trim="user.name"
                    readonly
                  />
                  <!-- </fg-input> -->
                  <label v-show="!editMode" for="floatingInputName">Name</label>
                  <fg-input
                    v-show="editMode"
                    type="text"
                    label="Name"
                    placeholder="Paper dashboard"
                    v-model.trim="edit.Name"
                  >
                  </fg-input>
                </div>
                <div class="col-sm-6 form-floating">
                  <input
                    v-show="!editMode"
                    type="text"
                    class="form-control-plaintext"
                    id="floatingInputEmail"
                    placeholder="Username"
                    v-model.trim="user.email"
                    readonly
                  />
                  <!-- </fg-input> -->
                  <label v-show="!editMode" for="floatingInputEmail">Email</label>
                  <fg-input
                    v-show="editMode"
                    type="text"
                    label="Email"
                    placeholder="Username"
                    v-model.trim="edit.Email"
                  >
                  </fg-input>
                </div>
              </div>
              <!-- <div class="row labelRows" v-show="!editMode">
                <span class="col-sm-3">Company</span>
                <span class="col-sm-3">Chain Code</span>
                <span class="col-sm-6">Branch Name</span>
              </div> -->
              <div class="row" v-show="!editMode" v-for="user in userMultiple">
                <div class="col-sm-3 form-floating">
                  <input
                    type="text"
                    class="form-control-plaintext"
                    id="floatingInputCompany"
                    placeholder="Company"
                    v-model="user.company"
                    readonly
                  />
                  <!-- </fg-input> -->
                  <label for="floatingInputCompany">Company</label>
                </div>
                <div class="col-sm-2 form-floating">
                  <input
                    type="text"
                    class="form-control-plaintext"
                    id="floatingInputChainCode"
                    placeholder="Chain Code"
                    v-model="user.chainCode"
                    readonly
                  />
                  <!-- </fg-input> -->
                  <label for="floatingInputChainCode">Chain Code</label>
                </div>
                <div class="col-sm-7 form-floating">
                  <input
                    type="text"
                    class="form-control-plaintext"
                    id="floatingInputBranchName"
                    placeholder="Branch Name"
                    v-model="user.branchName"
                    readonly
                  />
                  <!-- </fg-input> -->
                  <label for="floatingInputBranchName">Branch Name</label>
                </div>
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
                  <fg-input label="Chain Code">
                    <el-select
                      class="select-default"
                      size="large"
                      placeholder="Select Chain Code"
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
                <div class="col-sm-10">
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
                <div class="col-sm-2 gap-2 inputBtnBranch">
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

              <div class="row">
                <div class="col-sm-12 form-floating" v-show="!editMode">
                  <input
                    type="text"
                    class="form-control-plaintext text-center"
                    id="floatingInputDateRegistered"
                    placeholder="Date Registered"
                    v-model="user.date"
                    readonly
                  />
                  <!-- </fg-input> -->
                  <label for="floatingInputDateRegistered">Date Registered</label>
                </div>
                <div class="col-sm-12" v-show="editMode">
                  <fg-input
                    type="text"
                    label="Date Registered"
                    placeholder="Date Registered"
                    v-model="user.date"
                    readonly
                  >
                  </fg-input>
                </div>
                <!-- <div class="col-sm-6 form-floating">
                  <input
                    type="text"
                    class="form-control-plaintext"
                    id="floatingInputStatus"
                    placeholder="Status"
                    v-model="user.status"
                    readonly
                  />
                  <label for="floatingInputStatus">Status</label>
                </div> -->
              </div>

              <div class="clearfix"></div>
            </form>
          </div>
          <div class="card-footer">
            <div class="gap-2 d-flex justify-content-center">
              <button
                v-show="!editMode"
                class="btn btn-info btn-fill btn-wd"
                @click.prevent="editProfile"
                :disabled="user.withRequest"
              >
                Edit Profile
              </button>
              <button
                v-show="!editMode"
                class="btn btn-success btn-fill btn-wd"
                data-bs-toggle="modal"
                data-bs-target="#promoViewPendingRequestModal"
                :disabled="!user.withRequest"
                @click="fetchDataPromoRequest"
              >
                View Pending Request
              </button>
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
      @confirm="updateProfileWithRequest($event)"
    ></PromoRequestBranchConfirmationModal>
    <PromoViewPendingRequestModal
      :userData="userMultiple"
      :userRequest="userRequest"
      :dateRequested="dateCreated"
      @refetchUserData="fetchData"
    >
    </PromoViewPendingRequestModal>
  </div>
</template>
<script>
import axiosClient from "../../../../axios";
import linkName from "../../../../linkName";
import { Select, Option } from "element-ui";
import PromoRequestBranchConfirmationModal from "./PromoRequestBranchConfirmationModal.vue";
import PromoViewPendingRequestModal from "./PromoViewPendingRequest.vue";
import axios from "axios";

export default {
  components: {
    [Option.name]: Option,
    [Select.name]: Select,
    PromoRequestBranchConfirmationModal,
    PromoViewPendingRequestModal,
  },
  data() {
    return {
      user: {
        name: "",
        email: "",
        date: "",
        status: "",
        withRequest: null,
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
    // "edit.Name": function (val, oldaval) {
    //   this.validateUpdateProfile();
    // },
    // "edit.Email": function () {
    //   this.validateUpdateProfile();
    // },
  },
  mounted() {
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
          //console.log("Chain Code Response:", response.data);
          this.chainCodeList = response.data;
        })
        .catch((error) => {
          //console.error(error);
        });
      // this.notifyVue("bottom", "right");
      this.isChainCode = false;
    },
    fetchChainName() {
      axiosClient
        .get("/fetchChainName", {
          params: {
            chainCode: this.edit.ChainCode,
          },
        })
        .then((response) => {
          //console.log("Chain Name Response:", response.data);
          this.branchList = response.data;
        })
        .catch((error) => {
          //console.error(error);
        });
      this.isBranchName = false;
    },
    editProfile() {
      // alert("Your data: " + JSON.stringify(this.user));
      this.edit.Name = this.user.name;
      this.edit.Email = this.user.email;
      this.userMultiple.forEach((userM) => {
        var company1 = userM.company.split("(")[1];
        var company = company1.split(")")[0];
        this.branchMultiple.push({
          company: company,
          chainCode: userM.chainCode,
          branch: userM.branchName,
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
      // this.branchMultiple.forEach((item) => {
      //   if (this.editBranchMode && this.edit.BranchName != item.branch) {
      //     item.company = this.edit.Company;
      //     item.chainCode = this.edit.ChainCode;
      //     item.branch = this.edit.BranchName;
      //   } else if (item.branch != this.edit.BranchName)
      //     this.branchMultiple.push({
      //       company: this.edit.Company,
      //       chainCode: this.edit.ChainCode,
      //       branch: this.edit.BranchName,
      //     });
      // });
      this.edit.Company = "";
      this.edit.ChainCode = "";
      this.edit.BranchName = "";
      this.chainCodeList = [];
      this.branchList = [];
      this.editBranchMode = false;

      console.log("Add Branch", this.branchMultiple);
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
          console.log("Users Profile Data:", response.data);
          this.user.name = response.data[0].name;
          this.user.email = response.data[0].email;
          this.user.date = response.data[0].date;
          this.user.status = response.data[0].status;
          this.user.withRequest = response.data[0].withRequest;
          this.withRequest = response.data[0].withRequest;
          if (response.data[0].position == "User") {
            this.userMultiple = response.data;
          }
          console.log("UserMultiple: ", this.userMultiple);
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
          console.log("Pull Out User Promo Request: ", response.data);
          this.userRequest = response.data;
          this.dateCreated = response.data[0].dateCreated;
          console.log("User Reuest: ", this.dateCreated);
        })
        .catch((error) => {
          console.error(error);
        });
    },
    updateProfileWithRequest(confirm) {
      console.log(" POST with branch ");
      if (confirm)
        this.branchMultiple.forEach((ctr) => {
          axiosClient
            .post("/postPromoUserBranch", {
              userID: sessionStorage.getItem("UserID"),
              company: ctr.company,
              chainCode: ctr.chainCode,
              branchName: ctr.branch,
            })
            .then((response) => {
              console.log("Success Add Branch", response.data);
              this.cancelProfile();
              this.fetchData();
            })
            .catch((error) => {
              console.error(error);
            });
        });
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
      console.log(
        this.user.name === this.edit.Name,
        this.user.email === this.edit.Email,
        this.branchMultiple.length == this.userMultiple.length,
        !this.isBranchChanged
      );
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
.labelRows {
  font-size: 0.8571em;
  color: #9a9a9a;
  margin-bottom: 5px;
}
.form-control-plaintext {
  margin: 4px;
  border: solid;
  border-width: 1px;
  border-radius: 5px;
}
</style>
