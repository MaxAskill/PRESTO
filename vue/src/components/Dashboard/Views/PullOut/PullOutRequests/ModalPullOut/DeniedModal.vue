<template>
  <div>
    <div
      class="modal fade"
      id="deniedModal"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="deniedModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <!-- <h1 class="modal-title fs-5 text-weight-bold" id="deniedModalLabel">
            DENIED: {{ transferredData.branchName }}
          </h1> -->
            <div class="row">
              <div class="col d-flex justify-content-center">
                <h6 class="modal-title">Denied Pull Out Transaction</h6>
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
          <div class="modal-footer mrgn-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              Close
            </button>
            <button
              type="submit"
              class="btn btn-info"
              data-bs-target="#viewDeniedModal"
              data-bs-toggle="modal"
              @click="viewImage"
            >
              View
            </button>
            <button
              type="submit"
              class="btn btn-warning btn-fill btn-wd"
              @click="submit"
              data-bs-dismiss="modal"
            >
              Edit
            </button>
          </div>
        </div>
      </div>
    </div>
    <ViewDeniedModal :viewImages="viewImages"></ViewDeniedModal>
  </div>
</template>
<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";
import axiosClient from "../../../../../../axios";
import { createPopper } from "@popperjs/core/lib/popper-lite.js";
import PPagination from "../../../../../UIComponents/Pagination.vue";
import ViewDeniedModal from "./DeniedModal/ViewDeniedModal.vue";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  components: {
    PPagination,
    ViewDeniedModal,
  },
  props: ["transferredData", "itemData"],
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
          minWidth: 175,
        },
        {
          prop: "brand",
          label: "Brand",
          minWidth: 200,
        },
        {
          prop: "boxNumber",
          label: "Box Number",
          minWidth: 125,
        },
        {
          prop: "boxLabel",
          label: "Box Label",
          minWidth: 250,
        },
        {
          prop: "quantity",
          label: "Quantity",
          minWidth: 100,
        },
        {
          prop: "amount",
          label: "Amount",
          minWidth: 100,
        },
      ],
      headerCellStyle: {
        fontSize: "10px",
      },
      cellStyle: {
        fontSize: "12px !important",
      },
      viewImages: "",
    };
  },
  methods: {
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
      console.log("Transaction Number:", this.transferredData.plID);
      console.log("Company", this.transferredData.company);
      // location.href =
      //   "http://192.168.0.7:4040/#/pull-out/requisition-form?transactionID=" +
      //   this.transferredData.plID +
      //   "&company=" +
      //   this.transferredData.company;

      this.$router.push({
        path: "/pull-out/requisition-form",
        query: {
          transactionID: this.transferredData.plID,
          company: this.transferredData.company,
        },
      });
    },
  },
};
</script>
