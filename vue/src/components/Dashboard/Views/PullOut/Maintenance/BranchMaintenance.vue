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
      <div class="col-6">
        <fg-input
          class="input-md"
          placeholder="Search"
          v-model="searchQuery"
          addon-right-icon="nc-icon nc-zoom-split"
        >
        </fg-input>
      </div>
      <div class="col-2 first-btn whitespace-nowrap">
        <!-- <button type="primary" class="btn">Add Branch</button> -->
        <PButton
          type="primary"
          class="btn-margin"
          data-bs-toggle="modal"
          data-bs-target="#addbranchmodal"
          >Add Branch</PButton
        >
      </div>
      <div class="col-1 px-1">
        <p-button
          type="info"
          class="btn-margin"
          data-bs-toggle="modal"
          data-bs-target="#uploadbranchmodal"
          >Upload</p-button
        >
      </div>
    </div>
    <div class="row mx-2">
      <el-table
        class="table-striped pad-tbl"
        :data="queriedData"
        border
        style="width: 100%"
      >
        <el-table-column
          v-for="column in tableColumns"
          :key="column.label"
          :min-width="column.minWidth"
          :prop="column.prop"
          :label="column.label"
        >
        </el-table-column>
        <el-table-column :min-width="70" class-name="td-actions" label="Status">
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
            <!-- <p-button
              type="danger"
              size="sm"
              icon
              @click="handleDelete(props.$index, props.row)"
            >
              <i class="fa fa-times"></i>
            </p-button> -->
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
    <AddBranchModal :company="company" :chainCodeList="chainCodeList"></AddBranchModal>
    <UploadBranchModal></UploadBranchModal>
  </div>
</template>
<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";
import PButton from "../../../../UIComponents/Button.vue";
import PPagination from "../../../../UIComponents/Pagination.vue";
import axiosClient from "../../../../../axios";
import PSwitch from "src/components/UIComponents/Switch.vue";
import AddBranchModal from "../Maintenance/ModalMaintenance/AddBranchModal.vue";
import UploadBranchModal from "./ModalMaintenance/UploadBranchModal.vue";
import axios from "axios";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);

export default {
  components: {
    PButton,
    PPagination,
    PSwitch,
    AddBranchModal,
    UploadBranchModal,
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
      companyType: "NBFI",
      chainCodeList: "",
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
          prop: "chainCode",
          label: "Chain Name",
          minWidth: 100,
        },
        {
          prop: "branchCode",
          label: "Branch Code",
          minWidth: 100,
        },
        {
          prop: "branchName",
          label: "Branch Name",
          minWidth: 250,
        },
      ],
      tableData: [],
    };
  },
  methods: {
    fetchData() {
      axiosClient
        .get("/fetchBranchMaintenance", {
          params: {
            company: this.company,
          },
        })
        .then((response) => {
          this.tableData = response.data;

          // Remove duplicates and sort based on 'id' property
          this.chainCodeList = [
            ...new Map(this.tableData.map((item) => [item.chainCode, item])).values(),
          ];

          // Sort the unique items based on 'id' property
          this.chainCodeList.sort((a, b) => a.chainCode - b.chainCode);
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
        .then((response) => {
          // console.log("Success Update Branch:", response.data);
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
.el-select-w {
  width: 100%;
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
.first-btn {
  padding-left: 4px;
  padding-right: 12px;
}
</style>
