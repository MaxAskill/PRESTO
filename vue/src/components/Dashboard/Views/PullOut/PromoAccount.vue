<template>
  <div class="card">
    <div class="card-body">
      <!-- <div class="card-header">
        <h4 class="title">Promodiser's Account</h4>
      </div> -->
      <div class="row mx-2">
        <div class="col-2 pl-0 pr-1">
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
        <div class="col-10 pl-1 pr-0">
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
          <el-table-column :min-width="80" class-name="td-actions" label="Status">
            <template slot-scope="props">
              <!-- <p-button
                v-if="props.row.status === 'Activated'"
                type="success"
                size="sm"
                @click="handleEdit(props.$index, props.row)"
              >
                Activated
              </p-button> -->
              <p-button
                type="info"
                size="sm"
                data-bs-toggle="modal"
                data-bs-target="#promoActivation"
                @click="handleEdit(props.$index, props.row)"
              >
                New Account
              </p-button>
              <!-- <p-button
                v-else-if="props.row.status === 'Deactivated'"
                type="warning"
                size="sm"
                @click="handleEdit(props.$index, props.row)"
              >
                New Account
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
    </div>
    <PromoActivation :promoData="promoData"> </PromoActivation>
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
import linkName from "../../../../linkName";

import PromoActivation from "./PromoActivation.vue";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);

export default {
  components: {
    PButton,
    PPagination,
    PromoActivation,
  },
  mounted() {
    this.fetchData();
    if (window.resolveRouteChange) {
      window.resolveRouteChange();
      window.resolveRouteChange = null;
    }
    linkName.val = "Promodiser's Account";
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
      pagination: {
        perPage: 5,
        currentPage: 1,
        perPageOptions: [5, 10, 25, 50],
        total: 0,
      },
      searchQuery: "",
      propsToSearch: ["name", "email", "branchName", "company", "chainCode", "date"],
      tableColumns: [
        {
          prop: "name",
          label: "Full Name",
          minWidth: 100,
        },
        {
          prop: "email",
          label: "Email",
          minWidth: 180,
        },
        {
          prop: "company",
          label: "Company",
          minWidth: 120,
        },
        {
          prop: "chainCode",
          label: "Chain Code",
          minWidth: 80,
        },
        {
          prop: "branchName",
          label: "Branch Name",
          minWidth: 120,
        },
        {
          prop: "date",
          label: "Date Registered",
          minWidth: 80,
        },
      ],
      tableData: [],
      promoData: {},
    };
  },
  methods: {
    denied(row) {
      location.href =
        "http://192.168.0.7:4040/#/pull-out/requisition-form?transactionID=" +
        row.plID +
        "&company=" +
        row.company;
    },
    fetchData() {
      axiosClient
        .get("/usersMaintenance")
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
      if (row.status === "Activated") {
        row.status = "Deactivated";
        tempStatus = "Deactivated";
      } else if (row.status === "New Account") {
        row.status = "Activated";
        tempStatus = "Activated";
      }

      this.promoData = row;
      console.log("Promo Data:", this.promoData);
      // let promoActivationModal = new bootstrap.Modal("#promoActivation");
      // promoActivationModal.show();

      // axiosClient
      //   .post("/updateBranch", {
      //     company: this.company,
      //     id: row.id,
      //     status: tempStatus,
      //     userID: sessionStorage.getItem("UserID"),
      //   })
      //   .then((response) => {
      //     console.log("Success Update Branch:", response.data);
      //   })
      //   .catch((error) => {
      //     console.error(error);
      //   });
      // alert(`Your want to edit ${row.status}`);
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
</style>
