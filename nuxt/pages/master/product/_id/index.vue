<template>
  <section class="section p-5">
        <div class="section-body" 
            v-if="!isLoadingPage">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="d-flex justify-content-between">                                
                                    <h6>
                                    {{ isEditable 
                                        ? 'Edit' 
                                        : 'Tambah' 
                                    }} Produk
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
                                    name="name"
                                    rules="required">                                         
                                    <div class="form-group" slot-scope="{errors,valid}">
                                        <label for="name">Nama</label>
                                        <input type="text"
                                            class="form-control"
                                            name="name"
                                            :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                                            v-model="form.name">
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

                              <div class="form-group">
                                  <label for="price">Harga</label>
                                  <money v-model="form.price"
                                      class="form-control"                                                                              
                                      @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                  />    
                              </div>

                               <div class="form-group">
                                  <label for="stock">Stok</label>
                                  <money v-model="form.stock"
                                      class="form-control"                                                                              
                                      @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                  />    
                              </div>

                               <div class="form-group">
                                  <label for="sold">Terjual</label>
                                  <money v-model="form.sold"
                                      class="form-control"                                                                              
                                      @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                  />    
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

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                     <div class="card-title">
                                         <form id="form-upload" enctype="multipart/form-data">
                                            <h6>Upload Gambar Produk (Max 3 Gambar)</h6>
                                            
                                            <div class="text-center mt-4 mb-4"
                                                v-if="!preview_images.length">
                                                <i class="fas fa-upload"
                                                    style="font-size:4em"></i>
                                            </div>
                                            <div class="text-center mt-4 mb-4"
                                                v-else>                                                                                         
                                                <img
                                                    v-for="(image, imageIndex) in preview_images"
                                                    :key="imageIndex"
                                                    :src="image"
                                                    style="width:50px;height:50px;object-fit:cover"/>                                                                                                    
                                            </div>

                                            <div class="text-center"> 
                                                <input type="file" @change="onChangeImages" name="images[]" multiple>
                                                <div class="text-muted mt-3 text-info"
                                                    v-if="isEditable"> 
                                                    * Isi jika ingin menganti gambar
                                                </div>
                                            </div>
                                         </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    </section>
</template>

<script>
import MixinOnGetCode from "@/mixins/methods/on-get-code";
import ModalUser from "../modal/user.vue";

export default {     
  mixins: [
    MixinOnGetCode
  ],

  components : {
    ModalUser
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

      url : "/master/product",

      title : 'Product',    

      form : {
        code : '',
        name : '',
        price : 0,
        stock : 0,
        sold : 0,
        user_id : 0
      },

      default_form: {
        code : '',
        name : '',
        price : 0,
        stock : 0,
        sold : 0,
        user_id : 0
      },

      preview_images : [],

      modal_user : null
    }
  },

  async created(){
      if(this.isEditable){
          try{        
              let response = await this.$axios.get("master/product/"+this.id);

              Object.keys(this.form).forEach(item => {
                  if(item === 'user_id'){
                      this.form.user_id = response.data.user || '';
                  }else{
                      this.form[item] = response.data[item];
                  }
              });

              response.data.images.forEach(item => {
                  this.preview_images.push(process.env.IMAGES_URL + "/products/"+item);
              })        
              
              this.isLoadingPage = false;
          }catch(err){
              this.$router.push("/master/product");
          }
      }else{
        this.onGetCode();
      }
  },

  methods: {
      onChangeImages(event){
          if(!event.target.files.length) return;

          if(event.target.files.length >= 4){
              this.$toaster.error("Gambar hanya boleh 3");
              event.target.value = "";
              this.preview_images = [];
              return;
          }

          this.preview_images = [];

          for(let i=0;i<event.target.files.length;i++){
              let item = event.target.files[i];
          
              if(!['image/jpeg','image/jpg','image/png'].includes(item.type)){
                  this.$toaster.error("Gambar tidak valid");
                  event.target.value = "";
                  this.preview_images = [];
                  return;
              }
              
              this.preview_images.push(URL.createObjectURL(item));
          }
      },

      onSubmit(isInvalid){
          if(isInvalid || this.isLoadingForm) return;            
    
          this.isLoadingForm = true;

          let formData = new FormData(document.getElementById("form-upload"));
          let url = "master/product";

          if(this.isEditable){
              url += "/"+this.id;
              formData.append("_method","PUT");
          }

          Object.keys(this.form).forEach(item => {
              if(item == "user_id"){
                  formData.append(item,typeof this.form.user_id == 'object'
                      ? this.form.user_id.id 
                      : '',)
              }else{
                  formData.append(item,this.form[item]);
              }
          })
            
          this.$axios.post(url,formData)
          .then(res => {
              this.$toaster.success("Berhasil " + (this.isEditable ? "Update" : "Tambah") + " Produk");

              if(this.isEditable){
                  this.form.code = res.data.code;
              }else{
                  this.$refs["form-validate"].reset();

                  this.form = {
                      ...this.default_form
                  }

                  this.preview_images = [];

                  document.getElementById("form-upload").reset();
              }
          })
          .catch(err => {
              this.$globalErrorToaster(this.$toaster,err);
          })
          .finally(() => {
              this.isLoadingForm = false;
          })            
      },
      
      showModalUser(){
        this.modal_user = new bootstrap.Modal(document.getElementById('modal-user-data'), {
  		    keyboard: false
		    })

        this.modal_user.show();
        this.$refs["modal-user-data"].onLoad();
      },

      onChooseUser(item){
        this.modal_user.hide();
        this.form.user_id = item;
      }
  }

}
</script>