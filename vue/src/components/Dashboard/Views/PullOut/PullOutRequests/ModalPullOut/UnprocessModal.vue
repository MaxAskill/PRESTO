<template>
  <div>
    <div
      class="modal fade"
      id="unprocessModal"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="unprocessModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <div class="row">
              <div class="col d-flex justify-content-center">
                <h6 class="modal-title">For Review Pull Out Transaction</h6>
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
                    {{ transactionData.plID }}
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
                    {{ transactionData.transactionType }}
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
                    {{ transactionData.status }}
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
                    {{ transactionData.branchName }}
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
                    {{ transactionData.createdBy }}
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
                    {{ transactionData.date }}
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
                    {{ transactionData.chainCode }}
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
                    {{ transactionData.reviewedBy }}
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
                    {{ transactionData.reviewedDate }}
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
                    {{ transactionData.company }}
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
                    {{ transactionData.approvedBy }}
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
                    {{ transactionData.approvedDate }}
                  </p>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12 pt-5">
                <fg-input
                  class="input-md"
                  placeholder="Search"
                  v-model="searchQuery"
                  addon-right-icon="nc-icon nc-zoom-split"
                >
                </fg-input>
              </div>
              <div class="col-sm-12 mt-2">
                <table
                  class="table table-bordered table-hover table-responsive-sm table-font-size font-weight-normal"
                >
                  <thead>
                    <tr class="text-center">
                      <th scope="col" class="nowrap" style="font-size: 10px">
                        Item Code
                      </th>
                      <th scope="col" class="nowrap" style="font-size: 10px">
                        Category/Brand
                      </th>
                      <th scope="col" class="nowrap" style="font-size: 10px">
                        Box Number
                      </th>
                      <th scope="col" class="nowrap" style="font-size: 10px">
                        Box Label
                      </th>
                      <th scope="col" class="nowrap" style="font-size: 10px">Quantity</th>
                      <th scope="col" class="nowrap" style="font-size: 10px">Amount</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    <tr
                      v-for="(item, index) in queriedData"
                      :key="item.id"
                      style="font-size: 12px"
                    >
                      <th style="min-width: 150px" class="px-3">
                        {{ item.itemCode }}
                      </th>
                      <td style="min-width: 50px" class="cell-unprocess">
                        {{ item.brand }}
                      </td>
                      <td style="min-width: 100px" class="cell-unprocess">
                        {{ item.boxNumber }}
                      </td>
                      <td style="min-width: 400px" class="cell-unprocess">
                        {{ item.boxLabel }}
                      </td>
                      <td style="min-width: 50px" class="cell-unprocess">
                        {{ item.quantity }}
                      </td>
                      <td style="min-width: 50px" class="cell-unprocess">
                        {{ item.amount }}
                      </td>
                    </tr>
                  </tbody>
                </table>
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
            </div>
          </div>
          <div class="modal-footer mrgn-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              Close
            </button>
            <button
              type="submit"
              class="btn btn-info"
              data-bs-target="#viewModal"
              data-bs-toggle="modal"
              @click="viewImage"
            >
              View
            </button>
            <button type="submit" class="btn btn-info" @click="exportExcel">
              Export
            </button>
            <button
              type="submit"
              class="btn btn-warning"
              @click="submit"
              data-bs-dismiss="modal"
              v-if="isReviewer"
            >
              Edit
            </button>

            <button
              class="btn btn-danger"
              data-bs-target="#deniedunprocess"
              data-bs-toggle="modal"
              v-if="isReviewer"
            >
              Denied
            </button>
            <button
              class="btn btn-success"
              data-bs-target="#approvedunprocess"
              data-bs-toggle="modal"
              v-if="isReviewer"
            >
              Approved
            </button>
          </div>
        </div>
      </div>
    </div>
    <ViewModal :transferredData="transactionData" :viewImages="viewImages"></ViewModal>
    <DeniedUnprocessModal :transferredData="transactionData"></DeniedUnprocessModal>
    <ApprovedUnprocessModal
      :transferredData="transactionData"
      :itemData="itemData"
    ></ApprovedUnprocessModal>
  </div>
</template>
<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";
import axiosClient from "../../../../../../axios";
import NotifItemQuantity from "../../../Components/Notification/NotifSuccessItemQuantity.vue";
import NotifDeleteItem from "../../../Components/Notification/NotifSuccessDeleteItem.vue";
import { createPopper } from "@popperjs/core/lib/popper-lite.js";
import DeniedUnprocessModal from "./UnprocessModal/DeniedUnprocessModal.vue";
import ApprovedUnprocessModal from "./UnprocessModal/ApprovedUnprocessModal.vue";
import ViewModal from "./UnprocessModal/ViewModal.vue";
import PPagination from "../../../../../UIComponents/Pagination.vue";
import XLSX from "../../../../../../../node_modules/xlsx/dist/xlsx.full.min.js";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  props: ["transactionData", "itemData", "totalNumbers", "listBoxLabel"],
  components: {
    [Select.name]: Select,
    NotifItemQuantity,
    DeniedUnprocessModal,
    ApprovedUnprocessModal,
    ViewModal,
    PPagination,
    NotifDeleteItem,
  },

  mounted() {
    this.checkPos();
  },
  watch: {
    transferredData(newValue) {
      if (newValue === "") {
        this.$emit("closeModal");
      }
    },
  },
  computed: {
    transfer() {
      return this.itemData;
    },
    /***
     * Searches through table data and returns a paginated array.
     * Note that this should not be used for table with a lot of data as it might be slow!
     * Do the search and the pagination on the server and display the data retrieved from server instead.
     * @returns {computed.pagedData}
     */
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
          prop: "brand",
          label: "Brand",
          minWidth: 200,
        },
        {
          prop: "boxLabel",
          label: "Box Label",
          minWidth: 250,
        },
        {
          prop: "boxNumber",
          label: "Box Number",
          minWidth: 125,
        },
        {
          prop: "itemCode",
          label: "Item Code",
          minWidth: 175,
        },
        {
          prop: "quantity",
          label: "Quantity",
          minWidth: 100,
        },
      ],
      itemsData: [],
      button: null,
      popover: null,
      toggle: false,
      viewImages: [],
      isReviewer: false,
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
      const selectedItems = [this.transactionData.plID];

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
            currentDateString + "_ " + this.transactionData.branchName + ".xlsx";
          link.click();
          window.URL.revokeObjectURL(url);
        })
        .catch((error) => {
          console.error(error);
        });
    },
    checkPos() {
      if (
        sessionStorage.getItem("Position") == "Reviewer" ||
        sessionStorage.getItem("Position") == "Admin"
      )
        this.isReviewer = true;
    },

    viewImage() {
      axiosClient
        .get("/fetchImageBranchDoc", {
          params: {
            transactionID: this.transactionData.plID,
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
          [tempTransactionID]: this.transactionData.plID,
          [tempcompany]: this.convertToAlphanumeric(this.transactionData.shortName),
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
<style lang="scss">
.el-table .td-actions {
  button.btn {
    margin-right: 5px;
  }
}

.mrgn-footer {
  margin-right: 10px;
}

.popoverPanel {
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid rgba(0, 0, 0, 0.2);
  border-radius: 0.3rem;
  font-size: 0.875rem;
  display: none;
}

.popoverPanel[data-show] {
  display: block;
}

.popoverHeader {
  padding: 0.5rem 1rem;
  margin: 0;
  font-size: 1rem;
  background-color: #f0f0f0;
  border-bottom: 1px solid rgba(0, 0, 0, 0.2);
  border-top-left-radius: calc(0.3rem - 1px);
  border-top-right-radius: calc(0.3rem - 1px);
}

.popoverBody {
  padding: 1rem 1rem;
  color: #212529;
}

.popoverArrow,
.popoverArrow::before {
  position: absolute;
  width: 8px;
  height: 8px;
  background: inherit;
}

.popoverArrow {
  visibility: hidden;
}

.popoverArrow::before {
  visibility: visible;
  content: "";
  transform: rotate(45deg);
}

.popoverPanel[data-popper-placement^="left"] > .popoverArrow {
  right: -5px;
}

.popoverPanel[data-popper-placement^="left"] > .popoverArrow::before {
  border-top: 1px solid rgba(0, 0, 0, 0.2);
  border-right: 1px solid rgba(0, 0, 0, 0.2);
}
.cell-unprocess {
  display: table-cell !important;
  padding: 0px !important;
}
// td {
//   display: table-cell !important;
//   padding: 0px !important;
//   /* background-color: #111111 !important; */
// }
.table-input-box {
  width: 100%;
  height: 100%;
  box-sizing: border-box;
  padding: 14px;
  border: 1px solid transparent;
  transition: border-color 0.3s ease-in-out;
  background-color: transparent;
  font-size: 12px;
}

.table-select-box {
  /* border: 5px solid green !important; */
  transition: border-color 0.3s ease-in-out;
  /* background-color: yellow; */
  // border-radius: 0px !important;
  background-color: transparent !important;
  font-size: 12px;
}

.table-select-box.el-select .el-input input {
  border-radius: 0px !important;
  border: 5px solid transparent !important;
  background-color: transparent;
  font-size: 12px;
}
.delete-buttons {
  background-color: transparent;
  color: red;
  border: 2px solid red;
  border-radius: 0.25rem;
  cursor: pointer;
  padding-bottom: -2px;
}

.delete-buttons:hover {
  background-color: red;
  color: white;
}

.selector-label {
  font-size: 10px; /* Replace 16px with your desired font size */
  color: #000 !important;
}
</style>
