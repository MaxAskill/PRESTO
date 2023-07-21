<template>
  <div class="content">
    <transition name="fade" mode="out-in">
      <!-- your content here -->
      <router-view></router-view>
    </transition>
  </div>
</template>
<script>
import linkName from "../../../linkName";
export default {
  props: ["headerName"],
  watch: {
    $route: function (from, to) {
      new Promise((resolve) => {
        window.resolveRouteChange = resolve;
      }).then(() => {
        this.headerName = linkName.val;
        this.$emit("changedHN", this.headerName);
      });
    },
  },
};
</script>
<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.15s;
}

.fade-enter,
  .fade-leave-to
    /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}
</style>
