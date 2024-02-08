<template>
  <div class="card card-generator">
    <!-- <div class="card-header">
      <h4 class="title">Pull-Out Requisition Form</h4>
    </div> -->
    <div class="card-body">
      <div class="row">
        <div class="col-sm-6 text-center">
          <fg-input
            class="input-md"
            placeholder="Search"
            v-model="searchQuery"
            addon-right-icon="nc-icon nc-zoom-split"
          >
          </fg-input>
        </div>
        <div class="col-sm-1 text-center">
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
        <div class="col-sm-2">
          <p-button type="danger" outline @click="openModal()">
            Branch Deactivate
          </p-button>
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
        <el-table-column prop="city" label="CITY" width="130" />
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
    <div>
      <button
        class="bg-pink-500 text-white active:bg-pink-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
        type="button"
        v-on:click="toggleModal()"
      >
        Open large modal
      </button>
      <div
        v-if="showModal"
        class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex"
      >
        <div class="relative w-auto my-6 mx-auto max-w-6xl">
          <!--content-->
          <div
            class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none"
          >
            <!--header-->
            <div
              class="flex items-start justify-between p-5 border-b border-solid border-blueGray-200 rounded-t"
            >
              <h3 class="text-3xl font-semibold">Modal Title</h3>
              <button
                class="p-1 ml-auto bg-transparent border-0 text-black opacity-5 float-right text-3xl leading-none font-semibold outline-none focus:outline-none"
                v-on:click="toggleModal()"
              >
                <span
                  class="bg-transparent text-black opacity-5 h-6 w-6 text-2xl block outline-none focus:outline-none"
                >
                  ×
                </span>
              </button>
            </div>
            <!--body-->
            <div class="relative p-6 flex-auto">
              <p class="my-4 text-blueGray-500 text-lg leading-relaxed">
                I always felt like I could do anything. That’s the main thing people are
                controlled by! Thoughts- their perception of themselves! They're slowed
                down by their perception of themselves. If you're taught you can’t do
                anything, you won’t do anything. I was taught I could do everything.
              </p>
            </div>
            <!--footer-->
            <div
              class="flex items-center justify-end p-6 border-t border-solid border-blueGray-200 rounded-b"
            >
              <button
                class="text-red-500 bg-transparent border border-solid border-red-500 hover:bg-red-500 hover:text-white active:bg-red-600 font-bold uppercase text-sm px-6 py-3 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                type="button"
                v-on:click="toggleModal()"
              >
                Close
              </button>
              <button
                class="text-red-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                type="button"
                v-on:click="toggleModal()"
              >
                Save Changes
              </button>
            </div>
          </div>
        </div>
      </div>
      <div v-if="showModal" class="opacity-25 fixed inset-0 z-40 bg-black"></div>
    </div>
    <BranchDeactivationModal @closeModal="closeModal"></BranchDeactivationModal>
  </div>
</template>

<script>
import Vue from "vue";
import BranchDeactivationModal from "./Modal/BranchDeactivationModal.vue";
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
    BranchDeactivationModal,
  },
  data() {
    return {
      showModal: false,
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
  methods: {
    toggleModal: function () {
      this.showModal = !this.showModal;
    },
  },
};
</script>

<style scoped>
h3 {
  margin-bottom: 5%;
}
.card-generator {
  margin-top: 100px;
}

.btn {
  margin: 0px 0px;
}
</style>
