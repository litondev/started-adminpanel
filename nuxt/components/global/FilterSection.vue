<template>
  <portal to="modal-filter">
    <div class="container-modal-filter"
      id="modal-filter">
      <div class="row">
        <div class="col-md-8 left-modal-filter" 
          @click="onHideFilter"></div>
        <div class="col-md-4 right-modal-filter">         
          <div class="d-flex flex-row justify-content-between p-3">
            <h3>Filter : </h3>
            <i class="fa fa-times fa-2x cursor-pointer"
              @click="onHideFilter"></i>
          </div>

          <div class="p-3">
            <slot></slot>
          </div>

          <div class="mt-3">
            <button class="btn btn-primary btn-sm"
              @click="onSubmitFilter">
              <i class="fa fa-search"
                style="margin-right: 5px"></i> Filter
            </button>
            <button class="btn btn-warning btn-sm"
              @click="onResetFilter">
              <i class="fa fa-redo"
                style="margin-right: 5px"></i> Reset Filter
            </button>
          </div>
        </div>
      </div>
    </div>
  </portal>
</template>

<script>
export default {
  methods :{
    onHideFilter(){
      document.getElementById("modal-filter").style.display = "none";
    },

    onResetFilter(){
      this.$parent.parameters.params = {
        ...this.$parent.parameters.default_params
      }

      this.onHideFilter();
      this.$parent.onLoad();
    },  
  

    onSubmitFilter(){
      this.onHideFilter();
      this.$parent.onLoad();
    }
  }
}
</script>

<style scoped>
.container-modal-filter{
   position:fixed;
   top:0px;
   bottom:0px;
   left:0px;
   right:0px;
   display: none;
   z-index: 9999999
}

.left-modal-filter{
  opacity:0.3;
  background:black;
  min-height:100vh;
}

.right-modal-filter{
  background: white;
  min-height: 100vh;
}
</style>