<template>
  <div>
    <div
      class="modal fade"
      id="viewModal"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="viewModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5 text-weight-bold" id="deniedunprocessLabel">
              Images
            </h1>
          </div>
          <div class="modal-body">
            <div class="row">
              <div
                class="responsive col-2"
                v-for="(path, eindex) in viewImages"
                :key="index"
              >
                <div class="gallery">
                  <a target="_blank" :href="path">
                    <img class="img-size" :src="path" :alt="'Image ' + (index + 1)" />
                  </a>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>
          </div>
          <div class="modal-footer mrgn-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-target="#unprocessModal"
              data-bs-toggle="modal"
            >
              Back
            </button>
          </div>
        </div>
      </div>
    </div>
    <UnprocessModal></UnprocessModal>
  </div>
</template>
<script>
import UnprocessModal from "../UnprocessModal.vue";

export default {
  components: {
    UnprocessModal,
  },
  props: ["transferredData", "viewImages"],
  data() {
    return {};
  },
  methods: {},
};
</script>

<style>
div.gallery {
  border: 1px solid #ccc;
}

div.gallery:hover {
  border: 1px solid #777;
}

div.gallery img {
  width: 100%;
  height: auto;
}

div.desc {
  padding: 15px;
  text-align: center;
}

* {
  box-sizing: border-box;
}

.responsive {
  padding: 0 6px;
  float: left;
  width: 24.99999%;
}

@media only screen and (max-width: 700px) {
  .responsive {
    width: 49.99999%;
    margin: 6px 0;
  }
}

@media only screen and (max-width: 500px) {
  .responsive {
    width: 100%;
  }
}

.clearfix:after {
  content: "";
  display: table;
  clear: both;
}

.img-size {
  height: 200px !important;
}
</style>
