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
                            }} Penjualaan
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
                                    <label for="po_customer_id">Po Pelanggan</label>
                                    <v-select                           
                                        label="code"   
                                        :loading="isLoadingGetPoCustomer"
                                        :options="lookup_po_customers.data"
                                        :filterable="false"
                                        @search="onGetPoCustomer"
                                        @input="onSetPoCustomer"
                                        v-model="form.po_customer_id">              
                                        <template v-slot:option="option">                    
                                            {{ option.code }} | {{ option.customer ? option.customer.name : '-' }}
                                        </template> 
                                        <template #search="{attributes, events}">
                                            <input
                                            class="vs__search"
                                            :required="!form.po_customer_id"
                                            v-bind="attributes"
                                            v-on="events"
                                            />
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
                        </div>

                        <div class="row">
                            <div class="col">
                                 <div class="form-group">
                                    <label for="warehouse_id">Gudang</label>
                                    <v-select                           
                                        label="name"   
                                        :loading="isLoadingGetWarehouse"
                                        :options="lookup_warehouses.data"
                                        :filterable="false"
                                        @search="onGetWarehouse"
                                        v-model="form.warehouse_id">              
                                        <template v-slot:option="option">                    
                                            {{option.code }} | {{ option.name }}
                                        </template> 
                                        <template #search="{attributes, events}">
                                            <input
                                            class="vs__search"
                                            :required="!form.warehouse_id"
                                            v-bind="attributes"
                                            v-on="events"
                                            />
                                        </template>   
                                        <li slot-scope="{search}" slot="list-footer"
                                            class="d-flex justify-content-between"
                                            v-if="lookup_warehouses.data.length || search">
                                            <span v-if="lookup_warehouses.current_page > 1" 
                                                @click="onGetWarehouse(search,false)"
                                                class="flex-fill bg-primary text-white text-center"
                                                style="cursor:pointer">Sebelumnya</span>
                                            <span v-if="lookup_warehouses.last_page > lookup_warehouses.current_page" 
                                                @click="onGetWarehouse(search,true)"
                                                class="flex-fill bg-primary text-white text-center"
                                                style="cursor:pointer">Selanjutnya</span>
                                        </li> 
                                    </v-select>                                                                
                                </div>
                            </div>
                            <div class="col">
                                 <div class="form-group">
                                    <label for="customer_id">Pelanggan</label>
                                    <v-select                           
                                        label="name"   
                                        :loading="isLoadingGetCustomer"
                                        :options="lookup_customers.data"
                                        :filterable="false"
                                        @search="onGetCustomer"
                                        v-model="form.customer_id">              
                                        <template v-slot:option="option">                    
                                            {{option.code }} | {{ option.name }}
                                        </template> 
                                        <template #search="{attributes, events}">
                                            <input
                                            class="vs__search"
                                            :required="!form.customer_id"
                                            v-bind="attributes"
                                            v-on="events"
                                            />
                                        </template>   
                                        <li slot-scope="{search}" slot="list-footer"
                                            class="d-flex justify-content-between"
                                            v-if="lookup_customers.data.length || search">
                                            <span v-if="lookup_customers.current_page > 1" 
                                                @click="onGetCustomer(search,false)"
                                                class="flex-fill bg-primary text-white text-center"
                                                style="cursor:pointer">Sebelumnya</span>
                                            <span v-if="lookup_customers.last_page > lookup_customers.current_page" 
                                                @click="onGetCustomer(search,true)"
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
                                        :value="form.customer_id ? form.customer_id.address : '-'"
                                        class="form-control"
                                        :disabled="true"></textarea>                                
                                </div>
                            </div>
                        </div>

                        <div class="row">                        
                            <div class="col">
                                <div class="form-group">
                                    <label for="transaction_type">Type Transaksi</label>
                                    <select v-model="form.transaction_type"
                                        class="form-control">
                                        <option value="CASH">CASH</option>
                                        <option value="CREDIT">CREDIT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <ValidationProvider 
                                    name="due_date"
                                    rules="required">
                                    <div class="form-group" slot-scope="{errors,valid}">
                                        <label for="due_date">Temp Tgl</label>
                                        <input type="date"
                                            class="form-control"
                                            name="due_date"
                                            v-model="form.due_date"
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
                                        style="width:10%">Discount (Rp)</td>
                                    <td class="text-center"
                                        style="width:11%">Total</td>
                                    <td class="text-center"
                                        style="width:5%">Opsi</td>
                                </tr>
                                <tr v-for="item,index in form.selling_details"
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
                                        <money v-model="item.discount"
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
                                <tr v-if="!form.selling_details.length">
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
                                        <td>
                                            Discount
                                            <div class="text-muted text-small">
                                                * Nominal
                                            </div>
                                        </td> 
                                        <td>
                                            <money v-model="form.discount"
                                                class="form-control"                                                                              
                                                @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                                @change.native="countTotal"
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
    
        // selling_module-selling-id

        // 0 => selling_module (MODULE)
        // 1 => selling (MENU)
        // 2 => id (SUB MENU)

        let route_name = route.name.split("-");

        let main_role = user.group_role.roles.find(item => item.name == "selling");

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
            title: 'Penjualaan',
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

            isStopSearchPoCustomer : false,
            isLoadingGetPoCustomer : false,
            po_customer_search : '',

            isStopSearchCustomer : false,
            isLoadingGetCustomer : false,
            customer_search : '',

            isStopSearchWarehouse : false,
            isLoadingGetWarehouse : false,
            warehouse_search : '',

            isStopSearchProduct : false,
            isLoadingGetProduct : false,
            product_search : '',

            form : {
                po_customer_id : '',
                customer_id : '',
                warehouse_id : '',
                code : '',
                description : '',
                date : moment().format("YYYY-MM-DD"),
                due_date : moment().format("YYYY-MM-DD"),
                transaction_type : "CASH",
                total : 0,
                tax : 0,
                grand_total : 0,
                discount : 0,
                selling_details : [],
            },

            default_form : {              
                po_customer_id : '',
                customer_id : '',
                warehouse_id : '',
                code : '',
                description : '',
                date : moment().format("YYYY-MM-DD"),
                due_date : moment().format("YYYY-MM-DD"),
                transaction_type : "CASH",
                total : 0,
                tax : 0,
                grand_total : 0,
                discount : 0,
                selling_details : [],
            }
        }
    },

    async created(){
        try{        
            if(this.isEditable){
                let response = await this.$axios.get("selling_module/selling/"+this.id);

                Object.keys(this.form)
                    .forEach(item => {
                        if(item != "selling_details"){
                            if(item === 'po_customer_id'){
                                this.form.po_customer_id = response.data.po_customer;
                            }else if (item === 'customer_id') {
                                this.form.customer_id = response.data.customer;
                            }else if ( item === 'warehouse_id'){
                                this.form.warehouse_id = response.data.warehouse;
                            }else{
                                this.form[item] = response.data[item];
                            }
                        }
                    });
            
                this.form.selling_details = response.data
                    .selling_details
                    .map(item => {
                        return {
                            ...item,
                            product_id : item.product
                        }
                    });

                this.isLoadingPage = false;        
            }
        }catch(err){
            this.$router.push("/selling_module/selling");
        }
    },

    async mounted(){
        await this.lookUp({
            url : "selling_module/selling/get-po-customer",
            lookup : "po_customers",
            query : ""
        });

        await this.lookUp({
            url :  "selling_module/selling/get-customer",
            lookup : "customers",
            query : ""
        })

        await this.lookUp({
            url : "selling_module/selling/get-warehouse",
            lookup : "warehouses",
            query : ""
        })

        await this.lookUp({
            url : "selling_module/selling/get-product",
            lookup : "products",
            query : "?per_page=5"
        });

        if(!this.isEditable){
            this.setCode();
        }
    },

    computed :{
        ...mapState('moduleApi',[
            'lookup_po_customers',
            'lookup_customers',
            'lookup_warehouses',
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
                    url : "selling_module/selling/get-po-customer",
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
                    url : "selling_module/selling/get-customer",
                    lookup : "customers",
                    query : "?search="
                        + this.customer_search + 
                        "&page="
                        + this.lookup_customers.current_page + 
                        "&per_page=10"
                })

                this.isLoadingGetCustomer = false;      
            }
        },
        

        onGetWarehouse(search,isNext){      
            if(!search.length && typeof isNext === "function") return false;             

            clearTimeout(this.isStopSearchWarehouse);
            
            this.isStopSearchWarehouse = setTimeout(() => {
                this.warehouse_search = search;

                if(typeof isNext !== "function"){
                    this.lookup_warehouses.current_page = isNext 
                        ? this.lookup_warehouses.current_page + 1 
                        : this.lookup_warehouses.current_page - 1;        
                }else{
                    this.lookup_warehouses.current_page = 1;
                }

                this.onSearchWarehouse();
            },600);        
        },

        async onSearchWarehouse(){
            if(!this.isLoadingGetWarehouse){
                this.isLoadingGetWarehouse = true;
                        
                await this.lookUp({    
                    url : "selling_module/selling/get-warehouse",
                    lookup : "warehouses",
                    query : "?search="
                        + this.warehouse_search + 
                        "&page="
                        + this.lookup_warehouses.current_page + 
                        "&per_page=10"
                })

                this.isLoadingGetWarehouse = false;      
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
                    url : "selling_module/selling/get-product",
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
            this.form.selling_details.push({
                product_id : '',
                quantity : 0.00,
                price : 0.00,
                amount : 0.00,
                discount : 0.00
            })
        },

        deleteDetail(indexItem){
            this.form.selling_details = this.form
                .selling_details
                .filter((_,index) => index != indexItem);

            this.countTotal();
        },

        onSubmit(isInvalid){
            if(isInvalid || this.isLoadingForm) return;            
      
            this.isLoadingForm = true;

            let url = "selling_module/selling";

            let formData = {
                ...this.form,
                po_customer_id : typeof this.form.po_customer_id == 'object'
                    ? this.form.po_customer_id.id 
                    : '',
                customer_id : typeof this.form.customer_id == 'object'
                    ? this.form.customer_id.id 
                    : '',
                warehouse_id : typeof this.form.warehouse_id == 'object'
                    ? this.form.warehouse_id.id                 
                    : ''
            }

            formData.selling_details = formData
                .selling_details
                .map(item => {
                    return {
                        discount : item.discount,
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
                    " Penjualaan"
                );

                if(!this.isEditable){
                    this.form = {
                        ...this.default_form,
                        selling_details : []
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
                                    + "/selling_module/selling/get-print-detail/" + 
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
                url : "selling_module/selling",
                query : ""            
            });

            this.form.code = this.code;
        },

        itemChange(index){
            let selling = this.form.selling_details[index];

            if(
                selling.quantity >= 0.00
                &&
                selling.price >= 0.00
            ){

                let amount = selling.quantity * (selling.price - selling.discount);

                selling.amount = amount;

                this.countTotal();
            }
        },
    
        countTotal(){
            this.form.total = this.form
                .selling_details
                .reduce((amount,item) => {
                    return amount + item.amount
                },0);

            this.form.total_discount = this.form 
                .selling_details
                .reduce((amount,item) => {
                    return amount + item.amount
                },0) - this.form.discount;


            this.form.tax = this.tax * (this.form.total_discount / 100);

            this.form.grand_total = this.form.total_discount + this.form.tax;
        },

        onSetProduct(index){
            let product = this.form.selling_details[index];

            product.price = product.product_id 
                    ? product.product_id.purchase_price
                    : 0;
    
            let products = this.form
                .selling_details
                .filter(item => item.product_id.id == product.product_id.id);

            if(products.length > 1){
                this.$toaster.error("Product sudah ada di dalam list");
                
                this.form.selling_details = this.form
                    .selling_details
                    .filter((_,indexItem) => index != indexItem);
            }        
        },

        onSetPoCustomer(item){
            if(item){                
                this.form.customer_id = item.customer;
                this.form.selling_details = item.po_customer_details
                    .map(item => {
                        return {
                            ...item,
                            product_id : item.product
                        }
                    });
                    
                this.countTotal();

            }
        }
    }
}
</script>
