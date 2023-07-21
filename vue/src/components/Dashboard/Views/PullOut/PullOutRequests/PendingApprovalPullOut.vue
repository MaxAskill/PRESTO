<template>
  <div>
    <div class="row mx-1 justify-content-between">
      <div class="col-4">
        <fg-input
          class="input-md"
          placeholder="Search"
          v-model="searchQuery"
          addon-right-icon="nc-icon nc-zoom-split"
        >
        </fg-input>
      </div>
      <div class="col-1">
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
        class="table-striped"
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
          class="el-table-mod"
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
          class-name="td-actions el-table-mod"
          label="Details"
          header-align="center"
        >
          <template slot-scope="props">
            <div class="container d-flex justify-content-center">
              <p-button
                type="info"
                size="sm"
                icon
                data-bs-toggle="modal"
                data-bs-target="#approvalModal"
                @click="openModal(props.row)"
              >
                <i class="nc-icon nc-single-copy-04"></i>
              </p-button>
            </div>
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
        class="pull-right"
        v-model="pagination.currentPage"
        :per-page="pagination.perPage"
        :total="pagination.total"
      >
      </p-pagination>
    </div>
    <ApprovalModal
      :transferredData="transferredData"
      :itemData="itemData"
      :listBoxLabel="listBoxLabel"
      :totalNumbers="totalNumbers"
      @closeModal="closeModal"
    ></ApprovalModal>
  </div>
</template>
<script>
import ApprovalModal from "./ModalPullOut/ApprovalModal.vue";
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";
import PPagination from "../../../../UIComponents/Pagination.vue";
import axiosClient from "../../../../../axios";
Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  components: {
    PPagination,
    ApprovalModal,
  },
  mounted() {
    this.fetchData();
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
      transferredData: "",
      itemData: "",
      pagination: {
        perPage: 5,
        currentPage: 1,
        perPageOptions: [5, 10, 25, 50],
        total: 0,
      },
      searchQuery: "",
      propsToSearch: ["plID", "branchName", "transactionType", "date", "time"],
      tableColumns: [
        {
          prop: "plID",
          label: "TRANSACTION NO.",
          minWidth: 50,
        },
        {
          prop: "branchName",
          label: "BRANCH NAME",
          minWidth: 200,
        },
        {
          prop: "transactionType",
          label: "TRANSACTION TYPE",
          minWidth: 200,
        },
        {
          prop: "date",
          label: "DATE",
          minWidth: 50,
        },
        {
          prop: "time",
          label: "TIME",
          minWidth: 50,
        },
      ],
      headerCellStyle: {
        fontSize: "10px",
      },
      cellStyle: {
        fontSize: "12px",
      },
      tableData: [],
      listBoxLabel: [],
      totalNumbers: [],
    };
  },
  methods: {
    openModal(data) {
      this.transferredData = data;

      axiosClient
        .get("/fetchPullOutRequestItem", {
          params: {
            company: sessionStorage.getItem("Company"),
            plID: this.transferredData.plID,
          },
        })
        .then((response) => {
          console.log("Success Item Response:", response.data);
          this.itemData = response.data[0];
          this.totalNumbers = response.data[1];

          this.listBoxLabel = [];
          this.itemData.forEach((obj) => {
            const index = this.listBoxLabel.findIndex(
              (savedObj) => savedObj.boxNumber === obj.boxNumber
            );
            if (index === -1) {
              this.listBoxLabel.push({
                boxNumber: obj.boxNumber,
                boxLabel: obj.boxLabel,
              });
              // console.log(`Object ${obj.boxNumber} saved.`);
            }
          });
        })
        .catch((error) => {
          console.error(error);
        });
    },
    closeModal() {
      this.transferredData = "";
    },
    fetchData() {
      axiosClient
        .get("/fetchPullOutRequestEndorsement", {
          params: {
            company: sessionStorage.getItem("Company"),
          },
        })
        .then((response) => {
          console.log("Pull Out Request Endorsement", response.data);
          this.tableData = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>
<style lang="scss">
// .el-table .td-actions {
//   button.btn {
//     margin-right: 5px;
//   }
// }
// .p-margin {
//   margin-bottom: 0px;
// }
// .pl {
//   padding-left: 4px;
//   padding-right: 0px;
// }
// .pr {
//   padding-left: 0px;
//   padding-right: 4px;
// }
.el-table-mod {
  padding-top: 10px !important;
  padding-bottom: 10px !important;
}
</style>
