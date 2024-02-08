<template>
  <div class="card card-draft">
    <div class="card-body">
      <!-- <div class="card-header">
        <h4 class="title">Pull-Out Draft</h4>
      </div> -->
      <div class="row mx-2 justify-content-between">
        <div class="col-4 pl-1 pr-0">
          <fg-input
            class="input-md"
            placeholder="Search"
            v-model="searchQuery"
            addon-right-icon="nc-icon nc-zoom-split"
          >
          </fg-input>
        </div>
        <div class="col-1 pl-0 pr-1">
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
      <div class="row mx-2">
        <el-table
          class="table-striped pad-tbl"
          :data="queriedData"
          border
          :header-cell-style="headerCellStyle"
          :cell-style="cellStyle"
          style="width: 100%"
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
            :min-width="120"
            class-name="td-actions"
            label="Actions"
            header-align="center"
          >
            <template slot-scope="props">
              <p-button
                type="success"
                size="sm"
                icon
                @click="handleEdit(props.$index, props.row)"
              >
                <i class="fa fa-edit"></i>
              </p-button>

              <el-popconfirm
                width="280"
                confirm-button-text="Confirm"
                cancel-button-text="Cancel"
                icon-color="#c45656"
                title="Are you sure you want to delete this draft?"
                @confirm="handleDelete(true, props.$index, props.row)"
                @cancel="handleDelete(false, props.$index, props.row)"
              >
                <p-button slot="reference" type="danger" size="sm" icon>
                  <i class="fa fa-times"></i>
                </p-button>
              </el-popconfirm>
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

import { Table, TableColumn, Select, Option, Popconfirm, Message } from "element-ui";
import PButton from "../../../../UIComponents/Button.vue";
import PPagination from "../../../../UIComponents/Pagination.vue";
import axiosClient from "../../../../../axios";
import linkName from "../../../../../linkName";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
Vue.use(Popconfirm);

export default {
  components: {
    PButton,
    PPagination,
  },
  mounted() {
    this.fetchData();
    if (window.resolveRouteChange) {
      window.resolveRouteChange();
      window.resolveRouteChange = null;
    }
    linkName.val = "Pull-Out Draft";
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
      propsToSearch: ["branchName", "transactionType", "date", "time"],
      tableColumns: [
        {
          prop: "branchName",
          label: "Branch",
          minWidth: 150,
        },
        {
          prop: "transactionType",
          label: "Transaction Type",
          minWidth: 200,
        },
        {
          prop: "date",
          label: "Creation Date",
          minWidth: 200,
        },
        {
          prop: "time",
          label: "Creation Time",
          minWidth: 200,
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
    fetchData() {
      axiosClient
        .get("/fetchCompanyDraft", {
          params: {
            company: sessionStorage.getItem("Company"),
            promoEmail: sessionStorage.getItem("Email"),
            userID: sessionStorage.getItem("UserID"),
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
      var tempTransactionID = this.convertToAlphanumeric("transactionID");
      var tempcompany = this.convertToAlphanumeric("company");
      const routeParams = {
        path: "/pull-out/requisition-form",
        query: {
          [tempTransactionID]: row.plID,
          [tempcompany]: this.convertToAlphanumeric(row.company),
        },
      };

      this.$router.push(routeParams);
      // location.href =
      //   "http://192.168.0.7:93/#/pull-out/requisition-form?" +
      //   tempTransactionID +
      //   "=" +
      //   row.plID +
      //   "&" +
      //   tempcompany +
      //   "=" +
      //   this.convertToAlphanumeric(row.company);
    },
    handleDelete(confirm, index, row) {
      if (confirm) {
        let indexToDelete = this.tableData.findIndex(
          (tableRow) => tableRow.plID === row.plID
        );
        if (indexToDelete >= 0) {
          this.tableData.splice(indexToDelete, 1);
        }

        axiosClient
          .post("/deleteDraft", {
            id: row.plID,
            company: row.company,
          })
          .then((response) => {})
          .catch((error) => {
            console.error(error);
          });

        Message({
          type: "success",
          message: "Your draft has been deleted.",
        });
      }
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

.card-draft {
  margin-top: 100px;
}

.el-popconfirm {
  background-color: #ffffff;
  padding: 3px;
  border-radius: 5px;
}
</style>
