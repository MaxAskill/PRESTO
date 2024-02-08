<template>
  <div
    class="modal fade"
    id="promoAssignBranchByAgentModal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="promoAssignBranchByAgentModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">
            Assigning of Temporary Branch for promo: <b>{{ promoData.name }}</b>
          </h6>
        </div>
        <div class="modal-body text-center">
          <div class="row">
            <div class="col-sm-6">
              <fg-input label="Company">
                <el-select
                  class="select-default"
                  size="large"
                  placeholder="Select Company"
                  v-model="form.Company"
                  @change="
                      fetchChainCode(),
                    "
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
              </fg-input>
            </div>
            <div class="col-sm-6">
              <fg-input label="Chain Name">
                <el-select
                  class="select-default"
                  size="large"
                  placeholder="Select Chain Name"
                  v-model="form.ChainCode"
                  @change="fetchChainName()"
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
              </fg-input>
            </div>
            <div class="col-sm-6">
              <fg-input label="Branch Name">
                <el-select
                  class="select-default"
                  size="large"
                  placeholder="Select Branch Name"
                  v-model="form.BranchName"
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
              </fg-input>
            </div>
            <div class="col-sm-6 row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="date-label text-left">Start Date</label>
                  <el-date-picker
                    v-model="form.StartDate"
                    type="date"
                    placeholder="Pick a date"
                  >
                  </el-date-picker>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="date-label text-left">End Date</label>
                  <el-date-picker
                    v-model="form.EndDate"
                    type="date"
                    placeholder="Pick a date"
                  >
                  </el-date-picker>
                </div>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-center">
            <button class="btn btn-success" data-bs-dismiss="modal">
              Add This Branch
            </button>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-success" data-bs-dismiss="modal">Proceed</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axiosClient from "../../../../axios";

export default {
  components: {},
  watch: {},
  props: ["promoData"],
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
      companyList: [],
      chainCodeList: [],
      branchNameList: [],
      form: {
        Company: "",
        ChainCode: "",
        BranchName: "",
        StartDate: "",
        EndDate: "",
      },
    };
  },
  mounted() {
    this.fetchCompany();
  },
  methods: {
    fetchCompany() {
      axiosClient
        .get("/fetchCompany")
        .then((response) => {
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
            company: this.form.Company,
          },
        })
        .then((response) => {
          this.chainCodeList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    fetchChainName() {
      axiosClient
        .get("/fetchChainName", {
          params: {
            chainCode: this.form.ChainCode,
            company: this.form.Company,
          },
        })
        .then((response) => {
          this.branchNameList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>

<style lang="scss">
.date-label {
  font-size: 12px;
  color: gray;
}
</style>
