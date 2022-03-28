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
                            }} Po Supplier
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
                        <div class="row">
                            <div class="col">
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
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="supplier_id">Supplier</label>
                                       <v-select                           
                                        label="name"   
                                        :loading="isLoadingGetSupplier"
                                        :options="lookup_suppliers.data"
                                        :filterable="false"
                                        @search="onGetSupplier"
                                        v-model="form.supplier_id">              
                                        <template v-slot:option="option">                    
                                            {{option.code }} | {{ option.name }}
                                        </template> 
                                        <template #search="{attributes, events}">
                                            <input
                                            class="vs__search"
                                            :required="!form.supplier_id"
                                            v-bind="attributes"
                                            v-on="events"
                                            />
                                        </template>   
                                        <li slot-scope="{search}" slot="list-footer"
                                            class="d-flex justify-content-between"
                                            v-if="lookup_suppliers.data.length || search">
                                            <span v-if="lookup_suppliers.current_page > 1" 
                                                @click="onGetSupplier(search,false)"
                                                class="flex-fill bg-primary text-white text-center"
                                                style="cursor:pointer">Sebelumnya</span>
                                            <span v-if="lookup_suppliers.last_page > lookup_suppliers.current_page" 
                                                @click="onGetSupplier(search,true)"
                                                class="flex-fill bg-primary text-white text-center"
                                                style="cursor:pointer">Selanjutnya</span>
                                        </li> 
                                    </v-select>                                                                
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
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
                            </div>
                            <div class="col">                                     
                                <div class="form-group">
                                    <label for="address">Alamat </label>
                                    <textarea            
                                        :value="form.supplier_id ? form.supplier_id.address : '-'"
                                        class="form-control"
                                        :disabled="true"></textarea>                                
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="desirpition">Deskripsi</label>
                            <textarea class="form-control"
                                v-model="form.description"></textarea>
                        </div>

                        <div class="table-responsive">
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
                                        style="width:11%">Harga</td>
                                    <td class="text-center"
                                        style="width:11%">Total</td>
                                    <td class="text-center"
                                        style="width:5%">Opsi</td>
                                </tr>
                                <tr v-for="item,index in form.po_supplier_details"
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
                                            @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                            @change.native="itemChange(index)"
                                            />                                           
                                    </td>
                                    <td>
                                        <money v-model="item.price"
                                            class="form-control"                                                                              
                                            @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                            @change.native="itemChange(index)"
                                            />                                       
                                    </td>
                                    <td>
                                        <money v-model="item.amount"
                                            class="form-control"                                                                              
                                            @keydown.native="$event.key === '-' ? $event.preventDefault() : null"                                            
                                            />   
                                    </td>
                                    <td class="text-center">
                                        <i class="fas fa-trash"
                                            style="cursor:pointer"
                                            @click="deleteDetail(index)"></i>
                                    </td>                                    
                                </tr>
                                <tr v-if="!form.po_supplier_details.length">
                                    <td colspan="7"
                                        class="text-center pt-4 pb-4">
                                        Data ditemukan
                                    </td>
                                </tr>                  
                            </table>

                            <div class="clearfix">
                                <div class="float-right">
                                    <table class="table">
                                    <tr>
                                        <td colspan="5">
                                        </td>
                                        <td>Total</td> 
                                        <td>
                                            <money v-model="form.total"
                                                class="form-control"                                                                              
                                                @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                                />   
                                        </td> 
                                    </tr>                                  
                                    <tr>
                                        <td colspan="5">
                                        </td>
                                        <td>PPN</td>
                                        <td>
                                            <money v-model="form.tax"
                                                class="form-control"                                                                              
                                                @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                                />   
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">
                                        </td>
                                        <td>Grand Total</td> 
                                        <td>
                                            <money v-model="form.grand_total"
                                                class="form-control"                                                                              
                                                @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                                />   
                                        </td>
                                    </tr>
                                    </table>
                                </div>
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
    
        // purchaseing_module-po_supplier-id

        // 0 => purchaseing_module (MODULE)
        // 1 => po_supplier (MENU)
        // 2 => id (SUB MENU)

        let route_name = route.name.split("-");

        let main_role = user.group_role.roles.find(item => item.name == "po_supplier");

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
            title: 'Po Supplier',
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

            isStopSearchSupplier : false,
            isLoadingGetSupplier : false,
            supplier_search : '',

            isStopSearchProduct : false,
            isLoadingGetProduct : false,
            product_search : '',

            form : {
                supplier_id : '',
                code : '',
                description : '',
                date : moment().format("YYYY-MM-DD"),
                total : 0,
                tax : 0,
                grand_total : 0,
                po_supplier_details : [],
            },

            default_form : {
                customer_id : '',
                code : '',
                description : '',
                date : moment().format("YYYY-MM-DD"),
                total : 0,
                tax : 0,
                grand_total : 0,
                po_supplier_details : [],
            }
        }
    },

    async created(){
        try{        
            if(this.isEditable){
                let response = await this.$axios.get("purchaseing_module/po_supplier/"+this.id);

                Object.keys(this.form)
                    .forEach(item => {
                        if(item != "po_supplier_details"){
                            if(item === 'supplier_id'){
                                this.form.supplier_id = response.data.supplier;
                            }else{
                                this.form[item] = response.data[item];
                            }
                        }
                    });
            
                this.form.po_supplier_details = response.data
                    .po_supplier_details
                    .map(item => {
                        return {
                            ...item,
                            product_id : item.product
                        }
                    });

                this.isLoadingPage = false;        
            }
        }catch(err){
            this.$router.push("/purchaseing_module/po_supplier");
        }
    },

    async mounted(){
        await this.lookUp({
            url : "purchaseing_module/po_supplier/get-supplier",
            lookup : "suppliers",
            query : ""
        });

        await this.lookUp({
            url : "purchaseing_module/po_supplier/get-product",
            lookup : "products",
            query : "?per_page=5"
        });

        if(!this.isEditable){
            this.setCode();
        }
    },

    computed :{
        ...mapState('moduleApi',[
            'lookup_suppliers',
            'lookup_products',    
            'code'
        ]),
        
        tax(){
            return this.$store.state.setting
                .settings
                .find(item => item.name == "tax")
                .value;
        }
    },

    methods: {    
        ...mapActions('moduleApi',[
            'lookUp',
            'getCode'
        ]),

        onGetSupplier(search,isNext){      
            if(!search.length && typeof isNext === "function") return false;             

            clearTimeout(this.isStopSearchSupplier);
            
            this.isStopSearchSupplier = setTimeout(() => {
                this.supplier_search = search;

                if(typeof isNext !== "function"){
                    this.lookup_suppliers.current_page = isNext 
                        ? this.lookup_suppliers.current_page + 1 
                        : this.lookup_suppliers.current_page - 1;        
                }else{
                    this.lookup_suppliers.current_page = 1;
                }

                this.onSearchSupplier();
            },600);        
        },

        async onSearchSupplier(){
            if(!this.isLoadingGetSupplier){
                this.isLoadingGetSupplier = true;
                        
                await this.lookUp({    
                    url : "purchaseing_module/po_supplier/get-supplier",
                    lookup : "suppliers",
                    query : "?search="
                        + this.supplier_search + 
                        "&page="
                        + this.lookup_suppliers.current_page + 
                        "&per_page=10"
                })

                this.isLoadingGetSupplier = false;      
            }
        },
        
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
                    url : "purchaseing_module/po_supplier/get-product",
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
        
        addDetail(){
            this.form.po_supplier_details.push({
                product_id : '',
                quantity : 0.00,
                price : 0.00,
                amount : 0.00
            })
        },

        deleteDetail(indexItem){
            this.form.po_supplier_details = this.form
                .po_supplier_details
                .filter((_,index) => index != indexItem);

            this.countTotal();
        },

        onSubmit(isInvalid){
            if(isInvalid || this.isLoadingForm) return;            
      
            this.isLoadingForm = true;

            let url = "purchaseing_module/po_supplier";

            let formData = {
                ...this.form,
                supplier_id : typeof this.form.supplier_id == 'object'
                    ? this.form.supplier_id.id 
                    : ''
            }

            formData.po_supplier_details = formData
                .po_supplier_details
                .map(item => {
                    return {
                        quantity : item.quantity,
                        price : item.price,
                        amount : item.amount,
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
                method : this.isEditable ? "put" : "post",
                data : formData
            })
            .then(res => {
                this.$toaster.success(
                    "Berhasil " 
                    + (this.isEditable ? "Update" : "Tambah") + 
                    " Po Supplier"
                );

                if(!this.isEditable){
                    this.form = {
                        ...this.default_form,
                        po_supplier_details : []
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
                                    + "/purchaseing_module/po_supplier/get-print-detail/" + 
                                    res.data.id 
                                    + "?token=" + 
                                    token
                                ,'_blank');    
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
                url : "purchaseing_module/po_supplier",
                query : ""            
            });

            this.form.code = this.code;
        },

        itemChange(index){
            let po_supplier = this.form.po_supplier_details[index];

            if(
                po_supplier.quantity >= 0.00
                &&
                po_supplier.price >= 0.00
            ){

                let amount = po_supplier.quantity * po_supplier.price;

                po_supplier.amount = amount;

                this.countTotal();
            }
        },
    
        countTotal(){
            this.form.total = this.form
                .po_supplier_details
                .reduce((amount,item) => {
                    return amount + item.amount
                },0);


            this.form.tax = this.tax * (this.form.total / 100);

            this.form.grand_total = this.form.total + this.form.tax;
        },

        onSetProduct(index){
            let product = this.form.po_supplier_details[index];

            product.price = product.product_id 
                    ? product.product_id.purchase_price
                    : 0;
    
            let products = this.form
                .po_supplier_details
                .filter(item => item.product_id.id == product.product_id.id);

            if(products.length > 1){
                this.$toaster.error("Product sudah ada di dalam list");
                
                this.form.po_supplier_details = this.form
                    .po_supplier_details
                    .filter((_,indexItem) => index != indexItem);
            }        
        }
    }
}
</script>
