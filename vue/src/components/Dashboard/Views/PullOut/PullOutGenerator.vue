<template>
  <div class="card card-generator">
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
          <fg-input label="Chain Name" :disabled="isChainCode">
            <el-select
              class="select-default"
              size="large"
              placeholder="Select Chain Name"
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
              >Chain Name is required.</label
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
        <!-- <card type="plain"> -->
        <div class="row mt-1">
          <div
            class="col-xl-2 col-md-3 col-sm-6 p-0 h-div-32 mr-xl-2"
            v-show="isShowButton"
          >
            <p-button
              type="default"
              link
              class="font-weight-bold w-full m-0"
              @click.prevent="addBoxLabel()"
            >
              <i class="nc-icon nc-simple-add font-weight-bold"></i> Add Box Label
            </p-button>
          </div>
          <div
            class="col-xl-2 col-md-3 col-sm-6 p-0 h-div-32 mr-xl-2"
            v-show="isShowButton"
          >
            <p-button
              :disabled="isEditBLDisabled"
              type="default"
              link
              class="font-weight-bold w-full m-0"
              data-bs-toggle="modal"
              data-bs-target="#editboxlabelModal"
              @click="openModal(newTransaction)"
            >
              <i class="nc-icon nc-ruler-pencil font-weight-bold"></i> Delete Box Label
            </p-button>
          </div>
          <div
            class="col-xl-2 col-md-3 col-sm-6 p-0 h-div-32 mr-xl-2"
            v-show="isShowButton"
          >
            <span
              id="excelBtn"
              class="d-inline-block w-full"
              tabindex="0"
              data-bs-toggle="tooltip"
              data-bs-placement="bottom"
            >
              <p-button
                type="default"
                link
                class="font-weight-bold w-full m-0"
                onclick="document.getElementById('import_items_excel').click()"
              >
                Import Items Excel
              </p-button></span
            >
            <input
              id="import_items_excel"
              type="file"
              accept=".xlsx"
              style="display: none"
              @change="handleFileUpload"
            />
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12 p-0 mt-2" v-show="isBoxLabel">
            <form class="p-0" @submit.prevent="saveBoxLabel()">
              <div>
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
              <div class="mt-n3">
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
            </form>
          </div>
          <label v-show="isValid.boxLabel" class="label-font"
            >Box Label is required.</label
          >
        </div>
        <collapse>
          <collapse-item
            v-for="(boxLabel, indexBox) in newTransaction.boxLabels"
            :key="boxLabel.id"
            :title="`Box No. ${boxLabel.boxNumber} of ${newTransaction.boxLabels.length} \xa0\xa0\xa0 ${boxLabel.boxLabel}`"
            name="1"
            class="font-weight-bold"
          >
            <div v-show="isAddItem">
              <button
                class="btn btn-transparent bold-button"
                @click.prevent="addItem(boxLabel.boxNumber)"
              >
                <i class="nc-icon nc-simple-add font-weight-bold"></i> Add Item
              </button>
              <button
                v-if="indexBox == deleteItemBtn && multipleSelection.length != 0"
                class="btn btn-danger bold-button"
                @click="deleteSelectedItems()"
              >
                <i class="nc-icon nc-simple-remove font-weight-bold"></i> Delete Item
              </button>
            </div>
            <div>
              <div
                class="row"
                v-for="newItem in newItemInputBox"
                v-show="newItem.id === boxLabel.boxNumber"
              >
                <form @submit.prevent="saveItem(boxLabel.boxNumber)">
                  <div class="row" v-if="newItem.id == showItemInput">
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 d-flex mt-2">
                      <div class="col-6 text-center">
                        <input
                          type="radio"
                          v-model="barcode"
                          name="barcode"
                          value="item_16"
                          checked
                        />
                        <label for="item_16">16 Digit Barcode</label><br />
                      </div>
                      <div class="col-6 text-center">
                        <input
                          type="radio"
                          v-model="barcode"
                          name="barcode"
                          value="item_12"
                        />
                        <label for="item_12">12 Digit Barcode</label><br />
                      </div>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 d-flex mt-2">
                      <input
                        type="text"
                        placeholder="Item Code"
                        v-model="newItemInput"
                        @input="fetchItems"
                        @change="compareItemCode"
                        list="items"
                        class="input-datalist"
                        maxlength="150"
                      />

                      <datalist id="items">
                        <option
                          v-for="item in itemCodeList"
                          :value="item.ItemNo + ' - ' + item.ItemDescription"
                          :key="item.ItemNo"
                        ></option>
                      </datalist>

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
                    <label
                      v-show="isRightCode"
                      class="label-font font-weight-normal"
                      style="margin-left: 185px"
                      >Invalid Item Code.</label
                    >
                    <label
                      v-show="isNewItem"
                      class="label-font font-weight-normal"
                      style="margin-left: 185px"
                      >Item code is empty.</label
                    >
                  </div>
                </form>
              </div>
            </div>

            <!-- after table -->
            <div class="row">
              <el-table
                ref="itemsDataTable"
                class="p-0"
                border
                :header-cell-style="headerCellStyle"
                :cell-style="cellStyle"
                :data="tableData[indexBox]"
                style="width: 100%"
                @select="handleSelect"
                @select-all="handleSelectAll"
                :row-class-name="tableRowClassName"
              >
                <el-table-column
                  type="selection"
                  width="40"
                  header-align="center"
                  align="center"
                >
                </el-table-column>
                <el-table-column label="Item Code" width="155">
                  <template slot-scope="scope">
                    <b>{{ scope.row.code }}</b>
                  </template>
                </el-table-column>
                <el-table-column label="Description" min-width="450">
                  <template slot-scope="scope">
                    {{ scope.row.description }}
                  </template>
                </el-table-column>
                <el-table-column
                  label="Size"
                  :width="isNBFI ? '60' : ''"
                  :min-width="isNBFI ? '' : '150'"
                >
                  <template slot-scope="scope">
                    {{ scope.row.size }}
                  </template>
                </el-table-column>
                <el-table-column label="Color" width="100">
                  <template slot-scope="scope">
                    {{ scope.row.color }}
                  </template>
                </el-table-column>
                <el-table-column :label="isNBFI ? 'Brand' : 'Category'" min-width="300">
                  <template slot-scope="scope">
                    {{ scope.row.categorybrand }}
                  </template>
                </el-table-column>
                <el-table-column label="Quantity" width="165">
                  <template slot-scope="scope">
                    <div class="btn-group btn-group-sm d-flex flex-row">
                      <p-button
                        type="default"
                        round
                        outline
                        size="xs"
                        @click="scope.row.quantity > 0 ? scope.row.quantity-- : 0"
                      >
                        <i class="nc-icon nc-simple-delete"></i>
                      </p-button>
                      <input
                        type="text"
                        @blur="handleQuantity(scope.row)"
                        @input="handleLimitQuantity(scope.row)"
                        v-model="scope.row.quantity"
                        class="table-input-box"
                        style="width: 75px; text-align: center"
                        @paste="onPaste"
                        @keypress="numberOnly"
                        min="1"
                        max="150"
                      />

                      <p-button
                        type="default"
                        round
                        outline
                        size="xs"
                        @click="incrementQuantity(scope.row)"
                      >
                        <i class="nc-icon nc-simple-add"></i>
                      </p-button>
                    </div>
                  </template>
                </el-table-column>
                <el-table-column label="Box Label" min-width="300">
                  <template slot-scope="scope">
                    <el-select
                      class="table-select-box"
                      size="large"
                      v-model="scope.row.boxNumber"
                      @change="
                        editBoxLabel(
                          scope.row.code,
                          scope.row.quantity,
                          scope.row.boxNumber
                        )
                      "
                    >
                      <el-option
                        v-for="boxLabel in newTransaction.boxLabels"
                        class="table-select-box"
                        :value="boxLabel.boxNumber"
                        :label="
                          'Box No. ' +
                          boxLabel.boxNumber +
                          ' of ' +
                          newTransaction.boxLabels.length +
                          ' ' +
                          boxLabel.boxLabel
                        "
                        :key="boxLabel.id"
                      >
                      </el-option>
                    </el-select>
                  </template>
                </el-table-column>
              </el-table>
            </div>
          </collapse-item>
          <label v-show="isValid.item" class="label-font">Item is required.</label>
        </collapse>
        <!-- </card> -->
        <div class="row pt-5" v-if="showUploadImage">
          <div class="col-12 p-0">
            <label>
              Upload Images (Up to 10 images [JPEG, PNG, and other image files] with a
              maximum size of 2 MB per image will be accepted.)
            </label>
            <br />
          </div>

          <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 p-0">
            <button
              class="btn btn-default m-0"
              style="width: 100%"
              onclick="document.getElementById('upload_image').click()"
            >
              Upload Image
            </button>
            <input
              id="upload_image"
              multiple
              type="file"
              ref="image"
              accept="image/jpeg, image/png"
              style="display: none"
              @change="onFileChange"
            />
          </div>
        </div>
        <div
          v-if="images"
          class="d-flex justify-content-center row my-2 column-gap-3 row-gap-2"
        >
          <div class="image-container" v-for="(image, index) in images">
            <div class="image-overlay" v-if="!isMobile">
              <el-popconfirm
                width="280"
                confirm-button-text="Confirm"
                cancel-button-text="Cancel"
                icon-color="#c45656"
                title="Are you sure you want to remove this image?"
                @confirm="removeImage(true, index)"
                @cancel="removeImage(false, index)"
              >
                <el-button slot="reference" type="danger" icon="el-icon-delete" circle>
                </el-button>
              </el-popconfirm>
            </div>

            <el-popconfirm
              width="280"
              confirm-button-text="Confirm"
              cancel-button-text="Cancel"
              icon-color="#c45656"
              title="Are you sure you want to remove this image?"
              @confirm="removeImage(true, index)"
              @cancel="removeImage(false, index)"
              ><a slot="reference" class="imageClose" v-if="isMobile"
                >&times;</a
              ></el-popconfirm
            >
            <img class="imageUpload" :src="image" />
          </div>
        </div>
      </div>
      <div class="text-center mt-1">
        <span
          id="draftBTN"
          class="d-inline-block"
          tabindex="0"
          data-bs-toggle="tooltip"
          data-bs-placement="bottom"
        >
          <button
            :disabled="isDisabledDraft"
            class="btn btn-warning btn-fill btn-wd mx-1 mb-0 mt-2"
            data-bs-target="#confirmationDraftPullOut"
            data-bs-toggle="modal"
            v-show="isDenied"
          >
            Save as Draft
          </button></span
        >
        <button
          class="btn btn-warning btn-fill btn-wd mx-1 mb-0 mt-2"
          @click.prevent="cancelTransaction"
          v-show="isCancel"
        >
          Cancel
        </button>
        <button
          :disabled="isDisabledSubmit"
          class="btn btn-success btn-fill btn-wd mx-1 mb-0 mt-2"
          data-bs-target="#submiteditauthorizeddatemodal"
          data-bs-toggle="modal"
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
            class="btn btn-success btn-fill btn-wd mx-1 mb-0 mt-2"
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
      @TransferDataBoxNumber="reArrangeBoxNumber($event)"
      @DeletedBoxNumber="reArrangeItems($event)"
      @closeModal="closeModal()"
      @renameBoxLabel="editingBoxLabel($event)"
      :tempRemoveItems="tempRemoveItems"
    ></EditBoxLabelModal>

    <TransactionReceiptModal
      :transferTransactionID="transferTransactionID"
      :savingCounter="saving_counter"
    ></TransactionReceiptModal>

    <DraftModal :transferTransactionID="transferTransactionID"></DraftModal>
    <ConfirmationSubmitPullOutModal @confirm="submit($event)">
    </ConfirmationSubmitPullOutModal>

    <ConfirmationDraftPullOutModal @confirm="draft($event)">
    </ConfirmationDraftPullOutModal>

    <SubmitEditAuthorizedDateModal
      :transferTransactionID="transferTransactionID"
      :transferTransactionPromoName="promoName"
      :transferTransactionPromoEmail="promoEmail"
      :savingCounter="saving_counter"
      :dateStart="pullOutStartDate"
      :dateEnd="pullOutEndDate"
      :transactionDetails="transactionDetails"
      :tempRemoveItems="tempRemoveItems"
      :toSubmit="toSubmit"
      :isDraft="isDraft"
    ></SubmitEditAuthorizedDateModal>
  </div>
</template>
<script>
import { Collapse, CollapseItem, Tabs, TabPane, Card } from "src/components/UIComponents";
import {
  DatePicker,
  TimeSelect,
  Slider,
  Tag,
  Input,
  Select,
  Option,
  Message,
  MessageBox,
  Popconfirm,
} from "element-ui";
import PProgress from "src/components/UIComponents/Progress.vue";
import PSwitch from "src/components/UIComponents/Switch.vue";
import PButton from "src/components/UIComponents/Button.vue";
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
import SubmitEditAuthorizedDateModal from "./PullOutRequests/ModalPullOut/SubmitEditAuthorizedDateModal.vue";
import Swal from "sweetalert2";
import { Table, TableColumn, Button } from "element-ui";
import axiosClient from "../../../../axios";
import EditBoxLabelModal from "./PullOutRequests/ModalPullOut/EditBoxLabelModal.vue";
import TransactionReceiptModal from "./PullOutRequests/ModalPullOut/TransactionReceiptModal.vue";
import EditSubmitTransactionReceiptModal from "./PullOutRequests/ModalPullOut/EditSubmitTransactionReceiptModal.vue";
import DraftModal from "./PullOutRequests/ModalPullOut/DraftModal.vue";
import linkName from "../../../../linkName";
import ConfirmationSubmitPullOutModal from "./PullOutRequests/ModalPullOut/ConfirmationSubmitPullOutModal.vue";
import ConfirmationDraftPullOutModal from "./PullOutRequests/ModalPullOut/ConfirmationDraftPullOutModal.vue";
import * as XLSX from "xlsx";
import { Loading } from "element-ui";
import axios from "axios";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Popconfirm);
Vue.use(Button);

export default {
  components: {
    [DatePicker.name]: DatePicker,
    [TimeSelect.name]: TimeSelect,
    [Slider.name]: Slider,
    [Tag.name]: Tag,
    [Input.name]: Input,
    [Option.name]: Option,
    [Select.name]: Select,
    PButton,
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
    EditSubmitTransactionReceiptModal,
    DraftModal,
    ConfirmationSubmitPullOutModal,
    ConfirmationDraftPullOutModal,
    EditSubmitTransactionReceiptModal,
    SubmitEditAuthorizedDateModal,
  },
  data() {
    return {
      isDisabledDraft: true,
      showCategoryBrand: true,
      showUploadImage: true,
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
      uploadExcelFileName: "Choose a File",

      transactionTypeList: [
        {
          value: "CPO Item for Disposal in the Store c/o Supervisor",
          label: "CPO Item for Disposal in the Store c/o Supervisor",
          company: "NBFI",
        },
        {
          value: "CPO for Transfer to Another Store",
          label: "CPO for Transfer to Another Store",
          company: "NBFI",
        },
        {
          value: "CPO Back to WH via In-House Delivery Service",
          label: "CPO Back to WH via In-House Delivery Service",
          company: "NBFI",
        },
        {
          value: "CPO Back to WH via Chain Distribution Center",
          label: "CPO Back to WH via Chain Distribution Center",
          company: "NBFI",
        },
        {
          value: "CPO Back to WH via 3rd Party Trucking",
          label: "CPO Back to WH via 3rd Party Trucking",
          company: "NBFI",
        },
        {
          value: "CPO Back to WH c/o Supervisor",
          label: "CPO Back to WH c/o Supervisor",
          company: "NBFI",
        },
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
      remarksList: [
        "CLOSED STORE/BRANCH - GOOD ITEMS",
        "CLOSED STORE/BRANCH - DAMAGED/DIRTY ITEMS",
        "CLOSED STORE/BRANCH - DISPOSAL/CONTAINS BROKEN GLASS ITEMS",
        "REGULAR PULL-OUT - GOOD ITEMS",
        "REGULAR PULL-OUT - DAMAGED/DIRTY ITEMS",
        "REGULAR PULL-OUT - DISPOSAL/CONTAINS BROKEN GLASS ITEMS",
        "STORE TO STORE/BRANCH TO BRANCH - GOOD ITEMS",
        "STORE TO STORE/BRANCH TO BRANCH - DAMAGED/DIRTY ITEMS",
        "STORE TO STORE/BRANCH TO BRANCH - DISPOSAL/CONTAINS BROKEN GLASS ITEMS",
      ],
      images: [],
      files: [],
      img_counter: 0,
      strCategory: {
        boxNumber: 0,
        label: "",
      },
      isDraft: false,
      barcode: "item_16",
      saving_counter: null,
      isMobile: null,
      headerCellStyle: {
        color: "black",
      },
      cellStyle: {
        color: "black",
        fontWeight: "normal",
      },
      tableData: [],
      multipleSelection: [],
      deleteItemBtn: null,
      isNBFI: false,
      promoName: "",
      promoEmail: "",
      pullOutStartDate: "",
      pullOutEndDate: "",
      transactionDetails: {},
      tempRemoveItems: [],
      toSubmit: "",
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
    let tooltipExcel = bootstrap.Tooltip.getInstance("#excelBtn");
    tooltipExcel._config.title =
      "Only XLSX file with a standard Template (Column A: Box Number, Column B: Box Label, Column C: Item Code, Column D: Quantity)";
    tooltipExcel.update();
    this.fetchEdit();
    this.fetchCompany();
    if (window.resolveRouteChange) {
      window.resolveRouteChange();
      window.resolveRouteChange = null;
    }
    linkName.val = "Pull-Out Requisition Form";

    this.isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
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
      this.createTableData();
    },
    "newTransaction.boxLabels": function (val, oldVal) {
      this.enableDropDowns();
      this.createTableData();
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
    handleLimitQuantity(row) {
      if (row.quantity > 150) row.quantity = 150;
    },
    incrementQuantity(row) {
      if (row.quantity < 150) {
        row.quantity++;
      } else {
        // Optionally, you can display a message or take some action when the limit is reached.
        console.log("Quantity limit reached (150).");
      }
    },
    tableRowClassName({ row, rowIndex }) {
      if (row.quantity == 0) return "warning-row";
      else return "";
    },
    handleSelectAll(val) {
      if (val.length == 0) {
        this.multipleSelection = [];
        this.deleteItemBtn = null;
      } else this.deleteItemBtn = val[0].boxNumber - 1;

      val.forEach((row) => {
        let temp = true;
        for (let [index, mul] of this.multipleSelection.entries())
          if (row.boxNumber == mul.boxNumber && row.code == mul.code) {
            temp = false;
            break;
          }
        if (temp) this.multipleSelection.push(row);

        if (this.multipleSelection.length > 0) {
          const selectedItemsOther = this.multipleSelection.filter(
            (mul) => mul.boxNumber !== val[0].boxNumber
          );
          selectedItemsOther.forEach((rowSel) => {
            this.$refs.itemsDataTable[parseInt(rowSel.boxNumber - 1)].toggleRowSelection(
              rowSel
            );
          });
          const selectedItems = this.multipleSelection.filter(
            (mul) => mul.boxNumber === val[0].boxNumber
          );
          if (selectedItems.length > 0) this.multipleSelection = selectedItems;
        }
      });
    },
    handleSelect(val, row) {
      if (val.length == 0) this.deleteItemBtn = null;
      else this.deleteItemBtn = row.boxNumber - 1;

      let temp = true;
      for (let [index, mul] of this.multipleSelection.entries())
        if (row.boxNumber == mul.boxNumber && row.code == mul.code) {
          this.multipleSelection.splice(index, 1);
          temp = false;
          break;
        }
      if (temp) this.multipleSelection.push(row);

      if (this.multipleSelection.length > 0) {
        const selectedItemsOther = this.multipleSelection.filter(
          (mul) => mul.boxNumber !== val[0].boxNumber
        );
        selectedItemsOther.forEach((rowSel) => {
          this.$refs.itemsDataTable[parseInt(rowSel.boxNumber - 1)].toggleRowSelection(
            rowSel
          );
        });
        const selectedItems = this.multipleSelection.filter(
          (mul) => mul.boxNumber === val[0].boxNumber
        );
        if (selectedItems.length > 0) this.multipleSelection = selectedItems;
      }
    },
    deleteSelectedItems() {
      MessageBox.confirm(
        "The item/s you have selected will be remove. Continue?",
        "Removing of Items",
        {
          confirmButtonText: "Confirm",
          cancelButtonText: "Cancel",
          type: "warning",
        }
      )
        .then(() => {
          this.multipleSelection.forEach((selected) => {
            this.removeItem(selected.code, selected.boxNumber);
          });
          this.deleteItemBtn = null;
        })
        .catch(() => {
          Message({
            type: "info",
            message: "Removing of Items canceled.",
          });
        });
    },
    createTableData() {
      this.tableData = [];
      this.multipleSelection = [];
      this.newTransaction.boxLabels.forEach((box, key) => {
        this.tableData.push([]);
        this.newTransaction.items.forEach((item) => {
          if (box.boxNumber == item.boxNumber) this.tableData[key].push(item);
        });
      });
    },
    reArrangeItems(deletedBoxNumber) {
      this.newTransaction.items.forEach((temp) => {
        if (deletedBoxNumber < temp.boxNumber) temp.boxNumber--;
      });
    },
    reArrangeBoxNumber(transfer) {
      this.newTransaction.boxLabels = transfer;
      this.newItemInputBox = [];
      this.newTransaction.boxLabels.forEach((boxLabel) => {
        var tempIdBox = {
          id: boxLabel.id,
        };
        this.newItemInputBox.push(tempIdBox);
      });
    },
    clearItemInput() {
      this.newItemInput = "";
      this.itemCodeList = [];
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
      }
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
        Message.error("Please select an image file smaller than 2MB.");
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
    removeImage(confirm, index) {
      if (confirm) {
        if (typeof this.images[index] == "string") {
          let tempPath = this.images[index].split("/");
          let removePath = tempPath[tempPath.length - 1];
          axiosClient
            .post("/deleteImage", {
              company: this.newTransaction.company,
              path: removePath,
            })
            .then((response) => {
              console.log(response.data);
            })
            .catch((error) => {
              console.error(error);
            });
        }

        this.images.splice(index, 1);
        this.uploadImageFileName = "Choose an Image";

        Message({
          type: "success",
          message: "Image has been removed.",
        });
      }
    },
    clearImage() {
      document.getElementById("formFile").value = null;
      frame.src = "";
    },
    handleFileUploadImage() {
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
            this.img_counter++;
            setTimeout(this.handleFileUploadImage, 2000);
          })
          .catch((error) => {
            console.error(error);
          });
      }
    },
    handleFileUpload(event) {
      if (event.target.files.length != 0) {
        this.uploadExcelFileName = event.target.files[0].name;
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
          this.myData = jsonData;
          let arrayBox = [];
          for (var x = 0; x < this.myData.length; x++) {
            this.dataArray.push(this.myData[x]);
            arrayBox.push(this.myData[x][1]);
          }

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

          this.counter = 0;
          this.saveItemsExcel();
        };

        reader.readAsArrayBuffer(file);
      }
    },

    saveItemsExcel() {
      let loadingInstance = Loading.service({
        fullscreen: true,
        lock: true,
        text: "Loading",
        spinner: "el-icon-loading",
        background: "rgb(255,0,0)",
      });
      if (this.counter < this.dataArray.length) {
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
                  boxNumber: parseInt(boxNumber),
                  category: response.data[x].Category,
                };
                this.newTransaction.items.push(tempItem);
              }

              this.isAddItem = true;
              this.notifyVue("AddItem", "bottom", "right");
              setTimeout(this.saveItemsExcel, 2000);
            }
          })
          .catch((error) => {
            console.error(error);
          });
      } else {
        this.draft(true);
        loadingInstance.close();
      }
      this.counter++;
    },
    onPaste(event) {
      event.preventDefault();
    },
    numberOnly($event) {
      console.log("Number Only:", $event);
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
      for (let x in this.newTransaction.items) {
        if (this.newTransaction.items[x].quantity == 0) {
          itemsValidation = false;

          const submitTooltip = document.getElementById("submitBTN");
          const tooltip = new bootstrap.Tooltip(submitTooltip, {
            title: "No items should have a quantity of 0.",
          });
          tooltip.update();
          break;
        }
      }
      if (!itemsValidationTemp) {
        const submitTooltip = document.getElementById("submitBTN");
        const tooltip = new bootstrap.Tooltip(submitTooltip, {
          title: "Kindly add item/s on every boxes you've added.",
        });
        tooltip.update();
      }
      if (
        !this.newTransaction.company ||
        !this.newTransaction.chainCode ||
        !this.newTransaction.branchName ||
        !this.newTransaction.transactionType
      ) {
        const submitTooltip = document.getElementById("submitBTN");
        const tooltip = new bootstrap.Tooltip(submitTooltip, {
          title: "Complete the form above to enable this button.",
        });
        tooltip.update();
        const draftTooltip = document.getElementById("draftBTN");
        const tooltipDraft = new bootstrap.Tooltip(draftTooltip, {
          title: "Complete the form above to enable this button.",
        });
        tooltipDraft.update();
      } else if (this.newTransaction.boxLabels.length <= 0) {
        const submitTooltip = document.getElementById("submitBTN");
        const tooltip = new bootstrap.Tooltip(submitTooltip, {
          title: "No box added. Kindly add box/es to be able to add item/s.",
        });
        tooltip.update();
      } else if (this.newTransaction.items.length <= 0) {
        const submitTooltip = document.getElementById("submitBTN");
        const tooltip = new bootstrap.Tooltip(submitTooltip, {
          title: "No items added. Kindly add item/s for this pull-out request.",
        });
        tooltip.update();
      }

      //Validation for Submit Button
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

      //validation for Draft Button
      if (
        this.newTransaction.company &&
        this.newTransaction.chainCode &&
        this.newTransaction.branchName &&
        this.newTransaction.transactionType
      ) {
        this.isDisabledDraft = false;
        let tooltip = bootstrap.Tooltip.getInstance("#draftBTN");
        tooltip.disable();
      } else this.isDisabledDraft = true;
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

        var company = this.decodeFromAlphanumeric(transactionID.split("=")[2]);
        this.isDraft = true;
        this.transferTransactionID = id;
        axiosClient
          .get("/fetchEditDraftBranch", {
            params: {
              company: company,
              plID: id,
            },
          })
          .then((response) => {
            this.newTransaction.company = response.data[0].company;
            this.newTransaction.branchName = response.data[0].branchName;
            this.newTransaction.chainCode = response.data[0].chainCode;
            this.newTransaction.transactionType = response.data[0].transactionType;
            this.promoName = response.data[0].name;
            this.promoEmail = response.data[0].promoEmail;
            this.pullOutStartDate = response.data[0].dateStart;
            this.pullOutEndDate = response.data[0].dateEnd;

            if (
              response.data[0].status == "denied" ||
              response.data[0].status == "endorsement" ||
              response.data[0].status == "unprocessed"
            ) {
              this.isDenied = false;
              this.isCancel = true;
              this.isApproved = true;
              this.isSubmit = false;
            } else {
              this.isDenied = true;
              this.isCancel = false;
            }

            axiosClient
              .get("/fetchEditDraftItem", {
                params: {
                  company: company,
                  plID: id,
                },
              })
              .then((response) => {
                for (var x = 0; x < response.data.length; x++) {
                  this.newTransaction.items.push(response.data[x]);
                }

                const filteredData = this.newTransaction.items.filter(
                  (obj, index, self) => {
                    const boxNumber = obj.boxNumber;
                    return self.findIndex((o) => o.boxNumber === boxNumber) === index;
                  }
                );

                const boxData = filteredData.map((obj) => {
                  return {
                    boxLabel: obj.boxLabel,
                    boxNumber: obj.boxNumber,
                  };
                });

                for (var x = 0; x < boxData.length; x++) {
                  this.newTransaction.boxLabels.push({
                    id: boxData[x].boxNumber,
                    boxNumber: boxData[x].boxNumber,
                    boxLabel: boxData[x].boxLabel,
                  });
                  this.newItemInputBox.push({
                    id: boxData[x].boxNumber,
                  });
                }

                // this.isDraft = false;
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
                  this.isCompany = false;
                  this.isChainCode = false;
                  this.isBranchName = false;
                  this.isTransactionType = false;
                }

                if (this.newTransaction.items.length) {
                  this.isCompany = true;
                  this.isChainCode = true;
                  this.isBranchName = true;
                  this.isTransactionType = true;
                }
              })
              .catch((error) => {
                console.error(error);
              });
            axiosClient
              .get("/fetchImageBranchDoc", {
                params: {
                  transactionID: id,
                  company: company,
                },
              })
              .then((response) => {
                this.images = response.data.imagePaths;
              })
              .catch((error) => {
                console.error(error);
              });
          })
          .catch((error) => {
            console.error(error);
          });
      } catch {
        //Fetching Promo Info
        axiosClient
          .get("/fetchPromoBranchInfo", {
            params: {
              userID: sessionStorage.getItem("UserID"),
            },
          })
          .then((response) => {
            this.newTransaction.company = response.data[0].company;
            this.newTransaction.chainCode = response.data[0].chainCode;
            this.newTransaction.branchName = response.data[0].branchName;
            this.fetchChainCode();
            this.fetchChainName();
          })
          .catch((error) => {});
      }
    },
    cancelTransaction() {
      if (
        sessionStorage.getItem("Position") == "Reviewer" ||
        sessionStorage.getItem("Position") == "Approver"
      )
        this.$router.push({
          path: "/pull-out/requests",
        });
      else
        this.$router.push({
          path: "/pull-out/pullout-transaction",
        });
    },
    updateData(updatedData) {},
    openModal(data) {
      data.boxLabels.forEach((box) => {
        let tempIndex = box.boxLabel.indexOf("[");
        if (tempIndex > 0) box.boxLabel = box.boxLabel.substr(0, tempIndex).trim();
      });
      this.transferredData = data;
    },
    closeModal() {
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

      for (let key in this.newTransaction.items) {
        if (
          this.newTransaction.items[key].code === code &&
          this.newTransaction.items[key].boxNumber === boxNumber
        ) {
          try {
            this.tempRemoveItems.push(this.newTransaction.items[key].id);
            const uri = window.location.href;
            var transactionID = uri.split("?")[1];
            var id = transactionID.split("=")[1].split("&")[0];
            var company = transactionID.split("=")[2];
            axiosClient
              .post("/logsDeleteItemEdit", {
                id: id,
                itemCode: this.newTransaction.items[key].code,
                boxNumber: this.newTransaction.items[key].boxNumber,
                userID: sessionStorage.getItem("UserID"),
              })
              .then((response) => {})
              .catch((error) => {
                console.error(error);
              });
          } catch {}
          this.newTransaction.items.splice(key, 1);
          break;
        }
      }

      this.notifyVue("DeleteItem", "bottom", "right");
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
                barcode: this.barcode,
              },
            }) // Make a GET request to the specified URL
            .then((response) => {
              this.itemCodeList = response.data; // Update the 'data' variable with the retrieved data
            })
            .catch((error) => {});
        } else {
          axiosClient
            .get("/fetchItems", {
              params: {
                ItemNo: this.newItemInput,
                barcode: this.barcode,
              },
            }) // Make a GET request to the specified URL
            .then((response) => {
              this.itemCodeList = response.data; // Update the 'data' variable with the retrieved data
            })
            .catch((error) => {});
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
      if (!item.quantity) {
        item.quantity = 0;
      }

      item.quantity = Math.min(Math.max(item.quantity, 0), 150);
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
          .get("/fetchCompany", {
            params: {
              company: sessionStorage.getItem("Company"),
            },
          })
          .then((response) => {
            this.companyList = response.data;
          })
          .catch((error) => {
            console.error(error);
          });
      else
        axiosClient
          .get("/fetchCompanyByUser", {
            params: {
              userID: sessionStorage.getItem("UserID"),
            },
          })
          .then((response) => {
            this.companyList = response.data;
          })
          .catch((error) => {
            console.error(error);
          });
      var company = sessionStorage.getItem("Company");
      if (company == "NBFI" || company == "ASC" || company == "CMC")
        this.showCategoryBrand = true;
      else this.showCategoryBrand = false;
    },
    fetchChainCode() {
      if (
        this.newTransaction.company == "NBFI" ||
        this.newTransaction.company == "CMC" ||
        this.newTransaction.company == "ASC"
      )
        this.isNBFI = true;
      else this.isNBFI = false;

      if ("User" == sessionStorage.getItem("Position"))
        axiosClient
          .get("/fetchChainByUser", {
            params: {
              company: this.newTransaction.company,
              userID: sessionStorage.getItem("UserID"),
            },
          })
          .then((response) => {
            this.chainCodeList = response.data;
          })
          .catch((error) => {
            console.error(error);
          });
      else
        axiosClient
          .get("/fetchChain", {
            params: {
              company: this.newTransaction.company,
            },
          })
          .then((response) => {
            this.chainCodeList = response.data;
          })
          .catch((error) => {
            console.error(error);
          });

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
            this.branchNameList = response.data;
          })
          .catch((error) => {
            console.error(error);
          });
      else {
        axiosClient
          .get("/fetchChainName", {
            params: {
              chainCode: this.newTransaction.chainCode,
              company: this.newTransaction.company,
            },
          })
          .then((response) => {
            this.branchNameList = response.data;
          })
          .catch((error) => {
            console.error(error);
          });

        this.isBranchName = false;
      }
    },
    addItem(boxNUMBER) {
      this.isItem = false;
      this.isAddItem = false;
      this.showItemInput = boxNUMBER;
    },
    saveItem(boxNUMBER) {
      console.log("ItemCode:", this.newItemInput);

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
            this.newItemInput = response.data[0].ItemNo;
          })
          .catch((error) => {
            console.error(error);
          });
      }
      var checkItemData = true;
      setTimeout(() => {
        axiosClient
          .get("/compareItemCode", {
            params: {
              companyType: this.newTransaction.company,
              ItemNo: this.newItemInput,
            },
          })
          .then((response) => {
            if (response.data.length == 0) {
              checkItemData = false;
            }
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
                this.newBrand = response.data[0].brandNames;
              })
              .catch((error) => {
                console.error(error);
              });
          })
          .catch((error) => {
            this.missItemCode = 0;
            !this.newItemInput ? true : (this.isRightCode = true);
          });
      }, 300);
      var newResponseData;

      setTimeout(() => {
        if (checkItemData) {
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
              newResponseData = response.data;
              console.log("Fetch Same Item:", response.data);
            })
            .catch((error) => {});
        }
      }, 500);

      setTimeout(() => {
        if (checkItemData) {
          for (var x = 0; x < newResponseData.length; x++) {
            var flag = true;
            for (var i = 0; i < this.newTransaction.items.length; i++) {
              if (
                this.newTransaction.items[i].code == newResponseData[x].ItemNo &&
                this.newTransaction.items[i].boxNumber == boxNUMBER
              ) {
                flag = false;
                break;
              }
            }
            if (flag) {
              if (
                this.newTransaction.company == "NBFI" ||
                this.newTransaction.company == "CMC" ||
                this.newTransaction.company == "ASC"
              ) {
                var categorybrand = this.newBrand;
              } else {
                var categorybrand = newResponseData[x].Category;
              }
              let tempItem = {
                code: newResponseData[x].ItemNo,
                description: newResponseData[x].ItemDescription,
                categorybrand: categorybrand,
                quantity: 0,
                size: newResponseData[x].Size,
                color: newResponseData[x].Color,
                boxNumber: boxNUMBER,
                category: newResponseData[x].Category,
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
        }
      }, 1000);
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
            parseInt(
              this.newTransaction.boxLabels[this.newTransaction.boxLabels.length - 1].id
            ) + 1,
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
            parseInt(
              this.newTransaction.boxLabels[this.newTransaction.boxLabels.length - 1].id
            ) + 1,
        };
      }

      this.newTransaction.boxLabels.push(tempBoxLabel);
      this.newItemInputBox.push(tempItem);
      this.newBoxLabel = "";

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
    async submit(confirmation) {
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

          this.transactionDetails = this.newTransaction;
          this.toSubmit = "update";
          console.log("Transaction Details 1:", this.transactionDetails);

          // const updateBranch = await axiosClient.post("/updateBranchStatus", {
          //   userID: sessionStorage.getItem("UserID"),
          //   company: sessionStorage.getItem("Company"),
          //   status: "endorsement",
          //   id: this.transferTransactionID,
          //   email: sessionStorage.getItem("Email"),
          //   transactionData: this.transactionDetails,
          //   removedItems: this.tempRemoveItems,
          //   editStatus: true,
          // });

          this.transferTransactionID = id;

          // console.log("Update:", updateBranch);
          // const transactionModal = new bootstrap.Modal("#transactionReceipt");
          // transactionModal.show();
          const submitdatemodal = new bootstrap.Modal("#submiteditauthorizeddatemodal");
          submitdatemodal.show();
        } catch {
          this.isValid.company = !this.newTransaction.company ? true : false;
          this.isValid.chainCode = !this.newTransaction.chainCode ? true : false;
          this.isValid.branchName = !this.newTransaction.branchName ? true : false;
          this.isValid.transactionType = !this.newTransaction.transactionType
            ? true
            : false;
          this.isValid.boxLabel = !this.newTransaction.boxLabels.length ? true : false;
          this.isValid.item = !this.newTransaction.items.length ? true : false;

          this.transactionDetails = {
            chainCode: this.newTransaction.chainCode,
            companyType: this.newTransaction.company,
            branchName: this.newTransaction.branchName,
            transactionType: this.newTransaction.transactionType,
            boxes: this.newTransaction.boxLabels,
            items: this.newTransaction.items,
            email: sessionStorage.getItem("Email"),
            status: "endorsement",
          };

          var id = await axiosClient.get("/getLastID", {
            params: {
              company: sessionStorage.getItem("Company"),
            },
          });
          console.log("Last ID:", parseInt(id.data) + 1);
          this.transferTransactionID = parseInt(id.data) + 1;

          this.toSubmit = "save";
          // console.log("Items:", this.newTransaction.items);
          // console.log("Boxes:", this.newTransaction.boxLabels);
          // var response = await axiosClient.post("/savePullOutBranchRequest", {
          //   chainCode: this.newTransaction.chainCode,
          //   companyType: this.newTransaction.company,
          //   branchName: this.newTransaction.branchName,
          //   transactionType: this.newTransaction.transactionType,
          //   boxes: this.newTransaction.boxLabels,
          //   items: this.newTransaction.items,
          //   email: sessionStorage.getItem("Email"),
          //   status: "endorsement",
          // });
          // this.transferTransactionID = response.data;

          // console.log("Save:", response);
          // const transactionModal = new bootstrap.Modal("#transactionReceipt");
          // transactionModal.show();
          const submitdatemodal = new bootstrap.Modal("#submiteditauthorizeddatemodal");
          submitdatemodal.show();
        }
        this.saving_counter = this.files.length * 2;
        setTimeout(this.handleFileUploadImage, 2000);
      }
    },
    async draft(confirmation) {
      if (confirmation) {
        try {
          const uri = window.location.href;
          var transactionID = uri.split("?")[1];
          var id = transactionID.split("=")[1].split("&")[0];

          // const updateBranch = await axiosClient.post("/updateBranchStatus", {
          //   userID: sessionStorage.getItem("UserID"),
          //   company: sessionStorage.getItem("Company"),
          //   status: "draft",
          //   id: this.transferTransactionID,
          //   email: sessionStorage.getItem("Email"),
          //   transactionData: this.transactionDetails,
          //   removedItems: this.tempRemoveItems,
          //   editStatus: true,
          // });
          axiosClient
            .post("/updateBranchStatus", {
              userID: sessionStorage.getItem("UserID"),
              company: sessionStorage.getItem("Company"),
              status: "draft",
              id: this.transferTransactionID,
              email: sessionStorage.getItem("Email"),
              transactionData: this.newTransaction,
              removedItems: this.tempRemoveItems,
              editStatus: true,
            })
            .then((response) => {
              console.log("Success Updated:", response.data);
            })
            .catch((error) => {
              console.log("Error:", error);
            });
          // console.log("Draft Update:", updateBranch);
          const draftModal = new bootstrap.Modal("#draftModal");
          draftModal.show();
        } catch {
          console.log("Transactions:", this.newTransaction);
          var response = await axiosClient.post("/savePullOutBranchRequest", {
            chainCode: this.newTransaction.chainCode,
            companyType: this.newTransaction.company,
            branchName: this.newTransaction.branchName,
            transactionType: this.newTransaction.transactionType,
            boxes: this.newTransaction.boxLabels,
            items: this.newTransaction.items,
            email: sessionStorage.getItem("Email"),
            status: "draft",
          });
          this.transferTransactionID = response.data;

          const draftModal = new bootstrap.Modal("#draftModal");
          draftModal.show();
        }
        this.saving_counter = this.files.length * 2;
        setTimeout(this.handleFileUploadImage, 2000);
      }
    },
    editBoxLabel(code, quantity, boxNumber) {
      this.validateSubmit();

      var filteredItems = this.newTransaction.items.filter(
        (item) => item.code === code && item.boxNumber === boxNumber
      );

      var uniqueItems = [];
      filteredItems.forEach((item) => {
        var existingItem = uniqueItems.find(
          (uniqueItem) => uniqueItem.code === item.code
        );
        if (existingItem) {
          if (existingItem.quantity == 0 && item.quantity == 0) existingItem.quantity = 1;
          else existingItem.quantity += item.quantity;
        } else {
          uniqueItems.push({ ...item });
        }
      });

      this.newTransaction.items = this.newTransaction.items.filter(
        (item) =>
          item.code !== uniqueItems[0].code || item.boxNumber !== uniqueItems[0].boxNumber
      );
      this.newTransaction.items.push(uniqueItems[0]);
      this.notifyVue("ChangeBoxLabel", "bottom", "right");
    },
    decodeFromAlphanumeric(input) {
      let result = "";

      for (let i = 0; i < input.length; i += 2) {
        const alphanumericChar = input.substr(i, 2);
        const charCode = parseInt(alphanumericChar, 36);

        result += String.fromCharCode(charCode);
      }

      return result;
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
/* .tblItemsCell {
  padding-top: 10px;
  padding: 10px;
  color: red;
} */

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

.w-full {
  width: 100% !important;
}
.h-div-32 {
  height: 32.2px !important;
}
.importBtn {
  width: 100% !important;
  height: 100% !important;
  padding: 0.8vh !important;
}

.image-container {
  position: relative;
  width: fit-content;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 2px 4px rgba(0, 0, 0, 0.06);
  border-radius: 6px;
  padding: 5px;
  overflow: hidden;
}
.image-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: background 0.5s ease;
  transition: opacity 0.5s ease;
  border-radius: 6px;
}
.image-overlay:hover {
  backdrop-filter: blur(1px);
  background: rgba(0, 0, 0, 0.2);
  opacity: 1;
}
.image-overlay-button {
  width: 25% !important;
  height: 25% !important;
  /* height: 25% !important; */
}
.imageUpload {
  /* position: absolute; */
  display: block;
  width: 10vw;
}

.imageClose {
  display: block;
  height: 23px;
  font-size: 20px;
  position: absolute;
  top: -7px;
  right: 1px;
  color: red !important;
  padding: 1.5px;
  overflow: hidden;
  text-decoration: none;
  border-bottom-left-radius: 2.5px;
  background-color: white;
}

.span-upload-image {
  border: solid;
  border-left: none;
  border-width: 1px;
  border-radius: 4px;
  border-top-left-radius: 0px;
  border-bottom-left-radius: 0px;
  padding: 8px 9px;
  width: fit-content;
  white-space: nowrap;
  display: inline-block;
}

@media (max-width: 767px) {
  .span-upload-image {
    text-align: center;
    border: solid;
    border-width: 1px;
    border-radius: 4px;
    padding: 8px 9px;
    width: 100%;
    white-space: nowrap;
    text-overflow: clip;
    overflow: hidden;
    margin-top: 2px;
    display: inline-block;
  }
}
@media (max-width: 575px) {
  .imageUpload {
    width: 40vw;
  }
}
@media (min-width: 576px) {
  .imageUpload {
    width: 30vw;
  }
}
@media (min-width: 768px) {
  .imageUpload {
    width: 20vw;
  }
}
@media (min-width: 1283px) {
  .imageUpload {
    width: 15vw;
  }
}
@media (min-width: 1400px) {
  .imageUpload {
    width: 10vw;
  }
}

.el-table .warning-row {
  background: rgb(253, 230, 230) !important;
}
</style>
