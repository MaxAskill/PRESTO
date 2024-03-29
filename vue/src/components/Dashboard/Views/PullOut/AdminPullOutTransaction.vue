<template>
  <div class="card card-transactions">
    <div class="card-body">
      <div class="row mx-2 justify-content-between">
        <div class="col-4">
          <fg-input
            class="input-md"
            placeholder="Search"
            v-model="searchQuery"
            addon-right-icon="nc-icon nc-zoom-split"
          >
          </fg-input>
        </div>
        <div class="col-3 row">
          <div class="col-6">
            <el-select
              class="select-default el-select-w"
              placeholder="Select Company"
              v-model="company"
              @change="fetchData"
            >
              <el-option class="select-default" value="EPC" label="EPC">EPC</el-option>
              <el-option class="select-default" value="NBFI" label="NBFI">NBFI</el-option>
            </el-select>
          </div>
          <div class="col-6">
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
          </div>
        </div>
      </div>
      <div class="row mx-2">
        <el-table
          class="table-striped pad-tbl"
          :data="queriedData"
          border
          style="width: 100%"
          :header-cell-style="headerCellStyle"
          :cell-style="cellStyle"
        >
          <!-- Index Column -->
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
            :min-width="column.minWidth"
            :prop="column.prop"
            :label="column.label"
            header-align="center"
          >
          </el-table-column>
          <el-table-column
            :min-width="60"
            class-name="td-actions"
            label="Status"
            header-align="center"
          >
            <template #default="props">
              <badge
                v-if="props.row.status === 'approved'"
                slot="header"
                type="success"
                >{{ props.row.status }}</badge
              >
              <badge
                v-else-if="props.row.status === 'For Review'"
                slot="header"
                type="warning"
                >{{ props.row.status }}</badge
              >
              <badge
                v-else-if="props.row.status === 'For Approval'"
                slot="header"
                type="primary"
                >{{ props.row.status }}</badge
              >

              <button v-else class="denied-btn" @click="denied(props.row)">
                {{ props.row.status }}
              </button>
            </template>
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
</template>
<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";
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
  mounted() {
    this.fetchData();
    if (window.resolveRouteChange) {
      window.resolveRouteChange();
      window.resolveRouteChange = null;
    }
    linkName.val = "All Pull-Out Requests";
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
        perPage: 5,
        currentPage: 1,
        perPageOptions: [5, 10, 25, 50],
        total: 0,
      },
      searchQuery: "",
      propsToSearch: ["chainCode", "branchCode", "branchName", "status"],
      tableColumns: [
        {
          prop: "plID",
          label: "Transaction NO.",
          minWidth: 50,
        },
        {
          prop: "branchName",
          label: "Branch Name",
          minWidth: 200,
        },
        {
          prop: "transactionType",
          label: "Transaction Type",
          minWidth: 200,
        },
        {
          prop: "date",
          label: "Creation Date",
          minWidth: 50,
        },
        {
          prop: "time",
          label: "Creation Time",
          minWidth: 50,
        },
        {
          prop: "name",
          label: "Name",
          minWidth: 100,
        },
      ],
      headerCellStyle: {
        fontSize: "10px",
      },
      cellStyle: {
        fontSize: "12px",
      },
      tableData: [],
    };
  },
  methods: {
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
        .get("/fetchUserRequestTransactionListAdmin", {
          params: {
            company: this.company,
          },
        })
        .then((response) => {
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
.pad-tbl {
  padding: 0px 0px;
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
