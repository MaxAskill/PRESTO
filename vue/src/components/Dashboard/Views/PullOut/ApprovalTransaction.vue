<template>
  <div class="card">
    <div class="card-body margin-body">
      <div class="row">
        <div class="col-sm-2">
          <div class="form-group">
            <label class="font-weight-bold label-size">Transaction Number</label>
            <textarea rows="1" readonly class="form-control control-form">1002</textarea>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <label class="font-weight-bold label-size">Company</label>
            <textarea rows="1" readonly class="form-control control-form">
New Barbizon Fashion Incorporated (NBFI) </textarea
            >
          </div>
        </div>
        <div class="col-sm-2">
          <div class="form-group">
            <label class="font-weight-bold label-size">Chain Code</label>
            <textarea rows="1" readonly class="form-control control-form">
All Home </textarea
            >
          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <label class="font-weight-bold label-size">Branch Name</label>
            <textarea rows="1" readonly class="form-control control-form">
All Home Bataan </textarea
            >
          </div>
        </div>
        <div class="col-sm-2">
          <div class="form-group">
            <label class="font-weight-bold label-size">Transaction Type</label>
            <textarea rows="1" readonly class="form-control control-form">
Store Transfer </textarea
            >
          </div>
        </div>
        <!-- <div class="col-sm-3">
          <div class="form-group">
            <label class="font-weight-bold label-size">Created By</label>
            <textarea rows="1" readonly class="form-control control-form">
Roland Alavera
            </textarea>
          </div>
        </div> -->
      </div>
      <div class="row">
        <div class="col-sm-2">
          <div class="form-group">
            <label class="font-weight-bold label-size">Creation Date</label>
            <textarea rows="1" readonly class="form-control control-form">
July 02, 2023</textarea
            >
          </div>
        </div>

        <div class="col-sm-4 row">
          <div class="col-sm-6">
            <div class="form-group">
              <label class="font-weight-bold label-size">Reviewed Date</label>
              <textarea rows="1" readonly class="form-control control-form">
July 02, 2023</textarea
              >
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label class="font-weight-bold label-size">Reviewed By</label>
              <textarea rows="1" readonly class="form-control control-form">
Melvin Perez</textarea
              >
            </div>
          </div>
        </div>

        <div class="col-sm-4 row">
          <div class="col-sm-6">
            <div class="form-group">
              <label class="font-weight-bold label-size">Approved Date</label>
              <textarea rows="1" readonly class="form-control control-form">
July 02, 2023</textarea
              >
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label class="font-weight-bold label-size">Approved By</label>
              <textarea rows="1" readonly class="form-control control-form">
Melvin Perez</textarea
              >
            </div>
          </div>
        </div>

        <div class="col-sm-2">
          <div class="form-group">
            <label class="font-weight-bold label-size">Status</label>
            <textarea rows="1" readonly class="form-control control-form">
Melvin Perez</textarea
            >
          </div>
        </div>
      </div>

      <div class="row">
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
        </el-table>
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

      <div class="row">
        <label class="font-weight-bold label-size">Photo:</label>
      </div>

      <div class="text-center">
        <button
          type="submit"
          class="btn btn-default btn-fill btn-wd"
          @click.prevent="cancel"
        >
          Cancel
        </button>
        <button
          :disabled="isDisabledSubmit"
          type="submit"
          class="btn btn-warning btn-fill btn-wd"
          @click.prevent="submit"
        >
          Edit
        </button>
        <button
          type="submit"
          class="btn btn-danger btn-fill btn-wd"
          @click.prevent="cancel"
        >
          Denied
        </button>
        <button
          :disabled="isDisabledSubmit"
          type="submit"
          class="btn btn-info btn-fill btn-wd"
          @click.prevent="submit"
        >
          Approve
        </button>
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
        perPage: 10,
        currentPage: 1,
        perPageOptions: [5, 10, 25, 50],
        total: 0,
      },
      searchQuery: "",
      propsToSearch: ["chainCode", "branchCode", "branchName", "status"],
      tableColumns: [
        {
          prop: "plID",
          label: "Item Code",
          minWidth: 100,
        },
        {
          prop: "branchName",
          label: "Category/Brand",
          minWidth: 200,
        },
        {
          prop: "transactionType",
          label: "Box Number",
          minWidth: 150,
        },
        {
          prop: "date",
          label: "Box Label",
          minWidth: 100,
        },
        {
          prop: "time",
          label: "Quantity",
          minWidth: 100,
        },
        {
          prop: "name",
          label: "Amount",
          minWidth: 100,
        },
      ],
      tableData: [],
    };
  },
  methods: {
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
  padding: 0 !important;
  border: none !important;
  background-color: transparent !important;
  cursor: default !important;
  border-radius: 0 !important;
}

.label-size {
  font-size: 16px !important;
  color: black !important;
}

.margin-body {
  padding: 50px !important;
}
</style>
