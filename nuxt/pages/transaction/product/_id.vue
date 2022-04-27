<template>
  <section class="section p-5">
        <div class="section-body" 
            v-if="!isLoadingPage">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="d-flex justify-content-between">                                
                            <h6>
                            {{ isEditable 
                                ? 'Edit' 
                                : 'Tambah' 
                            }} {{ title }}
                            </h6>
                        
                            <button class="btn btn-primary"
                                @click="$router.back()">
                                <i class="fas fa-arrow-left"></i>
                                Kembali
                            </button>
                        </div>
                    </div>

                <ValidationObserver
                    v-slot="{invalid,validate}"
                    ref="form-validate">
                    <form @submit.prevent="validate().then(onSubmit(invalid))"
                        autocomplete="off">
                        <ValidationProvider
                            name="code"
                            rules="required">          
                            <div class="form-group" slot-scope="{errors,valid}">
                                <label for="code">Kode</label> 
                                <input type="text" 
                                    class="form-control"
                                    name="code"
                                    v-model="form.code"                                    
                                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                                    disabled/>

                                <div class="invalid-feedback" v-if="errors[0]">
                                    {{ errors[0] }}
                                </div>                                          
                            </div>
                        </ValidationProvider>                    


                        <ValidationProvider
                            name="user_id"
                            rules="required">                                         
                            <div class="form-group" slot-scope="{errors,valid}">
                                <label for="user_id">User</label>
                                <input type="text"
                                    class="form-control"
                                    name="user_id"
                                    :value="typeof form.user_id == 'object' ? form.user_id.name : ''" 
                                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                                    @focus="showModalUser"/>

                                <div class="invalid-feedback" v-if="errors[0]">
                                    {{ errors[0] }}
                                </div>   
                            </div>
                        </ValidationProvider>                 

                        <ValidationProvider
                            name="customer_id"
                            rules="required">                                         
                            <div class="form-group" slot-scope="{errors,valid}">
                                <label for="customer_id">Pelanggan</label>
                                <input type="text"
                                    class="form-control"
                                    name="customer_id"
                                    :value="typeof form.customer_id == 'object' ? form.customer_id.name : ''" 
                                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                                    @focus="showModalCustomer"/>

                                <div class="invalid-feedback" v-if="errors[0]">
                                    {{ errors[0] }}
                                </div>   
                            </div>
                        </ValidationProvider>                      

                        <div class="mt-2 mb-2">
                            <button class="btn btn-primary"
                                @click="onAddDetail"
                                type="button">
                                <i class="fa fa-plus"></i> Tambah
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tr>
                                    <th>No</th>
                                    <th width="40%">Produk</th>
                                    <th width="20%">Jumlah</th>
                                    <th width="20%">Harga</th> 
                                    <th width="20%">Total</th>
                                    <th></th>
                                </tr>

                                <tr v-for="(item,index) in form.transaction_details"
                                    :key="index">
                                    <td>{{index+1}}</td>
                                    <td> 
                                        <ValidationProvider
                                            :name="'product_id'+index"
                                            rules="required">                                         
                                            <div class="form-group" slot-scope="{errors,valid}">                        
                                                <input type="text"
                                                    class="form-control"
                                                    name="product_id[]"
                                                    :value="typeof item.product_id == 'object' ? item.product_id.name : ''" 
                                                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                                                    @focus="showModalProduct(index)"/>

                                                <div class="invalid-feedback" v-if="errors[0]">
                                                    {{ errors[0] }}
                                                </div>   
                                            </div>
                                        </ValidationProvider>               
                                    </td>
                                    <td>                                        
                                        <money v-model="item.quantity"
                                            class="form-control"                                                                              
                                            @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                            @keyup.native="onCountAll"
                                        />                                        
                                    </td>
                                    <td>                                        
                                        <money v-model="item.amount"
                                            class="form-control"                                                                              
                                            @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                            @keyup.native="onCountAll"
                                        />                                        
                                    </td>
                                      <td>                                        
                                        <money v-model="item.total"
                                            class="form-control"                                                                              
                                            @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                            disabled
                                        />                                        
                                    </td>
                                    <td class="text-center">
                                        <i class="fa fa-trash text-danger"
                                            @click="onDeleteDetail(index)"
                                            style="cursor:pointer"></i> 
                                    </td>                                        
                                </tr>

                                <tr v-if="!form.transaction_details.length">
                                    <td colspan="100"
                                        class="text-center">
                                        Data tidak ditemukan
                                    </td>
                                </tr>

                                <tr> 
                                    <td colspan="3"></td>
                                    <td>Jumlah</td>
                                    <td colspan="2">
                                        <money v-model="form.quantity"
                                            class="form-control"                                                                              
                                            @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                            disabled
                                        />     
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3"></td>
                                    <td>Total</td>
                                    <td colspan="2">
                                        <money v-model="form.total"
                                            class="form-control"                                                                              
                                            @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                            disabled
                                        />     
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3"></td>
                                    <td>Grand Total</td>
                                    <td colspan="2">
                                        <money v-model="form.grand_total"
                                            class="form-control"                                                                              
                                            @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                            disabled
                                        />     
                                    </td>
                                </tr>                            
                            </table>
                        </div>
                                                        
                        <button class="btn btn-primary">
                            <span v-if="isLoadingForm">
                                <i class="fas fa-circle-notch fa-spin"></i>
                            </span>
                            <span v-else>
                                Simpan
                                <i class="fas fa-save"></i>
                            </span>
                        </button>                            
                    </form>
                </ValidationObserver>
                </div>
            </div>        
        </div>

        <div class="section-body" v-else>
            <div class="row">
                <div class="col-12">
                    <div class="card"
                        style="min-height:300px">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">                        
                            <div>Loading</div>
                            <div><i class="fas fa-circle-notch fa-spin"></i> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <modal-user 
          ref="modal-user-data" 
          :onChooseUser="onChooseUser"/>
        
        <modal-customer
          ref="modal-customer-data"
          :onChooseCustomer="onChooseCustomer"/>
        
        <modal-product
          ref="modal-product-data"
          :onChooseProduct="onChooseProduct"/>
    </section>
</template>

<script>
import MixinOnGetCode from "@/mixins/methods/on-get-code";

import ModalUser from "./modal/user.vue";
import ModalCustomer from "./modal/customer.vue";
import ModalProduct from "./modal/product.vue";

export default {     
  mixins: [
    MixinOnGetCode
  ],

  components : {
    ModalUser,
    ModalCustomer,
    ModalProduct
  },

  head() {
      return {
          title: this.title,
      }
  },

  data () {
    let id = parseInt(this.$route.params.id);               

    return {
      id,
            
      isEditable : Number.isInteger(id)
          ? true
          : false,

      isLoadingPage : Number.isInteger(id) 
          ? true 
          : false,

      isLoadingForm : false,

      url : "/transaction/product",

      title : 'Transaksi Product',    

      form : {
        code : '',
        total : 0.00,
        quantity : 0.00,
        grand_total : 0.00,
        user_id : '',
        customer_id : '',
        transaction_details : []
      },

      default_form: {
        code : '',
        total : 0.00,
        quantity : 0.00,
        grand_total : 0.00,
        user_id : '',
        customer_id : '',
        transaction_details : []
      },

      modal_show_index : 0
    }
  },

  async created(){
      if(this.isEditable){
          try{        
              let response = await this.$axios.get("transaction/product/"+this.id);

              Object.keys(this.form).forEach(item => {
                  if(item === 'user_id'){
                      this.form.user_id = response.data.user || '';
                  }else if(item === "customer_id"){
                      this.form.customer_id = response.data.customer || '';
                  }else{
                      this.form[item] = response.data[item];
                  }
              });

              this.form.transaction_details = response.data.transaction_details.map(item => {
                  return {
                      ...item,
                      product_id : item.product
                  }
              })              

              this.isLoadingPage = false;
          }catch(err){
              this.$router.push("/transaction/product");
          }
      }else{
        this.onGetCode();
      }
  },

  methods: {    
      onSubmit(isInvalid){
            if(!this.form.transaction_details.length){
                this.$toaster.error("Detail transaksi harus diisi");
                return;
            }

            if(isInvalid || this.isLoadingForm) return;            
        
            this.isLoadingForm = true;

            let url = "transaction/product";

            let formData = {
                ...this.form,
                user_id : typeof this.form.user_id === 'object' 
                    ? this.form.user_id.id 
                    : '',
                customer_id : typeof this.form.customer_id === 'object'
                    ? this.form.customer_id.id 
                    : '',
                transaction_details : this.form.transaction_details.map(item => {
                    return {
                        ...item,
                        product_id : typeof item.product_id  === 'object' 
                            ? item.product_id.id 
                            : '',                    
                    }
                })
            };    

          if(this.isEditable){
              url += "/"+this.id;

              formData._method = "put";
          }

          this.$axios.post(url,formData)
          .then(res => {
              this.$toaster.success("Berhasil " + (this.isEditable ? "Update" : "Tambah") + " Transaksi Produk");

              if(!this.isEditable){
                  this.$refs["form-validate"].reset();

                  this.form = {
                      ...this.default_form,
                      transaction_details : []
                  }    

                this.onGetCode();
              }
          })
          .catch(err => {
              this.$globalErrorToaster(err);
          })
          .finally(() => {
              this.isLoadingForm = false;
          })            
      },
      
      showModalUser(){     
        this.$refs["modal-user-data"].onInit();
      },

      showModalCustomer(){
        this.$refs["modal-customer-data"].onInit();
      },

      showModalProduct(index){
        this.modal_show_index = index;       

        this.$refs["modal-product-data"].onInit();
      },

      onChooseUser(item){
        if(item){
            this.form.user_id = item;
        }else{
            this.form.user_id = '';
        }
      },

      onChooseCustomer(item){        
        if(item){
            this.form.customer_id = item;
        }else{
            this.form.customer_id = '';
        }
      },

      onChooseProduct(item){        
        if(item){
            let product = this.form.transaction_details.find(itemDetail => (itemDetail.product_id ? itemDetail.product_id.id : 0 ) == item.id);

            if(product){
                this.$toaster.error("Product telah dipilih sebelumnya");            
            }else{
                this.form.transaction_details[this.modal_show_index].product_id = item;
                this.form.transaction_details[this.modal_show_index].amount = item.price;
            }    
        }else{
            this.form.transaction_details[this.modal_show_index].product_id = '';
        }
      },

      onAddDetail(){
          this.form.transaction_details.push({
              product_id : '',
              quantity : 0,
              total : 0,
              amount : 0
          });
      },

      onDeleteDetail(index){
          this.form.transaction_details = [
              ...this.form.transaction_details.filter((_,indexDetail) => indexDetail != index)
          ];

          this.onCountAll();
      },

      onCountAll(){        
          let quantity = 0.00;
          let total = 0.00;
          let grand_total = 0.00;

          this.form.transaction_details.forEach((_,index) => {
              this.form.transaction_details[index].total = parseFloat(this.form.transaction_details[index].quantity) * parseFloat(this.form.transaction_details[index].amount);

              total += parseFloat(this.form.transaction_details[index].total);
              grand_total += parseFloat(this.form.transaction_details[index].total);
              quantity += parseFloat(this.form.transaction_details[index].quantity);
          })

          this.form.quantity = quantity;
          this.form.total = total;
          this.form.grand_total = grand_total;
      }
  }

}
</script>