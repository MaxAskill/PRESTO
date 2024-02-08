<template>
  <div class="card card-generator">
    <!-- <div class="card-header">
      <h4 class="title">Pull-Out Requisition Form</h4>
    </div> -->
    <div class="card-body py-5">
      <div class="row">
        <div class="col-sm-3 text-center">
          <img
            src="..//..//..//..//..//..//public/images/BElongerlogo.png"
            style="width: 35%"
          />
        </div>
        <div class="col-sm-6 text-center">
          <p>
            Document Name :
            <span class="fw-bold fs-5"
              >New Business Partner (BP) Master Data Requisition Form</span
            >
          </p>
        </div>
        <div class="col-sm-3 text-center">
          <p>Doc. Control No. : <span class="fw-bold fs-6">BP-GD-004.00</span></p>
        </div>

        <div class="col-sm-12 py-3"></div>
        <div class="col-sm-4">
          <p class="fw-bold">Business Unit*</p>
          <p-radio label="1" v-model="form.business_unit.company1">
            Everyday Products Corporation (EPC)
          </p-radio>
          <p-radio label="2" v-model="form.business_unit.company1">
            New Barbizon Fashion Incorporated (NBFI)
          </p-radio>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">Requisition Type*</p>
          <p-checkbox v-model="form.requisition_type" value="Branch Code"
            >Branch Code*</p-checkbox
          >
        </div>
        <div class="col-sm-2">
          <p class="fw-bold">Date of Requisition*</p>
          <el-date-picker
            style="width: 100%"
            v-model="form.date_requisition"
            type="date"
            placeholder="Pick a day"
          >
          </el-date-picker>
        </div>
        <div class="col-sm-2">
          <p class="fw-bold">Target Date of Completion*</p>
          <el-date-picker
            style="width: 100%"
            v-model="form.date_completion"
            type="date"
            placeholder="Pick a day"
          >
          </el-date-picker>
        </div>

        <div class="col-sm-4">
          <p class="fw-bold">Business Partner (BP) Branch Name*</p>
          <fg-input placeholder="" v-model="form.business_partner_name"></fg-input>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">Registered Name* (Company Name)</p>
          <fg-input placeholder="" v-model="form.registered_name"></fg-input>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">Business Partner (BP) Chain*</p>
          <fg-input placeholder="" v-model="form.business_partner_chain"></fg-input>
        </div>

        <div class="col-sm-4">
          <p class="fw-bold">Taxpayer Identification Number*</p>
          <fg-input placeholder="" v-model="form.tin"></fg-input>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">Payment Terms* (No. of days)</p>
          <fg-input placeholder="" v-model="form.payment_terms"></fg-input>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">Branch Code*</p>
          <fg-input placeholder="" v-model="form.branch_code"></fg-input>
        </div>

        <div class="col-sm-4">
          <p class="fw-bold">Area Number*</p>
          <el-select
            class="select-default"
            size="large"
            placeholder=""
            v-model="form.area_number"
          >
            <el-option
              v-for="option in area_number"
              class="select-default"
              :value="option"
              :label="option"
              :key="option"
            >
            </el-option>
          </el-select>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">Order Classification*</p>
          <fg-input placeholder="" v-model="form.order_classification"></fg-input>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">Store/Branch Classification*</p>
          <fg-input placeholder="" v-model="form.branch_classification"></fg-input>
        </div>

        <div class="col-sm-4">
          <p class="fw-bold">Delivery Classification*</p>
          <fg-input placeholder="" v-model="form.delivery_classification"></fg-input>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">Region*</p>
          <el-select
            class="select-default"
            size="large"
            placeholder=""
            v-model="form.region"
          >
            <el-option
              v-for="option in region_philippines"
              class="select-default"
              :value="option.region"
              :label="option.label"
              :key="option.region"
            >
            </el-option>
          </el-select>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">Geographical Location*</p>
          <fg-input placeholder="" v-model="form.geographical_location"></fg-input>
        </div>

        <div class="col-sm-4">
          <p class="fw-bold">Mall and Location*</p>
          <fg-input placeholder="" v-model="form.mall_location"></fg-input>
        </div>

        <div class="col-sm-12 py-3"></div>

        <div class="col-sm-4">
          <p class="fw-bold">Contact Person Name*</p>
          <fg-input placeholder="" v-model="form.contact_person"></fg-input>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">Telephone Number</p>
          <fg-input placeholder="" v-model="form.telephone_number"></fg-input>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">Mobile Number</p>
          <fg-input placeholder="" v-model="form.mobile_number"></fg-input>
        </div>

        <div class="col-sm-12 py-3"></div>
        <p class="fw-bold">Bill-to Address</p>

        <div class="col-sm-4">
          <p class="fw-bold">Province*</p>
          <fg-input placeholder="" v-model="form.bill_address.province"></fg-input>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">City*</p>
          <fg-input placeholder="" v-model="form.bill_address.city"></fg-input>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">Street/ PO Box*</p>
          <fg-input placeholder="" v-model="form.bill_address.street_PO_Box"></fg-input>
        </div>

        <div class="col-sm-12 py-1"></div>
        <p class="fw-bold">Ship-to Address (Branch)</p>

        <div class="col-sm-4">
          <p class="fw-bold">Province*</p>
          <fg-input placeholder="" v-model="form.ship_address_branch.province"></fg-input>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">City*</p>
          <fg-input placeholder="" v-model="form.ship_address_branch.city"></fg-input>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">Street/ PO Box*</p>
          <fg-input
            placeholder=""
            v-model="form.ship_address_branch.street_PO_Box"
          ></fg-input>
        </div>

        <div class="col-sm-12 py-1"></div>
        <p class="fw-bold">Ship-to Address (DC)</p>

        <div class="col-sm-4">
          <p class="fw-bold">Province*</p>
          <fg-input placeholder="" v-model="form.ship_address_DC.province"></fg-input>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">City*</p>
          <fg-input placeholder="" v-model="form.ship_address_DC.city"></fg-input>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">Street/ PO Box*</p>
          <fg-input
            placeholder=""
            v-model="form.ship_address_DC.street_PO_Box"
          ></fg-input>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">Allowed Delivery Day/s</p>
          <fg-input placeholder="" v-model="form.allowed_delivery_day"></fg-input>
        </div>

        <div class="col-sm-12 py-3"></div>
        <p class="fw-bold">List of Brands</p>
        <div class="col-sm-4">
          <p class="fw-bold">Brand*</p>
          <el-select
            class="select-default"
            size="large"
            placeholder=""
            v-model="brands.brand"
          >
            <el-option
              v-for="option in brand_list"
              class="select-default"
              :value="option"
              :label="option"
              :key="option"
            >
            </el-option>
          </el-select>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">Category*</p>
          <el-select
            class="select-default"
            size="large"
            placeholder=""
            v-model="brands.category"
          >
            <el-option
              v-for="option in category_list"
              class="select-default"
              :value="option"
              :label="option"
              :key="option"
            >
            </el-option>
          </el-select>
        </div>
        <div class="col-sm-4">
          <p class="fw-bold">Classification*</p>
          <el-select
            class="select-default"
            size="large"
            placeholder=""
            v-model="brands.classification"
          >
            <el-option
              v-for="option in classification_list"
              class="select-default"
              :value="option"
              :label="option"
              :key="option"
            >
            </el-option>
          </el-select>
        </div>

        <div class="col-sm-12 py-1"></div>
        <div class="col-sm-12">
          <el-table
            :data="tableData"
            border
            max-height="650"
            :header-cell-style="headerCellStyle"
            :cell-style="cellStyle"
          >
            <el-table-column prop="brand" label="BRAND" />
            <el-table-column prop="category" label="CATEGORY" />
            <el-table-column prop="classification" label="CLASSIFICATION" />
            <el-table-column fixed="right" label="Actions" width="100">
              <template #default="scope">
                <p-button
                  @click="deleteBrands(scope.row)"
                  type="danger"
                  link
                  class="font-weight-bold w-full m-0"
                >
                  <i class="nc-icon nc-simple-remove font-weight-bold"></i>
                </p-button>
              </template>
            </el-table-column>
          </el-table>
        </div>

        <div class="col-sm-12 py-3"></div>

        <div class="col-sm-4">
          <p class="fw-bold">Approver</p>
          <fg-input placeholder="" v-model="form.allowed_delivery_day"></fg-input>
        </div>

        <div class="col-sm-12 py-3 text-center">
          <p-button style="width: 15%" type="success" outline>
            Request New Branch
          </p-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option, DatePicker } from "element-ui";
// import PButton from "../../../../UIComponents/Button.vue";
import PButton from "../../../UIComponents/Button.vue";
import PPagination from "../../../UIComponents/Pagination.vue";
import axiosClient from "../../../../axios";
import { Card } from "src/components/UIComponents";
import linkName from "../../../../linkName";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  components: {
    [DatePicker.name]: DatePicker,
    [Option.name]: Option,
    [Select.name]: Select,
  },
  data() {
    return {
      brands: {
        brand: "",
        category: "",
        classification: "",
      },
      headerCellStyle: {
        fontSize: "12px",
        color: "black",
      },
      cellStyle: {
        fontSize: "14px",
      },
      pagination: {
        perPage: 5,
        currentPage: 1,
        perPageOptions: [5, 10, 25, 50],
        total: 0,
      },
      searchQuery: "",
      brand_list: [
        "ANGELFISH",
        "BARBIZON",
        "BARBIE",
        "DISNEY",
        "DANSKIN",
        "ISLAND HAZE",
        "MARVEL",
        "NATALIA",
        "ROCK LOBSTER",
        "SASSA",
        "SWIMLAB",
      ],
      category_list: [
        "Boys Swimwear",
        "Boys Underwear",
        "Girls Swimwear",
        "Girls Underwear",
      ],
      classification_list: ["Rashguard", "Body Suit", "Swim Bottoms"],
      area_number: ["Area 1", "Area 2", "Area 3", "Area 4", "Area 5", "Area 6", "Area 7"],
      region_philippines: [
        { label: "Region I – Ilocos Region", region: "I" },
        { label: "Region II – Cagayan Valley", region: "II" },
        { label: "Region III – Central Luzon", region: "III" },
        { label: "Region IV‑A – CALABARZON", region: "IV-A" },
        { label: "Region IV‑B - MIMAROPA Region", region: "IV-B" },
        { label: "Region V – Bicol Region", region: "V" },
        { label: "Region VI – Western Visayas", region: "VI" },
        { label: "Region VII – Central Visayas", region: "VII" },
        { label: "Region VIII – Eastern Visayas", region: "VIII" },
        { label: "Region IX – Zamboanga Peninsula", region: "IX" },
        { label: "Region X – Northern Mindanao", region: "X" },
        { label: "Region XI – Davao Region", region: "XI" },
        { label: "Region XII – SOCCSKSARGEN", region: "XII" },
        { label: "Region XIII – Caraga", region: "XIII" },
        { label: "NCR – National Capital Region", region: "NCR" },
        { label: "CAR – Cordillera Administrative Region", region: "CAR" },
        {
          label: "BARMM – Bangsamoro Autonomous Region in Muslim Mindanao",
          region: "BARMM",
        },
      ],
      tableData: [
        {
          id: 1,
          brand: "BARBIZON",
          category: "Women's Underwear",
          classification: "Bikini Top",
        },
        {
          id: 2,
          brand: "SASSA",
          category: "Women's Underwear",
          classification: "One Piece",
        },
        {
          id: 3,
          brand: "SASSA",
          category: "Women's Underwear",
          classification: "Bikini",
        },
      ],
      form: {
        business_unit: {
          company1: "EPC",
          company2: "NBFI",
        },
        requisition_type: "",
        date_requisition: "",
        date_completion: "",
        business_partner_name: "",
        registered_name: "",
        business_partner_chain: "",
        tin: "",
        payment_terms: "",
        branch_code: "",
        area_number: "",
        order_classification: "",
        branch_classification: "",
        delivery_classification: "",
        region: "",
        geographical_location: "",
        mall_location: "",
        contact_person: "",
        telephone_number: "",
        mobile_number: "",
        bill_address: {
          province: "",
          city: "",
          street_PO_Box: "",
        },
        ship_address_branch: {
          province: "",
          city: "",
          street_PO_Box: "",
        },
        ship_address_DC: {
          province: "",
          city: "",
          street_PO_Box: "",
        },
        allowed_delivery_day: "",
        list_brands: [{ id: "", brand: "", category: "", classification: "" }],
        approver: "",
      },
    };
  },
  methods: {
    deleteBrands(data) {
      alert(`You want to DELETE the brand : ${JSON.stringify(data)}`);

      this.tableData = this.tableData.filter((item) => item.id !== data.id);
    },
  },
};
</script>

<style>
h3 {
  margin-bottom: 5%;
}
.card-generator {
  margin-top: 100px;
}
</style>
