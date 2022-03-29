<template>
  <div class="card-body py-3">
      <div class="d-flex flex-row">
        <div class="mx-2 mt-1 mb-1">
          <h3 class="card-title"
            v-if="$parent.parameters.params.soft_deleted == 'all'">  {{ $parent.title }} Semua</h3>                              
          <h3 class="card-title"
            v-else-if="$parent.parameters.params.soft_deleted == 'deleted'">{{ $parent.title }} Terhapus</h3>                
          <h3 v-else>
            {{$parent.title}}
          </h3>
        </div>
                      
        <div class="mx-2 mt-1 mb-1"
          v-if="$parent.headers.showSearch">
          Search :
          <div class="ms-2 d-inline-block">
            <input type="text" 
              class="form-control form-control-sm" 
              aria-label="Search invoice"
              v-model="$parent.parameters.params.search"
              @keyup.enter="$parent.onLoad">
          </div>
        </div>

        <div class="mx-2 d-inline-block mb-1 mt-1"
          v-if="$parent.headers.showPerPage">
          <select class="form-control form-control-sm"
            v-model="$parent.parameters.params.per_page"
            @change="$parent.onLoad">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="40">40</option>
          </select>
        </div>            

        <div class="mx-2 mt-1 mb-1"
          v-if="$parent.headers.showAdd">
          <button class="btn btn-primary btn-sm">
            <i class="fa fa-plus"
              style="margin-right: 5px"></i> Tambah
          </button>              
        </div>

        <div class="mx-2 mt-1 mb-1"
          v-if="$parent.headers.showMaintenance">
          <div class="btn-group">
            <button class="btn btn-success btn-sm"
              @click="$parent.parameters.params.soft_deleted = 'all';$parent.onLoad()">
              <i class="fa fa-list"
              style="margin-right: 5px"></i> All
            </button>
            <button class="btn btn-primary btn-sm"
            @click="$parent.parameters.params.soft_deleted = '';$parent.onLoad()">
              <i class="fa fa-circle"
              style="margin-right: 5px"></i> Active
            </button>
            <button class="btn btn-danger btn-sm"
              @click="$parent.parameters.params.soft_deleted = 'deleted';$parent.onLoad()">
              <i class="fa fa-trash"
              style="margin-right: 5px"></i> Deleted
            </button>
          </div>
        </div>

        <div class="ms-auto text-muted mt-3">
          <div class="btn-group">
            <button class="btn btn-outline-primary btn-sm"
              v-if="$parent.headers.showFilter">
              <i class="fa fa-align-left"
              style="margin-right: 5px"></i> Filter
            </button>
            <button class="btn btn-outline-primary btn-sm"
              v-if="$parent.headers.showFilter">
              <i class="fa fa-redo"
              style="margin-right: 5px"></i> Reset Filter
            </button>
          </div>
          <button class="btn btn-outline-primary btn-sm"
            v-if="$parent.headers.showReport">
             <i class="fa fa-file"
             style="margin-right: 5px"></i> Laporan
          </button>
        </div>
      </div>
  </div>
</template>