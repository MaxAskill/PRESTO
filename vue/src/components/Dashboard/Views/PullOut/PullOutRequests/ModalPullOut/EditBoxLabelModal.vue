<template>
  <div>
    <div
      class="modal fade"
      id="editboxlabelModal"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="editboxlabelModalLabel"
      aria-hidden="true"
    >
      import ConfirmationDeleteBoxLabelModal from "./ConfirmationDeleteBoxLabelModal.vue";
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
                        :key="index"
                        @focus.capture="listBoxNumber(index)"
                        @change="saveBoxNumber(index)"
                        v-model="boxLabel.boxNumber"
                        disabled
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
                        @keypress="restrictChar"
                        list="remarks"
                      />
                      <datalist id="remarks">
                        <option v-for="remark in remarksList" :value="remark">
                          {{ remark }}
                        </option>
                      </datalist>
                      <button
                        class="table-delete-modal pull-left"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmationDeleteBoxLabel"
                        @click.prevent="transferBoxLabel(boxLabel)"
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
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
              @click="closeModal"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
    <ConfirmationDeleteBoxLabelModal
      :BoxLabelInfo="deleteBoxLabel"
      @confirm="removeBoxLabel($event)"
    ></ConfirmationDeleteBoxLabelModal>
  </div>
</template>
<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";
import axiosClient from "../../../../../../axios";
// import NotificationTemplate from "../../../Components/Notification/NotifSuccessRenameBoxLabel.vue";
import NotifRenameBoxLabel from "../../../Components/Notification/NotifSuccessRenameBoxLabel.vue";
import NotifDeleteBoxLabel from "../../../Components/Notification/NotifSuccessDeleteBoxLabel.vue";
import NotifRestoredBoxLabel from "../../../Components/Notification/NotifBoxLabelRestored.vue";

import ConfirmationDeleteBoxLabelModal from "./ConfirmationDeleteBoxLabelModal.vue";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  components: {
    NotifRenameBoxLabel,
    NotifDeleteBoxLabel,
    NotifRestoredBoxLabel,
    ConfirmationDeleteBoxLabelModal,
    ConfirmationDeleteBoxLabelModal,
  },
  props: ["transferredData", "newItemInputBox", "remarksList"],
  // mounted() {},
  // destroyed() {
  //   $("#myModal").modal("hide");
  // },
  watch: {
    // transferredData(newValue) {
    //   if (newValue === "") {
    //     this.$emit("closeModal");
    //   }
    // },
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
      selectedBox: null,
      count: 0,
      icount: 0,
      deleteBoxLabel: null,
    };
  },
  // this.selectedBoxLabel = BoxLabel;
  methods: {
    restrictChar($event) {
      let keyCode = $event.keyCode ? $event.keyCode : $event.which;
      if (keyCode == 91 || keyCode == 93) {
        // 46 is dot
        $event.preventDefault();
      }
    },
    closeModal() {
      this.$emit("closeModal");
    },
    saveBoxNumber(index) {
      const origItems = [];
      const selectedItems = [];
      for (let key in this.transferredData.boxLabels) {
        if (this.transferredData.boxLabels.hasOwnProperty(key)) {
          // console.log("Box Label", this.transferredData.boxLabels[key].id);
          // console.log("Box Number", this.transferredData.boxLabels[index].boxNumber);

          if (
            this.transferredData.boxLabels[index].boxNumber ===
            this.transferredData.boxLabels[key].boxNumber
          ) {
            if (index != key) {
              for (let ikey in this.transferredData.items) {
                //loop for items
                if (this.transferredData.items.hasOwnProperty(ikey)) {
                  //checking if the key still existed
                  console.log("same: ", this.transferredData.boxLabels[key].boxNumber); //new value
                  if (
                    this.transferredData.items[ikey].boxNumber ===
                    this.transferredData.boxLabels[key].boxNumber
                  ) {
                    this.transferredData.items[
                      ikey
                    ].boxNumber = this.transferredData.boxLabels[key].boxNumber;
                    origItems.push(parseInt(ikey)); //where the items from original item
                  }
                }
              }
              for (let ikey in this.transferredData.items) {
                //loop for items
                if (this.transferredData.items.hasOwnProperty(ikey)) {
                  //checking if the key still existed
                  // console.log("same: ", this.transferredData.boxLabels[key].boxNumber); //new value
                  if (
                    this.transferredData.items[ikey].boxNumber === this.selectedBoxLabel
                  ) {
                    this.transferredData.items[
                      ikey
                    ].boxNumber = this.transferredData.boxLabels[key].boxNumber;
                    selectedItems.push(parseInt(ikey)); //where the items from selected item
                  }
                }
              }
              this.transferredData.boxLabels[key].boxNumber = this.selectedBoxLabel; // transferring the box number of the selected box to original box
              this.selectedBox = this.transferredData.boxLabels[key].boxLabel;
              break;
            }
          }
        }
      }

      // console.log("Original Items: ", origItems);
      // console.log("Original Items: ", this.selectedBox);
      // console.log("Selected Items: ", selectedItems);
      // console.log("transfer: ", this.transferredData.boxLabels[index]);

      for (this.count = 0; this.count < origItems.length; this.count++) {
        this.transferredData.items[
          origItems[this.count]
        ].boxNumber = this.selectedBoxLabel;
        // this.transferredData.items[origItems[this.count]].boxLabel = this.selectedBox;
        console.log("origItems", this.transferredData.items[origItems[this.count]]);
      }

      for (this.icount = 0; this.icount < selectedItems.length; this.icount++) {
        this.transferredData.items[
          selectedItems[this.icount]
        ].boxNumber = this.transferredData.boxLabels[index].boxNumber;
        // this.transferredData.items[
        //   selectedItems[this.icount]
        // ].boxLabel = this.transferredData.boxLabels[index].boxLabel;
        // console.log("selectedItems", this.transferredData.items[selectedItems[count]]);
      }
    },
    listBoxNumber(index) {
      // console.log("Select input box focused", this.transferredData.boxLabels[index]);
      console.log("Select input box focused", index);
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
    transferBoxLabel(deleteboxLabel) {
      this.deleteBoxLabel = deleteboxLabel;
      this.deleteBoxLabel.boxLength = this.transferredData.boxLabels.length;
      console.log("Transfer: ", this.deleteBoxLabel);
    },
    removeBoxLabel(confirm) {
      if (confirm) {
        let localData = this.transferredData.boxLabels.findIndex(
          (tablerow) => tablerow.id === this.deleteBoxLabel.id
        );

        console.log("deleteboxLabel", this.deleteBoxLabel);
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
              if (box.boxNumber > this.deleteBoxLabel.boxNumber) {
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
      }
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
