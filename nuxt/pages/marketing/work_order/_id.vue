<template>
    <section class="section">
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
                            }} Spk Produksi
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
                                <label for="code">Kode </label>
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

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="po_customer_id">Po Pelanggan</label>
                                       <v-select                           
                                        label="code"   
                                        :loading="isLoadingGetPoCustomer"
                                        :options="lookup_po_customers.data"
                                        :filterable="false"
                                        @search="onGetPoCustomer"
                                        @input="onSelectPoCustomer"
                                        v-model="form.po_customer_id">      
                                        <template #search="{attributes, events}">
                                          <input
                                            class="vs__search"
                                            :required="!form.po_customer_id"
                                            v-bind="attributes"
                                            v-on="events"
                                            />
                                        </template>           
                                        <template v-slot:option="option">                    
                                            {{ option.code }} | {{ option.customer ? option.customer.name : '-' }}
                                        </template>                                      
                                        <li slot-scope="{search}" slot="list-footer"
                                            class="d-flex justify-content-between"
                                            v-if="lookup_po_customers.data.length || search">
                                            <span v-if="lookup_po_customers.current_page > 1" 
                                                @click="onGetPoCustomer(search,false)"
                                                class="flex-fill bg-primary text-white text-center"
                                                style="cursor:pointer">Sebelumnya</span>
                                            <span v-if="lookup_po_customers.last_page > lookup_po_customers.current_page" 
                                                @click="onGetPoCustomer(search,true)"
                                                class="flex-fill bg-primary text-white text-center"
                                                style="cursor:pointer">Selanjutnya</span>
                                        </li> 
                                    </v-select>                                                                
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="customer_id">Pelanggan</label>                                     
                                    <input type="text"
                                        class="form-control"
                                        disabled
                                        :value="form.po_customer_id ? form.po_customer_id.customer.name : '-'"/>
                                </div>
                            </div>
                        </div>
          
                        <ValidationProvider
                            name="date"
                            rules="required">          
                            <div class="form-group" slot-scope="{errors,valid}">
                                <label for="date">Tgl</label>
                                <input type="date" 
                                    class="form-control"
                                    name="date"
                                    v-model="form.date"                                    
                                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"/>

                                <div class="invalid-feedback" v-if="errors[0]">
                                    {{ errors[0] }}
                                </div>                       
                            </div>
                        </ValidationProvider>    

                        <div class="form-group">
                            <label for="departement">Departement</label>
                            <input type="text" 
                                class="form-control"
                                v-model="form.departement"/>
                        </div>
                        <div class="form-group">
                            <label for="desirpition">Deskripsi</label>
                            <textarea class="form-control"
                                v-model="form.description"></textarea>
                        </div>

                        <div class="table-responsive"
                            style="min-height:300px">
                            <div class="clearfix">
                                <span class="btn btn-primary float-right"
                                    @click="addDetail"
                                    style="cursor:pointer">
                                    <i class="fas fa-plus"></i>
                                    Tambah
                                </span>
                            </div>

                            <table class="table table-sm mt-3">
                                <tr>
                                    <td class="text-center"
                                        style="width:15%">Kode</td>
                                    <td class="text-center"
                                        style="width:15%">Product</td>
                                    <td class="text-center"
                                        style="width:10%">Unit</td>                                        
                                    <td class="text-center"
                                        style="width:10%">Qty</td>                                        
                                    <td class="text-center"
                                        style="width:5%">Opsi</td>
                                </tr>
                                <tr v-for="item,index in form.work_order_details"
                                    :key="index">
                                    <td>
                                        <input type="text" 
                                            class="form-control"
                                            :value="item.product_id ? item.product_id.code : '-'"
                                            disabled/>                            
                                    </td>
                                    <td>
                                        <v-select                           
                                            label="name"   
                                            :loading="isLoadingGetProduct"
                                            :options="lookup_products.data"
                                            :filterable="false"
                                            @search="onGetProduct"
                                            @input="onSetProduct(index)"
                                            v-model="item.product_id">              
                                            <template v-slot:option="option">                    
                                                {{ option.name }}
                                            </template> 
                                            <template #search="{attributes, events}">
                                                <input
                                                class="vs__search"
                                                :required="!item.product_id"
                                                v-bind="attributes"
                                                v-on="events"
                                                />
                                            </template>   
                                            <li slot-scope="{search}" slot="list-footer"
                                                class="d-flex justify-content-between"
                                                v-if="lookup_products.data.length || search">
                                                <span v-if="lookup_products.current_page > 1" 
                                                    @click="onGetProduct(search,false)"
                                                    class="flex-fill bg-primary text-white text-center"
                                                    style="cursor:pointer">Sebelumnya</span>
                                                <span v-if="lookup_products.last_page > lookup_products.current_page" 
                                                    @click="onGetProduct(search,true)"
                                                    class="flex-fill bg-primary text-white text-center"
                                                    style="cursor:pointer">Selanjutnya</span>
                                            </li> 
                                        </v-select>       
                                    </td>
                                    <td>
                                         <input type="text" 
                                            class="form-control"
                                            :value="item.product_id ? item.product_id.selling_unit : '-'"
                                            disabled/> 
                                    </td>
                                    <td>
                                        <money v-model="item.quantity"
                                            class="form-control"                                                                              
                                            @keydown.native="$event.key === '-' ? $event.preventDefault() : null"/>                                           
                                    </td>
                                    <td class="text-center">
                                        <i class="fas fa-trash"
                                            style="cursor:pointer"
                                            @click="deleteDetail(index)"></i>
                                    </td>                                    
                                </tr>
                                <tr v-if="!form.work_order_details.length">
                                    <td colspan="7"
                                        class="text-center pt-4 pb-4">
                                        Data ditemukan
                                    </td>
                                </tr>                  
                            </table>    

                            <div class="mt-5 mb-5"
                                style="height: 60px">                                
                            </div>            
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
    </section>
</template>

<script>
import { mapActions,mapState } from 'vuex'

export default {
    middleware({ store, redirect, route}) {
        let user = store.state.auth.user;

        if(!user.parent_id){
            return ;
        }
    
        // marketing-work_order-id

        // 0 => marketing (MODULE)
        // 1 => work_order (MENU)
        // 2 => id (SUB MENU)

        let route_name = route.name.split("-");

        let main_role = user.group_role.roles.find(item => item.name == "work_order");

        if(!main_role){
            return redirect("/");
        }

        if(JSON.parse(main_role.pivot.operators).includes("all")){
            return ;
        }

        if(Number.isInteger(parseInt(route.params.id))){        
            if(            
                !JSON.parse(main_role.pivot.operators).includes("update")
                ||
                !JSON.parse(main_role.pivot.operators).includes("show")            
            ){
                return redirect("/");  
            }
        }else{
            if(!JSON.parse(main_role.pivot.operators).includes("store")){        
                return redirect("/");
            }
        }       
    },

    head() {
        return {
            title: 'Spk Produksi',
        }
    },

    data(){     
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

            isStopSearchProduct : false,
            isLoadingGetProduct : false,
            product_search : '',

            isStopSearchPoCustomer : false,
            isLoadingGetPoCustomer : false,
            po_customer_search : '',

            form : {
                po_customer_id : '',
                code : '',
                description : '',
                date : moment().format("YYYY-MM-DD"),
                departement : '',
                work_order_details : []
            },


            default_form : {
                po_customer_id : '',
                code : '',
                description : '',
                date : moment().format("YYYY-MM-DD"),
                departement : '',
                work_order_details : []
            },
        }
    },

    async created(){
        try{        
            if(this.isEditable){
                let response = await this.$axios.get("marketing/work_order/"+this.id);

                Object.keys(this.form)
                    .forEach(item => {
                        if(item != "work_order_details"){
                            if(item === 'po_customer_id'){
                                this.form.po_customer_id = response.data.po_customer || '';
                            }else{
                                this.form[item] = response.data[item];
                            }
                        }
                    });
            
                this.form.work_order_details = response.data
                    .work_order_details
                    .map(item => {
                        return {
                            ...item,
                            product_id : item.product
                        }
                    });

                this.isLoadingPage = false;        
            }
        }catch(err){
            this.$router.push("/marketing/work_order");
        }
    },

    async mounted(){
        await this.lookUp({
            url : "marketing/work_order/get-product",
            lookup : "products",
            query : "?per_page=5"
        });

        await this.lookUp({
            url : "marketing/work_order/get-po-customer",
            lookup : "po_customers",
            query : ""
        })

        if(!this.isEditable){
            this.setCode();
        }
    },

    computed :{
        ...mapState('moduleApi',[
            'lookup_products',
            'lookup_po_customers',
            'code'
        ])
    },

    methods: {    
        ...mapActions('moduleApi',[
            'lookUp',
            'getCode'
        ]),

        
        onGetProduct(search,isNext){      
            if(!search.length && typeof isNext === "function") return false;             

            clearTimeout(this.isStopSearchProduct);
            
            this.isStopSearchProduct = setTimeout(() => {
                this.product_search = search;

                if(typeof isNext !== "function"){
                    this.lookup_products.current_page = isNext 
                        ? this.lookup_products.current_page + 1 
                        : this.lookup_products.current_page - 1;        
                }else{
                    this.lookup_products.current_page = 1;
                }

                this.onSearchProduct();
            },600);        
        },

        async onSearchProduct(){
            if(!this.isLoadingGetProduct){
                this.isLoadingGetProduct = true;
                        
                await this.lookUp({    
                    url : "marketing/work_order/get-product",
                    lookup : "products",
                    query : "?search="
                        + this.product_search + 
                        "&page="
                        + this.lookup_products.current_page + 
                        "&per_page=5"
                })

                this.isLoadingGetProduct = false;      
            }
        },

        onGetPoCustomer(search,isNext){      
            if(!search.length && typeof isNext === "function") return false;             

            clearTimeout(this.isStopSearchPoCustomer);
            
            this.isStopSearchPoCustomer = setTimeout(() => {
                this.po_customer_search = search;

                if(typeof isNext !== "function"){
                    this.lookup_po_customers.current_page = isNext 
                        ? this.lookup_po_customers.current_page + 1 
                        : this.lookup_po_customers.current_page - 1;        
                }else{
                    this.lookup_po_customers.current_page = 1;
                }

                this.onSearchPoCustomer();
            },600);        
        },

        async onSearchPoCustomer(){
            if(!this.isLoadingGetPoCustomer){
                this.isLoadingGetPoCustomer = true;
                        
                await this.lookUp({    
                    url : "marketing/work_order/get-po-customer",
                    lookup : "po_customers",
                    query : "?search=" 
                        + this.po_customer_search + 
                        "&page="
                        + this.lookup_po_customers.current_page + 
                        "&per_page=10"
                })

                this.isLoadingGetPoCustomer = false;      
            }
        },
        
        addDetail(){
            this.form.work_order_details.push({
                product_id : '',
                quantity : 0.00,
            })
        },

        deleteDetail(indexItem){
            this.form.work_order_details = this.form
                .work_order_details
                .filter((_,index) => index != indexItem);            
        },

        onSubmit(isInvalid){
            if(isInvalid || this.isLoadingForm) return;            
      
            this.isLoadingForm = true;

            let url = "marketing/work_order";

            let formData = {
                ...this.form,
                po_customer_id : typeof this.form.po_customer_id == 'object'
                    ? this.form.po_customer_id.id 
                    : '',             
            }

            formData.work_order_details = formData
                .work_order_details
                .map(item => {
                    return {
                        quantity : item.quantity,
                        product_id : typeof item.product_id == 'object'
                            ? item.product_id.id 
                            : ''
                    }
                });
            
            if(this.isEditable){
                url += "/"+this.id;        
            }

            this.$axios({
                url : url,
                method : this.isEditable 
                    ? "put" 
                    : "post",
                data : formData
            })
            .then(res => {
                this.$toaster.success(
                    "Berhasil " 
                    + (this.isEditable ? "Update" : "Tambah") 
                    + " Po Pelanggan"
                );
 
                if(!this.isEditable){                                                       
                    this.form = {
                        ...this.default_form,
                        work_order_details : []
                    }

                    this.setCode();

                    this.$confirm({
                        message: "Apakah anda ingin menampilkan halaman print",
                        button: {
                            no: 'No',
                            yes: 'Yes'
                        },
                        callback: async confirm => {         
                            if(confirm){
                                var token = this.$cookiz
                                    .get(('auth._token.local'))
                                    .replace('Bearer ','');    

                                window.open(
                                    process.env.API_URL 
                                    + "/marketing/work_order/get-print-detail/" + 
                                    res.data.id 
                                    + "?token=" + 
                                    token
                                , '_blank');    
                            } 
                        }
                    });
                }
            })
            .catch(err => {
                this.$globalErrorToaster(this.$toaster,err);
            })
            .finally(() => {
                this.isLoadingForm = false;
            })            
        },        

        async setCode(){
            await this.getCode({
                url : "marketing/work_order",
                query : ""            
            });

            this.form.code = this.code;
        },

        onSelectPoCustomer(item){
            if(item){
                this.form = {
                    ...this.form,
                    code : this.form.code,                                
                    date : item.date,
                    description : item.description,                    
                    work_order_details : item
                        .po_customer_details
                        .map(item => {
                            return {
                                product_id :  item.product,
                                quantity : item.quantity,
                            }
                        })
                }
            }
        },

        onSetProduct(index){
            let product = this.form.work_order_details[index];

            let products = this.form
                .work_order_details
                .filter(item => item.product_id.id == product.product_id.id);

            if(products.length > 1){
                this.$toaster.error("Product sudah ada di dalam list");
                
                this.form.work_order_details = this.form
                    .work_order_details
                    .filter((_,indexItem) => index != indexItem);
            }
        }
    }
}
</script>
