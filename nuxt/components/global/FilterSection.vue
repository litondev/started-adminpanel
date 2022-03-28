<template>
  <portal to="modal-filter">
    <div
      class="modal modal-outer right-modal fade"
      id="modal-filter"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myModalFilter"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 style="margin-left:-10px">Filter</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
            <span aria-hidden="true">&times;</span>
            </button>
          </div>

            <div class="modal-body">
              <slot></slot>            
              <hr>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <button class="btn btn-warning" type="button"  @click="onReset()"><i class="fas fa-redo-alt"></i> Reset</button>
                    <button class="btn btn-primary float-right"  @click="onFilter()"><i class="fas fa-check-circle"></i> Apply</button>
                  </div>
                </div>
              </div> 
            </div>
        </div>
      </div>
    </div>
  </portal>
</template>
<script>
export default {
  props: {
    self: Object,
    title: String,
  },

  methods: {    
     async onFilter(){  
      this.self.onLoad();
      window.$("#modal-filter").modal("hide")
    },

    onReset(){
      this.self.parameters.params = {      
        ...this.self.parameters.default_params,      
      }
      this.self.onLoad();
      window.$("#modal-filter").modal("hide")
    },
  },
};
</script>
<style scoped>
/*
select.form-control:not([size]):not([multiple]) {
  height: calc(2.25rem + -5px);
}
*/
</style>
