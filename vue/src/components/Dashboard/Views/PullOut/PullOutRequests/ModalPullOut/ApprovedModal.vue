<template>
  <!-- <div>
    <button
      type="button"
      class="btn btn-info btn-sm"
      data-bs-toggle="modal"
      data-bs-target="#exampleModal"
    >

      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="text-white h-5 w-5"
        viewBox="0 0 512 512"
      >
        <path
          d="M336 64h32a48 48 0 0148 48v320a48 48 0 01-48 48H144a48 48 0 01-48-48V112a48 48 0 0148-48h32"
          fill="none"
          stroke="currentColor"
          stroke-linejoin="round"
          stroke-width="32"
        />
        <rect
          x="176"
          y="32"
          width="160"
          height="64"
          rx="26.13"
          ry="26.13"
          fill="none"
          stroke="currentColor"
          stroke-linejoin="round"
          stroke-width="32"
        />
      </svg>
    </button> -->
  <div
    class="modal fade"
    id="approvedModal"
    tabindex="-1"
    aria-labelledby="approvedModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="approvedModalLabel">
            APPROVED: {{ transferredData.branchName }}
          </h1>
        </div>
        <div class="modal-body">
          <div class="col-sm-12 mt-2">
            <el-table class="table-striped" :data="itemData" border style="width: 100%">
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
          <button type="button" class="btn btn-primary">Generate Letter</button>
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

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  props: ["transferredData", "itemData"],

  watch: {
    transferredData(newValue) {
      if (newValue === "") {
        this.$emit("closeModal");
      }
    },
  },
  computed: {
    /***
     * Searches through table data and returns a paginated array.
     * Note that this should not be used for table with a lot of data as it might be slow!
     * Do the search and the pagination on the server and display the data retrieved from server instead.
     * @returns {computed.pagedData}
     */
  },
  data() {
    return {
      tableColumns: [
        {
          prop: "brand",
          label: "Brand",
          minWidth: 200,
        },
        {
          prop: "boxLabel",
          label: "Box Label",
          minWidth: 250,
        },
        {
          prop: "boxNumber",
          label: "Box Number",
          minWidth: 125,
        },
        {
          prop: "itemCode",
          label: "Item Code",
          minWidth: 175,
        },
        {
          prop: "quantity",
          label: "Quantity",
          minWidth: 100,
        },
      ],
    };
  },
};
</script>
