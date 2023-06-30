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
            <h1 class="modal-title fs-5 text-weight-bold" id="unprocessModalLabel">
              {{ transferredData.branchName }}
            </h1>
            <div class="col-12 pl">
              <fg-input
                class="input-md"
                placeholder="Search"
                v-model="searchQuery"
                addon-right-icon="nc-icon nc-zoom-split"
              >
              </fg-input>
            </div>
          </div>
          <div class="modal-body">
            <div class="col-sm-12 mt-2">
              <table
                class="table table-bordered table-hover table-responsive-sm table-font-size font-weight-normal"
              >
                <thead>
                  <tr>
                    <th scope="col" class="nowrap">Item Code</th>
                    <th scope="col" class="nowrap">Category/Brand</th>
                    <th scope="col" class="nowrap">Box Number</th>
                    <th scope="col" class="nowrap">Box Label</th>
                    <th scope="col" class="nowrap">Quantity</th>
                    <th scope="col" class="nowrap">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in queriedData" :key="item.id">
                    <th scope="row" style="width: 200px" class="px-3">
                      {{ item.itemCode }}
                    </th>
                    <td style="width: 100px" class="cell-unprocess">
                      {{ item.brand }}
                    </td>
                    <td style="width: 100px" class="cell-unprocess">
                      {{ item.boxNumber }}
                    </td>
                    <td style="width: 200px" class="cell-unprocess">
                      <!-- {{ item.boxLabel }} -->
                      <!-- <el-select
                        class="table-select-box"
                        size="large"
                        v-model="item.boxLabel"
                        @change="editBoxLabel(item.code, item.quantity, item.boxLabel)"
                      > -->
                      <el-select
                        class="table-select-box"
                        size="large"
                        @visible-change="getBoxLabels"
                        @change="changeBoxNumber(item)"
                        v-model="item.boxLabel"
                      >
                        <el-option
                          v-for="boxLabel in listBoxLabel"
                          class="table-select-box"
                          :value="boxLabel.boxLabel"
                          :label="boxLabel.boxLabel"
                          :key="boxLabel.id"
                        >
                        </el-option>
                      </el-select>
                    </td>
                    <!-- <td class="cell px-3">{{ item.quantity }}</td> -->
                    <td style="width: 50px" class="cell-unprocess">
                      <input
                        type="number"
                        min="1"
                        @blur="handleQuantity()"
                        v-model="item.quantity"
                        class="table-input-box"
                        required="true"
                        message="you can give score -10 to +10 only"
                      />
                    </td>
                    <td style="width: 100px" class="cell-unprocess">
                      <p-button type="danger" size="sm" icon @click="handleDelete(item)">
                        <i class="fa fa-times"></i>
                      </p-button>
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
            </div>
          </div>
          <div class="modal-footer mrgn-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              Close
            </button>
            <button type="submit" class="btn btn-warning btn-fill btn-wd">Edit</button>

            <button
              class="btn btn-danger"
              data-bs-target="#deniedunprocess"
              data-bs-toggle="modal"
            >
              Denied
            </button>
            <button
              class="btn btn-primary"
              data-bs-target="#approvedunprocess"
              data-bs-toggle="modal"
            >
              Approved
            </button>
          </div>
        </div>
      </div>
    </div>
    <DeniedUnprocessModal :transferredData="transferredData"></DeniedUnprocessModal>
    <ApprovedUnprocessModal
      :transferredData="transferredData"
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
import PPagination from "../../../../../UIComponents/Pagination.vue";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  // mounted() {
  //   this.fetchData();
  // },
  props: ["transferredData", "itemData", "listBoxLabel"],
  components: {
    [Select.name]: Select,
    NotifItemQuantity,
    DeniedUnprocessModal,
    ApprovedUnprocessModal,
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
    };
  },
  methods: {
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
    handleQuantity() {
      // this.getBoxLabels();
      this.notifyVue("ItemQuantity", "bottom", "right");
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
      // console.log("ID to be deleted", item);

      let indexToDelete = this.itemData.findIndex((tableRow) => tableRow.id === item.id);
      // console.log("ID to be deleted", indexToDelete);
      if (indexToDelete >= 0) {
        this.itemData.splice(indexToDelete, 1);
        this.notifyVue("DeleteItem", "bottom", "right");
        // console.log("Delete ID: ", this.itemData);
      }

      // axiosClient
      //   .post("/updateStatus", {
      //     company: sessionStorage.getItem("Company"),
      //     id: item.id,
      //     status: "deleted",
      //     userID: sessionStorage.getItem("UserID"),
      //   })
      //   .then((response) => {
      //     // console.log("Success Delete: ", response.data);
      //     this.notifyVue("DeleteItem", "bottom", "right");
      //   })
      //   .catch((error) => {
      //     // console.error(error);
      //   });
    },
    insertElement(btn, tip) {
      this.button = btn;
      this.popover = tip;
      // console.log("insertElement: ", this.popover, this.button);
    },

    handleClick(e) {
      // console.log("handleClick: ", this.popover);
      if (this.button === null && this.popover === null) {
        // console.log("true!");
        this.insertElement(e.target, document.querySelector(".popoverPanel"));
      }
      // console.log(this.popover);
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
}

.table-select-box {
  /* border: 5px solid green !important; */
  transition: border-color 0.3s ease-in-out;
  /* background-color: yellow; */
  // border-radius: 0px !important;
  background-color: transparent !important;
}

.table-select-box.el-select .el-input input {
  border-radius: 0px !important;
  border: 5px solid transparent !important;
  background-color: transparent;
}
</style>
