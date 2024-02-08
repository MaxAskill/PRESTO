<template>
  <div class="card card-generator">
    <!-- <div class="card-header">
      <h4 class="title">Pull-Out Requisition Form</h4>
    </div> -->
    <div class="card-body">
      <div class="row">
        <div class="col-sm-6">
          <fg-input
            class="input-md"
            placeholder="Search"
            v-model="searchQuery"
            addon-right-icon="nc-icon nc-zoom-split"
          >
          </fg-input>
        </div>
        <div class="col-sm-3">
          <el-select
            class="select-default"
            v-model="pagination.perPage"
            placeholder="Per page"
            style="width: 30%"
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
        <div class="col-sm-3"></div>
      </div>
      <el-table
        :data="tableData"
        border
        max-height="650"
        :header-cell-style="headerCellStyle"
        :cell-style="cellStyle"
      >
        <el-table-column prop="bpCode" label="BP CODE" width="120" />
        <el-table-column prop="businessUnit" label="BUSINESS UNIT" width="120" />
        <el-table-column prop="region" label="REGION" width="100" />
        <el-table-column prop="city" label="CITY" width="150" />
        <el-table-column prop="storeName" label="STORE NAME" width="300" />
        <el-table-column prop="mallName" label="MALL NAME" width="300" />
        <el-table-column prop="mallAddress" label="MALL ADDRESS" width="300" />
        <el-table-column prop="status" label="STATUS" />
        <el-table-column label="BRANDS">
          <template #default="scope">
            <p-button type="info" size="sm">
              <i class="fa fa-list"></i>
            </p-button>
          </template>
        </el-table-column>
      </el-table>
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
    </div>
  </div>
</template>

<script>
import Vue from "vue";
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
  data() {
    return {
      headerCellStyle: {
        fontSize: "12px",
      },
      cellStyle: {
        fontSize: "12px",
      },
      pagination: {
        perPage: 5,
        currentPage: 1,
        perPageOptions: [5, 10, 25, 50],
        total: 0,
      },
      searchQuery: "",
      tableData: [
        {
          bpCode: "C-RDS034",
          businessUnit: "NBFI",
          storeName: "ROBINSONS DEPT STORE RP MALOLOS",
          mallName: "Robinsons Place Malolos, Robinsons Department Store",
          mallAddress:
            "Levels 2 & 3 Robinsons Place Malolos Mac Arthur Highway Sumapang Matanda Malolos Bulacan",
          region: "III",
          city: "Malolos",
          status: "STOP-CST	",
        },
        {
          bpCode: "C-RDS017	",
          businessUnit: "NBFI",
          storeName: "ROBINSONS DEPT STORE RP ANTIPOLO",
          mallName: "Robinsons Place Antipolo, Department Store",
          mallAddress: "Robinsons Department Store Antipolo Sumulong Highway",
          region: "IV-A",
          city: "Antipolo",
          status: "Active",
        },
        {
          bpCode: "C-RDS034",
          businessUnit: "NBFI",
          storeName: "SM DEPT STORE SAN MATEO",
          mallName: "SM City San Mateo, The SM Store",
          mallAddress: "SM City San Mateo, Gen. A. Luna Avenue, Brgy. Ampid 1, 1850",
          region: "IV-A	",
          city: "San Mateo",
          status: "Active",
        },
      ],
    };
  },
};
</script>

<style>
h3 {
  margin-bottom: 5%;
}
.card-generator {
  margin-top: 100px;
}
</style>
