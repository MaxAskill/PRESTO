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
        <el-table-column :min-width="80" class-name="td-actions" label="Position">
          <template slot-scope="props">
            <!-- <label>{{ props.row.position }}</label> -->

            <el-select
              class="table-select-box"
              size="large"
              v-model="props.row.position"
              @change="handleEditPosition(props.$index, props.row)"
            >
              <el-option
                v-for="position in positions"
                class="table-select-box"
                :value="position.position"
                :label="position.position"
                :key="position.position"
              >
              </el-option>
            </el-select>

            <!-- <p-button
              type="success"
              size="sm"
              icon
              @click="handleEdit(props.$index, props.row)"
            >
              <i class="fa fa-edit"></i>
            </p-button>
            <p-button
              type="danger"
              size="sm"
              icon
              @click="handleDelete(props.$index, props.row)"
            >
              <i class="fa fa-times"></i>
            </p-button> -->
          </template>
        </el-table-column>
        <el-table-column :min-width="100" class-name="td-actions" label="Status">
          <template slot-scope="props">
            <p-button
              v-if="props.row.status === 'Active'"
              type="success"
              size="sm"
              @click="handleEdit(props.$index, props.row)"
            >
              Active
            </p-button>
            <p-button
              v-else
              type="danger"
              size="sm"
              @click="handleEdit(props.$index, props.row)"
            >
              Inactive
            </p-button>
          </template>
        </el-table-column>
        <!-- <el-table-column :min-width="120" class-name="td-actions" label="">
          <template slot-scope="props">
            <p-button
              type="success"
              size="sm"
              icon
              @click="handleEdit(props.$index, props.row)"
            >
              <i class="fa fa-edit"></i>
            </p-button>
            <p-button
              type="danger"
              size="sm"
              icon
              @click="handleDelete(props.$index, props.row)"
            >
              <i class="fa fa-times"></i>
            </p-button>
          </template>
        </el-table-column> -->
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
      positions: [
        { position: "Admin" },
        { position: "Agent" },
        { position: "Approver" },
        { position: "User" },
      ],
      pagination: {
        perPage: 8,
        currentPage: 1,
        perPageOptions: [5, 10, 25, 50],
        total: 0,
      },
      searchQuery: "",
      propsToSearch: [
        "name",
        "email",
        "position",
        "status",
        "creation_date",
        "creation_time",
      ],
      tableColumns: [
        {
          prop: "name",
          label: "Name",
          minWidth: 100,
        },
        {
          prop: "email",
          label: "Email",
          minWidth: 200,
        },
        {
          prop: "creation_date",
          label: "Creation Date",
          minWidth: 70,
        },
        {
          prop: "updated_date",
          label: "Updated Date",
          minWidth: 70,
        },
      ],
      tableData: [],
    };
  },
  methods: {
    fetchData() {
      axiosClient
        .get("/fetchUsersMaintenance")
        .then((response) => {
          // console.log("Users: ", response.data);
          this.tableData = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    handleEdit(index, row) {
      // alert(`Your want to edit ${row.branchName}`);
      var tempStatus = "";
      if (row.status === "Active") {
        row.status = "Inactive";
        tempStatus = "Inactive";
      } else {
        row.status = "Active";
        tempStatus = "Active";
      }
      axiosClient
        .post("/updateUserAcc", {
          id: row.id,
          status: tempStatus,
          userID: sessionStorage.getItem("UserID"),
          position: row.position,
        })
        .then((response) => {
          // console.log("Success Update User Account");
        })
        .catch((error) => {
          console.error(error);
        });
    },
    handleEditPosition(index, row) {
      axiosClient
        .post("/updateUserAcc", {
          id: row.id,
          status: row.status,
          userID: sessionStorage.getItem("UserID"),
          position: row.position,
        })
        .then((response) => {
          // console.log("Success Update User Account");
        })
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
</style>
