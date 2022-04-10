<template>
  <portal to="modal-other" :order="2"> 
  <div class="modal fade" 
    aria-hidden="true"  
    id="modal-error-report">
    <div class="modal-dialog" >
      <div class="modal-content" v-if="Object.values($parent.errorSelected).length">
        <div class="modal-header">
            <h5 class="modal-title">
                Baris 
                <b class="text-danger">
                    {{$parent.errorSelected.row}}
                </b>
            </h5>

            <button type="button" class="btn-close" @click="onBackOpenReport" aria-label="Close"></button>
        </div>

        <div class="modal-body">                              
            <div v-if="$parent.errorSelected.type == 'validation'"
                class="mt-2 mb-2 d-flex flex-row flex-wrap justify-content-around">
                <div 
                    v-for="itemError,indexItemError in Object.values($parent.errorSelected.errors)"
                    :key="indexItemError"
                    class="d-flex flex-row flex-wrap justify-content-around">
                    <div v-for="itemChildError,indexItemChildError in itemError" 
                        class="bg-danger mb-2 flex-fill p-2 ml-1 mr-1"
                        :key="indexItemChildError">
                        {{itemChildError}}
                    </div>
                </div>
            </div>

            <div v-if="$parent.errorSelected.type == 'query'"
                class="text-center text-danger mt-2 mb-2 d-flex justify-content-around">
                <div class="badge badge-danger">
                    {{$parent.errorSelected.errors}}
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
    methods : {
      onBackOpenReport(){
          this.$parent.modal_error_report.hide();
      
          setTimeout(() => {
            this.$parent.modal_report.show();
          },500);
      },
  }
}
</script>