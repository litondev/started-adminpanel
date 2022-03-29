<template>
  <div class="border-bottom py-3 px-2"
    v-if="$parent.headers.showCheckboxs && ($parent.headers.showDestroyAll || $parent.headers.showRestoreAll)">
    <div class="btn-group">
      <button class="btn btn-danger btn-sm"
        v-if="$parent.headers.showDestroyAll">
        <i class="fa fa-trash" 
          style="margin-right: 5px"></i> Hapus Semua
      </button>

      <button class="btn btn-success btn-sm"
        v-if="$parent.headers.showRestoreAll">
        <i class="fa fa-redo"
          style="margin-right: 5px"></i> Pulihkan Semua
      </button>
    </div>
    
    <slot></slot>
  </div>
</template>
