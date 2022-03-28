<template>
  <div class="float-right mt-2" style="width: 40%; margin-right: -75px">
    <div class="d-flex">
      <div class="mb-2" v-if="self.isShowSoftDeleted">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
          <label
            class="btn btn-success cursor-pointer"
            @click="
              self.search.soft_deleted = 'normal';
              self.onLoad();
            "
            :class="self.search.soft_deleted == 'normal' ? 'active' : ''"
          >
            <input
              type="radio"
              name="soft_deleted"
              id="soft_deleted_option1"
              autocomplete="off"
              checked
            />
            <span
              v-if="self.isLoadingPage && self.search.soft_deleted == 'normal'"
            >
              <i class="fas fa-circle-notch fa-spin fa-2x"></i>
            </span>
            <span v-else> Normal </span>
          </label>
          <label
            class="btn btn-success cursor-pointer"
            @click="
              self.search.soft_deleted = 'deleted';
              self.onLoad();
            "
            :class="self.search.soft_deleted == 'deleted' ? 'active' : ''"
          >
            <input
              type="radio"
              name="soft_deleted"
              id="soft_deleted_option2"
              autocomplete="off"
            />
            <span
              v-if="self.isLoadingPage && self.search.soft_deleted == 'deleted'"
            >
              <i class="fas fa-circle-notch fa-spin fa-2x"></i>
            </span>
            <span v-else> Trash </span>
          </label>
        </div>
      </div>


      <div class="mb-2" v-if="self.isShowImport">
        <nuxt-link :to="importTo" class="btn btn-primary"> Import </nuxt-link>
      </div>

      <div class="mb-2" v-if="self.isShowReport">
        <div class="dt-buttons btn-group">
          <button
            class="btn btn-sm btn-success"
            @click="self.onReport('excel')"
            :disabled="self.isLoadingReport && self.reportType == 'excel'"
          >
            <span v-if="self.isLoadingReport && self.reportType == 'excel'">
              <i class="fas fa-circle-notch fa-spin fa-2x"></i>
            </span>
            <span v-else> Excel </span>
          </button>
          <button
            class="btn btn-sm btn-success"
            @click="self.onReport('csv')"
            :disabled="self.isLoadingReport && self.reportType == 'csv'"
          >
            <span v-if="self.isLoadingReport && self.reportType == 'csv'">
              <i class="fas fa-circle-notch fa-spin fa-2x"></i>
            </span>
            <span v-else> Csv </span>
          </button>
          <button
            class="btn btn-sm btn-success"
            @click="self.onReport('pdf')"
            :disabled="self.isLoadingReport && self.reportType == 'pdf'"
          >
            <span v-if="self.isLoadingReport && self.reportType == 'pdf'">
              <i class="fas fa-circle-notch fa-spin fa-2x"></i>
            </span>
            <span v-else> Pdf </span>
          </button>

        </div>
      </div>

    </div>

    <div
      v-if="self.isShowCheckbox"
      class="text-right d-flex justify-content-around flex-row-reverse"
    >
      <button
        class="btn btn-danger"
        v-if="isShowDeletedAll"
        @click="self.onDeleteAll"
      >
        <i class="fas fa-trash"></i> Hapus Semua
      </button>

      <button
        class="btn btn-success"
        v-if="isShowRestoreAll"
        @click="self.onRestoreAll"
      >
        <i
          class="fas fa-circle-notch fa-spin fa-2x"
          v-if="self.isLoadingRestore && self.indexRestore == 'none'"
        >
        </i>
        <i class="fas fa-refresh" v-else></i>
        Restore Semua
      </button>
    </div>
    <div class="mb-2">
        <slot></slot>
      </div>
  </div>
</template>

<script>
export default {
  props: {
    self: Object,
  },

  computed: {



  },
};
</script>
