<template>
  <div>
    <div
      class="modal"
      id="unprocessModal"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="unprocessModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <!-- <h1 class="modal-title fs-5 text-weight-bold" id="unprocessModalLabel">
                Pull Out Transaction
              </h1> -->
            <div class="row">
              <div class="col d-flex justify-content-center">
                <h6 class="modal-title">For Review Pull Out Transaction</h6>
              </div>
              <div class="col-auto">
                <button class="delete-buttons" data-bs-dismiss="modal">
                  <i
                    class="nc-icon nc-simple-remove font-weight-bold"
                    style="margin-top: 5px"
                  ></i>
                </button>
              </div>
            </div>
          </div>
          <div class="modal-body">
            <p>HDSHAKJSDJHAGSD ASDHGFIAUSD</p>
          </div>
          <div class="modal-footer mrgn-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              Close
            </button>
            <button
              type="submit"
              class="btn btn-info"
              data-bs-target="#viewModal"
              data-bs-toggle="modal"
              @click="viewImage"
            >
              View
            </button>
            <button type="submit" class="btn btn-info" @click="exportExcel">
              Export
            </button>
            <button
              type="submit"
              class="btn btn-warning"
              @click="submit"
              data-bs-dismiss="modal"
              v-if="isReviewer"
            >
              Edit
            </button>

            <button
              class="btn btn-danger"
              data-bs-target="#deniedunprocess"
              data-bs-toggle="modal"
              v-if="isReviewer"
            >
              Denied
            </button>
            <button
              class="btn btn-success"
              data-bs-target="#approvedunprocess"
              data-bs-toggle="modal"
              v-if="isReviewer"
            >
              Approved
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Vue from "vue";

export default {
  // mounted() {
  //   this.fetchData();
  // },
  components: {},

  mounted() {},
  watch: {},
  computed: {},

  data() {
    return {};
  },
  methods: {},
};
</script>
<style lang="scss">
.el-table .td-actions {
  button.btn {
    margin-right: 5px;
  }
}

.mrgn-footer {
  margin-right: 10px;
}

.popoverPanel {
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid rgba(0, 0, 0, 0.2);
  border-radius: 0.3rem;
  font-size: 0.875rem;
  display: none;
}

.popoverPanel[data-show] {
  display: block;
}

.popoverHeader {
  padding: 0.5rem 1rem;
  margin: 0;
  font-size: 1rem;
  background-color: #f0f0f0;
  border-bottom: 1px solid rgba(0, 0, 0, 0.2);
  border-top-left-radius: calc(0.3rem - 1px);
  border-top-right-radius: calc(0.3rem - 1px);
}

.popoverBody {
  padding: 1rem 1rem;
  color: #212529;
}

.popoverArrow,
.popoverArrow::before {
  position: absolute;
  width: 8px;
  height: 8px;
  background: inherit;
}

.popoverArrow {
  visibility: hidden;
}

.popoverArrow::before {
  visibility: visible;
  content: "";
  transform: rotate(45deg);
}

.popoverPanel[data-popper-placement^="left"] > .popoverArrow {
  right: -5px;
}

.popoverPanel[data-popper-placement^="left"] > .popoverArrow::before {
  border-top: 1px solid rgba(0, 0, 0, 0.2);
  border-right: 1px solid rgba(0, 0, 0, 0.2);
}
.cell-unprocess {
  display: table-cell !important;
  padding: 0px !important;
}
// td {
//   display: table-cell !important;
//   padding: 0px !important;
//   /* background-color: #111111 !important; */
// }
.table-input-box {
  width: 100%;
  height: 100%;
  box-sizing: border-box;
  padding: 14px;
  border: 1px solid transparent;
  transition: border-color 0.3s ease-in-out;
  background-color: transparent;
  font-size: 12px;
}

.table-select-box {
  /* border: 5px solid green !important; */
  transition: border-color 0.3s ease-in-out;
  /* background-color: yellow; */
  // border-radius: 0px !important;
  background-color: transparent !important;
  font-size: 12px;
}

.table-select-box.el-select .el-input input {
  border-radius: 0px !important;
  border: 5px solid transparent !important;
  background-color: transparent;
  font-size: 12px;
}
.delete-buttons {
  background-color: transparent;
  color: red;
  border: 2px solid red;
  border-radius: 0.25rem;
  cursor: pointer;
  padding-bottom: -2px;
}

.delete-buttons:hover {
  background-color: red;
  color: white;
}

.selector-label {
  font-size: 10px; /* Replace 16px with your desired font size */
  color: #000 !important;
}
</style>
