<template>
  <div
    class="modal fade"
    id="uploaditemmodal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="uploaditemmodalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-weight-bold" id="uploaditemmodalLabel">
            UPLOAD ITEM
          </h1>
        </div>
        <div class="modal-body">
          <div class="upload-style">
            <input type="file" accept=".csv" class="upload-input-style" ref="fileInput" />
            <p class="upload-text-style">Drop a file here to click to upload</p>
          </div>
        </div>
        <div class="modal-footer px-5">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Close
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click.prevent="handleFileInputChange()"
          >
            Save
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Vue from "vue";
import { Table, TableColumn, Select, Option } from "element-ui";
import axiosClient from "../../../../../../axios";

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);
export default {
  watch: {},
  computed: {
    /***
     * Searches through table data and returns a paginated array.
     * Note that this should not be used for table with a lot of data as it might be slow!
     * Do the search and the pagination on the server and display the data retrieved from server instead.
     * @returns {computed.pagedData}
     */
  },
  data() {},
  methods: {
    handleFileInputChange() {
      const selectedFile = this.$refs.fileInput.files[0];
      // Do something with the selected file
      const formData = new FormData();
      formData.append("file", selectedFile);

      axiosClient
        .post("/itemsImport", formData)
        .then((response) => {
          // console.log("Success:", response.data);

          setTimeout(() => {
            window.location.reload();
          }, 3000); // Reload after 3 seconds
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>

<style lang="scss">
.form-group {
  text-align: left !important;
}
.upload-style {
  border-style: dashed;
  border-color: #9ca3af;
  border-radius: 0.5rem;
  border-width: 2px;
  padding-top: 12vh;
  padding-left: 10vw;
  padding-right: 10vw;
  padding-bottom: 12vh;
}
.upload-input-style {
  text-transform: uppercase;
  color: #ef4444;
  font-weight: 700;
  border-radius: 5px;
  border-style: solid;
  border-width: 1px;
  border-color: #9ca3af;
}
.upload-text-style {
  color: #4b5563;
  font-size: 13px;
  margin-top: 15px;
}
</style>
