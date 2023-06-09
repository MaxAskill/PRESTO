<template>
  <div class="card">
    <div class="card-header">
      <h4 class="title">Pull-Out Requisition Form</h4>
    </div>
    <div class="card-body">
      <form>
        <div class="row">
          <div class="col-sm-3">
            <fg-input label="Company">
              <el-select
                class="select-default"
                size="large"
                placeholder="Select Company"
                v-model="newTransaction.company"
                @change="
                  fetchChainCode(),
                    (newTransaction.chainCode = ''),
                    (newTransaction.branchName = ''),
                    (newTransaction.transactionType = '')
                "
                :disabled="isCompany"
              >
                <el-option
                  v-for="option in companyList"
                  class="select-default"
                  :value="option.shortName"
                  :label="option.shortName"
                  :key="option.id"
                >
                </el-option>
              </el-select>
              <label v-show="isValid.company" class="label-font"
                >Company is required.</label
              >
            </fg-input>
          </div>
          <div class="col-sm-3">
            <fg-input label="Chain Code">
              <el-select
                class="select-default"
                size="large"
                placeholder="Select Chain Code"
                v-model="newTransaction.chainCode"
                @change="
                  fetchChainName(),
                    (newTransaction.branchName = ''),
                    (newTransaction.transactionType = '')
                "
                :disabled="isChainCode"
              >
                <el-option
                  v-for="option in chainCodeList"
                  class="select-default"
                  :value="option.chainCode"
                  :label="option.chainCode"
                  :key="option.chainCode"
                >
                </el-option>
              </el-select>
              <label v-show="isValid.chainCode" class="label-font"
                >Chain Code is required.</label
              >
            </fg-input>
          </div>
          <div class="col-sm-3">
            <fg-input label="Branch Name">
              <el-select
                class="select-default"
                size="large"
                placeholder="Select Branch Name"
                v-model="newTransaction.branchName"
                @change="
                  (isTransactionType = false), (newTransaction.transactionType = '')
                "
                :disabled="isBranchName"
              >
                <el-option
                  v-for="option in branchNameList"
                  class="select-default"
                  :value="option.branchName"
                  :label="option.branchName"
                  :key="option.branchName"
                >
                </el-option>
              </el-select>
              <label v-show="isValid.branchName" class="label-font"
                >Branch Name is required.</label
              >
            </fg-input>
          </div>
          <div class="col-sm-3">
            <fg-input label="Transaction Type">
              <el-select
                class="select-default"
                size="large"
                placeholder="Select Transaction Type"
                v-model="newTransaction.transactionType"
                @change="isShowButton = true"
                :disabled="isTransactionType"
              >
                <el-option
                  v-for="option in transactionTypeList"
                  class="select-default"
                  :value="option.value"
                  :label="option.label"
                  :key="option.label"
                >
                </el-option>
              </el-select>
              <label v-show="isValid.transactionType" class="label-font"
                >Transaction Type is required.</label
              >
            </fg-input>
          </div>
        </div>
        <div class="col-sm-12">
          <card type="plain">
            <div class="row">
              <div class="col-sm-12" v-show="isShowButton">
                <p-button
                  type="default"
                  link
                  class="font-weight-bold"
                  @click.prevent="addBoxLabel()"
                >
                  <i class="nc-icon nc-simple-add font-weight-bold"></i> Add Box Label
                </p-button>
                <p-button
                  type="default"
                  link
                  class="font-weight-bold"
                  data-bs-toggle="modal"
                  data-bs-target="#exampleModal"
                  @click="openModal(newTransaction)"
                >
                  <i class="nc-icon nc-ruler-pencil font-weight-bold"></i> Edit Box Label
                </p-button>
              </div>
              <div class="col-sm-12 mx-n3" v-show="isBoxLabel">
                <div class="col-sm-12">
                  <fg-input
                    placeholder="Box Label"
                    v-model="newBoxLabel"
                    class="w-100"
                  ></fg-input>
                </div>
                <div class="col-sm-2 mt-n3">
                  <p-button
                    type="default"
                    size="sm"
                    outline
                    class="font-weight-bold"
                    @click.prevent="saveBoxLabel()"
                  >
                    <i class="nc-icon nc-check-2 font-weight-bold"></i>
                  </p-button>
                  <p-button
                    type="default"
                    size="sm"
                    outline
                    class="font-weight-bold"
                    @click.prevent="cancelBoxLabel()"
                  >
                    <i class="nc-icon nc-simple-remove font-weight-bold"></i>
                  </p-button>
                </div>
                <label v-show="isNewBoxLabel" class="label-font pl-3"
                  >Box Label is empty.</label
                >
              </div>
              <label v-show="isValid.boxLabel" class="label-font"
                >Box Label is required.</label
              >
            </div>
            <collapse>
              <collapse-item
                v-for="boxLabel in newTransaction.boxLabels"
                :key="boxLabel.id"
                :title="(boxLabel.boxLabel, boxLabel.id)"
                name="1"
                class="font-weight-bold"
              >
                <div class="row" v-show="isAddItem">
                  <button
                    class="btn btn-transparent text-left bold-button"
                    @click.prevent="addItem(boxLabel.id)"
                  >
                    <i class="nc-icon nc-simple-add font-weight-bold"></i> Add Item
                  </button>
                </div>
                <div>
                  <div
                    class="row"
                    v-for="newItem in newItemInputBox"
                    v-show="newItem.id === boxLabel.id"
                  >
                    <div class="col-sm-12 d-flex mt-2" v-if="newItem.id == showItemInput">
                      {{ newItem }}
                      <input
                        type="text"
                        v-model="newItemInput"
                        @input="fetchItems"
                        @change="compareItemCode"
                        list="items"
                        class="input-datalist"
                      />
                      <!-- <input
                        :disabled="switchItemCode"
                        type="text"
                        v-model="newItemInput"
                        @input="fetchItems"
                        @change="compareItemCode"
                        list="items"
                        class="input-datalist"
                      /> -->
                      <datalist id="items">
                        <option
                          v-for="item in itemCodeList"
                          :value="item.ItemNo + ' - ' + item.ItemDescription"
                          :key="item.ItemNo"
                        ></option>
                      </datalist>
                      <!-- <fg-input placeholder="Item" v-model="newItemInput" class="w-100"></fg-input> -->
                      <!-- <label> showItemInput: {{ showItemInput }}</label>
                          <label> newItem.id: {{ newItem.id }}</label> -->
                      <p-button
                        type="default"
                        size="sm"
                        class="font-weight-bold"
                        @click.prevent="saveItem(boxLabel.boxLabel, boxLabel.id)"
                      >
                        <i class="nc-icon nc-check-2 font-weight-bold"></i>
                      </p-button>
                      <p-button
                        type="default"
                        size="sm"
                        class="font-weight-bold"
                        @click.prevent="cancelItem()"
                      >
                        <i class="nc-icon nc-simple-remove font-weight-bold"></i>
                      </p-button>
                    </div>
                    <label v-show="isRightCode" class="label-font font-weight-normal"
                      >Invalid Item Code.</label
                    >
                    <label v-show="isNewItem" class="label-font font-weight-normal"
                      >Item code is empty.</label
                    >

                    <!-- <div class="col-sm-12 d-flex" v-show="newItem.id != showItemInput">
                          <fg-input :placeholder="newItem.id " v-model="newItem.item" class="w-100"></fg-input>
                          <p-button type="default" size="sm"   class="font-weight-bold" @click.prevent="saveItem(boxLabel.boxLabel)">
                            <i class="nc-icon nc-check-2 font-weight-bold"></i> {{ newItem.id }}
                          </p-button>
                          <p-button type="default" size="sm"   class="font-weight-bold" @click.prevent="cancelItem()">
                            <i class="nc-icon nc-simple-remove font-weight-bold"></i>
                          </p-button>
                        </div> -->
                  </div>
                </div>

                <div class="row">
                  <table
                    class="table table-bordered table-hover table-responsive-sm table-font-size font-weight-normal"
                  >
                    <thead>
                      <tr>
                        <th scope="col" class="nowrap">Item Code</th>
                        <th scope="col" class="nowrap">Description</th>
                        <th scope="col" class="nowrap">Category/Brand</th>
                        <th scope="col" class="nowrap">Quantity</th>
                        <th scope="col" class="nowrap">Box Label</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-show="boxLabel.boxLabel == item.boxLabel"
                        v-for="item in newTransaction.items"
                        :key="item.code"
                      >
                        <th scope="row" class="px-3 d-flex">
                          <button
                            class="table-delete-button"
                            @click.prevent="removeItem(item.code, item.boxLabel)"
                          >
                            <i class="nc-icon nc-simple-remove font-weight-bold"></i>
                          </button>
                          {{ item.code }}
                        </th>
                        <td class="cell px-3">{{ item.description }}</td>
                        <td class="cell px-3">{{ item.categorybrand }}</td>
                        <td class="cell">
                          <input
                            type="number"
                            @blur="handleQuantity(item.id)"
                            v-model="item.quantity"
                            class="table-input-box"
                          />
                        </td>
                        <td class="cell">
                          <el-select
                            class="table-select-box"
                            size="large"
                            v-model="item.boxLabel"
                            @change="
                              editBoxLabel(item.code, item.quantity, item.boxLabel)
                            "
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
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </collapse-item>
              <label v-show="isValid.item" class="label-font">Item is required.</label>
            </collapse>
          </card>
        </div>

        <div class="text-center">
          <button
            type="submit"
            class="btn btn-warning btn-fill btn-wd"
            @click.prevent="draft"
          >
            Draft
          </button>
          <button
            type="submit"
            class="btn btn-info btn-fill btn-wd"
            @click.prevent="submit"
          >
            Submit
          </button>
        </div>
        <div class="clearfix"></div>
      </form>
      <EditBoxLabelModal
        :transferredData="transferredData"
        :newItemInputBox="newItemInputBox"
        @closeModal="transferredData"
      ></EditBoxLabelModal>
    </div>
  </div>
</template>
<script>
import {
  Collapse,
  CollapseItem,
  Tabs,
  TabPane,
  Card,
  Button,
} from "src/components/UIComponents";
import { DatePicker, TimeSelect, Slider, Tag, Input, Select, Option } from "element-ui";
import PProgress from "src/components/UIComponents/Progress.vue";
import PSwitch from "src/components/UIComponents/Switch.vue";
import Vue from "vue";
import NotificationTemplate from "../Components/NotificationTemplate";
import Swal from "sweetalert2";
import { Table, TableColumn } from "element-ui";
import axiosClient from "../../../../axios";
import EditBoxLabelModal from "./PullOutRequests/ModalPullOut/EditBoxLabelModal.vue";

Vue.use(Table);
Vue.use(TableColumn);
export default {
  components: {
    [DatePicker.name]: DatePicker,
    [TimeSelect.name]: TimeSelect,
    [Slider.name]: Slider,
    [Tag.name]: Tag,
    [Input.name]: Input,
    [Button.name]: Button,
    [Option.name]: Option,
    [Select.name]: Select,
    PSwitch,
    PProgress,
    TabPane,
    Tabs,
    CollapseItem,
    Collapse,
    Card,
    NotificationTemplate,
    EditBoxLabelModal,
  },
  data() {
    return {
      transferredData: "",
      isDisabled: [false, true, true, true, false, true],
      isCompany: false,
      isChainCode: true,
      isBranchName: true,
      isTransactionType: true,
      isShowButton: false,
      itemCodeList: [],
      isBoxLabel: false,
      isItem: false,
      isAddItem: true,
      showItemInput: "",
      companyList: [],
      chainCodeList: [],
      branchNameList: [],
      newTransaction: {
        //input variable
        company: "",
        chainCode: "",
        branchName: "",
        transactionType: "",
        boxLabels: [], //list of inputted box label
        items: [], //list of inputted item
      },
      newItemInputBox: [],
      isValid: {
        company: false,
        chainCode: false,
        branchName: false,
        transactionType: false,
        boxLabel: false,
        item: false,
      },
      hoveredRow: null,

      transactionTypeList: [
        { value: "CPO - BranchDisposal", label: "Concess Pull-Out Item Disposal" },
        { value: "CPO - Store", label: "Concess Pull-Out - Store" },
        { value: "CPO - Warehouse", label: "Concess Pull-Out - Warehouse" },
        { value: "CPO - Warehouse(DC)", label: "Concess Direct Pull-Out" },
      ],
      newBoxLabel: "",
      newItemInput: "",
      newBrand: "",
      newItemDescription: "",
      newStyleCode: "",
      notifications: {
        topCenter: false,
      },
      modals: {
        classic: false,
        notice: false,
        mini: false,
      },
      isNewBoxLabel: false,
      isNewItem: false,
      isRightCode: false,

      itemss: [
        { id: 1, name: "Item 1" },
        { id: 2, name: "Item 2" },
        { id: 1, name: "Item 3" },
        { id: 3, name: "Item 4" },
        { id: 2, name: "Item 5" },
      ],
      uniqueItemss: [],
      duplicateItemss: [],
    };
  },
  mounted() {
    // this.processData();
    this.fetchEdit();
    this.fetchCompany();
  },
  methods: {
    fetchEdit() {
      try {
        const uri = window.location.href;
        var transactionID = uri.split("?")[1];
        var id = transactionID.split("=")[1].split("&")[0];
        console.log("ID", id);
        console.log("Company", transactionID.split("=")[2]);
        var company = transactionID.split("=")[2];
        axiosClient
          .get("/fetchEditDraftBranch", {
            params: {
              company: company,
              plID: id,
            },
          })
          .then((response) => {
            console.log("Edit Branch:", response.data, this.itemss);
            this.newTransaction.company = response.data[0].company;
            this.newTransaction.branchName = response.data[0].branchName;
            this.newTransaction.chainCode = response.data[0].chainCode;
            this.newTransaction.transactionType = response.data[0].transactionType;

            axiosClient
              .get("/fetchEditDraftItem", {
                params: {
                  company: company,
                  plID: id,
                },
              })
              .then((response) => {
                console.log("Edit Item:", response.data);
                for (var x = 0; x < response.data.length; x++) {
                  this.newTransaction.items.push(response.data[x]);
                  // console.log("New Items:", this.newTransaction.items);
                }

                const idMap = new Map();

                this.newTransaction.items.forEach((item) => {
                  if (!idMap.has(item.id)) {
                    // Unique ID
                    idMap.set(item.id, true);
                    this.newTransaction.boxLabels.push({
                      id: item.boxNumber,
                      boxLabel: item.boxLabel,
                    });
                    this.newItemInputBox.push({
                      id: item.boxNumber,
                    });
                  }
                });
                console.log("Edit Labels:", this.newTransaction.boxLabels);

                console.log(
                  "this.newTransaction.transactionType:",
                  this.newTransaction.transactionType
                );

                if (this.newTransaction.company) {
                  this.isCompany = false;
                  this.isChainCode = false;
                  this.fetchCompany();
                  this.fetchChainCode();
                }

                if (this.newTransaction.chainCode) {
                  this.isChainCode = false;
                  this.isBranchName = false;
                  this.fetchChainName();
                  this.fetchChainCode();
                }

                if (this.newTransaction.branchName) {
                  this.isBranchName = false;
                  this.isTransactionType = false;
                  this.fetchChainName();
                }

                if (this.newTransaction.transactionType) {
                  console.log("Yes", this.isShowButton);
                  this.isCompany = false;
                  this.isChainCode = false;
                  this.isBranchName = false;
                  this.isTransactionType = false;
                  this.isShowButton = true;
                  console.log("Yes 2", this.isShowButton);
                }

                if (this.newTransaction.items.length) {
                  console.log("Disable", this.newTransaction.items.length);
                  this.isCompany = true;
                  this.isChainCode = true;
                  this.isBranchName = true;
                  this.isTransactionType = true;
                  this.isShowButton = true;
                }
              })
              .catch((error) => {
                console.error(error);
              });
          })
          .catch((error) => {
            console.error(error);
          });
      } catch {
        console.log("No Edit");
      }
    },

    updateData(updatedData) {
      // this.newTransaction.boxLabels = updatedData;
      console.log("das", updatedData);
    },
    openModal(data) {
      this.transferredData = data;
    },
    closeModal() {
      this.transferredData = "";
    },
    showButton(itemId) {
      this.hoveredRow = itemId;
    },
    hideButton(itemId) {
      this.hoveredRow = null;
    },
    removeItem(code, boxLabel) {
      console.log("New DATAs", code, boxLabel);
      this.newTransaction.items = this.newTransaction.items.filter(
        (item) => item.code !== code || item.boxLabel !== boxLabel
      );

      // alert("Your data: " + JSON.stringify(data));

      // console.log("New DATA", data);
    },
    fetchItems() {
      if (this.newItemInput.length >= 4) {
        if (
          this.newTransaction.company == "NBFI" ||
          this.newTransaction.company == "CMC" ||
          this.newTransaction.company == "ASC"
        ) {
          axiosClient
            .get("/fetchItemsNBFI", {
              params: {
                ItemNo: this.newItemInput,
              },
            }) // Make a GET request to the specified URL
            .then((response) => {
              console.log("Item Code Response: ", response.data);
              this.itemCodeList = response.data; // Update the 'data' variable with the retrieved data

              // // this.itemCodeArray = Object.keys(this.itemCodeList).map((ItemNo) => ({
              // //   ItemNo: ItemNo,
              // // }));
              //     console.log("Success Array Item: ", this.itemCodeArray);
              //    console.log("Success Item: ", response.data);
              //this.validateItemCode();
            })
            .catch((error) => {
              // console.error(error.reponse); // Handle any errors that may occur
            });
        } else {
          axiosClient
            .get("/fetchItems", {
              params: {
                ItemNo: this.newItemInput,
              },
            }) // Make a GET request to the specified URL
            .then((response) => {
              console.log("Item Code Response: ", response.data);
              this.itemCodeList = response.data; // Update the 'data' variable with the retrieved data

              // // this.itemCodeArray = Object.keys(this.itemCodeList).map((ItemNo) => ({
              // //   ItemNo: ItemNo,
              // // }));
              //     console.log("Success Array Item: ", this.itemCodeArray);
              //    console.log("Success Item: ", response.data);
              //this.validateItemCode();
            })
            .catch((error) => {
              // console.error(error.reponse); // Handle any errors that may occur
            });
        }
      }
    },
    compareItemCode() {
      let value = this.newItemInput;
      if (value.indexOf(" - ") >= 0) {
        let parts = value.split(" - ");
        this.newItemInput = parts[0];
      }
      // console.log("Length Item Code:", this.newItemCode.length > 16);
      if (this.newItemInput.length > 16) {
        // this.showTooltip = true;
        this.newItemInput = this.newItemInput.slice(0, 16);
      } else {
        // this.showTooltip = false;
      }
      axiosClient
        .get("/compareItemCode", {
          params: {
            companyType: this.newTransaction.company,
            ItemNo: this.newItemInput,
          },
        })
        .then((response) => {
          console.log("Success Item No:", response.data);
          console.log("Success Item Description", response.data[0].ItemDescription);
          this.newItemDescription = response.data[0].ItemDescription;
          this.newStyleCode = response.data[0].StyleCode;
          // console.log(
          //   "Convert to String Item Description",
          //   response.data[0].ItemDescription.toString()
          // );
          let brandCode = response.data[0].ItemNo.toString().substr(1, 2);

          // console.log("Brand Code", brandCode);

          axiosClient
            .get("/fetchBrands", {
              params: {
                companyType: this.newTransaction.company,
                brandCode: brandCode,
              },
            })
            .then((response) => {
              console.log("Success", response.data);
              this.newBrand = response.data[0].brandNames;
              // console.log("Box Data:", this.boxes);
              // console.log("Box Count:", this.boxes.length);
              // if (this.boxes.length == 1) {
              //   this.newItemBoxType = this.boxes[0].boxType + "-" + this.boxes[0].id;
              //   // console.log("TEst", this.boxes[0].boxType);
              // }
              // this.$refs.Quantity.focus();
            })
            .catch((error) => {
              console.error(error);
            });
        })
        .catch((error) => {
          this.missItemCode = 0;
          !this.newItemInput ? true : (this.isRightCode = true);
          console.log("Not Equal");
          console.error(error);
        });
    },
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
          customValue: "Success",
        },
      });
    },
    fetchCompany() {
      axiosClient
        .get("/fetchCompany")
        .then((response) => {
          console.log("Company Response:", response.data);
          this.companyList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    fetchChainCode() {
      axiosClient
        .get("/fetchChain", {
          params: {
            company: this.newTransaction.company,
          },
        })
        .then((response) => {
          console.log("Chain Code Response:", response.data);
          this.chainCodeList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
      this.notifyVue("bottom", "right");
      this.isChainCode = false;
    },
    fetchChainName() {
      axiosClient
        .get("/fetchChainName", {
          params: {
            chainCode: this.newTransaction.chainCode,
          },
        })
        .then((response) => {
          console.log("Chain Name Response:", response.data);
          this.branchNameList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
      this.isBranchName = false;
    },
    addItem(id) {
      this.isItem = false;
      this.isAddItem = false;
      this.showItemInput = id;
      console.log("clicked ID:", this.showItemInput);
    },
    saveItem(label, boxID) {
      this.isNewItem = !this.newItemInput ? true : false;
      console.log("item", this.newItemInput);
      console.log("condition", this.isNewItem);

      if (this.isNewItem) {
        this.isRightCode = false;
        return 0;
      }

      console.log(this.newTransaction.items.length);
      console.log("Items: ", this.newTransaction.items);

      let str = label.toLowerCase();
      axiosClient
        .get("/fetchSameItem", {
          params: {
            company: this.newTransaction.company,
            ItemDescription: this.newItemDescription,
            StyleCode: this.newStyleCode,
          },
        })
        .then((response) => {
          for (var x = 0; x < response.data.length; x++) {
            var flag = true;

            for (var i = 0; i < this.newTransaction.items.length; i++) {
              if (
                this.newTransaction.items[i].code == response.data[x].ItemNo &&
                this.newTransaction.items[i].boxLabel.toLowerCase() == str
              ) {
                this.newTransaction.items[i].quantity =
                  parseInt(this.newTransaction.items[x].quantity) + 1;
                flag = false;
                break;
              }
            }
            if (flag) {
              let tempItem = {
                code: response.data[x].ItemNo,
                description:
                  response.data[x].ItemDescription + "-" + response.data[x].Size,
                categorybrand: this.newBrand,
                quantity: 1,
                boxLabel: label,
                boxNumber: boxID,
              };
              this.newTransaction.items.push(tempItem);
            }
            this.isRightCode = false;
            this.isItem = false;
            this.isAddItem = true;
            this.newItemInput = "";
            this.showItemInput = "";
            this.notifyVue("bottom", "right");
          }
        })
        .catch((error) => {
          console.error(error);
        });

      console.log("Items: ", this.newTransaction.items);

      // console.log(this.newListBoxLabel);
    },
    cancelItem() {
      this.isRightCode = false;
      this.isNewItem = false;
      this.isItem = false;
      this.isAddItem = true;
      this.showItemInput = "";
      this.newItemInput = "";
    },
    addBoxLabel() {
      this.isBoxLabel = true;
    },
    saveBoxLabel() {
      this.isNewBoxLabel = !this.newBoxLabel ? true : false;

      if (this.isNewBoxLabel) {
        return 0;
      }
      this.isBoxLabel = false;
      console.log(this.newTransaction.boxLabels.length);
      let tempBoxLabel = [];

      if (this.newTransaction.boxLabels.length == 0) {
        tempBoxLabel = {
          id: this.newTransaction.boxLabels.length + 1,
          boxLabel: this.newBoxLabel,
        };
      } else {
        tempBoxLabel = {
          id:
            this.newTransaction.boxLabels[this.newTransaction.boxLabels.length - 1].id +
            1,
          boxLabel: this.newBoxLabel,
        };
      }

      let tempItem = [];

      if (this.newTransaction.boxLabels.length == 0) {
        tempItem = {
          id: this.newTransaction.boxLabels.length + 1,
        };
      } else {
        tempItem = {
          id:
            this.newTransaction.boxLabels[this.newTransaction.boxLabels.length - 1].id +
            1,
        };
      }

      this.newTransaction.boxLabels.push(tempBoxLabel);
      this.newItemInputBox.push(tempItem);
      this.newBoxLabel = "";
      this.notifyVue("bottom", "right");
      console.log("Console:", this.newTransaction.boxLabels);
      //Disable the above select buttons
      this.isCompany = true;
      this.isChainCode = true;
      this.isBranchName = true;
      this.isTransactionType = true;
    },
    cancelBoxLabel() {
      this.isNewBoxLabel = false;
      this.isBoxLabel = false;
      this.newBoxLabel = "";
    },
    submit() {
      try {
        const uri = window.location.href;
        var transactionID = uri.split("?")[1];
        var id = transactionID.split("=")[1].split("&")[0];

        this.isValid.company = !this.newTransaction.company ? true : false;
        this.isValid.chainCode = !this.newTransaction.chainCode ? true : false;
        this.isValid.branchName = !this.newTransaction.branchName ? true : false;
        this.isValid.transactionType = !this.newTransaction.transactionType
          ? true
          : false;
        this.isValid.boxLabel = !this.newTransaction.boxLabels.length ? true : false;
        this.isValid.item = !this.newTransaction.items.length ? true : false;

        console.log("Company: ", this.isValid.company);

        console.log("company", this.newTransaction.company);
        // alert("Your data: " + JSON.stringify(this.newTransaction));
        axiosClient
          .post("/updatePullOutBranchRequest", {
            id: id,
            chainCode: this.newTransaction.chainCode,
            companyType: this.newTransaction.company,
            branchName: this.newTransaction.branchName,
            transactionType: this.newTransaction.transactionType,
            email: sessionStorage.getItem("Email"),
            status: "unprocessed",
          })
          .then((response) => {
            console.log("Success Branch Save: ", response.data);

            for (var x = 0; x < this.newTransaction.items.length; x++) {
              axiosClient
                .post("/updatePullOutItemRequest", {
                  plID: id,
                  companyType: this.newTransaction.company,
                  brand: this.newTransaction.items[x].categorybrand,
                  boxNumber: this.newTransaction.items[x].boxNumber,
                  boxLabel: this.newTransaction.items[x].boxLabel,
                  itemCode: this.newTransaction.items[x].code,
                  quantity: this.newTransaction.items[x].quantity,
                  email: sessionStorage.getItem("Email"),
                  status: "unprocessed",
                })
                .then((response) => {
                  console.log("Success Items Save: ", response.data);
                  window.location.href =
                    "http://192.168.0.7:4040/#/pull-out/requisition-form";
                })
                .catch((error) => {
                  console.error(error);
                });
            }
          })
          .catch((error) => {
            console.error(error);
          });
      } catch {
        this.isValid.company = !this.newTransaction.company ? true : false;
        this.isValid.chainCode = !this.newTransaction.chainCode ? true : false;
        this.isValid.branchName = !this.newTransaction.branchName ? true : false;
        this.isValid.transactionType = !this.newTransaction.transactionType
          ? true
          : false;
        this.isValid.boxLabel = !this.newTransaction.boxLabels.length ? true : false;
        this.isValid.item = !this.newTransaction.items.length ? true : false;

        console.log("Company: ", this.isValid.company);

        console.log("company", this.newTransaction.company);
        // alert("Your data: " + JSON.stringify(this.newTransaction));
        axiosClient
          .post("/savePullOutBranchRequest", {
            chainCode: this.newTransaction.chainCode,
            companyType: this.newTransaction.company,
            branchName: this.newTransaction.branchName,
            transactionType: this.newTransaction.transactionType,
            email: sessionStorage.getItem("Email"),
            status: "unprocessed",
          })
          .then((response) => {
            console.log("Success Branch Save: ", response.data);

            for (var x = 0; x < this.newTransaction.items.length; x++) {
              axiosClient
                .post("/savePullOutItemRequest", {
                  plID: response.data.id,
                  companyType: this.newTransaction.company,
                  brand: this.newTransaction.items[x].categorybrand,
                  boxNumber: this.newTransaction.items[x].boxNumber,
                  boxLabel: this.newTransaction.items[x].boxLabel,
                  itemCode: this.newTransaction.items[x].code,
                  quantity: this.newTransaction.items[x].quantity,
                  email: sessionStorage.getItem("Email"),
                  status: "unprocessed",
                })
                .then((response) => {
                  console.log("Success Items Save: ", response.data);
                })
                .catch((error) => {
                  console.error(error);
                });
            }
          })
          .catch((error) => {
            console.error(error);
          });
      }
    },
    draft() {
      try {
        const uri = window.location.href;
        var transactionID = uri.split("?")[1];
        var id = transactionID.split("=")[1].split("&")[0];

        axiosClient
          .post("/updatePullOutBranchRequest", {
            id: id,
            chainCode: this.newTransaction.chainCode,
            companyType: this.newTransaction.company,
            branchName: this.newTransaction.branchName,
            transactionType: this.newTransaction.transactionType,
            status: "draft",
            email: sessionStorage.getItem("Email"),
          })
          .then((response) => {
            console.log("Success Branch Save: ", response.data);
            console.log("Items Length:", this.newTransaction.items.length);
            for (var x = 0; x < this.newTransaction.items.length; x++) {
              axiosClient
                .post("/updatePullOutItemRequest", {
                  plID: id,
                  companyType: this.newTransaction.company,
                  brand: this.newTransaction.items[x].categorybrand,
                  boxNumber: this.newTransaction.items[x].boxNumber,
                  boxLabel: this.newTransaction.items[x].boxLabel,
                  itemCode: this.newTransaction.items[x].code,
                  quantity: this.newTransaction.items[x].quantity,
                  status: "draft",
                  email: sessionStorage.getItem("Email"),
                })
                .then((response) => {
                  console.log("Success Items Save: ", response.data);
                })
                .catch((error) => {
                  console.error(error);
                });
            }
          })
          .catch((error) => {
            console.error(error);
          });
      } catch {
        axiosClient
          .post("/savePullOutBranchRequest", {
            chainCode: this.newTransaction.chainCode,
            companyType: this.newTransaction.company,
            branchName: this.newTransaction.branchName,
            transactionType: this.newTransaction.transactionType,
            status: "draft",
            email: sessionStorage.getItem("Email"),
          })
          .then((response) => {
            console.log("Success Branch Save: ", response.data);

            for (var x = 0; x < this.newTransaction.items.length; x++) {
              axiosClient
                .post("/savePullOutItemRequest", {
                  plID: response.data.id,
                  companyType: this.newTransaction.company,
                  brand: this.newTransaction.items[x].categorybrand,
                  boxNumber: this.newTransaction.items[x].boxNumber,
                  boxLabel: this.newTransaction.items[x].boxLabel,
                  itemCode: this.newTransaction.items[x].code,
                  quantity: this.newTransaction.items[x].quantity,
                  status: "draft",
                  email: sessionStorage.getItem("Email"),
                })
                .then((response) => {
                  console.log("Success Items Save: ", response.data);
                })
                .catch((error) => {
                  console.error(error);
                });
            }
          })
          .catch((error) => {
            console.error(error);
          });
      }
    },
    editBoxLabel(code, quantity, boxLabel) {
      console.log(
        "Item Code Edit:",
        code,
        " Quantity:",
        quantity,
        " Box Label",
        boxLabel
      );
      var filteredItems = this.newTransaction.items.filter(
        (item) => item.code === code && item.boxLabel === boxLabel
      );

      console.log("Filtered Items: ", filteredItems);
      var uniqueItems = [];
      filteredItems.forEach((item) => {
        var existingItem = uniqueItems.find(
          (uniqueItem) => uniqueItem.code === item.code
        );
        if (existingItem) {
          existingItem.quantity += item.quantity;
        } else {
          uniqueItems.push({ ...item });
        }
      });
      // The uniqueItems array will contain unique items based on itemCode, with quantities added up.
      console.log("Unique Items: 1", uniqueItems);
      console.log("Unique Items: Code", uniqueItems[0].code);
      console.log("Unique Items: Box Label", uniqueItems[0].boxLabel);

      this.newTransaction.items = this.newTransaction.items.filter(
        (item) =>
          item.code !== uniqueItems[0].code || item.boxLabel !== uniqueItems[0].boxLabel
      );
      this.newTransaction.items.push(uniqueItems[0]);
    },
  },
};
</script>
<style>
.el-select {
  width: 100%;
  background-color: white;
  border: 20px;
  border-color: blueviolet;
}

.btn-transparent {
  background-color: transparent;
  border: none;
  color: #000000;
}

.btn-transparent:hover {
  background-color: rgba(0, 0, 0, 0.1);
  color: #ffffff;
}

.table-font-size {
  font-size: 14px;
}

.table-input-box {
  width: 100%;
  height: 100%;
  box-sizing: border-box;
  padding: 14px;
  border: 1px solid transparent;
  transition: border-color 0.3s ease-in-out;
  background-color: transparent;
}

.table-select-box {
  /* border: 5px solid green !important; */
  transition: border-color 0.3s ease-in-out;
  /* background-color: yellow; */
  border-radius: 0px !important;
  background-color: transparent !important;
}

.table-select-box.el-select .el-input input {
  border-radius: 0px !important;
  border: 5px solid transparent !important;
  background-color: transparent;
}

td {
  display: table-cell !important;
  padding: 0px !important;
  /* background-color: #111111 !important; */
}
table {
  border-collapse: collapse !important;
}
.bold-button {
  font-weight: bold !important;
}

.input-datalist {
  display: block !important;
  width: 100% !important;
  margin-bottom: 10px !important;
  position: relative !important;
  appearance: none !important;
  font-weight: 400 !important;
  background-clip: padding-box !important;
  border: 1px solid #66615b !important;
  border-radius: 5px !important;
  padding: 9px !important;
  outline: none;
  /* border: 1pc solid gray; */
}

.table-delete-button {
  background-color: transparent;
  color: red;
  border: none;
  cursor: pointer;
}

.label-font {
  color: red !important;
}

/* .table-delete:hover {
  color: red !important;
  cursor: pointer;
} */
</style>
