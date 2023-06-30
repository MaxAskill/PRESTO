<template>
  <div
    class="modal fade"
    id="deniedModal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="deniedModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="deniedModalLabel">
            DENIED: {{ transferredData.branchName }}
          </h1>
          <fg-input
            class="input-md"
            placeholder="Search"
            v-model="searchQuery"
            addon-right-icon="nc-icon nc-zoom-split"
          >
          </fg-input>
        </div>
        <div class="modal-body">
          <div class="col-sm-12 mt-2">
            <el-table
              class="table-striped"
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
          </div>
        </div>
        <div class="modal-footer mrgn-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Close
          </button>
          <button
            type="submit"
            class="btn btn-warning btn-fill btn-wd"
            @click="submit"
            data-bs-dismiss="modal"
          >
            Edit
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- </div> -->
</template>
<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";
import axiosClient from "../../../../../../axios";
import { createPopper } from "@popperjs/core/lib/popper-lite.js";
import PPagination from "../../../../../UIComponents/Pagination.vue";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  components: {
    PPagination,
  },
  props: ["transferredData", "itemData"],
  watch: {
    transferredData(newValue) {
      if (newValue === "") {
        this.$emit("closeModal");
      }
    },
  },
  computed: {
    pagedData() {
      return this.itemData.slice(this.from, this.to);
    },
    /***
     * Searches through table data and returns a paginated array.
     * Note that this should not be used for table with a lot of data as it might be slow!
     * Do the search and the pagination on the server and display the data retrieved from server instead.
     * @returns {computed.pagedData}
     */
    queriedData() {
      if (!this.searchQuery) {
        this.pagination.total = this.itemData.length;
        return this.pagedData;
      }
      let result = this.itemData.filter((row) => {
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
      this.pagination.total = this.itemData.length;
      return this.itemData.length;
    },
    /***
     * Searches through table data and returns a paginated array.
     * Note that this should not be used for table with a lot of data as it might be slow!
     * Do the search and the pagination on the server and display the data retrieved from server instead.
     * @returns {computed.pagedData}
     */
    popperInstance() {
      return createPopper(this.button, this.popover, {
        placement: "left",
        modifiers: [
          {
            name: "offset",
            options: {
              offset: [0, 30],
            },
          },
        ],
        strategy: "absolute",
      });
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
      propsToSearch: ["itemCode", "brand", "boxNumber", "boxLabel", "quantity"],
      tableColumns: [
        {
          prop: "itemCode",
          label: "Item Code",
          minWidth: 175,
        },
        {
          prop: "brand",
          label: "Brand",
          minWidth: 200,
        },
        {
          prop: "boxNumber",
          label: "Box Number",
          minWidth: 125,
        },
        {
          prop: "boxLabel",
          label: "Box Label",
          minWidth: 250,
        },
        {
          prop: "quantity",
          label: "Quantity",
          minWidth: 100,
        },
        {
          prop: "amount",
          label: "Amount",
          minWidth: 100,
        },
      ],
    };
  },
  methods: {
    submit() {
      console.log("Transaction Number:", this.transferredData.plID);
      console.log("Company", this.transferredData.company);
      location.href =
        "http://192.168.0.7:4040/#/pull-out/requisition-form?transactionID=" +
        this.transferredData.plID +
        "&company=" +
        this.transferredData.company;
    },
  },
};
</script>
