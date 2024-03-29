<template>
  <div>
    <div class="card card-transactions">
      <div class="card-body">
        <div class="row mx-2">
          <el-table
            class="p-0"
            :data="queriedData"
            border
            max-height="650"
            :header-cell-style="headerCellStyle"
            :cell-style="cellStyle"
            style="width: 100%"
          >
            <!-- Index Column -->
            <el-table-column>
              <template slot="header" slot-scope="scope">
                <fg-input
                  class="input-md"
                  placeholder="Search"
                  v-model="searchQuery"
                  addon-right-icon="nc-icon nc-zoom-split"
                  style="width: 50%"
                >
                </fg-input>
              </template>
              <el-table-column label="" class="el-table-mod" width="40">
                <template slot-scope="scope">
                  <span>{{
                    (pagination.currentPage - 1) * pagination.perPage + scope.$index + 1
                  }}</span>
                </template>
              </el-table-column>

              <el-table-column
                v-for="column in tableColumns"
                :key="column.label"
                :prop="column.prop"
                :label="column.label"
                :min-width="column.minWidth"
                header-align="center"
              >
              </el-table-column>
            </el-table-column>

            <el-table-column fixed="right" width="140">
              <template slot="header" slot-scope="scope">
                <el-select
                  class="select-default"
                  v-model="pagination.perPage"
                  placeholder="Per page"
                >
                  <el-option
                    class="select-default"
                    v-for="item in pagination.perPageOptions"
                    :key="item"
                    :label="item"
                    :value="item"
                  >
                  </el-option>
                </el-select>
              </template>
              <el-table-column
                width="140"
                class-name="td-actions"
                label="Status"
                header-align="center"
                fixed="right"
              >
                <template slot-scope="props">
                  <p-button
                    v-if="props.row.status === 'approved'"
                    type="success"
                    size="sm"
                    data-bs-toggle="modal"
                    data-bs-target="#pullOutTransactionModal"
                    @click="transferData(props.row)"
                  >
                    Approved
                  </p-button>
                  <p-button
                    v-else-if="props.row.status === 'unprocessed'"
                    type="warning"
                    size="sm"
                    data-bs-toggle="modal"
                    data-bs-target="#pullOutTransactionModal"
                    @click="transferData(props.row)"
                  >
                    For Review
                  </p-button>
                  <p-button
                    v-else-if="props.row.status === 'endorsement'"
                    type="primary"
                    size="sm"
                    data-bs-toggle="modal"
                    data-bs-target="#pullOutTransactionModal"
                    @click="transferData(props.row)"
                  >
                    For Approval
                  </p-button>
                  <p-button
                    v-else
                    type="danger"
                    size="sm"
                    data-bs-toggle="modal"
                    data-bs-target="#pullOutTransactionModal"
                    @click="transferData(props.row)"
                  >
                    Denied
                  </p-button>
                </template>
              </el-table-column>
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
            class="pull-center"
            v-model="pagination.currentPage"
            :per-page="pagination.perPage"
            :total="pagination.total"
          >
          </p-pagination>
        </div>
      </div>
    </div>
    <PullOutTransactionModal
      :transactionData="transactionData"
      :itemData="itemData"
      :viewImages="viewImages"
    ></PullOutTransactionModal>
  </div>
</template>
<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";
import PButton from "../../../UIComponents/Button.vue";
import PPagination from "../../../UIComponents/Pagination.vue";
import axiosClient from "../../../../axios";
import { Badge } from "src/components/UIComponents";
import linkName from "../../../../linkName";

import PullOutTransactionModal from "./PullOutTransactionModal.vue";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);

export default {
  components: {
    PButton,
    PPagination,
    Badge,
    PullOutTransactionModal,
  },
  mounted() {
    this.fetchData();
    if (window.resolveRouteChange) {
      window.resolveRouteChange();
      window.resolveRouteChange = null;
    }
    linkName.val = "Pull-Out Transactions";
  },
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
      propsToSearch: ["plID", "branchName", "transactionType", "date", "time"],
      tableColumns: [
        {
          prop: "plID",
          label: "Transaction No.",
          minWidth: 150,
        },
        {
          prop: "branchName",
          label: "Branch",
          minWidth: 200,
        },
        {
          prop: "transactionType",
          label: "Transaction Type",
          minWidth: 170,
        },
        {
          prop: "date",
          label: "Creation Date",
          minWidth: 130,
        },
        {
          prop: "time",
          label: "Creation Time",
          minWidth: 120,
        },
      ],
      headerCellStyle: {
        fontSize: "10px",
      },
      cellStyle: {
        fontSize: "12px !important",
      },
      tableData: [],
      transactionID: "",
      transactionData: {
        company: "",
        chainCode: "",
        branchName: "",
        transactionNumber: "",
        transactionType: "",
        status: "",
        createdBy: "",
        createdDate: "",
        reviewedBy: "",
        reviewedDate: "",
        approvedBy: "",
        approvedDate: "",
      },
      itemData: [],
      viewImages: [],
    };
  },
  methods: {
    transferData(row) {
      this.transactionData.company = row.company;
      this.transactionData.chainCode = row.chainCode;
      this.transactionData.branchName = row.branchName;
      this.transactionData.transactionNumber = row.plID;
      this.transactionData.status = row.status;
      this.transactionData.createdBy = row.createdBy;
      this.transactionData.createdDate = row.date;
      this.transactionData.reviewedBy = row.reviewedBy;
      this.transactionData.reviewedDate = row.reviewedDate;
      this.transactionData.transactionType = row.transactionType;
      this.transactionData.approvedBy = row.approvedBy;
      this.transactionData.approvedDate = row.approvedDate;

      var company1 = row.company.split("(")[1];
      var company = company1.split(")")[0];

      axiosClient
        .get("/fetchPullOutRequestItem", {
          params: {
            plID: row.plID,
            company: company,
          },
        })
        .then((response) => {
          this.itemData = response.data[0];
        })
        .catch((error) => {
          console.error(error);
        });

      axiosClient
        .get("/fetchImageBranchDoc", {
          params: {
            transactionID: row.plID,
            company: row.company,
          },
        })
        .then((response) => {
          this.viewImages = response.data.imagePaths;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    denied(row) {
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
        .get("/fetchUserRequestTransactionList", {
          params: {
            company: sessionStorage.getItem("Company"),
            promoEmail: sessionStorage.getItem("Email"),
          },
        })
        .then((response) => {
          this.tableData = response.data;

          this.tableData = response.data.map((item, index) => {
            return { index: index + 1, ...item };
          });
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

      axiosClient
        .post("/updateBranch", {
          company: this.company,
          id: row.id,
          status: tempStatus,
          userID: sessionStorage.getItem("UserID"),
        })
        .then((response) => {})
        .catch((error) => {
          console.error(error);
        });
    },
    handleDelete(index, row) {
      let indexToDelete = this.tableData.findIndex((tableRow) => tableRow.id === row.id);
      if (indexToDelete >= 0) {
        this.tableData.splice(indexToDelete, 1);
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

.nav-center {
  display: flex;
  justify-content: center; /* Center horizontally */
  align-items: center; /* Center vertically */
}
.btn-margin {
  margin: 0px 0px;
}
.p-margin {
  margin-bottom: 0px;
}
.el-select-w {
  width: 91%;
  margin-right: 10px;
}
.first-btn {
  padding-left: 4px;
  padding-right: 12px;
}
.last-btn {
  margin-left: 10px;
}
.denied-btn {
  background-color: #ef8157;
  font-size: 10px;
  border-radius: 10px;
  border: none;
  text-transform: uppercase;
  color: white;
}

.card-transactions {
  margin-top: 100px;
}
</style>
