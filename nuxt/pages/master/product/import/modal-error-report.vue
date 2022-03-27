<template>
   <portal to="modal-childs">
      <div class="modal fade" 
        aria-hidden="true"  
        id="modal-2"
        v-if="Object.values(self.errorSelected).length">
        <div class="modal-dialog">    
          <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Baris 
                    <b class="text-danger">
                        {{self.errorSelected.row}}
                    </b>
                </h5>

                <button type="button"
                    class="close"
                    @click="self.onBackOpenReport">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">                              
                <div v-if="self.errorSelected.type == 'validation'"
                    class="mt-2 mb-2 d-flex flex-row flex-wrap justify-content-around">
                    <div 
                        v-for="itemError,indexItemError in Object.values(self.errorSelected.errors)"
                        :key="indexItemError"
                        class="d-flex flex-row flex-wrap justify-content-around">
                        <div v-for="itemChildError,indexItemChildError in itemError" 
                            class="badge badge-danger mb-2 flex-fill p-2"
                            :key="indexItemChildError">
                            {{itemChildError}}
                        </div>
                    </div>
                </div>

                <div v-if="self.errorSelected.type == 'query'"
                    class="text-center text-danger mt-2 mb-2 d-flex justify-content-around">
                    <div class="badge badge-danger">
                        {{self.errorSelected.errors}}
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
   </portal>
</template>

<script>
export default{
    middleware : ["isNotAccessable"],
    props : ["self"]
}
</script>