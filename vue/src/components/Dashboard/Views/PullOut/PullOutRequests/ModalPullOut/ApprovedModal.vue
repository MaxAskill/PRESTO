<template>
  <div>
    <div
      class="modal fade"
      id="approvedModal"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="approvedModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <div class="row">
              <div class="col d-flex justify-content-center">
                <h6 class="modal-title">Approved Pull Out Transaction</h6>
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
            <!-- <h1 class="modal-title fs-5 text-weight-bold" id="approvedModalLabel">
              APPROVED: {{ transferredData.branchName }}
            </h1> -->
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
              <div class="col-sm-12 pt-5">
                <fg-input
                  class="input-md"
                  placeholder="Search"
                  v-model="searchQuery"
                  addon-right-icon="nc-icon nc-zoom-split"
                >
                </fg-input>
              </div>
              <div class="col-sm-12 mt-2">
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
                    style="font-weight: bold"
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
              data-bs-target="#viewApprovedModal"
              data-bs-toggle="modal"
              @click="viewImage"
            >
              View
            </button>
            <button type="submit" class="btn btn-info" @click="exportExcel">
              Export Excel
            </button>
            <button class="btn btn-success" @click="generateLetter">
              GENERATE LETTER
            </button>
          </div>
        </div>
      </div>
    </div>

    <ViewApprovedModal :viewImages="viewImages"></ViewApprovedModal>
  </div>
</template>
<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";
import axiosClient from "../../../../../../axios";
import { createPopper } from "@popperjs/core/lib/popper-lite.js";
import PPagination from "../../../../../UIComponents/Pagination.vue";
import ViewApprovedModal from "./ApprovedModal/ViewApprovedModal.vue";
import XLSX from "../../../../../../../node_modules/xlsx/dist/xlsx.full.min.js";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  components: {
    PPagination,
    // RegenerateApprovedModal,
    ViewApprovedModal,
  },
  props: ["transferredData", "totalNumbers", "itemData"],

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
        perPage: 5,
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
          minWidth: 100,
        },
        {
          prop: "brand",
          label: "Brand",
          minWidth: 100,
        },
        {
          prop: "boxNumber",
          label: "Box Number",
          minWidth: 60,
        },
        {
          prop: "boxLabel",
          label: "Box Label",
          minWidth: 250,
        },
        {
          prop: "quantity",
          label: "Quantity",
          minWidth: 50,
        },
        {
          prop: "amount",
          label: "Amount",
          minWidth: 50,
        },
      ],
      viewImages: "",
      headerRow: [
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

          dataArray = [this.headerRow, ...dataArray];
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
    generateLetter() {
      var holderName = this.convertToAlphanumeric("name");
      var holderplID = this.convertToAlphanumeric("plID");
      var holderDateStart = this.convertToAlphanumeric("dateStart");
      var holderDateEnd = this.convertToAlphanumeric("dateEnd");
      var holderEmail = this.convertToAlphanumeric("email");
      var holderUserID = this.convertToAlphanumeric("userID");
      var holderStatus = this.convertToAlphanumeric("status");
      var holderCompany = this.convertToAlphanumeric("company");
      var holderRegenerate = this.convertToAlphanumeric("regenerate");
      window.open(
        "http://192.168.0.7:90/api/generatePDF?" +
          holderName +
          "=" +
          this.transferredData.authorizedPersonnel +
          "&" +
          holderplID +
          "=" +
          this.transferredData.plID +
          "&" +
          holderDateStart +
          "=" +
          this.transferredData.dateStart +
          "&" +
          holderDateEnd +
          "=" +
          this.transferredData.dateEnd +
          "&" +
          holderEmail +
          "=" +
          this.transferredData.promoEmail +
          "&" +
          holderUserID +
          "=" +
          sessionStorage.getItem("UserID") +
          "&" +
          holderStatus +
          "=approved" +
          "&" +
          holderCompany +
          "=" +
          sessionStorage.getItem("Company"),
        "_blank"
      );
    },
    viewImage() {
      console.log("Transaction Number:", this.transferredData);

      axiosClient
        .get("/fetchImageBranchDoc", {
          params: {
            transactionID: this.transferredData.plID,
            company: sessionStorage.getItem("Company"),
          },
        })
        .then((response) => {
          console.log("Pull out path image:", response.data);
          console.log("Pull out path image length:", response.data.length);

          this.viewImages = response.data.imagePaths;
        })
        .catch((error) => {
          console.error(error);
        });
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
</style>
