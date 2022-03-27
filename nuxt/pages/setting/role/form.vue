<template>
  <portal to="modal">
    <div class="modal fade" 
      aria-hidden="true" 
      id="modal-form">
      <div class="modal-dialog">
        <div class="modal-content">

        <modal-header-section
          :self="this"
        />

        <ValidationObserver
          v-slot="{invalid,validate}"
          ref="form-validate">
            <form @submit.prevent="validate().then(onSubmit(invalid))"
              autocomplete="off">

            <div class="modal-body">
              <div class="row">
                <div class="col">
                  <ValidationProvider
                    name="name"
                    rules="required">
                    <div class="form-group" slot-scope="{errors,valid}">
                      <label for="name">Nama</label>
                      <input id="name"
                        type="text"
                        class="form-control"
                        name="name"
                        v-model="parameters.form.name"
                        :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                      />               
                      <div class="invalid-feedback" v-if="errors[0]">
                        {{ errors[0] }}
                      </div>
                      <div class="text-danger text-small"
                        v-if="isEditable">
                        * Hati-hati dalam menganti nama menu 
                      </div>
                    </div>
                  </ValidationProvider>
                </div>

                <div class="col">
                  <ValidationProvider
                    name="title"
                    rules="required">
                    <div class="form-group" slot-scope="{errors,valid}">
                      <label for="title">Judul</label>
                      <input id="title"
                        type="text"
                        class="form-control"
                        name="title"
                        v-model="parameters.form.title"
                        :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                      />

                      <div class="invalid-feedback" v-if="errors[0]">
                        {{ errors[0] }}
                      </div>      
                    </div>
                  </ValidationProvider>
                </div>
              </div>

              <div class="form-group">
                <label for="icon">Icon</label>
                <input id="icon"
                  type="text"
                  class="form-control"
                  name="icon"            
                  v-model="parameters.form.icon"/>
              </div>
                    
              <div class="form-group">            
                <label for="parent_id">Module</label>                                                          
                  <v-select                           
                    label="title"   
                    :loading="isLoadingGetRole"
                    :options="lookup_roles.data"
                    :filterable="false"
                    @search="onGetRole"
                    v-model="parameters.form.parent_id">              
                    <li slot-scope="{search}" slot="list-footer"
                      class="d-flex justify-content-between"
                      v-if="lookup_roles.data.length || search">
                      <a v-if="lookup_roles.current_page > 1" 
                        @click="onGetRole(search,false)"
                        class="flex-fill bg-primary text-white text-center"
                        href="#">Sebelumnya</a>
                      <a v-if="lookup_roles.last_page > lookup_roles.current_page" 
                        @click="onGetRole(search,true)"
                        class="flex-fill bg-primary text-white text-center"
                        href="#">Selanjutnya</a>
                    </li> 
                  </v-select>              
                  <span class="text-muted">
                    *Kosongi jika ingin membuat menu menjadi module 
                  </span>
              </div>                                                                   
            </div>
        
            <modal-footer-section     
              :isLoadingForm="isLoadingForm"
            />
          </form>
        </ValidationObserver>

        </div>
      </div>
    </div>
  </portal>
</template>

<script>
import { mapActions,mapState } from 'vuex'

export default {  
  middleware : ["isNotAccessable"],

  props: ["self"],

  mounted(){
    this.lookUp({
      url : "setting/role/get-role",
      lookup : "roles",
      query : ""
    });
  },

  data() {
    return {
      isStopSearchRole : false,
      isLoadingGetRole :  false,
      role_search : '',

      isEditable  : false,
      isLoadingForm : false,
      title: 'Role',      
      parameters : {
        url : 'setting/role',
        form : {
         name : '',
         title : '',
         icon : '',
         parent_id : ''
        }
      }
    };
  },

  computed :{
    ...mapState('moduleApi',[
      'error',
      'result',
      'lookup_roles'
    ]),  
  },

  methods: {    
    ...mapActions('moduleApi',[
       'addData',
       'updateData',
       'lookUp'
    ]),

    async onSubmit(isInvalid){       
      if(isInvalid || this.isLoadingForm) return;            
      
      this.isLoadingForm = true;

      let parameters = {
        ...this.parameters,
        form : {
          ...this.parameters.form,
          parent_id : this.parameters.form.parent_id 
            ? this.parameters.form.parent_id.id 
            : ''
        }
      };

      if(this.isEditable){
        await this.updateData(parameters)
      }else{ 
        await this.addData(parameters)
      }

      if (this.result == true) {      
        this.self.onLoad(this.self.parameters.params.page);  
        this.$toaster.success('Data berhasil di '+ (this.isEditable == true ? 'Diedit': 'Tambah'));
        window.$("#modal-form").modal("hide");
      }else {
        this.$globalErrorToaster(this.$toaster,this.error);      
      }

      this.isLoadingForm = false;
    },

    onGetRole(search,isNext){      
      if(!search.length && typeof isNext === "function") return false;             

      clearTimeout(this.isStopSearchRole);
      
      this.isStopSearchRole = setTimeout(() => {
        this.role_search = search;

        if(typeof isNext !== "function"){
          this.lookup_roles.current_page = isNext 
            ? this.lookup_roles.current_page + 1 
            : this.lookup_roles.current_page - 1;        
        }else{
          this.lookup_roles.current_page = 1;
        }

        this.onSearchRole();
      },600);        
    },

    async onSearchRole(){
      if(!this.isLoadingGetRole){
        this.isLoadingGetRole = true;
                
        await this.lookUp({    
          url : "setting/role/get-role",      
          lookup  : 'roles',
          query : "?search="+this.role_search+"&page="+this.lookup_roles.current_page+"&per_page=10"
        })

        this.isLoadingGetRole = false;      
      }
    }
  },
};
</script>
