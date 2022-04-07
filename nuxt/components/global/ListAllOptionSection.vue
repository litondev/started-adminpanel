<template>
  <div class="border-bottom py-3 px-2"
    v-if="$parent.headers.showCheckboxs 
      && ($parent.headers.showDestroyAll || $parent.headers.showRestoreAll) 
      && $parent.checkboxs.length">
    <div class="btn-group">
      <button class="btn btn-danger btn-sm"
        v-if="$parent.headers.showDestroyAll && $parent.checkboxs.length"
        @click="$parent.onDestroyAll">
        <i class="fa fa-trash" 
          style="margin-right: 5px"></i> Hapus Semua
      </button>

      <button class="btn btn-success btn-sm"
        v-if="$parent.headers.showRestoreAll && $parent.checkboxs.length"
        @click="$parent.onRestoreAll">
        <span v-if="$parent.isLoadingRestoreAll">
          <i class="fa fa-circle-notch fa-spin fa-1x"></i>
        </span>
        <span v-else>
          <i class="fa fa-redo"
            style="margin-right: 5px"></i> Pulihkan Semua
        </span>
      </button>
    </div>
    
    <slot></slot>
  </div>
</template>
