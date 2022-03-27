<template>
  <div class="modal-header">
    <h5 class="modal-title">{{ self.isEditable ? "Edit" : "Add" }} Data</h5>

    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
</template>

<script>
export default {
  props: {
    self : Object,
  },
};
</script>
