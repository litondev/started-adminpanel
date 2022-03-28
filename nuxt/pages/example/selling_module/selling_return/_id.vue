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
                            }} Return Pembelian                
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
                                    <label for="purchaseing">Pembelian</label>
                                       <v-select                           
                                        label="code"   
                                        :loading="isLoadingGetPurchaseing"
                                        :options="lookup_purchaseings.data"
                                        :filterable="false"
                                        @search="onGetPurchaseing"
                                        @input="onSetPurchaseing"
                                        v-model="form.purchaseing_id">              
                                        <template v-slot:option="option">                    
                                            {{option.code }} | {{ option.supplier ? option.supplier.name : "-"}}
                                        </template> 
                                        <template #search="{attributes, events}">
                                            <input
                                            class="vs__search"
                                            :required="!form.purchaseing_id"
                                            v-bind="attributes"
                                            v-on="events"
                                            />
                                        </template>   
                                        <li slot-scope="{search}" slot="list-footer"
                                            class="d-flex justify-content-between"
                                            v-if="lookup_purchaseings.data.length || search">
                                            <span v-if="lookup_purchaseings.current_page > 1" 
                                                @click="onGetPurchaseing(search,false)"
                                                class="flex-fill bg-primary text-white text-center"
                                                style="cursor:pointer">Sebelumnya</span>
                                            <span v-if="lookup_purchaseings.last_page > lookup_purchaseings.current_page" 
                                                @click="onGetPurchaseing(search,true)"
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
                                    <label for="address">Supplier </label>
                                    <input
                                        :value="form.supplier_id ? form.supplier_id.name : '-'"
                                        class="form-control"
                                        :disabled="true"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="desirpition">Deskripsi</label>
                            <textarea class="form-control"
                                v-model="form.description"></textarea>
                        </div>


                        <div class="row">
                            <div class="col-md-3">                        
                                <div class="clearfix">
                                    <h5>Product</h5>
                                </div>

                                <div class="table-responsive mb-3"
                                    style="max-height:350px">
                                    <table class="table table-sm mt-3">                       
                                        <tr>
                                            <td>Kode</td>
                                            <td>Item</td>
                                        </tr>
                                        <tr v-for="item,index in tem_purchaseing_return_details"
                                            :key="index"
                                            class="tr-product">
                                            <td
                                                style="cursor:pointer;"
                                                @click="onSetPurchaseingDetail(item.product_id.id)">
                                                {{ item.product_id ? item.product_id.code : '-' }}
                                            </td>
                                            <td
                                                style="cursor:pointer;"
                                                @click="onSetPurchaseingDetail(item.product_id.id)">
                                                {{ item.product_id ? item.product_id.name : '-' }}
                                            </td>   
                                        </tr>
                                        <tr v-if="!tem_purchaseing_return_details.length">
                                            <td colspan="2"
                                                class="text-center">
                                                Data tidak ditemukan
                                            </td>
                                        </tr>
                                    </table>
                                </div>                                                                
                            </div>
                            <div class="col-md-9">
                                <div class="table-responsive">
          <!--                           <div class="clearfix">
                                        <span class="btn btn-primary float-right"
                                            @click="addDetail"
                                            style="cursor:pointer">
                                            <i class="fas fa-plus"></i>
                                            Tambah
                                        </span>
                                    </div> -->

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
                                                style="width:10%">Disc (Rp)</td>
                                            <td class="text-center"
                                                style="width:11%">Total</td>
                                            <td class="text-center"
                                                style="width:5%">Opsi</td>
                                        </tr>
                                        <tr v-for="item,index in form.purchaseing_return_details"
                                            :key="index">
                                            <td>
                                                <input type="text" 
                                                    class="form-control"
                                                    :value="item.product_id ? item.product_id.code : '-'"
                                                    disabled/>                            
                                            </td>
                                            <td>
                                                <input type="text"
                                                    class="form-control"
                                                    :value="item.product_id ? item.product_id.name : '-'"
                                                    disabled/>                                          
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
                                        <tr v-if="!form.purchaseing_return_details.length">
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
                                            <tr v-if="form.discount > 0.00">
                                                <td colspan="5">
                                                </td>
                                                <td> 
                                                    Total Stl Discount 
                                                </td>
                                                <td>
                                                    <money v-model="form.total_discount"
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
    
        // purchaseing_module-purchaseing_return-id

        // 0 => purchaseing_module (MODULE)
        // 1 => purchaseing_return (MENU)
        // 2 => id (SUB MENU)

        let route_name = route.name.split("-");

        let main_role = user.group_role.roles.find(item => item.name == "purchaseing_return");

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
            title: 'Return Pembelian',
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

            isStopSearchPurchaseing : false,
            isLoadingGetPurchaseing : false,
            purchaseing_search : '',

            isStopSearchProduct : false,
            isLoadingGetProduct : false,
            product_search : '',

            form : {
                purchaseing_id : '',
                supplier_id : '',
                code : '',
                description : '',
                date : moment().format("YYYY-MM-DD"),                
                total : 0,
                tax : 0,
                discount : 0,
                grand_total : 0,
                purchaseing_return_details : [],        
            },

            default_form : {
                purchaseing_id : '',
                supplier_id : '',
                code : '',
                description : '',
                date : moment().format("YYYY-MM-DD"),
                total : 0,
                tax : 0,
                discount : 0,
                grand_total : 0,
                purchaseing_return_details : [],        
            },

            tem_purchaseing_return_details : []
        }
    },

    async created(){
        try{        
            if(this.isEditable){
                let response = await this.$axios.get("purchaseing_module/purchaseing_return/" + this.id);

                Object.keys(this.form)
                    .forEach(item => {
                        if(item != "purchaseing_return_details"){
                            if(item === 'supplier_id'){
                                this.form.supplier_id = response.data.supplier;
                            }else if(item === 'purchaseing_id'){
                                this.form.purchaseing_id = response.data.purchaseing;              
                            }else{
                                this.form[item] = response.data[item];
                            }
                        }
                    });
            
                this.form.purchaseing_return_details = response.data
                        .purchaseing_return_details
                        .map(item => {
                            return {
                                ...item,
                                product_id : item.product
                            }
                        });

                this.tem_purchaseing_return_details = this.form
                        .purchaseing_id.purchaseing_details
                        .filter(item => {
                            return !this.form
                                .purchaseing_return_details
                                .find(
                                    itemDetail => itemDetail.product_id.id == item.product.id
                                );
                        });            

                this.tem_purchaseing_return_details = this
                        .tem_purchaseing_return_details
                        .map(item => {
                            return {
                                ...item,
                                product_id : item.product
                            }
                        });

                this.isLoadingPage = false;        
            }
        }catch(err){
            this.$router.push("/purchaseing_module/purchaseing_return");
        }
    },

    async mounted(){
        await this.lookUp({
            url : "purchaseing_module/purchaseing_return/get-purchaseing",
            lookup : "purchaseing",
            query : ""
            
        })

        await this.lookUp({
            url : "purchaseing_module/purchaseing_return/get-product",
            lookup : "products",
            query : "?per_page=5"
        });

        if(!this.isEditable){
            this.setCode();
        }
    },

    computed :{
        ...mapState('moduleApi',[
            'lookup_purchaseings',
            'lookup_products',    
            'code'
        ]),
        
        tax(){
            return this.$store.state.setting.settings
                .find(item => item.name == "tax")
                .value;
        }
    },

    methods: {    
        ...mapActions('moduleApi',[
            'lookUp',
            'getCode'
        ]),

        onGetPurchaseing(search,isNext){      
            if(!search.length && typeof isNext === "function") return false;             

            clearTimeout(this.isStopSearchPurchaseing);
            
            this.isStopSearchPurchaseing = setTimeout(() => {
                this.purchaseing_search = search;

                if(typeof isNext !== "function"){
                    this.lookup_purchaseings.current_page = isNext 
                        ? this.lookup_purchaseings.current_page + 1 
                        : this.lookup_purchaseings.current_page - 1;        
                }else{
                    this.lookup_purchaseings.current_page = 1;
                }

                this.onSearchPurchaseing();
            },600);        
        },

        async onSearchPurchaseing(){
            if(!this.isLoadingGetPurchaseing){
                this.isLoadingGetPurchaseing = true;
                        
                await this.lookUp({    
                    url : "purchaseing_module/purchaseing_return/get-supplier",
                    lookup : "po_suppliers",
                    query : "?search="
                        + this.purchaseing_search +
                        "&page="
                        + this.lookup_purchaseings.current_page +
                        "&per_page=10"
                })

                this.isLoadingGetPurchaseing = false;      
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
                    url : "purchaseing_module/purchaseing_return/get-product",
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
            this.form.purchaseing_return_details.push({
                product_id : '',
                quantity : 0.00,
                price : 0.00,
                amount : 0.00,
                discount : 0.00
            })
        },

        deleteDetail(indexItem){
            this.tem_purchaseing_return_details
                .push(this.form.purchaseing_return_details.find(
                    (_,index) => index == indexItem
                ))

            this.form.purchaseing_return_details = this.form
                .purchaseing_return_details
                .filter((_,index) => index != indexItem);

            this.countTotal();
        },

        onSubmit(isInvalid){
            if(isInvalid || this.isLoadingForm) return;            
      
            this.isLoadingForm = true;

            let url = "purchaseing_module/purchaseing_return";

            let formData = {
                ...this.form,
                purchaseing_id : typeof this.form.purchaseing_id == 'object'
                    ? this.form.purchaseing_id.id 
                    : '',
                supplier_id : typeof this.form.supplier_id == 'object'
                    ? this.form.supplier_id.id
                    : ''
            }

            formData.purchaseing_return_details = formData
                .purchaseing_return_details
                .map(item => {
                    return {
                        quantity : item.quantity,
                        price : item.price,
                        amount : item.amount,
                        discount : item.discount,
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
                    " Pembelian"
                );

                if(!this.isEditable){
                    this.form = {
                        ...this.default_form,
                        purchaseing_return_details : []
                    }
                    
                    this.tem_purchaseing_return_details = [];

                    this.setCode();                
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
                url : "purchaseing_module/purchaseing_return",
                query : ""            
            });

            this.form.code = this.code;
        },

        itemChange(index){
            let purchaseing_return = this.form.purchaseing_return_details[index];

            if(
                purchaseing_return.quantity >= 0.00
                &&
                purchaseing_return.price >= 0.00
            ){
                let amount = purchaseing_return.quantity * (purchaseing_return.price - purchaseing_return.discount)

                purchaseing_return.amount = amount;

                this.countTotal();
            }
        },
    
        countTotal(){
            this.form.total = this.form
                .purchaseing_return_details
                .reduce((amount,item) => {
                    return amount + item.amount
                },0);


            this.form.total_discount =  this.form
                .purchaseing_return_details
                .reduce((amount,item) => {
                    return amount + item.amount
                },0) - this.form.discount;

            this.form.tax = this.tax * (this.form.total_discount / 100);

            this.form.grand_total = this.form.total_discount + this.form.tax;
        },
    
        onSetPurchaseing(item){
            if(item){         
                this.form = {
                    ...this.form,
                    supplier_id : item.supplier,                                        
                    date : item.date,
                    total : item.total,
                    discount : item.discount,
                    tax : item.tax,
                    grand_total : item.grand_total,
                    description : item.description,                                    
                    purchaseing_return_details : []
                }    

                this.tem_purchaseing_return_details = item
                    .purchaseing_details
                    .map(item => {
                        return {
                            id : item.id,
                            product_id : item.product,
                            quantity : item.quantity,
                            price : item.price,
                            amount : item.amount,
                            discount : item.discount
                        }
                    });   
            }
        },

        onSetPurchaseingDetail(product_id){        
            this.form
                .purchaseing_return_details
                .push(this.tem_purchaseing_return_details.find(
                    item => item.product_id.id == product_id
                ));

            this.tem_purchaseing_return_details = this
                .tem_purchaseing_return_details
                .filter(item => item.product_id.id != product_id);
        }
    }
}
</script>

<style>
.tr-product:hover{
    background: lightgray;
}
</style>