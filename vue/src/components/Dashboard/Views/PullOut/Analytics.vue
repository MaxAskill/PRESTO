<template>
  <div class="card card-draft">
    <div class="card-body">
      <iframe
        v-if="showEPC"
        id="analyticsIFrame"
        :src="urlEPC"
        frameborder="0"
        allowtransparency
      ></iframe>
      <iframe
        v-if="showNBFI"
        id="analyticsIFrame"
        :src="urlNBFI"
        frameborder="0"
        width="800"
        height="600"
        allowtransparency
      ></iframe>
    </div>
  </div>
</template>
<script>
import linkName from "../../../../linkName";

export default {
  components: {},
  mounted() {
    if (window.resolveRouteChange) {
      window.resolveRouteChange();
      window.resolveRouteChange = null;
    }
    linkName.val = "Requested Pull-Outs Analytics";

    if (sessionStorage.getItem("Position") != "Admin")
      switch (sessionStorage.getItem("Company")) {
        case "EPC":
        case "AHLC":
          this.showNBFI = false;
          this.urlEPC =
            "http://192.168.0.7:13000/public/dashboard/322ee40e-830f-4d41-9563-15d570e69a39#titled=false";
          break;

        case "NBFI":
        case "ASC":
        case "CMC":
          this.showEPC = false;
          this.urlNBFI =
            "http://192.168.0.7:13000/public/dashboard/559ba55e-5a0e-4ff8-a8c6-332388a18f63#titled=false";
          break;
      }
    else {
      this.urlEPC =
        "http://192.168.0.7:13000/public/dashboard/322ee40e-830f-4d41-9563-15d570e69a39";
      this.urlNBFI =
        "http://192.168.0.7:13000/public/dashboard/559ba55e-5a0e-4ff8-a8c6-332388a18f63";
    }
  },
  data() {
    return {
      showEPC: true,
      showNBFI: true,
      urlEPC: "",
      urlNBFI: "",
    };
  },
  methods: {},
};
</script>
<style>
#analyticsIFrame {
  width: 100%;
  height: 80vh;
}
iframe div .EmbedFrame-footer {
  height: 0px !important;
}
</style>
