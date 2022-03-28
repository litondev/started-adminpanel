<template>
  <!-- HEADER SECTION -->
  <div class="section-header">
    <div class="clearfix col-12">
      <div class="float-left">
        <div id="title">
          <h6 id="title">Data {{self.title}}</h6>
        </div>
        <p>
          Berikut adalah data {{self.title}} yang tersimpan di database.
        </p>
      </div>
      <div class="float-right" v-if="self.onAdd">
        <button class="btn btn-primary"
          @click="self.onAdd">
          <i class="fas fa-plus"></i>
          Tambah Data
        </button>
      </div>
    </div>
  </div>
  <!-- HEADER SECTION -->
</template>
<script>

import { mapState } from "vuex";
export default {
  props : {
    self : Object,
    // title : String,
    // onadd : Function
  },
  computed: {

  },
}
</script>
