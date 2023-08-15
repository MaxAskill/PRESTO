<template>
  <div>
    <!-- <div class="row mx-2 justify-content-between">
      <div class="col-4 px-1">
        <fg-input
          class="input-md"
          placeholder="Search"
          v-model="searchQuery"
          addon-right-icon="nc-icon nc-zoom-split"
        >
        </fg-input>
      </div>
      <div class="col-3 row">
        <div class="col-6 pr">
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
        <div class="col-6 whitespace-nowrap">
          <PButton
            type="success"
            data-bs-target="#exportDenied"
            data-bs-toggle="modal"
            class="btn-margin"
          >
            Export Excel
          </PButton>
        </div>
      </div>
    </div> -->
    <div class="row mx-2">
      <el-table
        class="p-0"
        :data="queriedData"
        border
        style="width: 100%"
        :header-cell-style="headerCellStyle"
        :cell-style="cellStyle"
      >
        <!-- Index Column -->
        <el-table-column>
          <template slot="header" slot-scope="scope">
            <fg-input
              class="input-md"
              placeholder="Search"
              v-model="searchQuery"
              addon-right-icon="nc-icon nc-zoom-split"
              style="width: 50%"
            >
            </fg-input>
          </template>
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
        </el-table-column>
        <el-table-column fixed="right" width="100">
          <template slot="header" slot-scope="scope">
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
          </template>
          <el-table-column
            width="100"
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
                  data-bs-target="#deniedModal"
                  @click="openModal(props.row)"
                >
                  <i class="fa fa-file-text-o"></i>
                </p-button>
              </div>
            </template>
          </el-table-column>
        </el-table-column>
        <el-table-column fixed="right" width="150">
          <template slot="header" slot-scope="scope">
            <PButton
              type="success"
              data-bs-target="#exportDenied"
              data-bs-toggle="modal"
              class="p-0"
              style="height: 40px; width: 100%; font-size: 12px"
            >
              Export Excel
            </PButton>
          </template>
          <el-table-column
            width="150"
            class-name="td-actions"
            label="Check Box"
            header-align="center"
          >
            <template slot-scope="props">
              <div class="container d-flex justify-content-center">
                <p-checkbox
                  @click="clickExport"
                  :checked="false"
                  v-model="checkedBranch[props.row.plID]"
                  :value="props.row.plID"
                ></p-checkbox>
              </div>
            </template>
          </el-table-column>
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
    <denied-modal
      :transferredData="transferredData"
      :itemData="itemData"
      @closeModal="closeModal"
    >
    </denied-modal>
    <ExportDeniedModal
      :disableBtn="disableExportBtn"
      @exportTransfer="exportExcel($event)"
    ></ExportDeniedModal>
  </div>
</template>
<script>
import DeniedModal from "./ModalPullOut/DeniedModal.vue";
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";
import PButton from "../../../../UIComponents/Button.vue";
import PPagination from "../../../../UIComponents/Pagination.vue";
import axiosClient from "../../../../../axios";
import XLSX from "../../../../../../node_modules/xlsx/dist/xlsx.full.min.js";
import ExportDeniedModal from "./ModalPullOut/ExportDeniedModal.vue";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  components: {
    PButton,
    PPagination,
    DeniedModal,
    ExportDeniedModal,
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
      disableExportBtn: true,
      checkedBranch: [],
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
          minWidth: 150,
        },
        {
          prop: "branchName",
          label: "BRANCH NAME",
          minWidth: 200,
        },
        {
          prop: "transactionType",
          label: "TRANSACTION TYPE",
          minWidth: 170,
        },
        {
          prop: "date",
          label: "DATE",
          minWidth: 100,
        },
        {
          prop: "time",
          label: "TIME",
          minWidth: 90,
        },
      ],
      headerCellStyle: {
        fontSize: "10px",
      },
      cellStyle: {
        fontSize: "12px",
      },
      tableData: [],
      headerRow: [
        "Branch Name",
        "Brand",
        "Transaction Type",
        "Box Label",
        "Item Code",
        "Quantity",
        "Date",
        "Time",
      ],
    };
  },
  watch: {
    checkedBranch: {
      handler(newValue, oldValue) {
        this.removedUncheckedBranch();
      },
      deep: true,
    },
  },
  methods: {
    exportExcel(x) {
      var dataArray = "";
      const selectedItems = [];
      if (!x) {
        for (const item in this.checkedBranch) {
          if (this.checkedBranch[item]) {
            selectedItems.push(parseInt(item));
          }
        }
      } else {
        for (const item in this.tableData) {
          selectedItems.push(parseInt(this.tableData[item].plID));
        }
      }
      axiosClient
        .get("/fetchAllItemsRequest", {
          params: {
            company: sessionStorage.getItem("Company"),
            plID: selectedItems,
          },
        })
        .then((response) => {
          this.excelBranch = response.data;
          dataArray = this.excelBranch.map((obj) => [
            obj.branchName,
            obj.brand,
            obj.transactionType,
            obj.boxLabel,
            obj.itemCode,
            obj.quantity,
            obj.date,
            obj.time,
          ]);

          const currentDate = new Date();
          const options = { year: "numeric", month: "2-digit", day: "2-digit" };
          const currentDateString = currentDate
            .toLocaleDateString("en-PH", options)
            .replace(/\//g, "-");

          dataArray = [this.headerRow, ...dataArray];
          const workbook = XLSX.utils.book_new();
          const worksheet = XLSX.utils.aoa_to_sheet(dataArray);

          XLSX.utils.book_append_sheet(workbook, worksheet, "Raw Data");
          const excelBuffer = XLSX.write(workbook, { bookType: "xlsx", type: "array" });
          const blob = new Blob([excelBuffer], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
          });
          const url = window.URL.createObjectURL(blob);
          const link = document.createElement("a");
          link.href = url;
          link.download = currentDateString + "_RawData(Approved Pull-Out).xlsx";
          link.click();
          window.URL.revokeObjectURL(url);
        })
        .catch((error) => {
          console.error(error);
        });
    },
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
          // console.log("Success Item Response:", response.data);
          this.itemData = response.data[0];
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
        .get("/fetchPullOutRequestDenied", {
          params: {
            company: sessionStorage.getItem("Company"),
          },
        })
        .then((response) => {
          console.log("Pull Out Request Denied", response.data);
          this.tableData = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    removedUncheckedBranch() {
      let temp = false;
      for (const item in this.checkedBranch) {
        if (this.checkedBranch[item]) {
          temp = true;
        }
      }
      if (temp) this.disableExportBtn = false;
      else this.disableExportBtn = true;
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
.btn-margin {
  margin: 0px 0px;
}
.el-table-mod {
  padding-top: 10px !important;
  padding-bottom: 10px !important;
}
</style>
