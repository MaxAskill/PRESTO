<template>
  <div class="sidebar" :data-color="backgroundColor" :data-active-color="activeColor">
    <div class="logo">
      <a
        class="simple-text logo-mini user-select-none"
        aria-label="sidebar mini logo"
        @click="logoDashboard"
      >
        <div class="logo-img">
          <img :src="logo" alt="" />
        </div>
      </a>
      <a class="simple-text logo-normal user-select-none" @click="logoDashboard">
        PRESTO
      </a>
      <!-- <div>
        <span class="simple-text"> Date Today: {{ date }} </span>
        <span class="simple-text"> Time Today: {{ time }} </span>
      </div> -->
    </div>

    <div class="sidebar-wrapper" ref="sidebarScrollArea">
      <slot> </slot>
      <ul class="nav">
        <slot name="links">
          <sidebar-item
            :name="name"
            v-for="(link, index) in filteredSidebarLinks"
            :key="link.name + index"
            :link="link"
          >
            <sidebar-item
              :name="name"
              v-for="(subLink, index) in link.children"
              v-if="
                positionEmployee === subLink.access ||
                positionEmployee === subLink.access2 ||
                positionEmployee === 'Admin' ||
                subLink.access === 'All'
              "
              :key="subLink.name + index"
              :link="subLink"
            >
            </sidebar-item>
          </sidebar-item>
        </slot>
      </ul>
    </div>
  </div>
</template>
<script>
import "perfect-scrollbar/css/perfect-scrollbar.css";
import axiosClient from "../../../axios";
import axios from "axios";
export default {
  data() {
    return {
      positionEmployee: "",
      name: "",
      date: "",
      time: "",
      ipAddress: "",
    };
  },
  props: {
    title: {
      type: String,
      default: "Vue PDP Pro",
      description: "Sidebar title",
    },
    backgroundColor: {
      type: String,
      default: "black",
      validator: (value) => {
        let acceptedValues = ["white", "brown", "black"];
        return acceptedValues.indexOf(value) !== -1;
      },
      description: "Sidebar background color (white|brown|black)",
    },
    activeColor: {
      type: String,
      default: "success",
      validator: (value) => {
        let acceptedValues = ["primary", "info", "success", "warning", "danger"];
        return acceptedValues.indexOf(value) !== -1;
      },
      description: "Sidebar active text color (primary|info|success|warning|danger)",
    },
    logo: {
      type: String,
      default: "static/img/logo.png",
      description: "Sidebar Logo",
    },
    sidebarLinks: {
      type: Array,
      default: () => [],
      description:
        "Sidebar links. Can be also provided as children components (sidebar-item)",
    },
    autoClose: {
      type: Boolean,
      default: true,
    },
  },
  provide() {
    return {
      autoClose: this.autoClose,
    };
  },
  methods: {
    async initScrollBarAsync() {
      this.positionEmployee = sessionStorage.getItem("Position");

      // if (this.positionEmployee == "Approver") {
      //   this.positionEmployee = "Reviewer";
      // }
      // console.log("SideBar: ", this.sidebarLinks);
      let isWindows = navigator.platform.startsWith("Win");
      if (!isWindows) {
        return;
      }
      // const PerfectScroll = await import('perfect-scrollbar')
      // PerfectScroll.initialize(this.$refs.sidebarScrollArea)
    },
    logoDashboard() {
      if (this.positionEmployee == "Admin") {
        // window.location.href = "http://192.168.0.7:4040/#/admin/overview";
        this.$router.push({
          path: "/admin/overview",
        });
      } else if (
        this.positionEmployee == "Reviewer" ||
        this.positionEmployee == "Approver"
      ) {
        // window.location.href = "http://192.168.0.7:4040/#/pull-out/requests";
        this.$router.push({
          path: "/pull-out/requests",
        });
      } else if (this.positionEmployee == "User") {
        // window.location.href = "http://192.168.0.7:4040/#/pull-out/requisition-form";
        this.$router.push({
          path: "/pull-out/requisition-form",
        });
      }
    },
    fetchData() {
      axiosClient
        .get("/users", {
          params: {
            email: sessionStorage.getItem("Email"),
          },
        })
        .then((response) => {
          // console.log("Success User Response:", response.data);
          this.name = response.data[0].name;
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
  mounted() {
    this.fetchData();
    this.initScrollBarAsync();
  },
  beforeDestroy() {
    if (this.$sidebar.showSidebar) {
      this.$sidebar.showSidebar = false;
    }
  },
  computed: {
    filteredSidebarLinks() {
      // Get the user's position from a variable or Vuex state, replace 'userPosition' with the actual variable or state
      const userPosition = this.positionEmployee;

      // Filter the sidebar links based on the user's position
      if (userPosition === "User") {
        return this.sidebarLinks.filter(
          (link) => link.access === "User" || link.access === "All"
        );
      } else if (userPosition === "Reviewer" || userPosition === "Approver") {
        console.log("Approver");
        return this.sidebarLinks.filter(
          (link) => link.access === "Reviewer" || link.access === "All"
        );
      } else {
        return this.sidebarLinks;
      }
    },
  },
};
</script>
<style>
@media (min-width: 992px) {
  .navbar-search-form-mobile,
  .nav-mobile-menu {
    display: none;
  }
}
</style>
