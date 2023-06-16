<template>
  <div>
    <div class="row mx-1">
      <div class="col-3 px-1">
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
      <div class="col-7">
        <fg-input
          class="input-md"
          placeholder="Search"
          v-model="searchQuery"
          addon-right-icon="nc-icon nc-zoom-split"
        >
        </fg-input>
      </div>
      <div class="col-2 px-1 whitespace-nowrap">
        <PButton
          type="primary"
          class="btn-margin"
          data-bs-toggle="modal"
          data-bs-target="#addbrandmodal"
          >Add Brand</PButton
        >
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
        <el-table-column :width="200" class-name="td-actions" label="Status">
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
      </el-table>
    </div>
    <div class="d-flex justify-content-end pagination-info">
      <p class="category p-margin">
        Showing {{ from + 1 }} to {{ to }} of {{ total }} entries
      </p>
    </div>
    <div class="nav-center">
      <p-pagination
        class="pull-center"
        v-model="pagination.currentPage"
        :per-page="pagination.perPage"
        :total="pagination.total"
      >
      </p-pagination>
    </div>
    <AddBrandModal :transferredData="company"></AddBrandModal>
  </div>
</template>
<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";
import PButton from "../../../../UIComponents/Button.vue";
import PPagination from "../../../../UIComponents/Pagination.vue";
import axiosClient from "../../../../../axios";
import AddBrandModal from "../Maintenance/ModalMaintenance/AddBrandModal.vue";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);

export default {
  components: {
    PButton,
    PPagination,
    AddBrandModal,
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
      company: "NBFI",
      pagination: {
        perPage: 10,
        currentPage: 1,
        perPageOptions: [5, 10, 25, 50],
        total: 0,
      },
      searchQuery: "",
      propsToSearch: ["id", "brandNames", "status"],
      tableColumns: [
        {
          prop: "id",
          label: "ID",
          minWidth: 50,
        },
        {
          prop: "brandNames",
          label: "Brand Name",
          minWidth: 250,
        },
      ],
      tableData: [],
    };
  },
  methods: {
    fetchData() {
      axiosClient
        .get("/fetchBrandMaintenance", {
          params: {
            company: this.company,
          },
        })
        .then((response) => {
          console.log("Brand Response:", response.data);
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
        tempStatus = "N";
      } else {
        row.status = "Active";
        tempStatus = "Y";
      }

      axiosClient
        .post("/updateBrand", {
          id: row.id,
          status: tempStatus,
          userID: sessionStorage.getItem("UserID"),
          company: this.company,
        })
        .then((response) => {
          console.log("Success Update Brand");
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

.nav-center {
  display: flex;
  justify-content: center; /* Center horizontally */
  align-items: center; /* Center vertically */
}
.btn-margin {
  margin: 0px 0px;
  width: 100%;
}
.p-margin {
  margin-bottom: 0px;
  margin-right: 15px;
}
.el-select-w {
  width: 100%;
}
</style>
