<template>
  <div :class="vertical ? 'row' : ''">
    <div :class="vertical ? 'col-lg-2 col-md-2' : ''">
      <div class="nav-tabs-navigation" :class="{ 'verical-navs': vertical }">
        <div class="nav-tabs-wrapper">
          <slot name="nav"></slot>
        </div>
      </div>
    </div>
    <div :class="vertical ? 'col-lg-10 col-md-10' : ''">
      <slot name="content"></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: "tabs-layout",
  props: {
    vertical: Boolean,
    verticalNavClasses: [String, Object],
    verticalTabContentClasses: [String, Object],
  },
};
</script>
