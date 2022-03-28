<template>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">
      <i class="fas fa-ban"></i> Close
    </button>
    <button type="submit" class="btn btn-primary" :disabled="isLoadingForm">
      <span v-if="isLoadingForm">
        <i class="fas fa-circle-notch fa-spin"></i>
      </span>
      <span v-else><i class="fas fa-save" ></i> Save </span>
    </button>
  </div>
</template>
<script>
export default {
  props: {
    isLoadingForm : Boolean
  },
};
</script>
