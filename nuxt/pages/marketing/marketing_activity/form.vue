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
              <div class="row">
                <div class="col">
                  <ValidationProvider
                    name="code"
                    rules="required">
                    <div class="form-group" slot-scope="{errors,valid}">
                      <label for="code">Kode</label>
                      <input id="code"
                        type="text"
                        class="form-control"
                        name="code"
                        v-model="parameters.form.code"
                        :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                        disabled
                      />

                      <div class="invalid-feedback" v-if="errors[0]">
                        {{ errors[0] }}
                      </div>      
                    </div>
                  </ValidationProvider>
                </div>

                <div class="col">
                  <ValidationProvider
                    name="date"
                    rules="required">
                    <div class="form-group" slot-scope="{errors,valid}">
                      <label for="date">Tgl</label>
                      <input id="data"
                        type="date"
                        class="form-control"
                        name="date"
                        v-model="parameters.form.date"
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
                <label for="customer_id">Pelanggan</label>
                  <v-select           
                    label="name"   
                    :loading="isLoadingGetCustomer"
                    :options="lookup_customers.data"
                    :filterable="false"
                    @search="onGetCustomer"
                    v-model="parameters.form.customer_id"
                    :required="true">                     
                    <template #search="{attributes, events}">
                      <input
                        class="vs__search"
                        :required="!parameters.form.customer_id"
                        v-bind="attributes"
                        v-on="events"
                      />
                    </template>        
                    <li slot-scope="{search}" slot="list-footer"
                      class="d-flex justify-content-between"
                      v-if="lookup_customers.data.length || search">
                      <a v-if="lookup_customers.current_page > 1" 
                        @click="onGetCustomer(search,false)"
                        class="flex-fill bg-primary text-white text-center"
                        href="#">Sebelumnya</a>
                      <a v-if="lookup_customers.last_page > lookup_customers.current_page" 
                        @click="onGetCustomer(search,true)"
                        class="flex-fill bg-primary text-white text-center"
                        href="#">Selanjutnya</a>
                    </li> 
                  </v-select>   
              </div>
              
              <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea class="form-control" v-model="parameters.form.description"></textarea>
              </div>

              <div class="form-group">
                <label for="progress">Progress</label>
                <select v-model="parameters.form.progress"
                  class="form-control">                            
                  <option value="Quotation">Quotation</option>
                  <option value="Order">Order</option>
                  <option value="Reject">Reject</option>
                  <option value="Follow Up">Follow Up</option>
                  <option value="Prospect">Prospect</option>
                </select>
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

  async mounted(){
    await this.lookUp({
      url : "marketing/marketing_activity/get-customer",
      lookup : "customers",
      query : ""
    });
  },

  data() {
    return {
      isStopSearchCustomer : false,
      isLoadingGetCustomer :  false,
      customer_search : '',

      isEditable  : false,
      isLoadingForm : false,
      title: 'Aktivitas Sales',      
      parameters : {
        url : 'marketing/marketing_activity',
        form : {
         code : '',
         date : moment().format("YYYY-MM-DD"),
         customer_id : '',
         description : '',
         progress : ''   
        }
      }
    };
  },

  computed :{
    ...mapState('moduleApi',[
      'error',
      'result',
      'code',
      'lookup_customers',  
    ]),  
  },

  methods: {    
    ...mapActions('moduleApi',[
       'addData',
       'updateData',
       'getCode',
       'lookUp'
    ]),

    async onSubmit(isInvalid){       
      if(isInvalid || this.isLoadingForm) return;            
      
      this.isLoadingForm = true;

      let parameters = {
        ...this.parameters,
        form : {
          ...this.parameters.form,
          customer_id : typeof this.parameters.form.customer_id == 'object'
            ? this.parameters.form.customer_id.id 
            : '',        
        }
      }

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

    async setCode(){
      await this.getCode({
        url : this.parameters.url
      });

      this.parameters.form.code = this.code;
    },

    onGetCustomer(search,isNext){      
      if(!search.length && typeof isNext === "function") return false;             

      clearTimeout(this.isStopSearchCustomer);
      
      this.isStopSearchCustomer = setTimeout(() => {
        this.customer_search = search;

        if(typeof isNext !== "function"){
          this.lookup_customers.current_page = isNext 
            ? this.lookup_customers.current_page + 1 
            : this.lookup_customers.current_page - 1;        
        }else{
          this.lookup_customers.current_page = 1;
        }

        this.onSearchCustomer();
      },600);        
    },

    async onSearchCustomer(){
      if(!this.isLoadingGetCustomer){
        this.isLoadingGetCustomer = true;
                
        await this.lookUp({    
        url : "marketing/marketing_activity/get-customer",
          lookup  : 'customers',
          query : "?search="+this.customer_search+"&page="+this.lookup_customers.current_page+"&per_page=10"
        })

        this.isLoadingGetCustomer = false;      
      }
    }
  },
};
</script>
