<template>
  <div
    class="modal fade"
    id="deniedPending"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="deniedPendingLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="deniedPendingLabel">
            REASON
          </h1>
        </div>
        <div class="modal-body">
          <div class="">
            <input
              type="text"
              placeholder="Reason"
              v-model="reason"
              list="reasonsList"
              class="input-datalist w-100"
            />

            <datalist id="reasonsList">
              <option
                v-for="reasonLabel in reasonLabelList"
                :value="reasonLabel.reasonLabel"
                :key="reasonLabel.id"
              ></option>
            </datalist>
            <!-- <fg-input label="Reason" placeholder="Reason" v-model="reason"></fg-input> -->
            <!-- <el-select class="denied-select-box" size="large" v-model="reason">
              <el-option
                v-for="reasonLabel in reasonLabelList"
                class="denied-select-box"
                :value="reasonLabel.reasonLabel"
                :label="reasonLabel.reasonLabel"
                :key="reasonLabel.id"
              >
              </el-option>
            </el-select> -->
          </div>
        </div>
        <div class="modal-footer px-5">
          <button
            class="btn btn-secondary"
            data-bs-target="#approvalModal"
            data-bs-toggle="modal"
            @click.prevent="cancelModal()"
          >
            Cancel
          </button>
          <button
            :disabled="isDisabled"
            type="button"
            class="btn btn-danger"
            data-bs-dismiss="modal"
            @click="submit"
          >
            Denied
          </button>
        </div>
      </div>
    </div>
    <ApprovalModal></ApprovalModal>
  </div>
</template>
<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";
import axiosClient from "../../../../../../../axios";
import ApprovalModal from "../ApprovalModal.vue";
import axios from "axios";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  props: ["transferredData"],
  components: {
    ApprovalModal,
  },
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
    return {
      reason: "",
      reasonLabelList: [],
      isDisabled: true,
    };
  },
  mounted() {
    this.fetchData();
  },
  watch: {
    reason: "validateGenerate",
  },
  methods: {
    cancelModal() {
      this.reason = "";
    },
    fetchData() {
      axiosClient
        .get("/fetchReasonLabel", {
          params: {
            company: sessionStorage.getItem("Company"),
          },
        })
        .then((response) => {
          this.reasonLabelList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    submit() {
      axiosClient
        .post("/sendDeniedBranch", {
          company: sessionStorage.getItem("Company"),
          id: this.transferredData.plID,
          userID: sessionStorage.getItem("UserID"),
          promoName: this.transferredData.createdBy,
          email: this.transferredData.promoEmail,
          reason: this.reason,
        })
        .then((response) => {
          // console.log("Success Denied", response.data);
          setTimeout(() => {
            window.location.reload();
          }, 3000); // Reload after 3 seconds
        })
        .catch((error) => {
          console.error(error);
        });
    },
    validateGenerate() {
      if (this.reason) this.isDisabled = false;
      else this.isDisabled = true;
    },
  },
};
</script>

<style>
.form-group {
  text-align: left !important;
}

.denied-select-box {
  /* border: 5px solid green !important; */
  transition: border-color 0.3s ease-in-out;
  /* background-color: yellow; */
  border-radius: 0px !important;
  background-color: transparent !important;
}

.denied-select-box.el-select .el-input input {
  border-radius: 5px !important;
  color: #ffffff !important;
  border: 5px solid transparent !important;
  background-color: #5c5c58 !important;
}
</style>
