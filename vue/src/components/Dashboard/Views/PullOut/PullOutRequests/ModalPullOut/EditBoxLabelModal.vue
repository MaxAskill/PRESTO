<template>
  <div
    class="modal fade"
    id="exampleModal"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="exampleModalLabel">
            LIST OF BOX LABEL [ {{ newInputItem }} ]
          </h1>
        </div>
        <div class="modal-body">
          <div class="row">
            <table
              class="table table-bordered table-hover table-responsive-sm table-font-size font-weight-normal"
            >
              <thead>
                <tr>
                  <th scope="col" class="nowrap">Box Label</th>
                  <!-- <th scope="col" class="nowrap">Category/Brand</th>
                  <th scope="col" class="nowrap">Quantity</th>
                  <th scope="col" class="nowrap">Box Label</th> -->
                </tr>
              </thead>
              <tbody>
                <tr v-for="boxLabel in transferredData.boxLabels" :key="boxLabel.id">
                  <!-- <td class="cell px-3">{{ item.description }}</td> -->
                  <!-- <td class="cell px-3">{{ item.categorybrand }}</td> -->
                  <td class="input-container">
                    <input
                      type="text"
                      @blur="handleBoxLabel(boxLabel.boxLabel)"
                      v-model="boxLabel.boxLabel"
                      class="table-input-box"
                    />
                    <button
                      class="table-delete-modal pull-left"
                      @click.prevent="removeBoxLabel(boxLabel)"
                    >
                      <i class="nc-icon nc-simple-remove font-weight-bold"></i>
                    </button>
                  </td>
                  <!-- <td class="cell">
                    <el-select
                      class="table-select-box"
                      size="large"
                      v-model="item.boxLabel"
                      @change="editBoxLabel(item.code, item.quantity, item.boxLabel)"
                    >
                      <el-option
                        v-for="boxLabel in newTransaction.boxLabels"
                        class="table-select-box"
                        :value="boxLabel.boxLabel"
                        :label="boxLabel.boxLabel"
                        :key="boxLabel.id"
                      >
                      </el-option>
                    </el-select>
                  </td> -->
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer px-5">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Close
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
import NotificationTemplate from "../../../Components/Notification/NotificationSuccessfulRename.vue";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  components: {
    NotificationTemplate,
  },
  props: ["transferredData", "newInputItem"],
  // mounted() {
  //   $("#myModal").modal("show");
  // },
  // destroyed() {
  //   $("#myModal").modal("hide");
  // },
  watch: {
    transferredData(newValue) {
      if (newValue === "") {
        console.log("Mabuhayaaaaaaaaaaaaaaaaa");
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
          prop: "id",
          label: "Box Number",
          minWidth: 200,
        },
        {
          prop: "boxLabel",
          label: "Box Label",
          minWidth: 250,
        },
      ],
    };
  },
  methods: {
    removeBoxLabel(deleteboxLabel) {
      let localData = this.transferredData.boxLabels.findIndex(
        (tablerow) => tablerow.id === deleteboxLabel.id
      );

      console.log("dasd", localData);
      console.log("before", this.transferredData.boxLabels[localData].id);

      // Remove objects with the same ID from the original list
      this.transferredData.items = this.transferredData.items.filter(
        (obj) => obj.boxNumber !== this.transferredData.boxLabels[localData].id
      );

      if (localData >= 0) {
        this.transferredData.boxLabels.splice(localData, 1);
      }
      // console.log("after", this.transferredData);
      // this.$emit("closeModal", this.transferredData);
    },
    handleBoxLabel(boxLabel) {
      if (!boxLabel) console.log("Empty boxlabel");
      else this.notifyVue("bottom", "right");
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
  },
};
</script>

<style>
.input-container {
  position: relative;
  display: inline-block;
}

.table-input-box {
  padding-right: 40px; /* Add enough padding to make room for the button */
}

.table-delete-modal {
  position: absolute;
  top: 50%;
  right: 5px;
  transform: translateY(-50%);
  background-color: transparent;
  color: red;
  border: none;
  cursor: pointer;
}
</style>
