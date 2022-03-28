<template>
  <portal to="modal">
    <div class="modal fade" 
      aria-hidden="true" 
      id="modal-form">
      <div class="modal-dialog modal-lg">
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
                </div>
              </ValidationProvider>
              
              <div>
                <a href="#" class="btn btn-primary"
                  @click="onAddGrant">
                  <i class="fas fa-plus"></i>
                </a>

                <div class="table table-respoonsive"
                  style="max-height:500px;overflow:auto"
                  :style="parameters.form.grants.length 
                    ? 'min-height:500px'
                    : ''">
                  <table class="table">
                    <tr>
                      <td style="width:300px">Hak Akses</td>
                      <td
                        style="width:400px">
                        Operator
                        <i class="fas fa-info-circle"
                         data-toggle="tooltip" 
                         :data-html="true"
                         style="cursor:pointer"
                         data-template='<div class="tooltip" role="tooltip"><div class="tooltip-inner text-left"></div></div>'                         
                         title="
                          <div>
                          <p>*all : Semua Operator</p>
                          <p>*index : Mengambil Banyak Data</p>
                          <p>*show : Mengambil Detail Data</p>                        
                          <p>*update : Update Data</p>                           
                          <p>*destroy : Menghapus Data</p>                          
                          <p>*delete-all : Menghapus Banyak Data</p>                        
                          <p>*restore : Mengembailkan Data Yang Telah Dihapus</p>                          
                          <p>*restore-all : Mengembailkan Banyak Data Yang Telah Dihapus</p>
                          <p>*print : Print Pdf</p>
                          <p>*export : Export Pdf Dan Excel</p>
                          </div>
                          ">
                        </i>
                      </td>
                      <td>Opsi</td>
                    </tr>
                    <tr v-for="item,index in parameters.form.grants"
                      :key="index">
                      <td>                        
                       <v-select           
                        label="title"   
                        :loading="isLoadingGetRole"
                        :options="lookup_roles.data"
                        :filterable="false"
                        @search="onGetRole"
                        v-model="item.role_id"
                        :required="true">     
                        <template v-slot:option="option">                    
                          {{ option.title }} | {{option.parent ? option.parent.title : '-'}}
                        </template> 
                        <template #search="{attributes, events}">
                          <input
                            class="vs__search"
                            :required="!item.role_id"
                            v-bind="attributes"
                            v-on="events"
                          />
                        </template>        
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
                      </td>
                      <td>
                        <multiselect 
                          :options="options"
                          v-model="item.operators" 
                          :multiple="true" 
                          :taggable="true"
                          @tag="addTag">
                          <template slot="option" slot-scope="props">                        
                            <div class="option__desc">
                              <span class="option__title">
                                {{ props.option }}            
                                <span class="text-small"
                                  v-if="props.option == 'all'">
                                  (Semua Operator)
                                </span>            
                                <span class="text-small"
                                  v-else-if="props.option == 'index'">
                                  (Lihat Data)
                                </span>
                                <span class="text-small"
                                  v-else-if="props.option == 'store'">
                                  (Tambah Data)
                                </span>
                                <span class="text-small"
                                  v-else-if="props.option == 'update'">
                                  (Update Data)
                                </span>
                                <span class="text-small"
                                  v-else-if="props.option == 'destroy'">
                                  (Hapus Data)
                                </span>
                                <span class="text-small"
                                  v-else-if="props.option == 'restore'">
                                  (Pulihkan Data)
                                </span>
                                <span class='text-small'
                                  v-else-if="props.option == 'print'">
                                  (Print Data Pdf)
                                </span>
                                <span class="text-small"
                                  v-else-if="props.option == 'export'">
                                  (Export Data Pdf/Excel)                                  
                                </span>
                                <span class="text-small"
                                  v-else-if="props.option == 'show'">
                                  (Lihat Detail Data)
                                </span>
                                <span class="text-small"
                                  v-else-if="props.option == 'destroy-all'">
                                  (Hapus Banyak Data)
                                </span>
                                <span class="text-small"
                                  v-else-if="props.option == 'restore-all'">
                                  (Pulihkan Banyak Data)
                                </span>
                                <span class="text-small"
                                  v-else-if="props.option == 'get-code'">
                                  (Ambil Code Otomatis)
                                </span>
                                <span class="text-small"
                                  v-else-if="props.option == 'import'">
                                  (Import file ke database)
                                </span>
                                <span class="text-small"
                                  v-else-if="props.option == 'import-template'">
                                  (Download template import)
                                </span>
                              </span>
                            </div>
                          </template>
                        </multiselect>
                      </td>
                      <td>
                        <i class="fas fa-trash"
                          style="cursor:pointer"
                          @click="onDeleteGrant(index)"></i>
                      </td>
                    </tr>
                    <tr v-if="!parameters.form.grants.length">
                      <td colspan="3"
                        class="text-center">
                        Data tidak ditemukan
                      </td>
                    </tr>
                  </table>
                </div>
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
      url : "setting/group_role/get-role",
      lookup : "roles",
      query : ""
    });
  },

  data() {
    return {
      isStopSearchRole : false,
      isLoadingGetRole :  false,
      role_search : '',

      options : [
        "all",
        "index",
        "store",
        "update",
        "show",
        "destroy",
        "destroy-all",
        "restore",
        "restore-all",
        "print",
        "export",
        "import",
        "import-template"
      ],

      isEditable  : false,
      isLoadingForm : false,
      title: 'User',      
      parameters : {
        url : 'setting/group_role',
        form : {
          name : '',
          grants   : []
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
          grants : this.parameters.form.grants.map(item => {
            return {
              operators : item.operators,
              role_id : item.role_id.id
            }
          })
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

    addTag(newTag){
      this.options.push(newTag)
    },

    onAddGrant(){
      this.parameters.form.grants.push({
        role_id : null,
        operators : [],
      })
    },

    onDeleteGrant(index){
      this.parameters.form.grants = this.parameters.form.grants.filter((_,itemIndex) => index != itemIndex);
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
          url : "setting/group_role/get-role",
          lookup  : 'roles',
          query : "?search="+this.role_search+"&page="+this.lookup_roles.current_page+"&per_page=10"
        })

        this.isLoadingGetRole = false;      
      }
    }
  },
};
</script>
