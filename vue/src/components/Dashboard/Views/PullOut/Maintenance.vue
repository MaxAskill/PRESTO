<template>
  <div class="row card-maintenance">
    <div class="col-lg-12">
      <card>
        <tabs vertical>
          <tab-pane title="Branch Maintenance" key="Branch">
            <BranchMaintenance></BranchMaintenance>
          </tab-pane>
          <tab-pane title="Brand Maintenance" key="Brand">
            <BrandMaintenance></BrandMaintenance>
          </tab-pane>
          <tab-pane title="Item Maintenance" key="Item">
            <ItemMaintenance></ItemMaintenance>
          </tab-pane>
          <tab-pane title="Log History" key="LogHistory">
            <LogHistory></LogHistory>
          </tab-pane>
          <tab-pane title="Personnel Maintenance" key="Personnel">
            <PersonnelMaintenance></PersonnelMaintenance>
          </tab-pane>
          <tab-pane title="Reason Maintenance" key="Reason">
            <ReasonMaintenance></ReasonMaintenance>
          </tab-pane>
          <tab-pane title="Request Pull-Out Transactions" key="User">
            <RequestPullOutTransactions></RequestPullOutTransactions>
          </tab-pane>
          <tab-pane title="User Maintenance" key="User">
            <UserMaintenance></UserMaintenance>
          </tab-pane>
        </tabs>
      </card>
    </div>
  </div>
</template>
<script>
import BranchMaintenance from "./Maintenance/BranchMaintenance.vue";
import BrandMaintenance from "./Maintenance/BrandMaintenance.vue";
import ItemMaintenance from "./Maintenance/ItemMaintenance.vue";
import LogHistory from "./Maintenance/LogHistory.vue";
import PersonnelMaintenance from "./Maintenance/PersonnelMaintenance.vue";
import ReasonMaintenance from "./Maintenance/ReasonMaintenance.vue";
import RequestPullOutTransactions from "./Maintenance/RequestPullOutTransactions.vue";
import UserMaintenance from "./Maintenance/UserMaintenance.vue";
import linkName from "../../../../linkName";

import { Card, Tabs, TabPane } from "src/components/UIComponents";

export default {
  components: {
    BranchMaintenance,
    BrandMaintenance,
    ItemMaintenance,
    LogHistory,
    PersonnelMaintenance,
    ReasonMaintenance,
    RequestPullOutTransactions,
    UserMaintenance,
    Tabs,
    TabPane,
    Card,
  },
  mounted() {
    if (window.resolveRouteChange) {
      window.resolveRouteChange();
      window.resolveRouteChange = null;
    }
    linkName.val = "Maintenance";
  },
};
</script>

<style>
.card-maintenance {
  margin-top: 100px;
}
</style>
