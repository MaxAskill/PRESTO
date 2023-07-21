<template>
  <div class="wrapper">
    <side-bar type="sidebar" :sidebar-links="$sidebar.sidebarLinks">
      <!-- <user-menu></user-menu> -->
    </side-bar>

    <div class="main-panel">
      <TopNavbar :key="changeHeaderNameKey" :headerName="headerName"></TopNavbar>

      <DashboardContent
        :headerName="headerName"
        @changedHN="getHeaderName($event)"
        @click.native="toggleSidebar"
      >
      </DashboardContent>

      <!-- <content-footer></content-footer> -->
    </div>
  </div>
</template>
<style lang="scss"></style>
<script>
import TopNavbar from "./TopNavbar.vue";
// import ContentFooter from "./ContentFooter.vue";
import DashboardContent from "./Content.vue";
import UserMenu from "../../UIComponents/SidebarPlugin/UserMenu.vue";
import linkName from "../../../linkName";
import { TimeSelect } from "element-ui";

export default {
  data() {
    return {
      headerName: "",
      changeHeaderNameKey: 0,
    };
  },
  // watch: {
  //   headerName: {
  //     handler(val, oldVal) {
  //       console.log("e changed", this.headerName);
  //       this.headerName = linkName.val;
  //     },
  //     immediate: true,
  //   },
  // },
  components: {
    TopNavbar,
    // ContentFooter,
    DashboardContent,
    UserMenu,
  },
  methods: {
    getHeaderName(name) {
      this.headerName = name;
      this.changeHeaderNameKey += 1;
    },
    toggleSidebar() {
      if (this.$sidebar.showSidebar) {
        this.$sidebar.displaySidebar(false);
      }
    },
  },
  mounted() {
    this.headerName = linkName.val;
  },
};
</script>
