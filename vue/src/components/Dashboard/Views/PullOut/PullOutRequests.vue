<template>
  <div class="row card-requests">
    <div class="col-lg-12">
      <card>
        <!-- <h4 class="title">Pull-Out Requests</h4> -->
        <el-tabs type="card">
          <el-tab-pane label="Unprocessed Pull-Out" v-if="isAdmin || isAgent">
            <unprocessed-pull-out></unprocessed-pull-out>
          </el-tab-pane>
          <el-tab-pane label="Pending Approval Pull-Out" v-if="isAdmin || isApprover">
            <pending-approval-pull-out></pending-approval-pull-out>
          </el-tab-pane>
          <el-tab-pane label="Approved Pull-Out">
            <ApprovedPullOut></ApprovedPullOut>
          </el-tab-pane>
          <el-tab-pane label="Denied Pull-Out">
            <DeniedPullOut></DeniedPullOut>
          </el-tab-pane>
        </el-tabs>
        <!-- <tabs value="Description" tab-content-classes="text-center" v-if="isAdmin">
          <tab-pane title="Unprocessed Pull-Out" key="UPO">
            <unprocessed-pull-out></unprocessed-pull-out>
          </tab-pane>
          <tab-pane title="Pending Approval Pull-Out" key="PAP">
            <pending-approval-pull-out></pending-approval-pull-out>
          </tab-pane>
          <tab-pane title="Approved Pull-Out" key="APO">
            <ApprovedPullOut></ApprovedPullOut>
          </tab-pane>
          <tab-pane title="Denied Pull-Out" key="DPO">
            <DeniedPullOut></DeniedPullOut>
          </tab-pane>
        </tabs>
        <tabs value="Description" tab-content-classes="text-center" v-if="isApprover">
          <tab-pane title="Pending Approval Pull-Out" key="PAP">
            <pending-approval-pull-out></pending-approval-pull-out>
          </tab-pane>
          <tab-pane title="Approved Pull-Out" key="APO">
            <ApprovedPullOut></ApprovedPullOut>
          </tab-pane>
          <tab-pane title="Denied Pull-Out" key="DPO">
            <DeniedPullOut></DeniedPullOut>
          </tab-pane>
        </tabs>
        <tabs value="Description" tab-content-classes="text-center" v-if="isAgent">
          <tab-pane title="Unprocessed Pull-Out" key="UPO">
            <unprocessed-pull-out></unprocessed-pull-out>
          </tab-pane>
          <tab-pane title="Approved Pull-Out" key="APO">
            <ApprovedPullOut></ApprovedPullOut>
          </tab-pane>
          <tab-pane title="Denied Pull-Out" key="DPO">
            <DeniedPullOut></DeniedPullOut>
          </tab-pane>
        </tabs> -->
        <!-- <tabs value="Description" tab-content-classes="text-center" v-if="isApprover">
          <tab-pane title="Pending Approval Pull-Out" key="PAP">
            <pending-approval-pull-out></pending-approval-pull-out>
          </tab-pane>
          <tab-pane title="Approved Pull-Out" key="APO">
            <ApprovedPullOut></ApprovedPullOut>
          </tab-pane>
          <tab-pane title="Denied Pull-Out" key="DPO">
            <DeniedPullOut></DeniedPullOut>
          </tab-pane>
        </tabs>
        <tabs value="Description" tab-content-classes="text-center" v-if="isAgent">
          <tab-pane title="Unprocessed Pull-Out" key="UPO">
            <unprocessed-pull-out></unprocessed-pull-out>
          </tab-pane>
          <tab-pane title="Approved Pull-Out" key="APO">
            <ApprovedPullOut></ApprovedPullOut>
          </tab-pane>
          <tab-pane title="Denied Pull-Out" key="DPO">
            <DeniedPullOut></DeniedPullOut>
          </tab-pane>
        </tabs> -->
      </card>
    </div>
  </div>
</template>
<script>
import Vue from "vue";

import { Tabs, TabPane } from "element-ui";
import UnprocessedPullOut from "./PullOutRequests/UnprocessedPullOut.vue";
import ApprovedPullOut from "./PullOutRequests/ApprovedPullOut.vue";
import DeniedPullOut from "./PullOutRequests/DeniedPullOut.vue";
import PendingApprovalPullOut from "./PullOutRequests/PendingApprovalPullOut.vue";
import linkName from "../../../../linkName";

import { Card } from "src/components/UIComponents";
Vue.use(Tabs);
Vue.use(TabPane);

export default {
  components: {
    UnprocessedPullOut,
    ApprovedPullOut,
    DeniedPullOut,
    PendingApprovalPullOut,
    Tabs,
    TabPane,
    Card,
  },
  mounted() {
    this.showingPerPosition();

    if (window.resolveRouteChange) {
      window.resolveRouteChange();
      window.resolveRouteChange = null;
    }
    linkName.val = "Pull-Out Requests";
  },
  methods: {
    showingPerPosition() {
      var position = sessionStorage.getItem("Position");
      if (position == "Agent") {
        this.isUnprocessed = true;
        this.isPendingApproval = false;
        this.isApproval = true;
        this.isDenied = true;
        this.isAgent = true;
      } else if (position == "Approver") {
        this.isUnprocessed = false;
        this.isPendingApproval = true;
        this.isApproval = true;
        this.isDenied = true;
        this.isApprover = true;
      } else if (position == "Admin") {
        this.isAdmin = true;
      }
    },
  },
  data() {
    return {
      isUnprocessed: null,
      isPendingApproval: null,
      isApproval: true,
      isDenied: true,
      isAdmin: false,
      isAgent: false,
      isApprover: false,
    };
  },
};
</script>

<style>
.card-requests {
  margin-top: 100px;
}
</style>
