<template>
  <div>
    <div class="row mx-1">
      <div class="col-2 px-1">
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
      <div class="col-2 px-1 whitespace-nowrap">
        <PButton
          type="primary"
          class="btn-margin"
          data-bs-toggle="modal"
          data-bs-target="#uploaditemmodal"
          >Add Item</PButton
        >
      </div>
      <div class="col-2 px-1 whitespace-nowrap">
        <PButton
          type="primary"
          class="btn-margin"
          data-bs-toggle="modal"
          data-bs-target="#uploaditemmodal"
          >Upload</PButton
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
        <el-table-column :min-width="70" class-name="td-actions" label="Actions">
          <template slot-scope="props">
            <p-button
              type="success"
              size="sm"
              icon
              data-bs-toggle="modal"
              data-bs-target="#edititemmodal"
              @click="editItem(props.row)"
            >
              <i class="fa fa-edit"></i>
            </p-button>
            <p-button
              type="danger"
              size="sm"
              icon
              @click="handleDelete(props.$index, props.row)"
            >
              <i class="fa fa-trash-o" aria-hidden="true"></i>
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
        class="pull-right"
        v-model="pagination.currentPage"
        :per-page="pagination.perPage"
        :total="pagination.total"
      >
      </p-pagination>
    </div>
    <UploadItemModal></UploadItemModal>
    <EditItemModal :itemDetail="selectedItem"></EditItemModal>
  </div>
</template>
<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";
import PButton from "../../../../UIComponents/Button.vue";
import PPagination from "../../../../UIComponents/Pagination.vue";
import axiosClient from "../../../../../axios";
import UploadItemModal from "./ModalMaintenance/UploadItemModal.vue";
import EditItemModal from "./ModalMaintenance/EditMaintenanceModal/EditItemModal.vue";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);

export default {
  components: {
    PButton,
    PPagination,
    UploadItemModal,
    EditItemModal,
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
      propsToSearch: [
        "itemNo",
        "itemDescription",
        "brandNames",
        "department",
        "subDepartment",
        "category",
        "subCategory",
      ],
      tableColumns: [
        {
          prop: "ItemNo",
          label: "Item Code",
          minWidth: 100,
        },
        {
          prop: "ItemDescription",
          label: "Item Description",
          minWidth: 180,
        },
        {
          prop: "brandNames",
          label: "Brand",
          minWidth: 70,
        },
        {
          prop: "Department",
          label: "Department",
          minWidth: 80,
        },
        {
          prop: "SubDepartment",
          label: "Sub-Department",
          minWidth: 100,
        },
        {
          prop: "Category",
          label: "Category",
          minWidth: 80,
        },
        {
          prop: "SubCategory",
          label: "Sub-Category",
          minWidth: 90,
        },
      ],
      tableData: [],
      selectedItem: {},
    };
  },
  methods: {
    editItem(itemDetail) {
      this.selectedItem = itemDetail;
    },
    fetchData() {
      axiosClient
        .get("/fetchItemsMaintenance", {
          params: {
            company: this.company,
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
