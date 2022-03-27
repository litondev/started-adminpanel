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
                </div>      
              </div>            

                <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="seller_id">Sales</label>
                      <v-select           
                        label="name"   
                        :loading="isLoadingGetSeller"
                        :options="lookup_sellers.data"
                        :filterable="false"
                        @search="onGetSeller"
                        v-model="parameters.form.seller_id"
                        :required="true">                     
                        <template #search="{attributes, events}">
                          <input
                            class="vs__search"
                            :required="!parameters.form.seller_id"
                            v-bind="attributes"
                            v-on="events"
                          />
                        </template>        
                        <li slot-scope="{search}" slot="list-footer"
                          class="d-flex justify-content-between"
                          v-if="lookup_sellers.data.length || search">
                          <a v-if="lookup_sellers.current_page > 1" 
                            @click="onGetSeller(search,false)"
                            class="flex-fill bg-primary text-white text-center"
                            href="#">Sebelumnya</a>
                          <a v-if="lookup_sellers.last_page > lookup_sellers.current_page" 
                            @click="onGetSeller(search,true)"
                            class="flex-fill bg-primary text-white text-center"
                            href="#">Selanjutnya</a>
                        </li> 
                      </v-select>   
                  </div>
                </div>
                 <div class="col">
                  <div class="form-group">
                    <label for="customer_group">Pelanggan Group</label>
                      <v-select           
                        label="name"   
                        :loading="isLoadingGetCustomerGroup"
                        :options="lookup_customer_groups.data"
                        :filterable="false"
                        @search="onGetCustomerGroup"
                        v-model="parameters.form.customer_group_id"
                        :required="true">                     
                        <template #search="{attributes, events}">
                          <input
                            class="vs__search"
                            :required="!parameters.form.customer_group_id"
                            v-bind="attributes"
                            v-on="events"
                          />
                        </template>        
                        <li slot-scope="{search}" slot="list-footer"
                          class="d-flex justify-content-between"
                          v-if="lookup_customer_groups.data.length || search">
                          <a v-if="lookup_customer_groups.current_page > 1" 
                            @click="onGetCustomerGroup(search,false)"
                            class="flex-fill bg-primary text-white text-center"
                            href="#">Sebelumnya</a>
                          <a v-if="lookup_customer_groups.last_page > lookup_customer_groups.current_page" 
                            @click="onGetCustomerGroup(search,true)"
                            class="flex-fill bg-primary text-white text-center"
                            href="#">Selanjutnya</a>
                        </li> 
                      </v-select>   
                  </div>
                </div>
              </div>                                                                  

              <div class="form-group">
                <label for="address">Alamat</label>
                <textarea class="form-control" v-model="parameters.form.address"></textarea>
              </div>

              <ValidationProvider
                  name="city"
                  rules="required">
                  <div class="form-group" slot-scope="{errors,valid}">
                    <label for="city">Kota</label>
                    <input id="city"
                      type="text"
                      class="form-control"
                      name="city"
                      v-model="parameters.form.city"
                      :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                    />               
                    <div class="invalid-feedback" v-if="errors[0]">
                      {{ errors[0] }}
                    </div>
                  </div>
                </ValidationProvider>

                <div class="row">
                  <div class="col">
                    <ValidationProvider
                      name="email"
                      rules="required|email">
                      <div class="form-group" slot-scope="{errors,valid}">
                        <label for="email">Email</label>
                        <input id="email"
                          type="text"
                          class="form-control"
                          name="email"
                          v-model="parameters.form.email"
                          :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                        />               
                        <div class="invalid-feedback" v-if="errors[0]">
                          {{ errors[0] }}
                        </div>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col">
                     <ValidationProvider
                      name="phone"
                      rules="required">
                      <div class="form-group" slot-scope="{errors,valid}">
                        <label for="phone">phone</label>
                        <input id="phone"
                          type="text"
                          class="form-control"
                          name="phone"
                          v-model="parameters.form.phone"
                          :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                        />               
                        <div class="invalid-feedback" v-if="errors[0]">
                          {{ errors[0] }}
                        </div>
                      </div>
                    </ValidationProvider>
                  </div>
                </div>

                <ValidationProvider
                  name="term_payment"
                  rules="required">
                  <div class="form-group" slot-scope="{errors,valid}">
                    <label for="term_payment">Tempo Pembayaran</label>
                    <input id="term_payment"
                      type="number"
                      class="form-control"
                      name="term_payment"
                      v-model="parameters.form.term_payment"
                      :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                    />               
                    <div class="invalid-feedback" v-if="errors[0]">
                      {{ errors[0] }}
                    </div>
                  </div>
                </ValidationProvider>
            


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
      url : "master/customer/get-seller",
      lookup : "sellers",
      query : ""
    });

    await this.lookUp({
      url : "master/customer/get-customer-group",
      lookup : "customer_groups",
      query : ""
    })
  },

  data() {
    return {
      isStopSearchSeller : false,
      isLoadingGetSeller :  false,
      seller_search : '',

      isStopSearchCustomerGroup : false,
      isLoadingGetCustomerGroup :  false,
      customer_group_search : '',

      isEditable  : false,
      isLoadingForm : false,
      title: 'Pelanggan',      
      parameters : {
        url : 'master/customer',
        form : {
         code : '',
         name : '',
         address : "",
         city : "",
         email : "",
         phone : "",
         term_payment : 0,
         seller_id : 0,
         customer_group_id : 0               
        }
      }
    };
  },

  computed :{
    ...mapState('moduleApi',[
      'error',
      'result',
      'code',
      'lookup_sellers',
      'lookup_customer_groups'
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
          seller_id : typeof this.parameters.form.seller_id == 'object'
            ? this.parameters.form.seller_id.id 
            : '',
          customer_group_id : typeof this.parameters.form.customer_group_id == 'object'
            ? this.parameters.form.customer_group_id.id 
            : ''
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

    onGetSeller(search,isNext){      
      if(!search.length && typeof isNext === "function") return false;             

      clearTimeout(this.isStopSearchSeller);
      
      this.isStopSearchSeller= setTimeout(() => {
        this.seller_search = search;

        if(typeof isNext !== "function"){
          this.lookup_sellers.current_page = isNext 
            ? this.lookup_sellers.current_page + 1 
            : this.lookup_sellers.current_page - 1;        
        }else{
          this.lookup_sellers.current_page = 1;
        }

        this.onSearchSeller();
      },600);        
    },

    async onSearchSeller(){
      if(!this.isLoadingGetSeller){
        this.isLoadingGetSeller = true;
                
        await this.lookUp({    
        url : "master/customer/get-seller",
          lookup  : 'sellers',
          query : "?search="+this.seller_search+"&page="+this.lookup_sellers.current_page+"&per_page=10"
        })

        this.isLoadingGetSeller = false;      
      }
    },

    onGetCustomerGroup(search,isNext){      
      if(!search.length && typeof isNext === "function") return false;             

      clearTimeout(this.isStopSearchCustomerGroup);
      
      this.isStopSearchCustomerGroup = setTimeout(() => {
        this.customer_group_search = search;

        if(typeof isNext !== "function"){
          this.lookup_customer_groups.current_page = isNext 
            ? this.lookup_customer_groups.current_page + 1 
            : this.lookup_customer_groups.current_page - 1;        
        }else{
          this.lookup_customer_groups.current_page = 1;
        }

        this.onSearchCustomerGroup();
      },600);        
    },

    async onSearchCustomerGroup(){
      if(!this.isLoadingGetCustomerGroup){
        this.isLoadingGetCustomerGroup = true;
                
        await this.lookUp({    
        url : "master/customer/get-customer-group",
          lookup  : 'customer_groups',
          query : "?search="+this.customer_group_search+"&page="+this.lookup_customer_groups.current_page+"&per_page=10"
        })

        this.isLoadingGetCustomerGroup = false;      
      }
    }
  },
};
</script>
