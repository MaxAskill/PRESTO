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
              <div class="col d-flex justify-content-center">
                <h6 class="modal-title">For Approval Pull Out Transaction</h6>
              </div>
              <div class="col-auto">
                <button class="delete-buttons" data-bs-dismiss="modal">
                  <i
                    class="nc-icon nc-simple-remove font-weight-bold"
                    style="margin-top: 5px"
                  ></i>
                </button>
              </div>
            </div>
          </div>

          <div class="modal-body">
            <div class="row">
              <div class="col-sm-3">
                <div class="form-group">
                  <label
                    class="selector-label d-flex justify-content-center align-items-center"
                    >Transaction Number</label
                  >
                  <p
                    rows="1"
                    readonly
                    class="form-control font-weight-bold control-form text-center font-weight-bold"
                  >
                    {{ transferredData.plID }}
                  </p>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label
                    class="selector-label d-flex justify-content-center align-items-center"
                    >Transaction Type</label
                  >
                  <p
                    rows="1"
                    readonly
                    class="form-control control-form text-center font-weight-bold"
                  >
                    {{ transferredData.transactionType }}
                  </p>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label
                    class="selector-label d-flex justify-content-center align-items-center"
                    >Status</label
                  >
                  <p
                    rows="1"
                    readonly
                    class="form-control control-form text-center font-weight-bold"
                    style="text-transform: capitalize"
                  >
                    {{ transferredData.status }}
                  </p>
                </div>
              </div>
            </div>

            <div class="row text-center">
              <div class="col-sm-3">
                <div class="form-group">
                  <label
                    class="selector-label d-flex justify-content-center align-items-center"
                    >Branch</label
                  >
                  <p
                    rows="1"
                    readonly
                    class="form-control font-weight-bold control-form text-center"
                  >
                    {{ transferredData.branchName }}
                  </p>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label
                    class="selector-label d-flex justify-content-center align-items-center"
                    >Created By</label
                  >
                  <p
                    rows="1"
                    readonly
                    class="form-control control-form text-center font-weight-bold"
                  >
                    {{ transferredData.createdBy }}
                  </p>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label
                    class="selector-label d-flex justify-content-center align-items-center"
                    >Date Created</label
                  >
                  <p
                    rows="1"
                    readonly
                    class="form-control control-form text-center font-weight-bold"
                    style="text-transform: capitalize"
                  >
                    {{ transferredData.date }}
                  </p>
                </div>
              </div>
            </div>

            <div class="row text-center">
              <div class="col-sm-3">
                <div class="form-group">
                  <label
                    class="selector-label d-flex justify-content-center align-items-center"
                    >Chain</label
                  >
                  <p
                    rows="1"
                    readonly
                    class="form-control font-weight-bold control-form text-center"
                  >
                    {{ transferredData.chainCode }}
                  </p>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label
                    class="selector-label d-flex justify-content-center align-items-center"
                    >Reviewed By</label
                  >
                  <p
                    rows="1"
                    readonly
                    class="form-control control-form text-center font-weight-bold"
                  >
                    {{ transferredData.reviewedBy }}
                  </p>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label
                    class="selector-label d-flex justify-content-center align-items-center"
                    >Date Reviewed</label
                  >
                  <p
                    rows="1"
                    readonly
                    class="form-control control-form text-center font-weight-bold"
                    style="text-transform: capitalize"
                  >
                    {{ transferredData.reviewedDate }}
                  </p>
                </div>
              </div>
            </div>

            <div class="row text-center">
              <div class="col-sm-3">
                <div class="form-group">
                  <label
                    class="selector-label d-flex justify-content-center align-items-center"
                    >Company</label
                  >
                  <p
                    rows="1"
                    readonly
                    class="form-control font-weight-bold control-form text-center"
                  >
                    {{ transferredData.company }}
                  </p>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label
                    class="selector-label d-flex justify-content-center align-items-center"
                    >Approved By</label
                  >
                  <p
                    rows="1"
                    readonly
                    class="form-control control-form text-center font-weight-bold"
                  >
                    {{ transferredData.approvedBy }}
                  </p>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label
                    class="selector-label d-flex justify-content-center align-items-center"
                    >Date Approved</label
                  >
                  <p
                    rows="1"
                    readonly
                    class="form-control control-form text-center font-weight-bold"
                    style="text-transform: capitalize"
                  >
                    {{ transferredData.approvedDate }}
                  </p>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <fg-input
                  class="input-md"
                  placeholder="Search"
                  v-model="searchQuery"
                  addon-right-icon="nc-icon nc-zoom-split"
                >
                </fg-input>
              </div>
              <div class="col-12">
                <el-table
                  class="table-striped"
                  :data="queriedData"
                  border
                  style="width: 100%"
                  :header-cell-style="headerCellStyle"
                  :cell-style="cellStyle"
                >
                  <el-table-column
                    class="el-table-mod"
                    v-for="column in tableColumns"
                    :key="column.label"
                    :min-width="column.minWidth"
                    :prop="column.prop"
                    :label="column.label"
                    header-align="center"
                    align="center"
                  >
                  </el-table-column>
                </el-table>
              </div>
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
              <div class="col-sm-6 text-center">
                <label> Number of Boxes</label><br />
                <span class="font-weight-bold label-size">
                  {{ totalNumbers.boxCount }}
                </span>
              </div>
              <div class="col-sm-6 text-center">
                <label> Number of Items</label><br />
                <span class="font-weight-bold label-size">
                  {{ totalNumbers.totalItems }}
                </span>
              </div>
            </div>
          </div>
          <div class="modal-footer mrgn-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              Close
            </button>
            <button
              type="submit"
              class="btn btn-info"
              data-bs-target="#viewPendingModal"
              data-bs-toggle="modal"
              @click="viewImage"
            >
              View
            </button>
            <button type="submit" class="btn btn-info" @click="exportExcel">
              Export Excel
            </button>
            <button
              :disabled="isDisabledSubmit"
              type="submit"
              class="btn btn-warning btn-fill btn-wd"
              @click.prevent="submit"
              data-bs-dismiss="modal"
              v-if="isApprover"
            >
              Edit
            </button>
            <button
              type="submit"
              class="btn btn-danger btn-fill btn-wd"
              data-bs-target="#deniedPending"
              data-bs-toggle="modal"
              v-if="isApprover"
            >
              Denied
            </button>
            <button
              :disabled="isDisabledSubmit"
              type="submit"
              class="btn btn-success btn-fill btn-wd"
              data-bs-target="#approvedPending"
              data-bs-toggle="modal"
              v-if="isApprover"
            >
              Approve
            </button>
          </div>
        </div>
      </div>
    </div>

    <DeniedPendingModal :transferredData="transferredData"></DeniedPendingModal>
    <ApprovedPendingModal
      :transferredData="transferredData"
      :itemData="itemData"
      :dateData="dateData"
    ></ApprovedPendingModal>
    <ViewPendingModal :viewImages="viewImages"></ViewPendingModal>
  </div>
</template>
<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";
import axiosClient from "../../../../../../axios";
import { createPopper } from "@popperjs/core/lib/popper-lite.js";
import PPagination from "../../../../../UIComponents/Pagination.vue";
import ApprovedPendingModal from "./PendingApprovalModal/ApprovedPendingModal.vue";
import DeniedPendingModal from "./PendingApprovalModal/DeniedPendingModal.vue";
import ViewPendingModal from "./PendingApprovalModal/ViewPendingModal.vue";
import XLSX from "../../../../../../../node_modules/xlsx/dist/xlsx.full.min.js";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  components: {
    PPagination,
    ApprovedPendingModal,
    DeniedPendingModal,
    ViewPendingModal,
    ViewPendingModal,
  },
  props: ["transferredData", "itemData", "totalNumbers", "dateData"],

  watch: {
    transferredData(newValue) {
      if (newValue === "") {
        this.$emit("closeModal");
      }
    },
  },
  mounted() {
    this.checkPos();
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
      viewImages: "",
      searchQuery: "",
      propsToSearch: ["itemCode", "brand", "boxNumber", "boxLabel", "quantity"],
      tableColumns: [
        {
          prop: "itemCode",
          label: "Item Code",
          minWidth: 125,
        },
        {
          prop: "brand",
          label: "Category/Brand",
          minWidth: 200,
        },
        {
          prop: "boxNumber",
          label: "Box Number",
          minWidth: 90,
        },
        {
          prop: "boxLabel",
          label: "Box Label",
          minWidth: 330,
        },
        {
          prop: "quantity",
          label: "Quantity",
          minWidth: 90,
        },
        {
          prop: "amount",
          label: "Amount",
          minWidth: 90,
        },
      ],
      isApprover: false,
      headerRowNBFI: [
        "Item Code",
        "Item Description",
        "Size",
        "Color",
        "Brand",
        "Box Number",
        "Box Label",
        "Quantity",
        "Amount",
      ],
      headerRowEPC: [
        "Item Code",
        "Item Description",
        "Size",
        "Color",
        "Sub-Category",
        "Box Number",
        "Box Label",
        "Quantity",
        "Amount",
      ],

      headerCellStyle: {
        fontSize: "10px",
      },
      cellStyle: {
        fontSize: "12px !important",
      },
    };
  },
  methods: {
    exportExcel() {
      var dataArray = "";
      const selectedItems = [this.transferredData.plID];

      axiosClient
        .get("/fetchAllItemsRequestExport", {
          params: {
            company: sessionStorage.getItem("Company"),
            plID: selectedItems,
          },
        })
        .then((response) => {
          this.excelBranch = response.data;
          dataArray = this.excelBranch.map((obj) => [
            obj.itemCode,
            obj.ItemDescription,
            obj.Size,
            obj.Color,
            obj.brand,
            obj.boxNumber,
            obj.boxLabel,
            obj.quantity,
            obj.amount,
          ]);

          const currentDate = new Date();
          const options = { year: "numeric", month: "2-digit", day: "2-digit" };
          const currentDateString = currentDate
            .toLocaleDateString("en-PH", options)
            .replace(/\//g, "-");
          if (sessionStorage.getItem("Company") == "NBFI") {
            dataArray = [this.headerRowNBFI, ...dataArray];
          } else {
            dataArray = [this.headerRowEPC, ...dataArray];
          }
          const workbook = XLSX.utils.book_new();
          const worksheet = XLSX.utils.aoa_to_sheet(dataArray);

          XLSX.utils.book_append_sheet(workbook, worksheet, "Raw Data");
          const excelBuffer = XLSX.write(workbook, { bookType: "xlsx", type: "array" });
          const blob = new Blob([excelBuffer], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
          });
          const url = window.URL.createObjectURL(blob);
          const link = document.createElement("a");
          link.href = url;
          link.download =
            currentDateString + "_ " + this.transferredData.branchName + ".xlsx";
          link.click();
          window.URL.revokeObjectURL(url);
        })
        .catch((error) => {
          console.error(error);
        });
    },
    checkPos() {
      if (
        sessionStorage.getItem("Position") == "Approver" ||
        sessionStorage.getItem("Position") == "Admin"
      )
        this.isApprover = true;
    },
    viewImage() {
      axiosClient
        .get("/fetchImageBranchDoc", {
          params: {
            transactionID: this.transferredData.plID,
            company: sessionStorage.getItem("Company"),
          },
        })
        .then((response) => {
          this.viewImages = response.data.imagePaths;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    submit() {
      var tempTransactionID = this.convertToAlphanumeric("transactionID");
      var tempcompany = this.convertToAlphanumeric("company");

      const routeParams = {
        path: "/pull-out/requisition-form",
        query: {
          [tempTransactionID]: this.transferredData.plID,
          [tempcompany]: this.convertToAlphanumeric(this.transferredData.shortName),
        },
      };

      this.$router.push(routeParams);
    },
    convertToAlphanumeric(input) {
      let result = "";

      for (let i = 0; i < input.length; i++) {
        const char = input[i];
        const charCode = char.charCodeAt(0);

        // Check if the character is alphanumeric
        if (
          (char >= "0" && char <= "9") ||
          (char >= "a" && char <= "z") ||
          (char >= "A" && char <= "Z")
        ) {
          // Convert the character code to a base-36 alphanumeric representation
          const alphanumericChar = charCode.toString(36);
          result += alphanumericChar;
        } else {
          // Non-alphanumeric characters are left unchanged
          result += char;
        }
      }

      return result;
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
