<template>
  <component
    :is="baseComponent"
    :to="link.path ? link.path : '/'"
    :class="{ active: isActive || $route.fullPath.startsWith(link.path) }"
    tag="li"
  >
    <div v-if="link.name == 'UserPromo'" class="user">
      <div class="photo">
        <img src="static/img/faces/user-icon.png" alt="user avatar" />
        <!-- import router from "../../../routes/routes"; -->
      </div>
      <div class="info">
        <a v-if="isMenu" data-toggle="collapse" href="#" @click.prevent="collapseMenu">
          <span>
            {{ name }}
            <b class="caret" :class="{ rotated: !collapsed }"></b>
          </span>
          <span class="simple-text customize-datetimeposition">
            {{ position }}
          </span>
          <span class="simple-text customize-datetimeposition">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date:
            {{ date }}
          </span>
          <span class="simple-text customize-datetimeposition">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Time:
            {{ time }}
          </span>
        </a>

        <collapse-transition v-if="$slots.default || this.isMenu">
          <div v-show="!collapsed" class="collapse-menu">
            <ul class="nav">
              <slot></slot>
            </ul>
          </div>
        </collapse-transition>

        <slot name="title" v-if="children.length === 0 && !$slots.default && link.path">
          <component
            :to="link.path"
            :is="elementType(link, false)"
            :class="{ active: link.active }"
            :target="link.target"
            @click.native.stop="onItemClick"
            :href="link.path"
          >
            <template v-if="addLink">
              <span class="sidebar-mini-icon">{{ link.name.substring(0, 1) }}</span>
              <span class="sidebar-normal">{{ link.name }}</span>
            </template>
            <template v-else>
              <i :class="link.icon"></i>
              <p>{{ link.name }}</p>
            </template>
          </component>
        </slot>
      </div>
    </div>
    <a
      v-if="isMenu && link.name != 'UserPromo'"
      data-toggle="collapse"
      href="#"
      @click.prevent="collapseMenu"
    >
      <i :class="link.icon"></i>
      <p>
        {{ link.name }}
        <b class="caret" :class="{ rotated: !collapsed }"></b>
      </p>
    </a>
    <collapse-transition
      v-if="($slots.default || this.isMenu) && this.link.name != 'UserPromo'"
    >
      <div v-show="!collapsed" class="collapse-menu">
        <ul class="nav">
          <slot></slot>
        </ul>
      </div>
    </collapse-transition>
    <slot name="title" v-if="children.length === 0 && !$slots.default && link.path">
      <component
        :to="link.path"
        :is="elementType(link, false)"
        :class="{ active: link.active }"
        :target="link.target"
        @click.native.stop="onItemClick"
        :href="link.path"
      >
        <template v-if="addLink">
          <span class="sidebar-mini-icon">{{ link.name.substring(0, 1) }}</span>
          <span class="sidebar-normal">{{ link.name }}</span>
        </template>
        <template v-else>
          <i :class="link.icon"></i>
          <p>{{ link.name }}</p>
        </template>
      </component>
    </slot>
  </component>
</template>
<script>
import { CollapseTransition } from "vue2-transitions";
import linkName from "../../../linkName";
import axiosClient from "../../../axios";

export default {
  components: {
    CollapseTransition,
  },
  props: {
    name: {
      type: String,
      default: "",
    },
    menu: {
      type: Boolean,
      default: false,
      description: "Whether item is a menu containing multiple items",
    },
    link: {
      type: Object,
      default: () => {
        return {
          name: "",
          path: "",
          children: [],
        };
      },
      description: "Link object",
    },
  },
  provide() {
    return {
      addLink: this.addChild,
      removeLink: this.removeChild,
    };
  },
  inject: {
    addLink: { default: null },
    removeLink: { default: null },
    autoClose: { default: false },
  },
  data() {
    return {
      children: [],
      collapsed: true,
      date: "",
      time: "",
      position: "",
    };
  },
  created() {
    setInterval(this.dateTime, 1000);
  },
  computed: {
    baseComponent() {
      return this.isMenu || this.link.isRoute ? "li" : "router-link";
    },
    isMenu() {
      return this.children.length > 0 || this.menu === true;
    },
    isActive() {
      if (this.$route) {
        let matchingRoute = this.children.find((c) =>
          this.$route.path.startsWith(c.link.path)
        );
        if (matchingRoute !== undefined) {
          return true;
        }
      }
      return false;
    },
  },
  methods: {
    dateTime() {
      const today = new Date();
      const months = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ];
      const formattedDate =
        months[today.getMonth()] +
        " " +
        (today.getDate() < 10 ? "0" : "") +
        today.getDate() +
        ", " +
        today.getFullYear();

      this.date = formattedDate;

      const hours = today.getHours();
      const minutes = today.getMinutes();
      const seconds = today.getSeconds();

      let formattedHours = hours % 12;
      formattedHours = formattedHours === 0 ? 12 : formattedHours;

      const period = hours >= 12 ? "PM" : "AM";
      const formattedMinutes = minutes < 10 ? "0" + minutes : minutes;
      const formattedSeconds = seconds < 10 ? "0" + seconds : seconds;

      const time =
        formattedHours + ":" + formattedMinutes + ":" + formattedSeconds + " " + period;
      // const dateTime = date + ' ' + time;

      this.time = time;
      // console.log("Date:", this.date);

      this.position = sessionStorage.getItem("Position");
    },
    addChild(item) {
      const index = this.$slots.default.indexOf(item.$vnode);
      this.children.splice(index, 0, item);
    },
    removeChild(item) {
      const tabs = this.children;
      const index = tabs.indexOf(item);
      tabs.splice(index, 1);
    },
    elementType(link, isParent = true) {
      if (link.isRoute === false) {
        return isParent ? "li" : "a";
      } else {
        return "router-link";
      }
    },
    collapseMenu() {
      this.collapsed = !this.collapsed;
    },
    onItemClick() {
      if (this.autoClose) {
        this.$sidebar.showSidebar = false;
      }
      if (this.link.name == "Logout") this.logout();
      else linkName.val = this.link.name;
    },
    logout() {
      axiosClient.post("/logout").then((response) => {
        // console.log(response);
        sessionStorage.removeItem("UserID");
        sessionStorage.removeItem("Token");
        sessionStorage.removeItem("Position");
        sessionStorage.removeItem("Company");
        sessionStorage.removeItem("Email");
        this.$router.push({ name: "Login" });
      });
    },
  },
  mounted() {
    if (this.addLink) {
      this.addLink(this);
    }
    if (this.link.collapsed !== undefined) {
      this.collapsed = this.link.collapsed;
    }
    if (this.isActive && this.isMenu) {
      this.collapsed = false;
    }
  },
  destroyed() {
    if (this.$el && this.$el.parentNode) {
      this.$el.parentNode.removeChild(this.$el);
    }
    if (this.removeLink) {
      this.removeLink(this);
    }
  },
};
</script>
<style scoped>
.caret.rotated {
  transform: rotate(180deg);
}

.customize-datetimeposition {
  font-size: 12px !important;
}
</style>
