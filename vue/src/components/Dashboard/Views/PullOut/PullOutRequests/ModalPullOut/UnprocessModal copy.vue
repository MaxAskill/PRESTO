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
            <!-- <h1 class="modal-title fs-5 text-weight-bold" id="unprocessModalLabel">
              Pull Out Transaction
            </h1> -->
            <div class="row">
              <div class="col d-flex justify-content-center">
                <h6 class="modal-title">Unprocessed Pull Out Transaction</h6>
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
                      <th scope="col" class="nowrap" style="font-size: 10px">
                        Amount (P)
                      </th>
                      <!-- <th scope="col" class="nowrap" style="font-size: 10px">Action</th> -->
                    </tr>
                  </thead>
                  <tbody>
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
                        <!-- {{ item.boxLabel }} -->
                        <!-- <el-select
                        class="table-select-box"
                        size="large"
                        v-model="item.boxLabel"
                        @change="editBoxLabel(item.code, item.quantity, item.boxLabel)"
                      > -->
                        <!-- <el-select
                        class="table-select-box"
                        style="font-size: 12px; font-weight: bold"
                        size="large"
                        @visible-change="getBoxLabels"
                        @change="changeBoxNumber(item)"
                        v-model="item.boxLabel"
                        :style="{ width: '100%' }"
                      >
                        <el-option
                          v-for="boxLabel in listBoxLabel"
                          class="table-select-box"
                          :value="boxLabel.boxLabel"
                          :label="boxLabel.boxLabel"
                          :key="boxLabel.id"
                        >
                        </el-option>
                      </el-select> -->
                      </td>
                      <!-- <td class="cell px-3">{{ item.quantity }}</td> -->
                      <td style="min-width: 50px" class="cell-unprocess">
                        <!-- <div class="btn-group btn-group-sm d-flex flex-row"> -->
                        {{ item.quantity }}
                        <!-- <p-button
                          type="default"
                          round
                          outline
                          size="sm"
                          @click="
                            item.quantity > 0 ? item.quantity-- : 0, handleQuantity(item)
                          "
                        >
                          <i class="nc-icon nc-simple-delete"></i>
                        </p-button>
                        <input
                          type="text"
                          @blur="handleQuantity(item)"
                          v-model="item.quantity"
                          class="table-input-box"
                          style="width: 75px; text-align: center"
                          @keypress="numberOnly"
                        />
                        <p-button
                          type="default"
                          round
                          outline
                          size="sm"
                          @click="item.quantity++, handleQuantity(item)"
                        >
                          <i class="nc-icon nc-simple-add"></i>
                        </p-button> -->
                        <!-- </div> -->
                        <!-- <input
                        type="number"
                        min="1"
                        @blur="handleQuantity(item)"
                        v-model="item.quantity"
                        class="table-input-box"
                        required="true"
                        message="you can give score -10 to +10 only"
                      /> -->
                      </td>
                      <td style="min-width: 80px" class="cell-unprocess">
                        <!-- <input
                        :disabled="true"
                        v-model="item.amount"
                        class="table-input-box"
                        required="true"
                        message="you can give score -10 to +10 only"
                      /> -->
                        {{ item.amount }}
                      </td>
                      <!-- <td style="min-width: 50px" class="cell-unprocess">
                      <p-button type="danger" size="sm" icon @click="handleDelete(item)">
                        <i class="fa fa-times"></i>
                      </p-button>
                    </td> -->
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
            <button
              type="submit"
              class="btn btn-warning"
              @click="submit"
              data-bs-dismiss="modal"
            >
              Edit
            </button>

            <button
              class="btn btn-danger"
              data-bs-target="#deniedunprocess"
              data-bs-toggle="modal"
            >
              Denied
            </button>
            <button
              class="btn btn-success"
              data-bs-target="#approvedunprocess"
              data-bs-toggle="modal"
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
import axios from "axios";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  // mounted() {
  //   this.fetchData();
  // },
  props: ["transactionData", "itemData", "listBoxLabel"],
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
    // this.itemsData = this.transfer();
    // console.log("Item Data: ", this.itemsData);
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
      // listBoxLabel: [],
      viewImages: [],
    };
  },
  methods: {
    viewImage() {
      console.log("Transaction Number:", this.transactionData);

      axiosClient
        .get("/fetchImageBranchDoc", {
          params: {
            transactionID: this.transactionData.plID,
            company: sessionStorage.getItem("Company"),
          },
        })
        .then((response) => {
          console.log("Pull out path image:", response.data);
          console.log("Pull out path image length:", response.data.length);

          this.viewImages = response.data.imagePaths;
          // for (var x = 0; x < response.data.length; x++) {
          //   this.viewImages.push(
          //     "http://192.168.0.7:40/public/uploads/" +
          //       sessionStorage.getItem("Company") +
          //       "/" +
          //       response.data[x].path
          //   );
          // }
          // console.log("Images:", this.viewImages);
        })
        .catch((error) => {
          console.error(error);
        });
    },
    submit() {
      console.log("Transaction Number:", this.transactionData);
      console.log("Company", this.transactionData.shortName);

      location.href =
        "http://192.168.0.7:4040/#/pull-out/requisition-form?transactionID=" +
        this.transactionData.plID +
        "&company=" +
        this.transactionData.shortName;
    },
    changeBoxNumber(item) {
      console.log("Edit Labels:", item);
      const matchingObject = this.listBoxLabel.find(
        (obj) => obj.boxLabel === item.boxLabel
      );
      console.log("Edit matchingObject:", matchingObject);

      if (matchingObject) {
        item.boxNumber = matchingObject.boxNumber;
        item.boxLabel = matchingObject.boxLabel;
      }

      console.log("Edit Labels:", item);
    },
    getBoxLabels() {
      // this.itemData.forEach((obj) => {
      //   const index = this.listBoxLabel.findIndex(
      //     (savedObj) => savedObj.boxNumber === obj.boxNumber
      //   );
      //   if (index === -1) {
      //     this.listBoxLabel.push(obj);
      //     // console.log(`Object ${obj.boxNumber} saved.`);
      //   }
      // });
      // console.log("Edit Labels:", this.listBoxLabel);
    },
    handleQuantity(item) {
      console.log("Quantity", item.itemCode);
      axiosClient
        .get("/fetchNewAmount", {
          params: {
            company: sessionStorage.getItem("Company"),
            itemCode: item.itemCode,
            quantity: item.quantity,
          },
        })
        .then((response) => {
          console.log("Success New Amount", response.data);
          item.amount = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
      // this.getBoxLabels();
      // this.notifyVue("ItemQuantity", "bottom", "right");
    },
    notifyVue(notify, verticalAlign, horizontalAlign) {
      var notification = "";
      let notifType = "";

      if (notify === "ItemQuantity") {
        notification = NotifItemQuantity;
        notifType = "success";
      } else {
        notification = NotifDeleteItem;
        notifType = "danger";
      }
      this.$notify({
        component: notification,
        // icon: 'nc-icon nc-app',
        horizontalAlign: horizontalAlign,
        verticalAlign: verticalAlign,
        type: notifType,
        props: {
          customValue: "Success Add Box",
        },
      });
    },
    handleEdit(index, row) {
      alert(`Your want to edit ${row.id}`);
    },
    handleDelete(item) {
      let indexToDelete = this.itemData.findIndex((tableRow) => tableRow.id === item.id);
      if (indexToDelete >= 0) {
        this.itemData.splice(indexToDelete, 1);
        this.notifyVue("DeleteItem", "bottom", "right");
      }
    },
    insertElement(btn, tip) {
      this.button = btn;
      this.popover = tip;
    },

    handleClick(e) {
      if (this.button === null && this.popover === null) {
        this.insertElement(e.target, document.querySelector(".popoverPanel"));
      }
      this.popover.setAttribute("data-show", "");
      this.popperInstance.update();
      this.toggle = true;
    },

    handleHide(e) {
      if (this.button === null && this.popover === null) {
        this.insertElement(e.target, document.querySelector(".popoverPanel"));
      }
      this.popover.removeAttribute("data-show");
      this.toggle = false;
    },
    numberOnly($event) {
      let keyCode = $event.keyCode ? $event.keyCode : $event.which;
      if (keyCode < 48 || keyCode > 57) {
        // 46 is dot
        $event.preventDefault();
      }
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
