<template>
  <div>
    <div
      class="modal fade"
      id="approvalModal"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="approvalModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <div class="row">
              <div class="col-sm-3">
                <div class="form-group">
                  <label class="font-weight-bold label-size">Transaction Number</label>
                  <textarea rows="1" readonly class="form-control control-form">{{
                    transferredData.plID
                  }}</textarea>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="font-weight-bold label-size">Company</label>
                  <textarea rows="1" readonly class="form-control control-form"
                    >{{ transferredData.company }} </textarea
                  >
                </div>
              </div>
              <div class="col-sm-5">
                <div class="form-group">
                  <label class="font-weight-bold label-size">Branch Name</label>
                  <textarea rows="1" readonly class="form-control control-form"
                    >{{ transferredData.branchName }} </textarea
                  >
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-3">
                <div class="form-group">
                  <label class="font-weight-bold label-size">Transaction Type</label>
                  <textarea rows="1" readonly class="form-control control-form"
                    >{{ transferredData.transactionType }} </textarea
                  >
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="font-weight-bold label-size">Reviewed By</label>
                  <textarea rows="1" readonly class="form-control control-form">{{
                    transferredData.editedBy
                  }}</textarea>
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-group">
                  <label class="font-weight-bold label-size">Created By</label>
                  <textarea rows="1" readonly class="form-control control-form"
                    >{{ transferredData.name }}
            </textarea
                  >
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label class="font-weight-bold label-size">Creation Date</label>
                  <textarea rows="1" readonly class="form-control control-form">{{
                    transferredData.date
                  }}</textarea>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-body">
            <!-- <h1 class="modal-title fs-5 text-weight-bold" id="approvedModalLabel">
              APPROVEDs:
            </h1> -->
            <fg-input
              class="input-md"
              placeholder="Search"
              v-model="searchQuery"
              addon-right-icon="nc-icon nc-zoom-split"
            >
            </fg-input>
            <div>
              <el-table
                class="table-striped"
                :data="queriedData"
                border
                style="width: 100%"
              >
                <el-table-column
                  class="el-table-mod"
                  v-for="column in tableColumns"
                  :key="column.label"
                  :min-width="column.minWidth"
                  :prop="column.prop"
                  :label="column.label"
                >
                </el-table-column>
              </el-table>
            </div>
            <div class="d-flex justify-content-end pagination-info">
              <p class="category p-margin">
                Showing {{ from + 1 }} to {{ to }} of {{ total }} entries
              </p>
            </div>
            <div class="d-flex justify-content-center">
              <p-pagination
                class="pull-right"
                v-model="pagination.currentPage"
                :per-page="pagination.perPage"
                :total="pagination.total"
              >
              </p-pagination>
            </div>
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="font-weight-bold label-size">Total Number of Box</label>
                  <textarea rows="1" readonly class="form-control control-form">{{
                    totalNumbers.boxCount
                  }}</textarea>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="font-weight-bold label-size">Total Number of Item</label>
                  <textarea rows="1" readonly class="form-control control-form">{{
                    totalNumbers.totalItems
                  }}</textarea>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="font-weight-bold label-size">Total Amount</label>
                  <textarea rows="1" readonly class="form-control control-form">
P {{ totalNumbers.totalAmount }}</textarea
                  >
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer mrgn-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              Close
            </button>
            <button
              :disabled="isDisabledSubmit"
              type="submit"
              class="btn btn-warning btn-fill btn-wd"
              @click.prevent="submit"
              data-bs-dismiss="modal"
            >
              Edit
            </button>
            <button
              type="submit"
              class="btn btn-danger btn-fill btn-wd"
              data-bs-target="#deniedPending"
              data-bs-toggle="modal"
            >
              Denied
            </button>
            <button
              :disabled="isDisabledSubmit"
              type="submit"
              class="btn btn-info btn-fill btn-wd"
              data-bs-target="#approvedPending"
              data-bs-toggle="modal"
            >
              Approve
            </button>
          </div>
        </div>
      </div>
    </div>
    <RegenerateApprovedModal
      :transferredData="transferredData"
      :itemData="itemData"
    ></RegenerateApprovedModal>
    <DeniedPendingModal :transferredData="transferredData"></DeniedPendingModal>
    <ApprovedPendingModal
      :transferredData="transferredData"
      :itemData="itemData"
    ></ApprovedPendingModal>
  </div>
</template>
<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";
import axiosClient from "../../../../../../axios";
import RegenerateApprovedModal from "./RegenerateApprovedModal.vue";
import { createPopper } from "@popperjs/core/lib/popper-lite.js";
import PPagination from "../../../../../UIComponents/Pagination.vue";
import ApprovedPendingModal from "./PendingApprovalModal/ApprovedPendingModal.vue";
import DeniedPendingModal from "./PendingApprovalModal/DeniedPendingModal.vue";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  components: {
    PPagination,
    RegenerateApprovedModal,
    ApprovedPendingModal,
    DeniedPendingModal,
  },
  props: ["transferredData", "itemData", "totalNumbers"],

  watch: {
    transferredData(newValue) {
      if (newValue === "") {
        this.$emit("closeModal");
      }
    },
  },
  computed: {
    pagedData() {
      return this.itemData.slice(this.from, this.to);
    },
    /***
     * Searches through table data and returns a paginated array.
     * Note that this should not be used for table with a lot of data as it might be slow!
     * Do the search and the pagination on the server and display the data retrieved from server instead.
     * @returns {computed.pagedData}
     */
    queriedData() {
      if (!this.searchQuery) {
        this.pagination.total = this.itemData.length;
        return this.pagedData;
      }
      let result = this.itemData.filter((row) => {
        let isIncluded = false;
        for (let key of this.propsToSearch) {
          let rowValue = row[key].toString().toLowerCase();
          if (rowValue.includes && rowValue.includes(this.searchQuery.toLowerCase())) {
            isIncluded = true;
          }
        }
        return isIncluded;
      });
      this.pagination.total = result.length;
      return result.slice(this.from, this.to);
    },
    to() {
      let highBound = this.from + this.pagination.perPage;
      if (this.total < highBound) {
        highBound = this.total;
      }
      return highBound;
    },
    from() {
      return this.pagination.perPage * (this.pagination.currentPage - 1);
    },
    total() {
      this.pagination.total = this.itemData.length;
      return this.itemData.length;
    },
    /***
     * Searches through table data and returns a paginated array.
     * Note that this should not be used for table with a lot of data as it might be slow!
     * Do the search and the pagination on the server and display the data retrieved from server instead.
     * @returns {computed.pagedData}
     */
    popperInstance() {
      return createPopper(this.button, this.popover, {
        placement: "left",
        modifiers: [
          {
            name: "offset",
            options: {
              offset: [0, 30],
            },
          },
        ],
        strategy: "absolute",
      });
    },
  },
  data() {
    return {
      pagination: {
        perPage: 10,
        currentPage: 1,
        perPageOptions: [5, 10, 25, 50],
        total: 0,
      },
      searchQuery: "",
      propsToSearch: ["itemCode", "brand", "boxNumber", "boxLabel", "quantity"],
      tableColumns: [
        {
          prop: "itemCode",
          label: "Item Code",
          minWidth: 175,
        },
        {
          prop: "brand",
          label: "Category/Brand",
          minWidth: 200,
        },
        {
          prop: "boxNumber",
          label: "Box Number",
          minWidth: 120,
        },
        {
          prop: "boxLabel",
          label: "Box Label",
          minWidth: 250,
        },
        {
          prop: "quantity",
          label: "Quantity",
          minWidth: 90,
        },
        {
          prop: "amount",
          label: "Amount",
          minWidth: 100,
        },
      ],
    };
  },
  methods: {
    submit() {
      console.log("Transaction Number:", this.transferredData.plID);
      console.log("Company", this.transferredData.shortName);
      location.href =
        "http://192.168.0.7:4040/#/pull-out/requisition-form?transactionID=" +
        this.transferredData.plID +
        "&company=" +
        this.transferredData.shortName;
    },
  },
};
</script>

<style>
.el-table-mod {
  padding-top: 10px !important;
  padding-bottom: 10px !important;
}

.control-form {
  padding: 0 !important;
  border: none !important;
  background-color: transparent !important;
  cursor: default !important;
  border-radius: 0 !important;
}

.label-size {
  font-size: 16px !important;
  color: black !important;
}

.margin-body {
  padding: 50px !important;
}
</style>
