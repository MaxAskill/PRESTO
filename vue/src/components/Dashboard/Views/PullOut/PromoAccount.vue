<template>
  <div class="card card-account">
    <div class="card-body">
      <div class="row mx-2">
        <el-table
          class="table-striped p-0"
          :data="queriedData"
          border
          max-height="650"
          style="width: 100%"
        >
          <el-table-column>
            <template slot="header" slot-scope="scope">
              <fg-input
                class="input-md"
                placeholder="Search"
                v-model="searchQuery"
                addon-right-icon="nc-icon nc-zoom-split"
                style="width: 100%"
              >
              </fg-input>
            </template>
            <el-table-column
              v-for="column in tableColumns"
              :key="column.label"
              :min-width="column.minWidth"
              :prop="column.prop"
              :label="column.label"
              header-align="center"
              :sortable="column.sortable"
            >
            </el-table-column>
          </el-table-column>
          <el-table-column>
            <template slot="header" slot-scope="scope"> </template>
            <el-table-column
              :width="150"
              class-name="td-actions"
              label="Branch"
              header-align="center"
              align="center"
            >
              <template slot-scope="props">
                <p-button
                  v-if="
                    (props.row.status === 'Activated' ||
                      props.row.status === 'Deactivated') &&
                    props.row.roving > 1
                  "
                  type="success"
                  size="sm"
                  data-bs-toggle="modal"
                  data-bs-target="#promoDetailsModal"
                  @click="
                    handleEdit(props.$index, props.row),
                      fetchDataPromoDetails(props.row.id)
                  "
                >
                  Roving [ {{ props.row.roving }} ]
                </p-button>
                <p-button
                  v-if="
                    (props.row.status === 'Activated' ||
                      props.row.status === 'Deactivated') &&
                    props.row.roving == 1
                  "
                  type="info"
                  size="sm"
                  data-bs-toggle="modal"
                  data-bs-target="#promoDetailsModal"
                  @click="
                    handleEdit(props.$index, props.row),
                      fetchDataPromoDetails(props.row.id)
                  "
                >
                  Non-Roving
                </p-button>
              </template>
            </el-table-column>

            <el-table-column
              :width="110"
              class-name="td-actions"
              label="Request"
              header-align="center"
              :filters="[
                { text: 'With Request', value: 1 },
                { text: 'Without Request', value: 0 },
              ]"
              :filter-method="filterRequest"
              filter-placement="bottom-end"
            >
              <template slot-scope="props">
                <p-button
                  v-if="props.row.request"
                  type="info"
                  class="m-0"
                  icon
                  data-bs-toggle="modal"
                  data-bs-target="#promoRequestBranchModal"
                  @click="
                    handleEdit(props.$index, props.row),
                      fetchDataPromoRequest(props.row.id)
                  "
                >
                  <i class="nc-icon nc-badge"></i>
                </p-button>
              </template>
            </el-table-column>
          </el-table-column>
          <el-table-column fixed="right" width="160">
            <template slot="header" slot-scope="scope">
              <el-select
                class="select-default"
                v-model="pagination.perPage"
                placeholder="Per page"
              >
                <el-option
                  class="select-default"
                  v-for="item in pagination.perPageOptions"
                  :key="item"
                  :label="item"
                  :value="item"
                >
                </el-option>
              </el-select>
            </template>
            <el-table-column
              :width="160"
              class-name="td-actions"
              label="Status"
              header-align="center"
              :filters="[
                { text: 'New Account', value: null },
                { text: 'Activated', value: 'Activated' },
                { text: 'Deactivated', value: 'Deactivated' },
              ]"
              :filter-method="filterStatus"
              filter-placement="bottom-end"
            >
              <template slot-scope="props">
                <p-button
                  v-if="props.row.status === 'Activated'"
                  type="success"
                  size="sm"
                  data-bs-toggle="modal"
                  data-bs-target="#promoDeactivationModal"
                  @click="handleEdit(props.$index, props.row)"
                >
                  Activated
                </p-button>
                <p-button
                  v-else-if="props.row.status === 'Deactivated'"
                  type="warning"
                  size="sm"
                  data-bs-toggle="modal"
                  data-bs-target="#promoReactivationModal"
                  @click="handleEdit(props.$index, props.row)"
                >
                  Deactivated
                </p-button>
                <p-button
                  v-else-if="!props.row.status"
                  type="info"
                  size="sm"
                  data-bs-toggle="modal"
                  data-bs-target="#promoActivation"
                  @click="handleEdit(props.$index, props.row)"
                >
                  New Account
                </p-button>
              </template>
            </el-table-column>
          </el-table-column>
        </el-table>
      </div>
      <div class="d-flex justify-content-end pagination-info">
        <p class="category p-margin">
          Showing {{ from + 1 }} to {{ to }} of {{ total }} entries
        </p>
      </div>
      <div class="d-flex justify-content-center">
        <p-pagination
          class="pull-center"
          v-model="pagination.currentPage"
          :per-page="pagination.perPage"
          :total="pagination.total"
        >
        </p-pagination>
      </div>
    </div>
    <PromoActivation :promoData="promoData" @fetchUsers="fetchData"> </PromoActivation>
    <PromoDeactivationModal
      :promoData="promoData"
      @fetchUsers="fetchData"
    ></PromoDeactivationModal>
    <PromoReactivationModal
      :promoData="promoData"
      @fetchUsers="fetchData"
    ></PromoReactivationModal>
    <PromoDetailsModal
      :promoData="promoData"
      :promoDetails="promoDetails"
      :dateBranchEnd="dateBranchEnd"
      @fetchPromoDetails="fetchDataPromoDetails($event)"
      @fetchData="fetchData()"
    ></PromoDetailsModal>
    <PromoRequestBranchModal
      :promoData="promoData"
      :promoDetails="promoDetails"
      :promoRequest="promoRequest"
      @fetchUsers="fetchData"
      @fetchPromoRequest="fetchDataPromoRequest($event)"
    >
    </PromoRequestBranchModal>
  </div>
</template>
<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option, Popover, Tag } from "element-ui";
import PButton from "../../../UIComponents/Button.vue";
import PPagination from "../../../UIComponents/Pagination.vue";
import axiosClient from "../../../../axios";
import linkName from "../../../../linkName";

import PromoActivation from "./PromoActivation.vue";
import PromoDeactivationModal from "./PromoDeactivationModal.vue";
import PromoReactivationModal from "./PromoReactivationModal.vue";
import PromoDetailsModal from "./PromoDetailsModal.vue";
import PromoRequestBranchModal from "./PromoRequestBranchModal.vue";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
Vue.use(Popover);
Vue.use(Tag);

export default {
  components: {
    PButton,
    PPagination,
    PromoActivation,
    PromoDeactivationModal,
    PromoReactivationModal,
    PromoDetailsModal,
    PromoRequestBranchModal,
  },
  mounted() {
    this.fetchData();
    if (window.resolveRouteChange) {
      window.resolveRouteChange();
      window.resolveRouteChange = null;
    }
    linkName.val = "Promodiser's Account";
  },
  computed: {
    pagedData() {
      return this.tableData.slice(this.from, this.to);
    },
    /***
     * Searches through table data and returns a paginated array.
     * Note that this should not be used for table with a lot of data as it might be slow!
     * Do the search and the pagination on the server and display the data retrieved from server instead.
     * @returns {computed.pagedData}
     */
    queriedData() {
      if (!this.searchQuery) {
        this.pagination.total = this.tableData.length;
        return this.pagedData;
      }
      let result = this.tableData.filter((row) => {
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
      this.pagination.total = this.tableData.length;
      return this.tableData.length;
    },
  },
  data() {
    return {
      pagination: {
        perPage: 10,
        currentPage: 1,
        perPageOptions: [5, 10, 25, 50],
        total: 0,
      },
      searchQuery: "",
      propsToSearch: ["name", "email", "date"],
      tableColumns: [
        {
          prop: "name",
          label: "Full Name",
          minWidth: 120,
          sortable: false,
        },
        {
          prop: "email",
          label: "Email",
          minWidth: 150,
          sortable: false,
        },

        {
          prop: "date",
          label: "Date Registered",
          minWidth: 180,
          sortable: true,
        },
      ],
      tableData: [],
      promoData: {},
      promoDetails: [],
      promoRequest: [],
      dateBranchEnd: "",
    };
  },
  methods: {
    denied(row) {
      this.$router.push({
        path: "/pull-out/requisition-form",
        query: {
          transactionID: row.plID,
          company: row.company,
        },
      });
    },
    fetchData() {
      axiosClient
        .get("/usersMaintenance", {
          params: {
            company: sessionStorage.getItem("Company"),
          },
        })
        .then((response) => {
          this.tableData = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    fetchDataPromoDetails(user_ID) {
      axiosClient
        .get("/usersMaintenanceViewDetails", {
          params: {
            userID: user_ID,
          },
        })
        .then((response) => {
          this.promoDetails = response.data;
          this.dateBranchEnd = this.promoDetails[0].dateEnd;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    fetchDataPromoRequest(user_ID) {
      axiosClient
        .get("/usersMaintenanceRequestBranch", {
          params: {
            userID: user_ID,
          },
        })
        .then((response) => {
          this.promoRequest = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
      this.fetchDataPromoDetails(user_ID);
    },
    handleEdit(index, row) {
      this.promoData = row;
    },
    handleDelete(index, row) {
      let indexToDelete = this.tableData.findIndex((tableRow) => tableRow.id === row.id);
      if (indexToDelete >= 0) {
        this.tableData.splice(indexToDelete, 1);
      }
    },
    filterStatus(value, row) {
      return row.status === value;
    },
    filterRequest(value, row) {
      return row.request === value;
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

.nav-center {
  display: flex;
  justify-content: center; /* Center horizontally */
  align-items: center; /* Center vertically */
}
.btn-margin {
  margin: 0px 0px;
}
.p-margin {
  margin-bottom: 0px;
}
.el-select-w {
  width: 91%;
  margin-right: 10px;
}
.first-btn {
  padding-left: 4px;
  padding-right: 12px;
}
.last-btn {
  margin-left: 10px;
}
.denied-btn {
  background-color: #ef8157;
  font-size: 10px;
  border-radius: 10px;
  border: none;
  text-transform: uppercase;
  color: white;
}

.card-account {
  margin-top: 100px;
}
.span-popover {
  font-size: 0.8571em;
  color: #9a9a9a;
}
</style>
