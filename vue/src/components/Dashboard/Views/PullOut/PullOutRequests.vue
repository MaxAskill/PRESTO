<template>
  <div class="row">
    <div class="col-lg-12">
      <card>
        <h4 class="title">Pull-Out Requests</h4>
        <tabs value="Description" tab-content-classes="text-center">
          <tab-pane title="Unprocessed Pull-Out" key="UPO">
            <unprocessed-pull-out></unprocessed-pull-out>
          </tab-pane>
          <tab-pane title="Approved Pull-Out" key="APO">
            <ApprovedPullOut></ApprovedPullOut>
          </tab-pane>
          <tab-pane title="Denied Pull-Out" key="DPO">
            <DeniedPullOut></DeniedPullOut>
          </tab-pane>
        </tabs>
      </card>
    </div>
  </div>
</template>
<script>
import UnprocessedPullOut from "./PullOutRequests/UnprocessedPullOut.vue";
import ApprovedPullOut from "./PullOutRequests/ApprovedPullOut.vue";
import DeniedPullOut from "./PullOutRequests/DeniedPullOut.vue";

import { Card, Tabs, TabPane } from "src/components/UIComponents";

export default {
  components: {
    UnprocessedPullOut,
    ApprovedPullOut,
    DeniedPullOut,
    Tabs,
    TabPane,
    Card,
  },
};
</script>
