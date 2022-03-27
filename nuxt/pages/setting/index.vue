<template>
  <section class="section">
    <div class="section-body"
      v-if="isLoadingPage">
      <div class="row">
        <div class="col-12">
          <div class="card d-flex align-items-center justify-content-center"
            style="height:300px">
            <div class="text-center">
              <i class="fas fa-circle-notch fa-spin fa-2x"></i>
              <br/>
              Loading
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="section-body"
      v-if="!isLoadingPage">

      <div class="row mt-sm-4"
        v-if="!isLoadingPage">
        <div class="col-12 col-lg-4">
          <div class="card text-center p-3">
            <div class="card-header">
              <h4>Edit Logo</h4>
            </div>

            <form @submit.prevent="onSubmitLogo()"
              id="form-logo">
              <img :src="form.logo"
                v-if="form.logo"
                class="mb-2"
                style="width:200px;height:200px;object-fit:contain;"
              />

              <input type="file"
                name="logo"
                class="form-control mb-2"
                @change="onLogoChange"
                required
              />

              <button class="btn btn-primary btn-block"
                v-if="logo"
                :disabled="isLoadingFormLogo">
                <span v-if="isLoadingFormLogo">
                  <i class="fas fa-circle-notch fa-spin"></i>
                </span>
                <span v-else>
                  <i class="fas fa-upload"></i> Upload
                </span>
              </button>
            </form>
          </div>

          <div class="card text-center p-3">
            <div class="card-header">
              <h4>Edit Logo Print</h4>
            </div>

            <form @submit.prevent="onSubmitLogoPrint()"
              id="form-logo-print">

              <img :src="form.logo_print"
                v-if="form.logo_print"
                class="mb-2"
                style="width:200px;height:200px;object-fit:contain"
              />

              <input type="file"  
                name="logo_print"
                class="form-control mb-2"
                @change="onLogoPrintChange"
                required
              />

              <button class="btn btn-primary btn-block"
                v-if="logo_print"
                :disabled="isLoadingFormLogoPrint">
                <span v-if="isLoadingFormLogoPrint">
                  <i class="fas fa-circle-notch fa-spin"></i>
                </span>
                <span v-else>
                  <i class="fas fa-upload"></i> Upload
                </span>
              </button>
            </form>
          </div>

          <div class="card text-center p-3">
            <div class="card-header">
              <h4>Edit Tanda Tangan</h4>
            </div>

            <form @submit.prevent="onSubmitSignature()"
              id="form-signature">
              <img :src="form.signature"
                v-if="form.signature"
                class="mb-2"
                style="width:200px;height:200px;object-fit:contain"
              />

              <input type="file"
                name="signature"
                class="form-control mb-2"
                @change="onSignatureChange"
                required
              />

              <button class="btn btn-primary btn-block"
                v-if="signature"
                :disabled="isLoadingFormSignature">
                <span v-if='isLoadingFormSignature'>
                  <i class="fas fa-circle-notch fa-spin"></i>
                </span>
                <span v-else>
                  <i class="fas fa-upload"></i> Upload
                </span>
              </button>
            </form>
          </div>
        </div>      

        <div class="col-12 col-lg-8">
          <div class="card">
            <ValidationObserver
              v-slot="{invalid,validate}"
              ref="form-validate">
            <form @submit.prevent="validate().then(onSubmit(invalid))"
              autocomplete="off">
              <div class="card-header">
                <h4>Edit Aplikasi</h4>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col">
                  <ValidationProvider
                      name="company_name"
                      rules="required">
                      <div class="form-group col" slot-scope="{errors,valid}">
                        <label>Perusahaan Nama</label>
                        <input type="text"
                          class="form-control"
                          v-model="form.company_name"
                          name="company_name"
                          :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                        />
                         <div class="invalid-feedback" v-if="errors[0]">
                          {{ errors[0] }}
                        </div>
                      </div>
                  </ValidationProvider>

                  <ValidationProvider
                      name="phone"
                      rules="required">
                      <div class="form-group col-12" slot-scope="{errors,valid}">
                        <label>Phone</label>
                        <input type="text"
                          class="form-control"
                          v-model="form.phone"
                          name="phone"
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
                    name="email"
                    rules="required|email">
                    <div class="form-group col" slot-scope="{errors,valid}">
                      <label>Email</label>
                      <input type="text"
                        class="form-control"
                        v-model="form.email"
                        name="email"
                        :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                      />
                        <div class="invalid-feedback" v-if="errors[0]">
                        {{ errors[0] }}
                      </div>
                    </div>
                </ValidationProvider>

                <div class="form-group col-12">
                  <label for="address">Alamat</label>
                  <textarea
                    class="form-control is-valid"
                    v-model="form.address">
                  </textarea>
                </div>

                <div class="form-group col-12">
                  <label for="website">Website</label>
                  <input type="text" 
                    class="form-control" 
                    v-model="form.website"
                  />
                </div>

                <div class="form-group col-12">
                  <label for="tax">PPN</label>
                  <money v-model="form.tax"
                    class="form-control"                                                                              
                    @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                    />    
                  <div class="text-muted text-small">* Percent</div>
                </div>

                <div class="form-group col-12">
                  <label for="signature_owner">Pemilik Tanda Tangan</label>
                  <input type="text"
                    class="form-control"
                    v-model="form.signature_owner"
                    />
                </div>

                <div class="form-group col-12">
                  <label for="header_color">Warna Header</label>
                  <input type="color" 
                    class="form-control" 
                    v-model="form.header_color"
                  />
                </div>                          

                <div class="form-group col-12">
                  <label for="footer_print_quotation">
                  Info Print Bagian Bawah Penawaran
                  </label>
                  <vue-editor 
                    class="is-valid"
                    v-model="form.footer_print_quotation" 
                    :editorToolbar="customToolbar"/>                
                </div>
              </div>
              <div class="card-footer text-right">
                <button class="btn btn-primary"
                  :disabled="isLoadingForm">
                  <span v-if="isLoadingForm">
                    <i class="fas fa-circle-notch fa-spin"></i>
                  </span>
                  <span v-else>
                    Kirim
                  </span>
                </button>
              </div>
            </form>
            </ValidationObserver>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>

import { mapMutations } from "vuex";

import { VueEditor } from "vue2-editor";

export default {
  middleware({ store, redirect }) {
    let user = store.state.auth.user;

    if(user.parent_id){
      return redirect('/')
    }
  },

  components: {
    VueEditor
  },

  head() {
    return {
      title: 'Aplikasi',
    }
  },

  data(){
    return {
      form : {
        company_name : '',
        address : '',
        email : '',
        phone : '',
        logo : '',
        logo_print : '',
        header_color : '',
        tax : 0.00,
        footer_print_quotation : '',
        website : '',
        signature : '',
        signature_owner : ''
      },

      isLoadingForm : false,
      isLoadingFormLogo : false,
      isLoadingFormSignature : false,
      isLoadingFormLogoPrint : false,
      isLoadingPage : true,


      logo : '',
      logo_print : '',
      signature : '',

      customToolbar: [
        ["bold", "italic", "underline"],    
        [{ list: "ordered" }, { list: "bullet" }],
      ]
    }
  },


  created(){
    this.$axios.get("/setting")
    .then(res => {
      res.data.forEach(item => {          
        this.form[item.name] = item.name == 'umk'  
          ? parseInt(item.value) 
          : item.value;
      });
      
      this.isLoadingPage = false;
    })
    .catch((err) => {
      console.log(err);
      this.$globalErrorToaster(this.$toaster,err);
    })
  },

  methods : {
    ...mapMutations('setting',['SET_SETTINGS']),

    onSubmit(isInvalid){
      if(isInvalid || this.isLoadingForm) return;

      this.isLoadingForm = true;

      this.$axios.put("/setting",this.form)
      .then(() => {
        return this.$axios.get("/setting");
      })      
      .then((res) => {      
        this.SET_SETTINGS(res.data);
        this.$toaster.success("Berhasil update data");      
      })
      .catch(err => {
        console.log(err);
        this.$globalErrorToaster(this.$toaster,err);
      })
      .finally(() => {
        this.isLoadingForm = false;
      })
    },

    onSubmitLogo(){
      if(this.isLoadingFormLogo) return;

      this.isLoadingFormLogo = true;

      let formData = new FormData(document.getElementById("form-logo"));
      formData.append("_method","PUT");

      this.$axios.post("/setting/logo",formData)
      .then(() => {
        this.logo = '';
        document.getElementById("form-logo").reset();
        this.$toaster.success("Berhasil upload logo");
      })
      .catch(err => {
        console.log(err);
        this.$globalErrorToaster(this.$toaster,err);
      })
      .finally(() => {
        this.isLoadingFormLogo = false
      });
    },

    onSubmitSignature(){
      if(this.isLoadingFormSignature) return;

      this.isLoadingFormSignature = true;

      let formData = new FormData(document.getElementById("form-signature"));
      formData.append("_method","PUT");

      this.$axios.post("/setting/signature",formData)
      .then(() => {
        this.signature = '';
        document.getElementById("form-signature").reset();
        this.$toaster.success("Berhasil upload tanda tangan")
      })
      .catch(err => {
        console.log(err);
        this.$globalErrorToaster(this.$toaster,err);
      })
      .finally(() => {
        this.isLoadingFormSignature = false;
      })
    },

    onSubmitLogoPrint(){
      if(this.isLoadingFormLogoPrint) return;

      this.isLoadingFormLogoPrint = true;

      let formData = new FormData(document.getElementById("form-logo-print"));
      formData.append("_method","PUT");

      this.$axios.post("/setting/logo-print",formData)
      .then(() => {
        this.logo_print = '';
        document.getElementById("form-logo-print").reset();
        this.$toaster.success("Berhasil upload logo print");
      })
      .catch(err => {
        console.log(err);
        this.$globalErrorToaster(this.$toaster,err);
      })
      .finally(() => {
        this.isLoadingFormLogoPrint = false;
      })
    },

    onLogoChange(evt){
      if(this.isLoadingFormLogo || !evt.target.files[0]) return;

      if(!['image/jpeg','image/jpg','image/png'].includes(evt.target.files[0].type)){
        this.$toaster.error("Gambar tidak valid");
        evt.target.value = "";
        return;
      }

      this.logo = evt.target.files[0].name;
      this.form.logo = URL.createObjectURL(evt.target.files[0]);
    },

    onSignatureChange(evt){
      if(this.isLoadingFormSignature || !evt.target.files[0]) return;

      if(!["images/jpeg","image/jpg","image/png"].includes(evt.target.files[0].type)){
        this.$toaster.error("Gambar tidak valid");
        evt.target.value = "";
        return;
      }

      this.signature = evt.target.files[0].name;
      this.form.signature = URL.createObjectURL(evt.target.files[0]);
    },

    onLogoPrintChange(evt){
      if(this.isLoadingFormLogoPrint || !evt.target.files[0]) return;

      if(!['images/jpeg','image/jpg','image/png'].includes(evt.target.files[0].type)){
        this.$toaster.error("Gambar tidak valid");
        evt.target.value = "";
        return;
      }

      this.logo_print = evt.target.files[0].name;
      this.form.logo_print = URL.createObjectURL(evt.target.files[0]);
    }
  }
}
</script>
