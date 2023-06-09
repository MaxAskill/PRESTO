<template>
  <div
    class="modal fade"
    id="unprocessModal"
    tabindex="-1"
    aria-labelledby="unprocessModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="unprocessModalLabel">
            {{ transferredData.branchName }}
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
              <el-table-column :min-width="120" class-name="td-actions" label="Actions">
                <template slot-scope="props">
                  <p-button type="success" size="sm" icon>
                    <i class="fa fa-edit"></i>
                  </p-button>
                  <p-button
                    type="danger"
                    size="sm"
                    icon
                    @click="handleDelete(props.$index, props.row)"
                  >
                    <i class="fa fa-times"></i>
                  </p-button>
                </template>
              </el-table-column>
            </el-table>
          </div>
        </div>
        <div class="modal-footer mrgn-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Close
          </button>
          <button type="button" class="btn btn-danger">Denied</button>
          <button type="button" class="btn btn-primary">Approved</button>
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
import NotificationTemplate from "../../../Components/Notification/NotificationSuccessfulRename.vue";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  // mounted() {
  //   this.fetchData();
  // },
  props: ["transferredData", "itemData"],
  components: {
    NotificationTemplate,
  },

  mounted() {
    // this.itemsData = this.transfer();
    // console.log("Item Data: ", this.itemsData);
  },
  watch: {
    transferredData(newValue) {
      if (newValue === "") {
        this.$emit("closeModal");
      }
    },
  },
  computed: {
    transfer() {
      return this.itemData;
    },
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
      itemsData: [],
    };
  },
  methods: {
    handleQuantity() {
      this.notifyVue("bottom", "right");
    },
    notifyVue(verticalAlign, horizontalAlign) {
      const color = Math.floor(Math.random() * 4 + 1);
      this.$notify({
        component: NotificationTemplate,
        // icon: 'nc-icon nc-app',
        horizontalAlign: horizontalAlign,
        verticalAlign: verticalAlign,
        type: "success",
        props: {
          customValue: "Success Add Box",
        },
      });
    },
    handleEdit(index, row) {
      alert(`Your want to edit ${row.id}`);
    },
    handleDelete(index, row) {
      let indexToDelete = this.itemData.findIndex((tableRow) => tableRow.id === row.id);
      console.log("ID to be deleted", row.id);
      if (indexToDelete >= 0) {
        this.itemData.splice(indexToDelete, 1);
        console.log("Delete ID: ", this.itemData);
      }

      axiosClient
        .post("/updateStatus", {
          company: sessionStorage.getItem("Company"),
          id: row.id,
          status: "deleted",
          userID: sessionStorage.getItem("UserID"),
        })
        .then((response) => {
          console.log("Success Delete: ", response.data);
        })
        .catch((error) => {
          console.error(error);
        });
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

.mrgn-footer {
  margin-right: 10px;
}
</style>
