<template>
  <div
    class="modal fade"
    id="pullOutTransactionModal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="pullOutTransactionModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <h6 class="modal-title">Pull Out Transaction</h6>
          <button class="delete-buttons">
            <i class="nc-icon nc-simple-remove font-weight-bold"></i>
          </button> -->

          <div class="row">
            <div class="col d-flex justify-content-center">
              <h6 class="modal-title">Pull Out Transaction</h6>
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
                <label class="label-size d-flex justify-content-center align-items-center"
                  >Transaction Number</label
                >
                <p
                  rows="1"
                  readonly
                  class="form-control font-weight-bold control-form text-center font-weight-bold"
                >
                  {{ transactionData.transactionNumber }}
                </p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="label-size d-flex justify-content-center align-items-center"
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
                <label class="label-size d-flex justify-content-center align-items-center"
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
                <label class="label-size d-flex justify-content-center align-items-center"
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
                <label class="label-size d-flex justify-content-center align-items-center"
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
                <label class="label-size d-flex justify-content-center align-items-center"
                  >Date Created</label
                >
                <p
                  rows="1"
                  readonly
                  class="form-control control-form text-center font-weight-bold"
                  style="text-transform: capitalize"
                >
                  {{ transactionData.createdDate }}
                </p>
              </div>
            </div>
          </div>

          <div class="row text-center">
            <div class="col-sm-3">
              <div class="form-group">
                <label class="label-size d-flex justify-content-center align-items-center"
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
                <label class="label-size d-flex justify-content-center align-items-center"
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
                <label class="label-size d-flex justify-content-center align-items-center"
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
                <label class="label-size d-flex justify-content-center align-items-center"
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
                <label class="label-size d-flex justify-content-center align-items-center"
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
                <label class="label-size d-flex justify-content-center align-items-center"
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

          <div class="row pt-5">
            <el-table
              class="table-striped pad-tbl"
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
              >
              </el-table-column>
            </el-table>
            <div class="d-flex justify-content-end pagination-info">
              <p class="category p-margin">
                Showing {{ from + 1 }} to {{ to }} of {{ total }} entries
              </p>
            </div>
            <!-- <div class="d-flex justify-content-center">
              <p-pagination
                class="pull-center"
                v-model="pagination.currentPage"
                :per-page="pagination.perPage"
                :total="pagination.total"
              >
              </p-pagination>
            </div> -->
          </div>

          <div class="row">
            <label class="font-weight-bold label-size">Photo:</label>
            <div class="responsive" v-for="(path, index) in viewImages" :key="index">
              <div class="gallery">
                <a target="_blank" :href="path">
                  <img :src="path" :alt="'Image ' + (index + 1)" />
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <!-- <button class="btn btn-primary" data-bs-dismiss="modal">Okay</button> -->
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Vue from "vue";
// import {
//   Collapse,
//   CollapseItem,
//   Tabs,
//   TabPane,
//   Card,
//   Button,
// } from "src/components/UIComponents";
import { Table, TableColumn, Select, Option } from "element-ui";
// import PButton from "../../../../UIComponents/Button.vue";
import PButton from "../../../UIComponents/Button.vue";
import PPagination from "../../../UIComponents/Pagination.vue";
import axiosClient from "../../../../axios";
import { Badge } from "src/components/UIComponents";
import linkName from "../../../../linkName";
Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);

export default {
  components: {
    PButton,
    PPagination,
    Badge,
  },
  watch: {
    itemData: {
      handler() {
        this.transfer();
      },
      deep: true,
    },
  },
  props: ["transactionData", "itemData", "viewImages"],
  computed: {
    pagedData() {
      return this.tableData.slice(this.from, this.to);
    },
    /***
     * Searches through table data and returns a paginated array.
     * Note that this should not be used for table with a lot of data as it might be slow!
     * Do the search and the pagination on the server and display the data retrieved from server instead.
     * @returns {computed.pagedData}
     */
    queriedData() {
      if (!this.searchQuery) {
        this.pagination.total = this.tableData.length;
        return this.pagedData;
      }
      let result = this.tableData.filter((row) => {
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
      this.pagination.total = this.tableData.length;
      return this.tableData.length;
    },
  },
  data() {
    return {
      company: "NBFI",
      pagination: {
        perPage: 10,
        currentPage: 1,
        perPageOptions: [5, 10, 25, 50],
        total: 0,
      },
      searchQuery: "",
      propsToSearch: ["chainCode", "branchCode", "branchName", "status"],
      tableColumns: [
        {
          prop: "itemCode",
          label: "Item Code",
          minWidth: 100,
        },
        {
          prop: "brand",
          label: "Category/Brand",
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
          minWidth: 100,
        },
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
  mounted() {
    // this.fetchData();
    if (window.resolveRouteChange) {
      window.resolveRouteChange();
      window.resolveRouteChange = null;
    }
    linkName.val = "All Pull-Out Requests";
  },
  methods: {
    transfer() {
      console.log("Transfer");
      this.tableData = this.itemData;
    },
    denied(row) {
      console.log("Company", row);
      // location.href =
      //   "http://192.168.0.7:4040/#/pull-out/requisition-form?transactionID=" +
      //   row.plID +
      //   "&company=" +
      //   row.company;

      this.$router.push({
        path: "/pull-out/requisition-form",
        query: {
          transactionID: row.plID,
          company: row.company,
        },
      });
    },
    fetchData() {
      axiosClient
        .get("/fetchUserRequestTransactionListAdmin", {
          params: {
            company: this.company,
          },
        })
        .then((response) => {
          console.log("Pull Out Request", response.data);
          this.tableData = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    handleEdit(index, row) {
      var tempStatus = "";
      if (row.status === "Active") {
        row.status = "Inactive";
        tempStatus = "Inactive";
      } else {
        row.status = "Active";
        tempStatus = "Active";
      }

      console.log("Branch ID:", row.id);
      console.log("BranchCode:", row.branchCode);

      axiosClient
        .post("/updateBranch", {
          company: this.company,
          id: row.id,
          status: tempStatus,
          userID: sessionStorage.getItem("UserID"),
        })
        .then((response) => {
          console.log("Success Update Branch:", response.data);
        })
        .catch((error) => {
          console.error(error);
        });
      // alert(`Your want to edit ${row.status}`);
    },
    handleDelete(index, row) {
      console.log("ID:", row.id, row.company);

      let indexToDelete = this.tableData.findIndex((tableRow) => tableRow.id === row.id);
      if (indexToDelete >= 0) {
        this.tableData.splice(indexToDelete, 1);
      }
    },
  },
};
</script>

<style lang="scss">
.control-form {
  font-size: 14px !important;
  padding: 0 !important;
  border: none !important;
  background-color: transparent !important;
  cursor: default !important;
  border-radius: 0 !important;
}

.label-size {
  font-size: 12px !important;
  color: black !important;
}

.margin-body {
  padding: 50px !important;
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
</style>
