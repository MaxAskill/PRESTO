<template>
  <div>
    <div class="row mx-2">
      <div class="col-2 pr">
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
      <div class="col-10 pl">
        <fg-input
          class="input-md"
          placeholder="Search"
          v-model="searchQuery"
          addon-right-icon="nc-icon nc-zoom-split"
        >
        </fg-input>
      </div>
    </div>
    <div class="row mx-2">
      <el-table class="table-striped" :data="queriedData" border style="width: 100%">
        <el-table-column
          v-for="column in tableColumns"
          :key="column.label"
          :min-width="column.minWidth"
          :prop="column.prop"
          :label="column.label"
        >
        </el-table-column>
        <el-table-column :width="120" class-name="td-actions" label="Details">
          <template slot-scope="props">
            <p-button
              type="info"
              size="sm"
              icon
              data-bs-toggle="modal"
              data-bs-target="#approvedModal"
              @click="openModal(props.row)"
            >
              <i class="fa fa-file-text-o"></i>
            </p-button>
          </template>
        </el-table-column>
        <el-table-column :width="120" class-name="td-actions" label="Check All">
          <template slot-scope="props">
            <!-- <p-button
              type="info"
              size="sm"
              icon
              @click="handleLike(props.$index, props.row)"
            >
              <i class="fa fa-file-text-o"></i>
            </p-button> -->
            <p-checkbox :checked="false"></p-checkbox>
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
    <ApprovedModal
      :transferredData="transferredData"
      :itemData="itemData"
      @closeModal="closeModal"
    >
    </ApprovedModal>
  </div>
</template>
<script>
import ApprovedModal from "./ModalPullOut/ApprovedModal.vue";
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
    ApprovedModal,
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
          label: "TRANSACTION ID",
          minWidth: 100,
        },
        {
          prop: "branchName",
          label: "BRANCH NAME",
          minWidth: 250,
        },
        {
          prop: "transactionType",
          label: "TRANSACTION TYPE",
          minWidth: 150,
        },
        {
          prop: "date",
          label: "DATE",
          minWidth: 100,
        },
        {
          prop: "time",
          label: "TIME",
          minWidth: 120,
        },
      ],
      tableData: [],
    };
  },
  methods: {
    openModal(data) {
      this.transferredData = data;
      console.log("Branch Data: ", this.transferredData.plID);
      axiosClient
        .get("/fetchPullOutRequestItem", {
          params: {
            company: sessionStorage.getItem("Company"),
            plID: this.transferredData.plID,
          },
        })
        .then((response) => {
          console.log("Success Item Response:", response.data);
          this.itemData = response.data;
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
        .get("/fetchPullOutRequestApproved", {
          params: {
            company: sessionStorage.getItem("Company"),
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
  },
};
</script>
<style lang="scss">
.el-table .td-actions {
  button.btn {
    margin-right: 5px;
  }
}
</style>
