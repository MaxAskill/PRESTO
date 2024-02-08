<template>
  <div class="row card-requests">
    <div class="col-lg-12">
      <card>
        <!-- <h4 class="title">Pull-Out Requests</h4> -->
        <el-tabs type="card">
          <el-tab-pane label="For Review Pull-Out Request">
            <unprocessed-pull-out></unprocessed-pull-out>
          </el-tab-pane>
          <el-tab-pane label="For Approval Pull-Out Request">
            <pending-approval-pull-out></pending-approval-pull-out>
          </el-tab-pane>
          <el-tab-pane label="Approved Pull-Out Request">
            <ApprovedPullOut></ApprovedPullOut>
          </el-tab-pane>
          <el-tab-pane label="Denied Pull-Out Request">
            <DeniedPullOut></DeniedPullOut>
          </el-tab-pane>
        </el-tabs>
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
    if (window.resolveRouteChange) {
      window.resolveRouteChange();
      window.resolveRouteChange = null;
    }
    linkName.val = "Pull-Out Requests";
  },
  methods: {},
  data() {
    return {};
  },
};
</script>

<style>
.card-requests {
  margin-top: 100px;
}
</style>
