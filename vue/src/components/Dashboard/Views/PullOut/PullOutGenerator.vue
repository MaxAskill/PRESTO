<template>
  <div class="card card-generator">
    <!-- <div class="card-header">
      <h4 class="title">Pull-Out Requisition Form</h4>
    </div> -->
    <div class="card-body">
      <div class="row">
        <div class="col-sm-3">
          <fg-input label="Company" :disabled="isCompany">
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
                :label="option.name + ' (' + option.shortName + ') '"
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
          <fg-input label="Chain Code" :disabled="isChainCode">
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
          <fg-input label="Branch Name" :disabled="isBranchName">
            <el-select
              class="select-default"
              size="large"
              placeholder="Select Branch Name"
              v-model="newTransaction.branchName"
              @change="(isTransactionType = false), (newTransaction.transactionType = '')"
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
          <fg-input label="Transaction Type" :disabled="isTransactionType">
            &nbsp;<i
              data-bs-toggle="tooltip"
              class="nc-icon nc-alert-circle-i font-weight-bold"
            ></i>
            <el-select
              class="select-default"
              size="large"
              placeholder="Select Transaction Type"
              v-model="newTransaction.transactionType"
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
                :disabled="isEditBLDisabled"
                type="default"
                link
                class="font-weight-bold"
                data-bs-toggle="modal"
                data-bs-target="#editboxlabelModal"
                @click="openModal(newTransaction)"
              >
                <i class="nc-icon nc-ruler-pencil font-weight-bold"></i> Edit Box Label
              </p-button>
              <p-button
                type="default"
                outline
                class="font-weight-bold"
                onclick="document.getElementById('import_items_excel').click()"
              >
                Import Items Excel
              </p-button>
              <input
                id="import_items_excel"
                type="file"
                accept=".xlsx"
                style="display: none"
                @change="handleFileUpload"
              />
            </div>
            <form @submit.prevent="saveBoxLabel()">
              <div class="col-sm-12 mx-n3" v-show="isBoxLabel">
                <div class="col-sm-12">
                  <fg-input
                    placeholder="Box Label ( Items Remarks )"
                    v-model.trim="newBoxLabel"
                    class="w-100"
                    @keypress="restrictChar"
                    @input="filterRemarks"
                    list="remarks"
                  ></fg-input>
                  <datalist id="remarks">
                    <option v-for="remark in filteredRemarks" :value="remark">
                      {{ remark }}
                    </option>
                  </datalist>
                </div>
                <div class="col-sm-2 mt-n3">
                  <p-button
                    native-type="submit"
                    type="default"
                    size="sm"
                    outline
                    class="font-weight-bold"
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
            </form>
            <label v-show="isValid.boxLabel" class="label-font"
              >Box Label is required.</label
            >
          </div>
          <collapse>
            <collapse-item
              v-for="boxLabel in newTransaction.boxLabels"
              :key="boxLabel.id"
              :title="`Box No. ${boxLabel.boxNumber} of ${newTransaction.boxLabels.length} \xa0\xa0\xa0 Box Label: ${boxLabel.boxLabel}`"
              name="1"
              class="font-weight-bold"
            >
              <div class="row" v-show="isAddItem">
                <button
                  class="btn btn-transparent text-left bold-button"
                  @click.prevent="addItem(boxLabel.boxNumber)"
                >
                  <i class="nc-icon nc-simple-add font-weight-bold"></i> Add Item
                </button>
              </div>
              <div>
                <form @submit.prevent="saveItem(boxLabel.boxNumber)">
                  <div
                    class="row"
                    v-for="newItem in newItemInputBox"
                    v-show="newItem.id === boxLabel.boxNumber"
                  >
                    <div class="col-sm-12 d-flex mt-2" v-if="newItem.id == showItemInput">
                      <input
                        type="radio"
                        v-model="barcode"
                        name="barcode"
                        value="item_16"
                        checked
                      />
                      <label for="item_16">16 Digit Barcode</label><br />
                      <input
                        type="radio"
                        v-model="barcode"
                        name="barcode"
                        value="item_12"
                      />
                      <label for="item_12">12 Digit Barcode</label><br />
                      <input
                        type="text"
                        placeholder="Item Code"
                        v-model="newItemInput"
                        @input="fetchItems"
                        @change="compareItemCode"
                        list="items"
                        class="input-datalist"
                      />

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
                        native-type="submit"
                        type="default"
                        size="sm"
                        class="font-weight-bold"
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
                </form>
              </div>

              <div class="row">
                <table
                  class="table table-bordered table-hover table-responsive-sm table-font-size font-weight-normal"
                >
                  <thead>
                    <tr>
                      <th scope="col" class="text-nowrap">Item Code</th>
                      <th scope="col" class="text-nowrap">Description</th>
                      <th scope="col" class="text-nowrap">Size</th>
                      <th scope="col" class="text-nowrap">Color</th>
                      <th scope="col" class="text-nowrap">Category/Brand</th>
                      <th scope="col" class="text-nowrap">Quantity</th>
                      <th scope="col" class="text-nowrap">Box Label</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-show="boxLabel.boxNumber == item.boxNumber"
                      v-for="item in newTransaction.items"
                      :key="item.code"
                    >
                      <th scope="row" class="px-3 d-flex">
                        <button
                          class="gendelete-buttons"
                          @click.prevent="removeItem(item.code, item.boxNumber)"
                        >
                          <i class="nc-icon nc-simple-remove font-weight-bold"></i>
                        </button>
                        {{ item.code }}
                      </th>
                      <td class="cell px-3" style="width: 550px">
                        {{ item.description }}
                      </td>
                      <td class="cell px-3" style="width: 10px">
                        {{ item.size }}
                      </td>
                      <td class="cell px-3" style="width: 20px">
                        {{ item.color }}
                      </td>
                      <td class="cell px-3" style="width: 300px">
                        {{ item.categorybrand }}
                      </td>
                      <!-- <td class="cell" style="width: 50px">
                          <input
                            type="number"
                            @blur="handleQuantity(item.id)"
                            v-model="item.quantity"
                            class="table-input-box"
                            @keypress="numberOnly"
                          />
                        </td> -->
                      <td class="cell">
                        <span>
                          <div class="btn-group btn-group-sm d-flex flex-row">
                            <p-button
                              type="default"
                              round
                              outline
                              size="xs"
                              @click="item.quantity > 0 ? item.quantity-- : 0"
                            >
                              <i class="nc-icon nc-simple-delete"></i>
                            </p-button>
                            <input
                              type="text"
                              @blur="handleQuantity(item)"
                              v-model="item.quantity"
                              class="table-input-box"
                              style="width: 75px; text-align: center"
                              @keypress="numberOnly"
                            />
                            <p-button
                              type="default"
                              round
                              outline
                              size="xs"
                              @click="item.quantity++"
                            >
                              <i class="nc-icon nc-simple-add"></i>
                            </p-button>
                          </div>
                        </span>
                      </td>
                      <td class="cell" style="width: 300px">
                        <el-select
                          class="table-select-box"
                          size="large"
                          v-model="item.boxNumber"
                          @change="editBoxLabel(item.code, item.quantity, item.boxNumber)"
                        >
                          <el-option
                            v-for="boxLabel in newTransaction.boxLabels"
                            class="table-select-box"
                            :value="boxLabel.boxNumber"
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
              <!-- <div class="row">
                  <input
                    placeholder="Input Items Remarks"
                    v-model="remarks"
                    @input="filterRemarks"
                    class="input-datalist"
                    list="remarks"
                  />
                  <datalist id="remarks">
                    <option v-for="remark in filteredRemarks" :value="remark">
                      {{ remark }}
                    </option>
                  </datalist>
                </div> -->
            </collapse-item>
            <label v-show="isValid.item" class="label-font">Item is required.</label>
          </collapse>
        </card>
        <div>
          <label>Insert Image (Only JPG and PNG files with max 2 MB and 10 images)</label
          ><br />
          <button
            class="btn btn-default my-0"
            onclick="document.getElementById('upload_image').click()"
          >
            Upload Image
          </button>
          <input
            id="upload_image"
            multiple
            type="file"
            ref="image"
            accept=".jpeg, .png"
            style="display: none"
            @change="onFileChange"
          />
          <span
            onclick="document.getElementById('upload_image').click()"
            style="
              border: solid;
              border-left: none;
              border-width: 1px;
              border-radius: 4px;
              border-top-left-radius: 0px;
              border-bottom-left-radius: 0px;
              padding: 8px 9px 10px;
            "
            >{{ uploadImageFileName }}</span
          >
        </div>
        <div v-if="images" class="row">
          <div
            class="col-md-1 gap-2"
            v-for="(image, index) in images"
            style="max-width: 10%; padding: 5px"
          >
            <!-- <button @click="removeImage(index)">Remove image</button> -->
            <p-button
              class="container-fluid pic-button nowrap"
              type="danger"
              @click="removeImage(index)"
            >
              <!-- <i slot="label" class="nc-icon nc-simple-remove"></i>  -->
              Remove Image
            </p-button>
            <img class="box-with-shadow" :src="image" />
          </div>
        </div>
      </div>
      <div class="text-center">
        <button
          class="btn btn-warning btn-fill btn-wd"
          data-bs-target="#confirmationDraftPullOut"
          data-bs-toggle="modal"
          v-show="isDenied"
        >
          Save as Draft
        </button>
        <button
          class="btn btn-warning btn-fill btn-wd"
          @click.prevent="cancelTransaction"
          v-show="isCancel"
        >
          Cancel
        </button>
        <button
          class="btn btn-info btn-fill btn-wd"
          @click.prevent="submit"
          v-show="isApproved"
        >
          Approved
        </button>
        <span
          id="submitBTN"
          class="d-inline-block"
          tabindex="0"
          data-bs-toggle="tooltip"
          data-bs-placement="bottom"
        >
          <button
            :disabled="isDisabledSubmit"
            class="btn btn-success btn-fill btn-wd"
            data-bs-target="#confirmationSubmitPullOut"
            data-bs-toggle="modal"
            v-show="isSubmit"
          >
            Submit
          </button></span
        >
      </div>
      <div class="clearfix"></div>
    </div>
    <EditBoxLabelModal
      :transferredData="transferredData"
      :newItemInputBox="newItemInputBox"
      :remarksList="remarksList"
      @closeModal="closeModal()"
      @renameBoxLabel="editingBoxLabel($event)"
    ></EditBoxLabelModal>

    <TransactionReceiptModal
      :transferTransactionID="transferTransactionID"
    ></TransactionReceiptModal>
    <DraftModal :transferTransactionID="transferTransactionID"></DraftModal>
    <ConfirmationSubmitPullOutModal
      @confirm="submit($event)"
    ></ConfirmationSubmitPullOutModal>
    <ConfirmationDraftPullOutModal
      @confirm="draft($event)"
    ></ConfirmationDraftPullOutModal>
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
import NotifAddBoxLabel from "../Components/Notification/NotifSuccessAddBoxLabel.vue";
import NotifAddItem from "../Components/Notification/NotifSuccessAddItem.vue";
import NotifDeleteBoxLabel from "../Components/Notification/NotifSuccessDeleteBoxLabel.vue";
import NotifDeleteItem from "../Components/Notification/NotifSuccessDeleteItem.vue";
import NotifDraftSave from "../Components/Notification/NotifSuccessDraftSave.vue";
import NotifItemChangeBoxLabel from "../Components/Notification/NotifSuccessItemChangeBoxLabel.vue";
import NotifItemQuantity from "../Components/Notification/NotifSuccessItemQuantity.vue";
import NotifRenameBoxLabel from "../Components/Notification/NotifSuccessRenameBoxLabel.vue";
import NotifSubmitTransaction from "../Components/Notification/NotifSuccessSubmitTransaction.vue";
import Swal from "sweetalert2";
import { Table, TableColumn } from "element-ui";
import axiosClient from "../../../../axios";
import EditBoxLabelModal from "./PullOutRequests/ModalPullOut/EditBoxLabelModal.vue";
import TransactionReceiptModal from "./PullOutRequests/ModalPullOut/TransactionReceiptModal.vue";
import DraftModal from "./PullOutRequests/ModalPullOut/DraftModal.vue";
import linkName from "../../../../linkName";
import ConfirmationSubmitPullOutModal from "./PullOutRequests/ModalPullOut/ConfirmationSubmitPullOutModal.vue";
import ConfirmationDraftPullOutModal from "./PullOutRequests/ModalPullOut/ConfirmationDraftPullOutModal.vue";
import axios, { isCancel } from "axios";
import * as XLSX from "xlsx";

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
    NotifAddBoxLabel,
    NotifAddItem,
    NotifDeleteBoxLabel,
    NotifDeleteItem,
    NotifDraftSave,
    NotifItemChangeBoxLabel,
    NotifItemQuantity,
    NotifRenameBoxLabel,
    NotifSubmitTransaction,
    EditBoxLabelModal,
    TransactionReceiptModal,
    DraftModal,
    ConfirmationSubmitPullOutModal,
    ConfirmationDraftPullOutModal,
  },
  data() {
    return {
      transferredData: "",
      isCompany: false,
      isChainCode: true,
      isBranchName: true,
      isTransactionType: true,
      isShowButton: false,
      itemCodeList: [],
      isEditBLDisabled: false,
      isBoxLabel: false,
      isItem: false,
      isAddItem: true,
      isDisabledSubmit: true,
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
      isApproved: false,
      isCancel: false,
      isSubmit: true,
      hoveredRow: null,
      uploadImageFileName: "Choose an Image",

      transactionTypeList: [
        {
          value: "CPO Item for Disposal in the Store c/o Supervisor",
          label: "CPO Item for Disposal in the Store c/o Supervisor",
        },
        {
          value: "CPO for Transfer to Another Store",
          label: "CPO for Transfer to Another Store",
        },
        {
          value: "CPO Back to WH via In-House Service",
          label: "CPO Back to WH via In-House Service",
        },
        {
          value: "CPO Back to WH via Chain Distribution Center",
          label: "CPO Back to WH via Chain Distribution Center",
        },
        {
          value: "CPO Back to WH via 3rd Party Trucking",
          label: "CPO Back to WH via 3rd Party Trucking",
        },
        {
          value: "CPO Back to WH c/o Supervisor",
          label: "CPO Back to WH c/o Supervisor",
        },
        { value: "Concess Direct Pull-Out", label: "Concess Direct Pull-Out" },
      ],
      newBoxLabel: "",
      newItemInput: "",
      newBrand: "",
      newItemDescription: "",
      newStyleCode: "",
      newItemCode: "",
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
      transferTransactionID: "",
      uniqueItemss: [],
      duplicateItemss: [],
      isDenied: true,
      dataArray: [],
      myData: [],
      counter: 0,
      remarksList: ["GOOD", "DAMAGED/DIRTY", "DISPOSAL"],
      images: [],
      files: [],
      img_counter: 0,
      strCategory: {
        boxNumber: 0,
        label: "",
      },
      isDraft: false,
      barcode: "item_16",
    };
  },
  computed: {
    filteredRemarks() {
      return this.remarksList.filter((newBoxLabel) => {
        return newBoxLabel.toLowerCase().includes(this.newBoxLabel.toLowerCase());
      });
    },
    sortedBoxes() {
      return this.newTransaction.boxLabels.sort((a, b) => a.boxNumber - b.boxNumber);
    },
    getItemBoxLabel() {
      return (boxNumber) => {
        const boxLabel = this.newTransaction.boxLabels.find(
          (label) => label.boxNumber === boxNumber
        );
        return boxLabel ? boxLabel.boxLabel : "";
      };
    },
  },
  mounted() {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(
      (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
    );
    // this.processData();
    this.fetchEdit();
    this.fetchCompany();
    if (window.resolveRouteChange) {
      window.resolveRouteChange();
      window.resolveRouteChange = null;
    }
    linkName.val = "Pull-Out Requisition Form";
    console.log(
      this.isCompany,
      this.isChainCode,
      this.isBranchName,
      this.isTransactionType
    );
  },
  watch: {
    newTransaction: {
      handler(val) {
        this.validateSubmit();
      },
      deep: true,
    },
    "newTransaction.items": function (val, oldVal) {
      this.addCategoryBoxLabel();
    },
    "newTransaction.boxLabels": function (val, oldVal) {
      this.enableDropDowns();
    },
    "newTransaction.company": function (val, oldVal) {
      this.showButtons();
    },
    "newTransaction.chainCode": function (val, oldVal) {
      this.showButtons();
    },
    "newTransaction.branchName": function (val, oldVal) {
      this.showButtons();
    },
    "newTransaction.transactionType": function (val, oldVal) {
      this.showButtons();
    },
    barcode: function (val, oldVal) {
      this.clearItemInput();
    },
    sortedBoxes(newSortedBoxes) {
      // Option 1: Using Vue.set
      Vue.set(this, "newTransaction.boxLabels", newSortedBoxes);

      // Option 2: Reassigning the array
      // this.boxes = newSortedBoxes.slice();
    },
  },
  methods: {
    clearItemInput() {
      this.newItemInput = "";
    },
    restrictChar($event) {
      let keyCode = $event.keyCode ? $event.keyCode : $event.which;
      if (keyCode == 91 || keyCode == 93) {
        // 46 is dot
        $event.preventDefault();
      }
    },
    addCategoryBoxLabel() {
      if (this.isDraft == false) {
        this.newTransaction.boxLabels.forEach((box) => {
          var filteredItems = this.newTransaction.items.filter(
            (item) => item.boxNumber === box.boxNumber
          );
          console.log("Filtered Items", filteredItems);
          var uniqueCategory = [
            ...new Set(filteredItems.map((item) => item.categorybrand)),
          ];
          let strCategory = "";
          for (let i = 0; i < uniqueCategory.length; i++) {
            if (i == 0) strCategory = " [ " + uniqueCategory[i];
            else strCategory = strCategory + ", " + uniqueCategory[i];
            if (i == uniqueCategory.length - 1) strCategory = strCategory + " ]";
          }
          let tempIndex = box.boxLabel.indexOf("[");
          if (tempIndex > 0) box.boxLabel = box.boxLabel.substr(0, tempIndex).trim();
          box.boxLabel = box.boxLabel + strCategory;
        });
        console.log("Changes on Category Box Label");
      }
      // console.log(this.newTransaction.boxLabels);
    },
    filterRemarks() {
      this.filteredRemarks = this.remarksList.filter((newBoxLabel) => {
        return newBoxLabel.toLowerCase().includes(this.newBoxLabel.toLowerCase());
      });
    },
    onFileChange(e) {
      var files = e.target.files || e.dataTransfer.files;

      var file = e.target.files[0];
      const maxSizeInBytes = 2 * 1024 * 1024; // 2MB

      if (file && file.size > maxSizeInBytes) {
        // File size exceeds the limit
        alert("Please select an image file smaller than 2MB.");
        // Clear the file input
        e.target.value = "";
        return;
      } else {
        this.files.push(e.target.files[0]);

        if (!files.length) return;
        this.createImage(files);
        this.uploadImageFileName = e.target.files[0].name;
      }
    },
    createImage(files) {
      var vm = this;
      for (var index = 0; index < files.length; index++) {
        var reader = new FileReader();
        reader.onload = function (event) {
          const imageUrl = event.target.result;
          vm.images.push(imageUrl);
        };
        reader.readAsDataURL(files[index]);
      }
    },
    removeImage(index) {
      this.images.splice(index, 1);
      this.uploadImageFileName = "Choose an Image";
    },
    clearImage() {
      document.getElementById("formFile").value = null;
      frame.src = "";
    },
    handleFileUploadImage() {
      console.log("image:", this.files);
      console.log("Transaction ID:", this.transferTransactionID);
      const config = {
        headers: {
          "content-type": "multipart/form-data",
        },
      };

      if (this.img_counter < this.files.length) {
        axiosClient
          .post(
            "/upload",
            {
              image: this.files[this.img_counter],
              company: this.newTransaction.company,
              branchName: this.newTransaction.branchName,
              transactionID: this.transferTransactionID,
            },
            config
          )
          .then((response) => {
            console.log("Success Upload Image", response.data);
            this.img_counter++;
            setTimeout(this.handleFileUploadImage, 2000);
          })
          .catch((error) => {
            console.error(error);
          });
      }
    },
    handleFileUpload(event) {
      console.log("File Name: ", event.target.files[0].name);
      const file = event.target.files[0];
      const reader = new FileReader();

      reader.onload = (e) => {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: "array" });

        const worksheetName = workbook.SheetNames[0];
        const worksheet = workbook.Sheets[worksheetName];

        const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
        // header: 1 indicates that the first row contains column headers

        // Save the JSON data to a Vue variable
        console.log("Excel Data", jsonData);
        this.myData = jsonData;
        let arrayBox = [];
        for (var x = 0; x < this.myData.length; x++) {
          this.dataArray.push(this.myData[x]);
          arrayBox.push(this.myData[x][1]);
        }

        console.log("Data:", this.dataArray);

        console.log("Import Excel", this.dataArray.length);

        const filteredArray = arrayBox.filter((value, index, self) => {
          return self.indexOf(value) === index;
        });

        let tempBoxLabel = [];
        for (var x = 0; x < filteredArray.length; x++) {
          tempBoxLabel = {
            id: x + 1,
            boxNumber: x + 1,
            boxLabel: filteredArray[x],
          };

          this.newTransaction.boxLabels.push(tempBoxLabel);
          let tempItem = [];

          if (this.newTransaction.boxLabels.length == 0) {
            tempItem = {
              id: this.newTransaction.boxLabels.length + 1,
            };
          } else {
            tempItem = {
              id:
                this.newTransaction.boxLabels[this.newTransaction.boxLabels.length - 1]
                  .id + 1,
            };
          }
          this.newItemInputBox.push(tempItem);
        }

        console.log(filteredArray);
        // for (var x = 0; x < this.dataArray.length; x++) {
        //   console.log("ItemCode", this.dataArray[x][2]);
        // }
        // var y = 0;
        // while (y < this.dataArray.length) {

        //   y++;
        // }
        this.counter = 0;
        this.saveItemsExcel();
      };

      reader.readAsArrayBuffer(file);
    },

    saveItemsExcel() {
      if (this.counter < this.dataArray.length) {
        // console.log("Data", this.dataArray[this.counter - 1]);
        var boxNumber = this.dataArray[this.counter][0];
        var quantity = this.dataArray[this.counter][3];
        axiosClient
          .get("/fetchSameItems", {
            params: {
              company: this.newTransaction.company,
              ItemNo: this.dataArray[this.counter][2],
            },
          })
          .then((response) => {
            console.log("Data", response.data);
            if (response.data.length == 0) {
              setTimeout(this.saveItemsExcel, 2000);
            }
            for (var x = 0; x < response.data.length; x++) {
              var flag = true;

              for (var i = 0; i < this.newTransaction.items.length; i++) {
                if (
                  this.newTransaction.items[i].code == response.data[x].ItemNo &&
                  this.newTransaction.items[i].boxNumber == boxNumber
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
                  description: response.data[x].ItemDescription,
                  categorybrand: response.data[x].brandNames,
                  quantity: quantity,
                  size: response.data[x].Size,
                  color: response.data[x].Color,
                  // boxLabel: label,
                  boxNumber: parseInt(boxNumber),
                  category: response.data[x].Category,
                };
                this.newTransaction.items.push(tempItem);
              }

              console.log("Items", this.newTransaction.items);

              this.isAddItem = true;
              this.notifyVue("AddItem", "bottom", "right");
              setTimeout(this.saveItemsExcel, 2000);
            }
          })
          .catch((error) => {
            console.error(error);
          });
      }
      this.counter++;
    },

    numberOnly($event) {
      let keyCode = $event.keyCode ? $event.keyCode : $event.which;
      if (keyCode < 48 || keyCode > 57) {
        // 46 is dot
        $event.preventDefault();
      }
    },
    validateSubmit() {
      let uniqueItems = [
        ...new Set(this.newTransaction.items.map((item) => item.boxNumber)),
      ];
      let itemsValidation = false;
      let itemsValidationTemp = false;
      for (let j in this.newTransaction.boxLabels) {
        for (let i in uniqueItems) {
          if (uniqueItems[i] == this.newTransaction.boxLabels[j].id) {
            itemsValidationTemp = true;
            itemsValidation = true;
            break;
          } else {
            itemsValidation = false;
            itemsValidationTemp = false;
          }
        }
      }
      for (let x in this.newTransaction.items)
        if (this.newTransaction.items[x].quantity == 0) {
          itemsValidation = false;
          let tooltip = bootstrap.Tooltip.getInstance("#submitBTN");
          tooltip._config.title = "No items should have a quantity with 0.";
          tooltip.update();
          break;
        }
      if (!itemsValidationTemp) {
        let tooltip = bootstrap.Tooltip.getInstance("#submitBTN");
        tooltip._config.title = "Kindly add item/s on every boxes you've added.";
        tooltip.update();
      }
      if (
        !this.newTransaction.company ||
        !this.newTransaction.chainCode ||
        !this.newTransaction.branchName ||
        !this.newTransaction.transactionType
      ) {
        let tooltip = bootstrap.Tooltip.getInstance("#submitBTN");
        tooltip._config.title = "Complete the form above to enable this button.";
        tooltip.update();
      } else if (this.newTransaction.boxLabels.length <= 0) {
        let tooltip = bootstrap.Tooltip.getInstance("#submitBTN");
        tooltip._config.title =
          "No box added. Kindly add box/es to be able to add item/s.";
        tooltip.update();
      } else if (this.newTransaction.items.length <= 0) {
        let tooltip = bootstrap.Tooltip.getInstance("#submitBTN");
        tooltip._config.title =
          "No items added. Kindly add item/s for this pull-out request.";
        tooltip.update();
      }
      if (
        this.newTransaction.company &&
        this.newTransaction.chainCode &&
        this.newTransaction.branchName &&
        this.newTransaction.transactionType &&
        this.newTransaction.boxLabels.length > 0 &&
        itemsValidation
      ) {
        this.isDisabledSubmit = false;
        let tooltip = bootstrap.Tooltip.getInstance("#submitBTN");
        tooltip.disable();
      } else this.isDisabledSubmit = true;
    },
    editingBoxLabel({ index, value, tempBL }) {
      if (value) this.newTransaction.boxLabels[index].boxLabel = value;
      else this.newTransaction.boxLabels[index].boxLabel = tempBL;
    },
    enableDropDowns() {
      if (this.newTransaction.boxLabels.length == 0) {
        this.isCompany = false;
        this.isChainCode = false;
        this.isBranchName = false;
        this.isTransactionType = false;
        this.isEditBLDisabled = true;
        // console.log(this.newTransaction.boxLabels.length);
      } else this.isEditBLDisabled = false;
    },
    showButtons() {
      if (
        this.newTransaction.company &&
        this.newTransaction.chainCode &&
        this.newTransaction.branchName &&
        this.newTransaction.transactionType
      ) {
        this.isShowButton = true;
        if (this.newTransaction.boxLabels.length == 0) this.isEditBLDisabled = true;
        else this.isEditBLDisabled = false;
      } else this.isShowButton = false;
    },
    fetchEdit() {
      try {
        const uri = window.location.href;
        var transactionID = uri.split("?")[1];
        var id = transactionID.split("=")[1].split("&")[0];
        // console.log("ID", id);
        // console.log("Company", transactionID.split("=")[2]);
        var company = transactionID.split("=")[2];
        this.isDraft = true;
        console.log("Draft", this.isDraft);
        axiosClient
          .get("/fetchEditDraftBranch", {
            params: {
              company: company,
              plID: id,
            },
          })
          .then((response) => {
            // console.log("Edit Branch:", response.data, this.itemss);
            this.newTransaction.company = response.data[0].company;
            this.newTransaction.branchName = response.data[0].branchName;
            this.newTransaction.chainCode = response.data[0].chainCode;
            this.newTransaction.transactionType = response.data[0].transactionType;

            if (
              response.data[0].status == "denied" ||
              response.data[0].status == "endorsement" ||
              response.data[0].status == "unprocessed"
            ) {
              this.isDenied = false;
              this.isCancel = true;
            }

            if (response.data[0].status == "endorsement") {
              this.isApproved = true;
              this.isSubmit = false;
            }

            axiosClient
              .get("/fetchEditDraftItem", {
                params: {
                  company: company,
                  plID: id,
                },
              })
              .then((response) => {
                // console.log("Edit Item:", response.data);
                for (var x = 0; x < response.data.length; x++) {
                  this.newTransaction.items.push(response.data[x]);
                }

                const filteredData = this.newTransaction.items.filter(
                  (obj, index, self) => {
                    const boxLabel = obj.boxLabel;
                    return self.findIndex((o) => o.boxLabel === boxLabel) === index;
                  }
                );

                const boxData = filteredData.map((obj) => {
                  return {
                    boxLabel: obj.boxLabel,
                    boxNumber: obj.boxNumber,
                  };
                });

                // console.log("Filtered Box label:", boxData);

                for (var x = 0; x < boxData.length; x++) {
                  // console.log("Box Label:", boxData[x].boxLabel);
                  this.newTransaction.boxLabels.push({
                    id: boxData[x].boxNumber,
                    boxNumber: boxData[x].boxNumber,
                    boxLabel: boxData[x].boxLabel,
                  });
                  this.newItemInputBox.push({
                    id: boxData[x].boxNumber,
                  });
                  console.log("New Box Label:", this.newTransaction.boxLabels);
                }

                this.isDraft = false;
                console.log("Draft:", this.isDraft);
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
                  // console.log("Yes", this.isShowButton);
                  this.isCompany = false;
                  this.isChainCode = false;
                  this.isBranchName = false;
                  this.isTransactionType = false;
                  // this.isShowButton = true;
                  // console.log("Yes 2", this.isShowButton);
                }

                if (this.newTransaction.items.length) {
                  // console.log("Disable", this.newTransaction.items.length);
                  this.isCompany = true;
                  this.isChainCode = true;
                  this.isBranchName = true;
                  this.isTransactionType = true;
                  // this.isShowButton = true;
                }
              })
              .catch((error) => {
                // console.error(error);
              });
          })
          .catch((error) => {
            // console.error(error);
          });
      } catch {
        // console.log("No Edit");
        //Fetching Promo Info
        axiosClient
          .get("/fetchPromoBranchInfo", {
            params: {
              userID: sessionStorage.getItem("UserID"),
            },
          })
          .then((response) => {
            // console.log("Promo Branch Info:", response.data);
            this.newTransaction.company = response.data[0].company;
            this.newTransaction.chainCode = response.data[0].chainCode;
            this.newTransaction.branchName = response.data[0].branchName;
            this.fetchChainCode();
            this.fetchChainName();
          })
          .catch((error) => {
            // console.error(error);
          });
      }
    },
    cancelTransaction() {
      // location.href = "http://192.168.0.7:4040/#/pull-out/requests";
      this.$router.push({
        path: "/pull-out/requests",
      });
    },
    updateData(updatedData) {
      // this.newTransaction.boxLabels = updatedData;
      // console.log("das", updatedData);
    },
    openModal(data) {
      data.boxLabels.forEach((box) => {
        let tempIndex = box.boxLabel.indexOf("[");
        if (tempIndex > 0) box.boxLabel = box.boxLabel.substr(0, tempIndex).trim();
      });
      console.log("Edit: ", data);
      this.transferredData = data;
    },
    closeModal() {
      console.log("CLOSE");
      this.addCategoryBoxLabel();
      this.transferredData = "";
    },
    showButton(itemId) {
      this.hoveredRow = itemId;
    },
    hideButton(itemId) {
      this.hoveredRow = null;
    },
    removeItem(code, boxNumber) {
      this.validateSubmit();
      // console.log("New DATAs", code, boxNumber);
      this.newTransaction.items = this.newTransaction.items.filter(
        (item) => item.code !== code || item.boxNumber !== boxNumber
      );

      this.notifyVue("DeleteItem", "bottom", "right");

      // alert("Your data: " + JSON.stringify(data));

      // console.log("New DATA", data);
    },
    fetchItems() {
      console.log("Barcode", this.barcode);
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
                barcode: this.barcode,
              },
            }) // Make a GET request to the specified URL
            .then((response) => {
              //console.log("Item Code Response: ", response.data);
              this.itemCodeList = response.data; // Update the 'data' variable with the retrieved data
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
              //console.log("Item Code Response: ", response.data);
              this.itemCodeList = response.data; // Update the 'data' variable with the retrieved data
            })
            .catch((error) => {
              // console.error(error.reponse); // Handle any errors that may occur
            });
        }
      } else if (this.newItemInput.length == 0) this.itemCodeList = [];
    },
    compareItemCode() {
      let value = this.newItemInput;
      if (value.indexOf(" - ") >= 0) {
        let parts = value.split(" - ");
        this.newItemInput = parts[0];
      }
    },
    handleQuantity(item) {
      //console.log("Item Quantity", item.quantity);
      if (!item.quantity) {
        //console.log("Empty");
        item.quantity = 0;
      }
      this.notifyVue("ItemQuantity", "bottom", "right");
    },
    notifyVue(notify, verticalAlign, horizontalAlign) {
      var notification = "";
      let notifType = "";

      if (notify === "AddBoxLabel") {
        notification = NotifAddBoxLabel;
        notifType = "success";
      } else if (notify === "AddItem") {
        notification = NotifAddItem;
        notifType = "success";
      } else if (notify === "DeleteBoxLabel") {
        notification = NotifDeleteBoxLabel;
        notifType = "danger";
      } else if (notify === "DeleteItem") {
        notification = NotifDeleteItem;
        notifType = "danger";
      } else if (notify === "DraftSave") {
        notification = NotifDraftSave;
        notifType = "success";
      } else if (notify === "ChangeBoxLabel") {
        notification = NotifItemChangeBoxLabel;
        notifType = "success";
      } else if (notify === "ItemQuantity") {
        notification = NotifItemQuantity;
        notifType = "success";
      } else if (notify === "RenameBoxLabel") {
        notification = NotifRenameBoxLabel;
        notifType = "success";
      } else {
        notification = NotifSubmitTransaction;
        notifType = "success";
      }

      this.$notify({
        component: notification,
        // icon: "nc-icon nc-bell-55",
        horizontalAlign: horizontalAlign,
        verticalAlign: verticalAlign,
        type: notifType,
        props: {
          customValue: "Success",
        },
      });
    },
    fetchCompany() {
      if ("User" != sessionStorage.getItem("Position"))
        axiosClient
          .get("/fetchCompany")
          .then((response) => {
            console.log("Company Response:", response.data);
            this.companyList = response.data;
          })
          .catch((error) => {
            //console.error(error);
          });
      else
        axiosClient
          .get("/fetchCompanyByUser", {
            params: {
              userID: sessionStorage.getItem("UserID"),
            },
          })
          .then((response) => {
            console.log("Company Response:", response.data);
            this.companyList = response.data;
          })
          .catch((error) => {
            //console.error(error);
          });
    },
    fetchChainCode() {
      if ("User" == sessionStorage.getItem("Position"))
        axiosClient
          .get("/fetchChainByUser", {
            params: {
              company: this.newTransaction.company,
              userID: sessionStorage.getItem("UserID"),
            },
          })
          .then((response) => {
            //console.log("Chain Code Response:", response.data);
            this.chainCodeList = response.data;
          })
          .catch((error) => {
            //console.error(error);
          });
      // this.notifyVue("bottom", "right");
      else
        axiosClient
          .get("/fetchChain", {
            params: {
              company: this.newTransaction.company,
            },
          })
          .then((response) => {
            //console.log("Chain Code Response:", response.data);
            this.chainCodeList = response.data;
          })
          .catch((error) => {
            //console.error(error);
          });
      // this.notifyVue("bottom", "right");

      this.isChainCode = false;
    },
    fetchChainName() {
      if ("User" == sessionStorage.getItem("Position"))
        axiosClient
          .get("/fetchChainNameByUser", {
            params: {
              chainCode: this.newTransaction.chainCode,
              userID: sessionStorage.getItem("UserID"),
            },
          })
          .then((response) => {
            //console.log("Chain Name Response:", response.data);
            this.branchNameList = response.data;
          })
          .catch((error) => {
            //console.error(error);
          });
      else
        axiosClient
          .get("/fetchChainName", {
            params: {
              chainCode: this.newTransaction.chainCode,
            },
          })
          .then((response) => {
            //console.log("Chain Name Response:", response.data);
            this.branchNameList = response.data;
          })
          .catch((error) => {
            //console.error(error);
          });

      this.isBranchName = false;
    },
    addItem(boxNUMBER) {
      this.isItem = false;
      this.isAddItem = false;
      this.showItemInput = boxNUMBER;
      console.log("clicked nUMBER:", this.showItemInput);
    },
    saveItem(boxNUMBER) {
      if (this.barcode == "item_16") {
        if (this.newItemInput.length > 16)
          this.newItemInput = this.newItemInput.slice(0, 16);
      } else {
        this.newItemInput = this.newItemInput.slice(0, 12);
      }
      if (this.barcode == "item_12") {
        axiosClient
          .get("/fetchItemsBarcode", {
            params: {
              ItemNo: this.newItemInput,
              company: this.newTransaction.company,
            },
          })
          .then((response) => {
            console.log("Item Barcode to Item No: ", response.data[0]);
            this.newItemInput = response.data[0].ItemNo;
          })
          .catch((error) => {
            console.error(error);
          });
      }
      // console.log("Length Item Code:", this.newItemCode.length > 16);
      setTimeout(() => {
        console.log("New Item Input:", this.newItemInput);
        axiosClient
          .get("/compareItemCode", {
            params: {
              companyType: this.newTransaction.company,
              ItemNo: this.newItemInput,
            },
          })
          .then((response) => {
            console.log("Success Item No:", response.data);
            //console.log("Success Item Description", response.data[0].ItemDescription);
            this.newItemCode = response.data[0].ItemNo;
            this.newItemDescription = response.data[0].ItemDescription;
            this.newStyleCode = response.data[0].StyleCode;

            let brandCode = response.data[0].ItemNo.toString().substr(1, 2);

            axiosClient
              .get("/fetchBrands", {
                params: {
                  companyType: this.newTransaction.company,
                  brandCode: brandCode,
                },
              })
              .then((response) => {
                //console.log("Success", response.data);
                this.newBrand = response.data[0].brandNames;
              })
              .catch((error) => {
                //console.error(error);
              });
          })
          .catch((error) => {
            this.missItemCode = 0;
            !this.newItemInput ? true : (this.isRightCode = true);
            //console.log("Not Equal");
            //console.error(error);
          });
      }, 300);

      setTimeout(() => {
        console.log("Barcode Selected", this.barcode);
        let confirmSave = false;
        this.isNewItem = !this.newItemInput ? true : false;

        if (this.isNewItem) {
          this.isRightCode = false;
          return 0;
        }

        axiosClient
          .get("/fetchSameItem", {
            params: {
              company: this.newTransaction.company,
              ItemCode: this.newItemCode,
              ItemDescription: this.newItemDescription,
              StyleCode: this.newStyleCode,
            },
          })
          .then((response) => {
            //console.log("Same Item:", response.data);
            for (var x = 0; x < response.data.length; x++) {
              var flag = true;
              for (var i = 0; i < this.newTransaction.items.length; i++) {
                if (
                  this.newTransaction.items[i].code == response.data[x].ItemNo &&
                  this.newTransaction.items[i].boxNumber == boxNUMBER
                ) {
                  this.newTransaction.items[i].quantity =
                    parseInt(this.newTransaction.items[x].quantity) + 1;
                  flag = false;
                  break;
                }
              }
              if (flag) {
                console.log("Company:", this.newTransaction.company);
                if (
                  this.newTransaction.company == "NBFI" ||
                  this.newTransaction.company == "CMC" ||
                  this.newTransaction.company == "ASC"
                ) {
                  var categorybrand = this.newBrand;
                } else {
                  var categorybrand = response.data[x].Category;
                }
                let tempItem = {
                  code: response.data[x].ItemNo,
                  description: response.data[x].ItemDescription,
                  categorybrand: categorybrand,
                  quantity: 0,
                  size: response.data[x].Size,
                  color: response.data[x].Color,
                  // boxLabel: label,
                  boxNumber: boxNUMBER,
                  category: response.data[x].Category,
                };
                this.newTransaction.items.push(tempItem);
              }
              this.isRightCode = false;
              this.isItem = false;
              this.isAddItem = true;
              this.newItemInput = "";
              this.showItemInput = "";
              this.notifyVue("AddItem", "bottom", "right");
            }
          })
          .catch((error) => {
            //console.error(error);
          });
        console.log("Confirm Save: ", confirmSave);
        console.log("Items: ", this.newTransaction.items);
        console.log(this.newListBoxLabel);
      }, 500);
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
      this.isEditBLDisabled = true;
    },
    saveBoxLabel() {
      this.isNewBoxLabel = !this.newBoxLabel ? true : false;

      if (this.isNewBoxLabel) {
        return 0;
      }
      this.isBoxLabel = false;
      //console.log(this.newTransaction.boxLabels.length);
      let tempBoxLabel = [];

      if (this.newTransaction.boxLabels.length == 0) {
        tempBoxLabel = {
          id: this.newTransaction.boxLabels.length + 1,
          boxNumber: this.newTransaction.boxLabels.length + 1,
          boxLabel: this.newBoxLabel,
        };
      } else {
        tempBoxLabel = {
          id:
            this.newTransaction.boxLabels[this.newTransaction.boxLabels.length - 1].id +
            1,
          boxNumber: this.newTransaction.boxLabels.length + 1,
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
      // this.notifyVue("AddBoxLabel", "bottom", "right");
      // console.log("Console:", this.newTransaction.boxLabels);
      //Disable the above select buttons
      this.isCompany = true;
      this.isChainCode = true;
      this.isBranchName = true;
      this.isTransactionType = true;
      this.notifyVue("AddBoxLabel", "bottom", "right");
      this.isEditBLDisabled = false;
    },
    cancelBoxLabel() {
      this.isNewBoxLabel = false;
      this.isBoxLabel = false;
      this.newBoxLabel = "";
      if (this.newTransaction.boxLabels.length == 0) this.isEditBLDisabled = true;
      else this.isEditBLDisabled = false;
    },
    submit(confirmation) {
      if (confirmation) {
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

          //console.log("Company: ", this.isValid.company);

          console.log("company", this.newTransaction.items);
          // alert("Your data: " + JSON.stringify(this.newTransaction));

          if (
            sessionStorage.getItem("Position") == "Agent" ||
            sessionStorage.getItem("Position") == "Admin"
          ) {
            var status = "endorsement";
          } else if (
            sessionStorage.getItem("Position") == "Admin" ||
            sessionStorage.getItem("Position") == "Approver"
          ) {
            var status = "approved";
          } else {
            var status = "unprocessed";
          }
          axiosClient
            .post("/updatePullOutBranchRequest", {
              id: id,
              chainCode: this.newTransaction.chainCode,
              companyType: this.newTransaction.company,
              branchName: this.newTransaction.branchName,
              transactionType: this.newTransaction.transactionType,
              email: sessionStorage.getItem("Email"),
              status: status,
            })
            .then((response) => {
              //console.log("Success Branch Save: ", response.data);
              this.transferTransactionID = id;

              for (var x = 0; x < this.newTransaction.items.length; x++) {
                let labelBox = "";
                for (let box of this.newTransaction.boxLabels) {
                  if (box.id == this.newTransaction.items[x].boxNumber) {
                    labelBox = box.boxLabel;
                  }
                }

                axiosClient
                  .post("/updatePullOutItemRequest", {
                    id: this.newTransaction.items[x].id,
                    plID: id,
                    companyType: this.newTransaction.company,
                    brand: this.newTransaction.items[x].categorybrand,
                    boxNumber: this.newTransaction.items[x].boxNumber,
                    boxLabel: labelBox,
                    itemCode: this.newTransaction.items[x].code,
                    quantity: this.newTransaction.items[x].quantity,
                    email: sessionStorage.getItem("Email"),
                    status: status,
                  })
                  .then((response) => {
                    console.log("Success Items Save: ", response.data);
                    // window.location.href =
                    //   "http://192.168.0.7:4040/#/pull-out/requisition-form";
                  })
                  .catch((error) => {
                    console.error(error);
                  });
              }
              const transactionModal = new bootstrap.Modal("#transactionReceipt");
              transactionModal.show();
            })
            .catch((error) => {
              //console.error(error);
            });
          setTimeout(this.handleFileUploadImage, 2000);
        } catch {
          this.isValid.company = !this.newTransaction.company ? true : false;
          this.isValid.chainCode = !this.newTransaction.chainCode ? true : false;
          this.isValid.branchName = !this.newTransaction.branchName ? true : false;
          this.isValid.transactionType = !this.newTransaction.transactionType
            ? true
            : false;
          this.isValid.boxLabel = !this.newTransaction.boxLabels.length ? true : false;
          this.isValid.item = !this.newTransaction.items.length ? true : false;

          //console.log("Company: ", this.isValid.company);

          //console.log("company", this.newTransaction.company);
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
              //console.log("Success Branch Save: ", response.data);
              this.transferTransactionID = response.data.id;

              for (var x = 0; x < this.newTransaction.items.length; x++) {
                let labelBox = "";
                for (let box of this.newTransaction.boxLabels) {
                  if (box.id == this.newTransaction.items[x].boxNumber) {
                    labelBox = box.boxLabel;
                  }
                }
                axiosClient
                  .post("/savePullOutItemRequest", {
                    plID: response.data.id,
                    companyType: this.newTransaction.company,
                    brand: this.newTransaction.items[x].categorybrand,
                    boxNumber: this.newTransaction.items[x].boxNumber,
                    boxLabel: labelBox,
                    itemCode: this.newTransaction.items[x].code,
                    quantity: this.newTransaction.items[x].quantity,
                    email: sessionStorage.getItem("Email"),
                    status: "unprocessed",
                  })
                  .then((response) => {
                    //console.log("Success Items Save: ", response.data);
                  })
                  .catch((error) => {
                    //console.error(error);
                  });
              }
              const transactionModal = new bootstrap.Modal("#transactionReceipt");
              transactionModal.show();
            })
            .catch((error) => {
              //console.error(error);
            });
        }
        setTimeout(this.handleFileUploadImage, 2000);
      }
    },
    draft(confirmation) {
      console.log("Confirmed");
      if (confirmation) {
        console.log("Confirmed Data:", confirmation);
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
              //console.log("Items Length:", this.newTransaction.items.length);
              for (var x = 0; x < this.newTransaction.items.length; x++) {
                let labelBox = "";
                for (let box of this.newTransaction.boxLabels) {
                  if (box.id == this.newTransaction.items[x].boxNumber) {
                    labelBox = box.boxLabel;
                  }
                }
                axiosClient
                  .post("/updatePullOutItemRequest", {
                    plID: id,
                    companyType: this.newTransaction.company,
                    brand: this.newTransaction.items[x].categorybrand,
                    boxNumber: this.newTransaction.items[x].boxNumber,
                    boxLabel: labelBox,
                    itemCode: this.newTransaction.items[x].code,
                    quantity: this.newTransaction.items[x].quantity,
                    status: "draft",
                    email: sessionStorage.getItem("Email"),
                  })
                  .then((response) => {
                    console.log("Success Items Save: ", response.data);
                  })
                  .catch((error) => {
                    //console.error(error);
                  });
              }
              const draftModal = new bootstrap.Modal("#draftModal");
              draftModal.show();
            })
            .catch((error) => {
              //console.error(error);
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
              //console.log("Success Branch Save: ", response.data);

              for (var x = 0; x < this.newTransaction.items.length; x++) {
                let labelBox = "";
                for (let box of this.newTransaction.boxLabels) {
                  if (box.id == this.newTransaction.items[x].boxNumber) {
                    labelBox = box.boxLabel;
                  }
                }
                axiosClient
                  .post("/savePullOutItemRequest", {
                    plID: response.data.id,
                    companyType: this.newTransaction.company,
                    brand: this.newTransaction.items[x].categorybrand,
                    boxNumber: this.newTransaction.items[x].boxNumber,
                    boxLabel: labelBox,
                    itemCode: this.newTransaction.items[x].code,
                    quantity: this.newTransaction.items[x].quantity,
                    status: "draft",
                    email: sessionStorage.getItem("Email"),
                  })
                  .then((response) => {
                    //console.log("Success Items Save: ", response.data);
                  })
                  .catch((error) => {
                    //console.error(error);
                  });
              }
              const draftModal = new bootstrap.Modal("#draftModal");
              draftModal.show();
            })
            .catch((error) => {
              //console.error(error);
            });
        }
      }
    },
    editBoxLabel(code, quantity, boxNumber) {
      console.log("Items", code, quantity, boxNumber);
      this.validateSubmit();
      //console.log(
      //   "Item Code Edit:",
      //   code,
      //   " Quantity:",
      //   quantity,
      //   " Box Number",
      //   boxNumber
      // );
      //console.log(" == : ", this.newTransaction.items);
      var filteredItems = this.newTransaction.items.filter(
        (item) => item.code === code && item.boxNumber === boxNumber
      );

      //console.log("Filtered Items: ", filteredItems);
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
      //console.log("Unique Items: 1", uniqueItems);
      //console.log("Unique Items: Code", uniqueItems[0].code);
      //console.log("Unique Items: Box Number", uniqueItems[0].boxNumber);

      this.newTransaction.items = this.newTransaction.items.filter(
        (item) =>
          item.code !== uniqueItems[0].code || item.boxNumber !== uniqueItems[0].boxNumber
      );
      this.newTransaction.items.push(uniqueItems[0]);
      this.notifyVue("ChangeBoxLabel", "bottom", "right");
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

.gendelete-buttons {
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

.card-attachment {
  background-color: #66615b;
}

.card-generator {
  margin-top: 100px;
}

.box-with-shadow {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 2px 4px rgba(0, 0, 0, 0.06);
  padding: 10px;
}

.pic-button {
  padding-top: 5px;
  padding-bottom: 5px;
  font-size: 10px !important;
}
</style>
