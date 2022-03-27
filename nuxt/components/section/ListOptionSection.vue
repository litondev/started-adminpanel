<template>
  <div class="row">
    <div class="col-md-12 mb-2">
      <h6>Data {{self.title}}</h6>
    </div>
    <div class="col-md-12"
      style="margin-top:-10px;">
      <div class="row">
        <!-- Pages -->
        <div class="col-md-1 mt-2"
          v-if="isShowingPage">
            <select class="form-control form-control-sm"
              style="height:calc(1.5em + .5rem + 2px);padding:0px;padding-left:2px;padding-right:0px;"
              v-model="self.parameters.params.per_page"
              @change="self.parameters.params.page = 1;self.onLoad()">
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="30">30</option>
              <option value="40">40</option>
              <option value="50">50</option>
            </select>
        </div>

        <!-- Searching -->
        <div class="col-md-4 mt-2"
          v-if="isSearching">
          <div class="row">
            <div class="col-md-12">
              <input type="text"
                class="form-control form-control-sm"
                placeholder="search..."
                v-model="self.parameters.params.search"
                @keyup.enter="self.onLoad()">
            </div>
          </div>
        </div>

        <div class="col-md-7 mt-2">
          <!-- Maintenance Page (All, Active, Trashed) -->
          <div class="btn-group" v-if="isMaintenancePage">
            <button class="btn btn-sm btn-primary"
              :class="self.parameters.params.soft_deleted == 'all' ? 'active' : ''"
              @click="self.parameters.params.soft_deleted = 'all';self.onLoad()"
              data-toggle="tooltip"
              data-placement="top"
              data-original-title="Semua Data">
              <i class="fas fa-font"></i>
              <!-- <span class="badge badge-danger">{{total}}</span> -->
            </button>
            <button class="btn btn-sm btn-success"
              :class="self.parameters.params.soft_deleted == '' ? 'active' : ''"
              @click="self.parameters.params.soft_deleted = '';self.onLoad()"
              data-toggle="tooltip"
              data-placement="top"
              data-original-title="Data Active">
              <i class="fas fa-list"></i>
             <!-- <span class="badge badge-danger">999</span> -->
            </button>
            <button class="btn btn-sm btn-danger"
              :class="self.parameters.params.soft_deleted == 'deleted' ? 'active' : ''"
              @click="self.parameters.params.soft_deleted = 'deleted';self.onLoad()"
              data-toggle="tooltip"
              data-placement="top"
              data-original-title="Data Terhapus">
              <i class="fas fa-trash"></i>
              <!-- <span class="badge badge-primary">999</span> -->
            </button>
          </div>

          <!-- Filter -->
          <div class="btn-group" v-if="isFilter">
            <button class="btn btn-sm btn-info"
              data-toggle="modal"
              data-target="#modal-filter">
              <i class="fas fa-filter"></i> Filter
            </button>
          </div>

          <!-- Add data -->
          <div class="btn-group" v-if="isAddData">
            <button class="btn btn-sm btn-primary"
              data-toggle="tooltip"
              data-placement="top"
              data-original-title="Tambah Data"
              @click="self.onFormShow()">
              <i class="fas fa-plus fa-spin"></i> Add
            </button>
          </div>

          <!-- SLOT FOR DOWNLOD DATA SCHOOL DAPODIK -->

          <!-- Export (Excel, Pdf, Print) -->
          <div class="btn-group float-right" v-if="isExport">
            <button class="btn btn-sm btn-info"
              data-toggle="tooltip"
              data-placement="top"
              data-original-title="Download Excel"
              @click="exportFile({type : 'execl',parameters : self.parameters})"
              v-if="isExportFile">
              <i class="fas fa-file-excel"></i> Excel
            </button>
            <button class="btn btn-sm btn-info"
              data-toggle="tooltip"
              data-placement="top"
              data-original-title="Download Pdf"
              @click="exportFile({type : 'pdf',parameters : self.parameters})"
              v-if="isExportFile">
              <i class="fas fa-file-pdf"></i> PDF
            </button>
            <button
              class="btn btn-sm btn-info"
              data-toggle="tooltip"
              data-placement="top"
              data-original-title="Print Semua Data"
              @click="printFile({parameters : self.parameters})"
              v-if="isExportPrint">
              <i class="fas fa-print"></i> Print
            </button>

          </div>
          <div class="btn-group float-right">
          <slot></slot>
          </div>
          <div class="btn-group float-right">
          <slot name="paging"></slot>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  props: {
    self: Object,
  },

  data() {
    return {
      isMaintenancePage   : true,
      isSearching         : true,
      isFilter            : true,
      isExport            : true,
      isExportFile        : true,
      isExportPrint       : true,
      isAddData           : true,
      isShowingPage       : true,
    }
  },

  methods: {
    ...mapActions('print',['printFile','exportFile']),
  },
};
</script>
