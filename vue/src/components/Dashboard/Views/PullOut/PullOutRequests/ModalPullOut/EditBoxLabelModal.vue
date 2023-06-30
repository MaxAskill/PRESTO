<template>
  <div
    class="modal fade"
    id="editboxlabelModal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="editboxlabelModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="editboxlabelModalLabel">
            <!-- LIST OF BOX LABEL {{ transferredData }} -->
            LIST OF BOX LABEL
            <!-- <label scope="col" class="nowrap">{{ transferredData }}</label> -->
          </h1>
        </div>
        <div class="modal-body">
          <div class="row">
            <table
              class="table table-bordered table-hover table-responsive-sm table-font-size font-weight-normal"
            >
              <thead>
                <tr>
                  <th scope="col" class="nowrap">Box Number</th>
                  <th scope="col" class="nowrap">Box Label</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(boxLabel, index) in transferredData.boxLabels"
                  :key="boxLabel.id"
                >
                  <td class="input-container">
                    <el-select
                      class="table-select-box"
                      size="large"
                      @focus="listBoxNumber(index)"
                      @change="saveBoxNumber(index)"
                      v-model="boxLabel.boxNumber"
                    >
                      <el-option
                        v-for="boxNumber in numberBoxLabel"
                        class="table-select-box"
                        :value="boxNumber"
                        :label="boxNumber"
                      >
                      </el-option>
                    </el-select>

                    <!-- {{ boxLabel.id }} -->
                  </td>
                  <td class="input-container">
                    <input
                      type="text"
                      @focus="handleClick(index, boxLabel.boxLabel)"
                      @blur="handleBoxLabel(index, boxLabel.boxLabel)"
                      @input="renameBoxLabel(index, boxLabel.boxLabel, tempBoxLabel)"
                      v-model.trim="boxLabel.boxLabel"
                      class="table-input-box"
                    />
                    <button
                      class="table-delete-modal pull-left"
                      @click.prevent="removeBoxLabel(boxLabel)"
                    >
                      <i class="nc-icon nc-simple-remove font-weight-bold"></i>
                    </button>
                  </td>
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
// import NotificationTemplate from "../../../Components/Notification/NotifSuccessRenameBoxLabel.vue";
import NotifRenameBoxLabel from "../../../Components/Notification/NotifSuccessRenameBoxLabel.vue";
import NotifDeleteBoxLabel from "../../../Components/Notification/NotifSuccessDeleteBoxLabel.vue";
import NotifRestoredBoxLabel from "../../../Components/Notification/NotifBoxLabelRestored.vue";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  components: {
    NotifRenameBoxLabel,
    NotifDeleteBoxLabel,
    NotifRestoredBoxLabel,
  },
  props: ["transferredData", "newItemInputBox"],
  // mounted() {
  //   $("#myModal").modal("show");
  // },
  // destroyed() {
  //   $("#myModal").modal("hide");
  // },
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
      tempBoxLabel: "",
      tempindexBoxLabel: "",
      clicked: false,
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
      numberBoxLabel: [],
      selectedBoxLabel: null,
      isFocused: false,
    };
  },
  // this.selectedBoxLabel = BoxLabel;
  methods: {
    saveBoxNumber(index) {
      const origItems = [];
      for (let key in this.transferredData.boxLabels) {
        if (this.transferredData.boxLabels.hasOwnProperty(key)) {
          // console.log("Box Label", this.transferredData.boxLabels[key].id);
          // console.log("Box Number", this.transferredData.boxLabels[index].boxNumber);

          if (
            this.transferredData.boxLabels[index].boxNumber ===
            this.transferredData.boxLabels[key].boxNumber
          ) {
            // console.log(index, key, "same", this.transferredData.boxLabels[key]);
            if (index != key) {
              // console.log("NOT SAME ID");

              for (let ikey in this.transferredData.items) {
                //loop for items
                if (this.transferredData.items.hasOwnProperty(ikey)) {
                  //checking if the key still existed
                  if (
                    this.transferredData.items[ikey].boxNumber ===
                    this.transferredData.boxLabels[key].boxNumber
                  ) {
                    origItems.push(this.transferredData.items[ikey]);
                    //check if the box number of each item is same with previous box number of the selected/changed box number
                    // this.transferredData.items[
                    //   ikey
                    // ].boxNumber = this.transferredData.boxLabels[key].boxNumber; //transferring the boxnumber to the items inside the selected box
                    // console.log("boxnumber", this.transferredData.items[ikey].boxNumber);
                    // console.log("Items", this.transferredData.items[ikey]);
                  }
                }
              }

              this.transferredData.boxLabels[key].boxNumber = this.selectedBoxLabel; //transfer the boxnumber of the selected box to the original box
            }
          }
        }
      }

      console.log("Original Items: ", origItems);

      // for (let key in this.transferredData.items) {
      //   //loop for items
      //   if (this.transferredData.items.hasOwnProperty(key)) {
      //     //checking if the key still existed
      //     if (this.transferredData.items[key].boxNumber === this.selectedBoxLabel) {
      //       //check if the box number of each item is same with previous box number of the selected/changed box number
      //       this.transferredData.items[key].boxNumber = this.transferredData.boxLabels[
      //         index
      //       ].boxNumber; //transferring the boxnumber to the items inside the selected box
      //       console.log("boxnumber", this.transferredData.items[key].boxNumber);
      //       console.log("Items", this.transferredData.items[key]);
      //     }
      //   }
      // }
    },
    listBoxNumber(index) {
      // console.log("Select input box focused", this.transferredData.boxLabels[index]);
      this.selectedBoxLabel = this.transferredData.boxLabels[index].boxNumber;
      this.numberBoxLabel = [];
      this.transferredData.boxLabels.forEach((obj) => {
        this.numberBoxLabel.push(obj.boxNumber);
      });
    },
    renameBoxLabel(index, value, tempBL) {
      this.$emit("renameBoxLabel", { index, value, tempBL });
    },
    handleClick(index, boxLabel) {
      if (!this.clicked) {
        this.inputClicked(index, boxLabel);
        this.clicked = true;
      }
    },
    inputClicked(index, boxLabel) {
      // console.log("Input box was clicked!");
      this.tempBoxLabel = boxLabel;
      this.tempindexBoxLabel = index;
      console.log("Input box was clicked!", this.tempBoxLabel);
    },
    resetFlag() {
      this.clicked = false;
    },
    removeBoxLabel(deleteboxLabel) {
      let localData = this.transferredData.boxLabels.findIndex(
        (tablerow) => tablerow.id === deleteboxLabel.id
      );

      console.log("deleteboxLabel", deleteboxLabel);
      console.log("dasd", localData);
      console.log("before", this.transferredData.boxLabels[localData].id);

      // Remove objects with the same ID from the original list
      this.transferredData.items = this.transferredData.items.filter(
        (obj) => obj.boxNumber !== this.transferredData.boxLabels[localData].id
      );

      if (Object.keys(this.transferredData.boxLabels).length !== 0) {
        for (let key in this.transferredData.boxLabels) {
          if (this.transferredData.boxLabels.hasOwnProperty(key)) {
            const box = this.transferredData.boxLabels[key];
            if (box.boxNumber > deleteboxLabel.boxNumber) {
              box.boxNumber--;
            }
          }
        }
      }

      if (localData >= 0) {
        this.transferredData.boxLabels.splice(localData, 1);
        this.newItemInputBox.splice(localData, 1);
        this.notifyVue("DeleteBoxLabel", "bottom", "right");
      }

      // console.log("after", this.transferredData);
      // this.$emit("closeModal", this.transferredData);
    },
    handleBoxLabel(index, boxLabel) {
      // console.log("items changed:", boxLabel);
      // const newItemLabel = "New Label";
      // const itemToUpdate = this.transferredData.items.find(
      //   (item) => item.id === this.transferredData.boxLabels[index].id
      // );
      // if (itemToUpdate) {
      //   itemToUpdate.boxLabel = newItemLabel;
      // }
      // console.log("items changed:", itemToUpdate);
      if (!boxLabel) {
        console.log("Empty boxlabel", this.tempBoxLabel);
        this.notifyVue("RestoredBoxLabel", "bottom", "right");
        // console.log("Empty boxlabels", this.transferredData);
        this.transferredData.boxLabels[index].boxLabel = this.tempBoxLabel;
      } else {
        this.notifyVue("EditBoxLabel", "bottom", "right");
      }
      this.clicked = false;
      this.tempBoxLabel = "";
    },
    notifyVue(notify, verticalAlign, horizontalAlign) {
      var notification = "";
      let notifType = "";

      if (notify == "DeleteBoxLabel") {
        notification = NotifDeleteBoxLabel;
        notifType = "danger";
      } else if (notify == "RestoredBoxLabel") {
        notification = NotifRestoredBoxLabel;
        notifType = "warning";
      } else {
        notification = NotifRenameBoxLabel;
        notifType = "success";
      }

      // const color = Math.floor(Math.random() * 4 + 1);
      this.$notify({
        component: notification,
        // icon: "nc-icon nc-app",
        horizontalAlign: horizontalAlign,
        verticalAlign: verticalAlign,
        type: notifType,
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
