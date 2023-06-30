<template>
  <div>
    <div class="row mx-1">
      <div class="col-12 px-1">
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
      </el-table>
    </div>
    <div class="d-flex justify-content-end pagination-info">
      <p class="category p-margin">
        Showing {{ from + 1 }} to {{ to }} of {{ total }} entries
      </p>
    </div>
    <div class="nav-center">
      <p-pagination
        class="pull-right"
        v-model="pagination.currentPage"
        :per-page="pagination.perPage"
        :total="pagination.total"
      >
      </p-pagination>
    </div>
  </div>
</template>
<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";
import PButton from "../../../../UIComponents/Button.vue";
import PPagination from "../../../../UIComponents/Pagination.vue";
import axiosClient from "../../../../../axios";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);

export default {
  components: {
    PButton,
    PPagination,
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
          let rowValue = row[key].toString();
          if (rowValue.includes && rowValue.includes(this.searchQuery)) {
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
      pagination: {
        perPage: 5,
        currentPage: 1,
        perPageOptions: [5, 10, 25, 50],
        total: 0,
      },
      searchQuery: "",
      propsToSearch: [
        "user_name",
        "action_type",
        "table_affected",
        "date",
        "time",
        "old_data",
        "new_data",
      ],
      tableColumns: [
        {
          prop: "name",
          label: "User Name",
          minWidth: 100,
        },
        {
          prop: "action_type",
          label: "Action",
          minWidth: 70,
        },
        {
          prop: "table_affected",
          label: "Table Affected",
          minWidth: 70,
        },
        {
          prop: "creation_date",
          label: "Created Date",
          minWidth: 70,
        },
        {
          prop: "time",
          label: "Created Time",
          minWidth: 70,
        },
        {
          prop: "old_data",
          label: "Old Data",
          minWidth: 200,
        },
        {
          prop: "new_data",
          label: "New Data",
          minWidth: 200,
        },
      ],
      tableData: [],
    };
  },
  methods: {
    fetchData() {
      axiosClient
        .get("/fetchTransactionLogs", {
          params: {
            company: sessionStorage.getItem("Company"),
          },
        })
        .then((response) => {
          this.tableData = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>
