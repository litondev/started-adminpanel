<template>
  <section class="section">
    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="card-title">
                <list-option-section
                  :self="this"
                  ref="form-option"/>                 
              </div>            

              <div v-if="parameters.form.checkboxs.length">
                <button class="btn btn-sm btn-danger"
                  data-toggle="tooltip"
                  data-placement="top"
                  data-original-title="Hapus Semua Data"
                  @click="onDeleteAll()"
                  v-if="parameters.params.soft_deleted != 'deleted' && getRoles.destroy_all">
                  Hapus <i class="fas fa-trash"></i>
                </button>
                <button class="btn btn-sm btn-success"
                  data-toggle="tooltip"
                  data-placement="top"
                  data-original-title="Restore Semua Data"
                  @click="onRestoreAll()"
                  v-if="parameters.params.soft_deleted && getRoles.restore_all">
                  Pulihkan <i class="fas fa-redo"></i>
                </button>
              </div>

              <!-- start table -->
              <div class="table-responsive">
                <table class="table table-striped table-sm vld-parent"
                  ref="formContainer">
                  <thead>
                    <tr>         
                      <th>
                        <input 
                          type="checkbox" 
                          id="checkAll" 
                          @click="onCheckAll"
                          v-if="getRoles.destroy_all || getRoles.restore_all"
                        />
                      </th>            
                      <th>No</th>     
                      <th>Kode</th>            
                      <th @click="onSort('date',parameters.params.sort == 'asc' ? 'desc' : 'asc')"
                        class="cursor-pointer">
                        <div class="d-flex flex-row justify-content-between align-items-baseline">
                          <div>Tgl</div>
                          <div>
                            <i class="fas fa-caret-up"
                              :class="parameters.params.order == 'date' && parameters.params.sort == 'asc' ? '' : 'light-gray'"></i>
                            <i class="fas fa-caret-down"
                              :class="parameters.params.order == 'date' && parameters.params.sort == 'desc' ? '' : 'light-gray'"></i>
                          </div>
                        </div>
                      </th>              
                      <th>Pelanggan</th>              
                      <th @click="onSort('total',parameters.params.sort == 'asc' ? 'desc' : 'asc')"
                        class="cursor-pointer">
                        <div class="d-flex flex-row justify-content-between align-items-baseline">
                          <div>Total</div>
                          <div>
                            <i class="fas fa-caret-up"
                              :class="parameters.params.order == 'total' && parameters.params.sort == 'asc' ? '' : 'light-gray'"></i>
                            <i class="fas fa-caret-down"
                              :class="parameters.params.order == 'total' && parameters.params.sort == 'desc' ? '' : 'light-gray'"></i>
                          </div>
                        </div>
                      </th>
                       <th @click="onSort('discount',parameters.params.sort == 'asc' ? 'desc' : 'asc')"
                        class="cursor-pointer">
                        <div class="d-flex flex-row justify-content-between align-items-baseline">
                          <div>Diskon</div>
                          <div>
                            <i class="fas fa-caret-up"
                              :class="parameters.params.order == 'discount' && parameters.params.sort == 'asc' ? '' : 'light-gray'"></i>
                            <i class="fas fa-caret-down"
                              :class="parameters.params.order == 'discount' && parameters.params.sort == 'desc' ? '' : 'light-gray'"></i>
                          </div>
                        </div>
                      </th>
                      <th @click="onSort('tax',parameters.params.sort == 'asc' ? 'desc' : 'asc')"
                        class="cursor-pointer">
                        <div class="d-flex flex-row justify-content-between align-items-baseline">
                          <div>PPN</div>
                          <div>
                            <i class="fas fa-caret-up"
                              :class="parameters.params.order == 'tax' && parameters.params.sort == 'asc' ? '' : 'light-gray'"></i>
                            <i class="fas fa-caret-down"
                              :class="parameters.params.order == 'tax' && parameters.params.sort == 'desc' ? '' : 'light-gray'"></i>
                          </div>
                        </div>
                      </th>        
                       <th @click="onSort('grand_total',parameters.params.sort == 'asc' ? 'desc' : 'asc')"
                        class="cursor-pointer">
                        <div class="d-flex flex-row justify-content-between align-items-baseline">
                          <div>Grand Total</div>
                          <div>
                            <i class="fas fa-caret-up"
                              :class="parameters.params.order == 'grand_total' && parameters.params.sort == 'asc' ? '' : 'light-gray'"></i>
                            <i class="fas fa-caret-down"
                              :class="parameters.params.order == 'grand_total' && parameters.params.sort == 'desc' ? '' : 'light-gray'"></i>
                          </div>
                        </div>
                      </th>    
                      <th class="text-center"
                        v-if="
                          getRoles.show || 
                          getRoles.update || 
                          getRoles.destroy || 
                          getRoles.restore
                        ">
                        Options
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item,i) in data" :key="i">
                      <td>
                        <input 
                          type="checkbox" 
                          name="checkboxs[]" 
                          :value="item.id" 
                          v-model="parameters.form.checkboxs" 
                          v-if="getRoles.destroy_all || getRoles.restore_all"
                        />
                      </td>
                      <td>{{ i + 1 }}</td>
                      <td>{{ item.code }}</td>
                      <td>{{ item.date }}</td>
                      <td>
                        Kode Penawaraan : {{ item.quotation ? item.quotation.code : '-'}} 
                        <br/>
                        Pelanggan : {{ item.customer ? item.customer.name : '-' }}
                        <br/>
                        Pengguna : {{ item.user ? item.user.username : '-' }}
                      </td>
                      <td>{{ item.total | formatPrice }}</td>
                      <td>{{ item.discount | formatPrice }}</td> 
                      <td>{{ item.tax | formatPrice}} </td>
                      <td>{{ item.grand_total | formatPrice }}</td>
                      <td class="text-center">
                        <div class="btn-group">                         
                          <button class="btn btn-sm btn-danger"
                            v-if="!item.deleted_at"
                            @click="onPrintDetail(item)">
                            <i class="fas fa-print"></i>                            
                          </button>
                          <button class="btn btn-sm btn-primary" 
                            @click="onEdit(item.id)"
                            v-if="!item.deleted_at && getRoles.update">
                            <i class="fas fa-pen"></i>
                          </button>
                          <button class="btn btn-sm btn-danger" 
                            @click="onTrashed(item)" 
                            v-if="!item.deleted_at && getRoles.destroy">
                            <i class="fas fa-trash"></i>
                          </button>
                          <button class="btn btn-sm btn-success" 
                            @click="onRestored(item)" 
                            v-if="item.deleted_at && getRoles.restore">
                            <i class="fas fa-redo"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  </tbody>

                  <table-data-loading-section
                    :self="this"
                  />

                  <table-data-not-found-section
                    :self="this"
                  />
                </table>
              </div>
              <!-- end table -->

              <div class="card-title border-top"
                style="padding-bottom: -100px !important">
                <pagination-section
                  :self="this"
                  ref="pagination"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

 <filter-section
      :self="this"
      ref="form-filter">
      <template>
       <div class="col-md-12">
          <div class="mb-3"><b>Tanggal Awal</b></div>
          <div class="form-group">
            <input type="date"
              class="form-control"
              v-model="parameters.params.start_date"/>          
          </div>
          <div class="mb-3"><b>Tanggal Akhir</b></div>
          <div class="form-group">
            <input type="date"
              class="form-control"
              v-model="parameters.params.end_date"/>          
          </div>
        </div>
      </template>
    </filter-section>    

  </section>
</template>

<script>
import { mapActions,mapState,mapMutations } from 'vuex'

export default {
  middleware({ store, redirect,route}) {
    let user = store.state.auth.user;

    if(!user.parent_id){
      return ;
    }
    
    // marketing-po_customer

    // 0 => marketing (MODULE)
    // 1 => po_customer (MENU)

    let route_name = route.name.split("-");

    let main_role = user.group_role.roles.find(item => item.name == "po_customer");

    if(!main_role){
      return redirect("/");
    }

    if(JSON.parse(main_role.pivot.operators).includes("all")){
      return ;
    }

    if(!JSON.parse(main_role.pivot.operators).includes("index")){
      return redirect("/");  
    }
  },

  head() {
    return {
      title: 'Po Pelanggan',
    }
  },

  created() {
    this.set_data([]);
    this.onLoad();
  },

  mounted() {
    this.$refs["form-option"].isAddData = false;
    this.$refs["form-option"].isMaintenancePage = false;
    this.$refs['form-option'].isFilter          = true;
    this.$refs["form-option"].isExport          = true;
    this.$refs["form-option"].isExportFile      = false;
    this.$refs["form-option"].isExportPrint     = false;

    if(
      this.getRoles.destroy || 
      this.getRoles.destroy_all || 
      this.getRoles.restore || 
      this.getRoles.restore_all
    ){
      this.$refs["form-option"].isMaintenancePage = true;
    }
    
    if(this.getRoles.store){
      this.$refs["form-option"].isAddData = true;
    }

    if(this.getRoles.export){
      this.$refs["form-option"].isExportFile = true;
    }

    if(this.getRoles.print){
      this.$refs["form-option"].isExportPrint = true;
    }
  },

  data() {
    return {
      title               : 'Po Pelanggan',
      isLoadingData       : false,
      isPaginate          : true,
      parameters : {
        url : 'marketing/po_customer',
        type : 'pdf',
        params :{
          soft_deleted : '',
          search      : '',
          order       : 'id',
          sort        : 'desc',
          all         : '',
          per_page    : 10,
          page        : 1,
          start_date  : '',
          end_date    : ''
        },
        default_params :{
          soft_deleted : '',
          search      : '',
          order       : 'id',
          sort        : 'desc',
          all         : '',
          per_page    : 10,
          page        : 1,
          start_date  : '',
          end_date    : ''
        },
        form : {
          checkboxs : []
        },
        loadings : {
          isDelete  : false,
          isRestore : false,
        },        
      },
      default_roles : {
        store : true,
        update : true,
        destroy : true,
        restore : true,
        show : true,
        export : true,
        print : true,
        destroy_all : true,
        restore_all : true,
        import : true
      }
    }
  },
  
  computed : {
    ...mapState('moduleApi',[
      'data',
      'error',
      'result'
    ]),

    user(){      
      return this.$auth.user;    
    },

    getRoles(){
      if(!this.user.parent_id){
        return this.default_roles;        
      }else{
        let main_role = this.user.group_role.roles.find(item => item.name == "po_customer");

        let roles = {};

        if(JSON.parse(main_role.pivot.operators).includes("all")){
          return this.default_roles;        
        }

        JSON.parse(main_role.pivot.operators).forEach(item => {
          roles[item.replace("-","_")] = true;
        })

        return roles;
      }    
    }
  },

  methods : {
    ...mapActions('moduleApi',[
      'getData',
      'deleteData',
      'restoreData',
      'deleteAllData',
      'restoreAllData'
    ]),

    ...mapMutations('moduleApi',[
      'set_data'
    ]),

    onFormShow(){    
      this.$router.push("/marketing/po_customer/add");
    },

    onEdit(id){
      this.$router.push("/marketing/po_customer/"+id);
    },

    async onLoad(page = 1){
      if(this.isLoadingData) return;

      this.isLoadingData            = true;
      this.parameters.params.page   = page

      this.parameters.form.checkboxs = [];
      if(document.getElementById("checkAll")){
        document.getElementById("checkAll").checked = false;
      }

      let loader = this.$loading.show({
        container: this.$refs.formContainer,
        canCancel: true,
        onCancel: this.onCancel,
      });

      await this.getData(this.parameters);
      
      if(this.result == true){
        loader.hide();

        if (page == 1) {
            this.$refs['pagination'].generatePage();
        }

        this.$refs['pagination'].active_page = this.parameters.params.page;  
      }else{
        this.$globalErrorToaster(this.$toaster,this.error);
      }

      this.isLoadingData = false;
    },

    onTrashed(item){
      if(this.parameters.loadings.isDelete) return

      this.$confirm({
        auth: false,
        message: "Data ini akan dipindahkan ke dalam Trash. Yakin ??",
        button: {
          no: 'No',
          yes: 'Yes'
        },
        callback: async confirm => {
          if (confirm) {
            this.parameters.loadings.isDelete = true;

            await this.deleteData({
              url :  this.parameters.url,
              id  :  item.id,
              params : this.parameters.params
            });

            if (this.result == true){
              this.onLoad(this.parameters.params.page)
              this.$toaster.success("Data berhasil di pindahkan ke dalam Trash!");
            }else {
              this.$globalErrorToaster(this.$toaster,this.error);
            }

            this.parameters.loadings.isDelete = false;
          }
        },
      });
    },

    async onRestored(item){
      if(this.parameters.loadings.isRestore) return

      this.parameters.loadings.isRestore = true;

      await this.restoreData({
        url : this.parameters.url,
        id : item.id,
        params : this.parameters.params
      })

      if(this.result == true){
        this.onLoad(this.parameters.params.page)
        this.$toaster.success("Data berhail di restore")
      }else{
        this.$globalErrorToaster(this.$toaster,this.error);
      }

      this.parameters.loadings.isRestore = false;
    },

    async onRestoreAll(){
      if(!this.parameters.form.checkboxs.length || this.parameters.loadings.isRestore) return

      this.parameters.loadings.isRestore = true;

      await this.restoreAllData({
        url : this.parameters.url,
        checkboxs : this.parameters.form.checkboxs,
        params : this.parameters.params
      })

      if(this.result == true){
        this.onLoad(this.parameters.params.page)
        this.parameters.form.checkboxs = [];
        document.getElementById("checkAll").checked = false;
        this.$toaster.success("Data berhail di restore")
      }else{
        this.$globalErrorToaster(this.$toaster,this.error);
      }

      this.parameters.loadings.isRestore = false;
    },

    onDeleteAll(){
      if(!this.parameters.form.checkboxs.length || this.parameters.loadings.isDelete) return

      this.$confirm({
        auth: false,
        message: "Semua Data ini akan dipindahkan ke dalam Trash. Yakin ??",
        button: {
          no: 'No',
          yes: 'Yes'
        },
        callback: async confirm => {
          if (confirm) {
            this.parameters.loadings.isDelete = true;

            await this.deleteAllData({
              url :  this.parameters.url,
              checkboxs : this.parameters.form.checkboxs,
              params : this.parameters.params
            });

            if (this.result == true){
              this.onLoad()
              this.parameters.form.checkboxs = [];
              document.getElementById("checkAll").checked = false;
              this.$toaster.success("Data berhasil di pindahkan ke dalam Trash!");
            }else {
              this.$globalErrorToaster(this.$toaster,this.error);
            }

            this.parameters.loadings.isDelete = false;
          }
        },
      });
    },

    onCheckAll(evt){
      let tmpCheckboxs = [];

      document.querySelectorAll("input[name='checkboxs[]']").forEach(item => {
        item.checked = evt.target.checked;
        if(evt.target.checked){
          tmpCheckboxs.push(item.value);
        }
      })

      this.parameters.form.checkboxs = tmpCheckboxs
    },

    onSort(column,sort = 'asc'){
      this.parameters.params = {
        ...this.parameters.params,
        order : column,
        sort : sort
      }

      this.onLoad(this.parameters.params.page)
    },

    onPrintDetail(item){
      var token = this.$cookiz.get(('auth._token.local')).replace('Bearer ','');      
      window.open(process.env.API_URL + "/marketing/po_customer/get-print-detail/" + item.id + "?token="+token, '_blank');    
    }
  }
}
</script>