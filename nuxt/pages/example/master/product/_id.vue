<template>
    <section class="section">
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
                                        <label for="code">Kode <span class="text-small text-muted">(PT.bentuk.detail 1.detail 2.dominan.finishing.no)</span></label>
                                        <input type="text" 
                                            class="form-control"
                                            name="code"
                                            v-model="form.code"                                    
                                            :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                                            disabled/>

                                        <div class="invalid-feedback" v-if="errors[0]">
                                            {{ errors[0] }}
                                        </div>   

                                        <div class="text-small text-danger mt-2 mb-2"
                                            v-if="isEditable">
                                            * Ketika Bentuk,Detail 1,Detail 2,Dominan dan Finshing diubah maka code juga akan berubah
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

                                <div class="form-group">
                                    <label for="name_marketplace">Nama Marketplace</label>
                                    <input type="text"
                                        class="form-control"
                                        name="name_marketplace"
                                        v-model="form.name_marketplace">                                        
                                </div>

                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <textarea class="form-control"
                                        v-model="form.description"></textarea>
                                </div>

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
                                        <a v-if="lookup_suppliers.current_page > 1" 
                                            @click="onGetSupplier(search,false)"
                                            class="flex-fill bg-primary text-white text-center"
                                            href="#">Sebelumnya</a>
                                        <a v-if="lookup_suppliers.last_page > lookup_suppliers.current_page" 
                                            @click="onGetSupplier(search,true)"
                                            class="flex-fill bg-primary text-white text-center"
                                            href="#">Selanjutnya</a>
                                        </li> 
                                    </v-select>                                              
                                </div>

                                <ValidationProvider
                                    name="shape"
                                    rules="required">  
                                    <div class="form-group" slot-scope="{errors,valid}">
                                        <label for="shape">Bentuk</label>
                                        <select class="form-control"
                                            :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                                            @change="onCodeChange"
                                            v-model="form.shape">
                                            <option value="">
                                                Pilih
                                            </option>
                                            <option v-for="item in shape"
                                                :key="item.id"
                                                :value="item.name">
                                                {{ item.code }} | {{ item.name }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback" v-if="errors[0]">
                                            {{ errors[0] }}
                                        </div>  
                                    </div>
                                </ValidationProvider>

                                <div class="row">
                                    <div class="col">
                                        <ValidationProvider
                                            name="detail_1"
                                            rules="required">  
                                            <div class="form-group" slot-scope="{errors,valid}">
                                                <label for="detail_1">Detail 1</label>
                                                <select class="form-control"
                                                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                                                    @change="onCodeChange"
                                                    v-model="form.detail_1">
                                                    <option value="">
                                                        Pilih
                                                    </option>
                                                    <option v-for="item in detail_1"
                                                        :key="item.id"
                                                        :value="item.name">
                                                        {{ item.code }} | {{ item.name }}
                                                    </option>
                                                </select>
                                                <div class="invalid-feedback" v-if="errors[0]">
                                                    {{ errors[0] }}
                                                </div>  
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col">
                                         <ValidationProvider
                                            name="detail_2"
                                            rules="required">  
                                            <div class="form-group" slot-scope="{errors,valid}">
                                                <label for="detail_2">Detail 2</label>
                                                <select class="form-control"
                                                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                                                    @change="onCodeChange"
                                                    v-model="form.detail_2">
                                                    <option value="">
                                                        Pilih
                                                    </option>
                                                    <option v-for="item in detail_2"
                                                        :key="item.id"
                                                        :value="item.name">
                                                        {{ item.code }} | {{ item.name }}
                                                    </option>
                                                </select>
                                                <div class="invalid-feedback" v-if="errors[0]">
                                                    {{ errors[0] }}
                                                </div>  
                                            </div>
                                         </ValidationProvider>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <ValidationProvider
                                            name="finishing"
                                            rules="required"> 
                                            <div class="form-group" slot-scope="{errors,valid}">
                                                <label for="finishing">Finishing</label>
                                                <select class="form-control"
                                                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                                                    @change="onCodeChange"
                                                    v-model="form.finishing">
                                                    <option value="">
                                                        Pilih
                                                    </option>
                                                    <option v-for="item in finishing"
                                                        :key="item.id"
                                                        :value="item.name">
                                                        {{ item.code }} | {{ item.name }}
                                                    </option>                                                    
                                                </select>
                                                <div class="invalid-feedback" v-if="errors[0]">
                                                    {{ errors[0] }}
                                                </div>  
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col">
                                        <ValidationProvider
                                            name="dominant"
                                            rules="required"> 
                                            <div class="form-group" slot-scope="{errors,valid}">
                                                <label for="dominant">Dominan</label>
                                                <select class="form-control"
                                                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                                                    @change="onCodeChange"
                                                    v-model="form.dominant">
                                                    <option value="">
                                                        Pilih
                                                    </option>
                                                    <option v-for="item in dominant"
                                                        :key="item.id"
                                                        :value="item.name">
                                                        {{ item.code }} | {{ item.name }}
                                                    </option>
                                                </select>
                                                <div class="invalid-feedback" v-if="errors[0]">
                                                    {{ errors[0] }}
                                                </div>  
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <ValidationProvider
                                            name="purchase_unit"
                                            rules="required"> 
                                            <div class="form-group" slot-scope="{errors,valid}">
                                                <label for="purchase_unit">Satuan Beli</label>
                                                <select class="form-control"
                                                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                                                    v-model="form.purchase_unit">
                                                    <option value="">
                                                        Pilih
                                                    </option>
                                                    <option v-for="item in lookup_units"   
                                                        :key="item.id"
                                                        :value="item.name">
                                                        {{ item.name }}
                                                    </option>
                                                </select>
                                                 <div class="invalid-feedback" v-if="errors[0]">
                                                    {{ errors[0] }}
                                                </div>  
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col">
                                        <ValidationProvider
                                            name="selling_unit"
                                            rules="required"> 
                                            <div class="form-group" slot-scope="{errors,valid}">
                                                <label for="selling_unit">Satuan Jual</label>
                                                <select class="form-control"
                                                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                                                    v-model="form.selling_unit">
                                                    <option value="">
                                                        Pilih
                                                    </option>
                                                    <option v-for="item in lookup_units"   
                                                        :key="item.id"
                                                        :value="item.name">
                                                        {{ item.name }}
                                                    </option>
                                                </select>
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
                                            <label for="quantity_unit">Jumlah Unit (Qty)</label>
                                            <money v-model="form.quantity_unit"
                                                class="form-control"                                                                              
                                                @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                            />     
                                        </div>
                                    </div>
                                    <div class="col">                            
                                        <div class="form-group">
                                            <label for="purchase_price">Harga Beli</label>
                                            <money v-model="form.purchase_price"
                                                class="form-control"                                                                              
                                                @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                            />    
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

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title">
                                        <h6>Setting Harga Jual</h6>
                                        <div class="table-responsive">
                                            <div class="clearfix">
                                                <button class="btn btn-primary float-right"
                                                    @click="addProductPrice">
                                                    <i class="fas fa-plus"></i> Tambah
                                                </button>
                                            </div>

                                            <table class="table p-0 m-0 table-sm">
                                                <tr>
                                                    <td>Jenis Harga</td>
                                                    <td>Judul</td>
                                                    <td>Harga</td>
                                                    <td>Opsi</td>                                                    
                                                </tr>
                                                <tr v-for="item,index in product_prices"
                                                    :key="item.id">
                                                    <td style="width:30%">
                                                        <select class="form-control"
                                                            v-model="item.name"
                                                            @change="checkProductPrice">
                                                            <option value="">
                                                                Pilih 
                                                            </option>
                                                            <option v-for="itemCustomerGroup in lookup_customer_groups"
                                                                :key="itemCustomerGroup.id"
                                                                :value="itemCustomerGroup.name">
                                                                {{itemCustomerGroup.name}}
                                                            </option>
                                                        </select>
                                                    </td>                                                    
                                                    <td style="width:30%">
                                                        <input type="text" 
                                                            class="form-control"
                                                            v-model="item.title">
                                                    </td>
                                                    <td style="width:25%">
                                                        <money v-model="item.price"
                                                            class="form-control"                                                                              
                                                            @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                                        />   
                                                    </td>
                                                    <td style="width:10%"
                                                        class="text-center">
                                                        <i class="fas fa-trash" 
                                                            style="cursor:pointer"
                                                            @click="deleteProductPrice(index)">
                                                        </i>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12"> 
                            <div class="card">
                                <div class="card-body">                            
                                    <h6>Setting Harga Bertingkat</h6>
                                    <div class="table-responsive">
                                        <div class="clearfix">
                                            <button class="btn btn-primary float-right"
                                                @click="addTieredPrice">
                                                <i class="fas fa-plus"></i> Tambah
                                            </button>
                                        </div>
                                        
                                        <table class="table p-0 m-0 table-sm">
                                            <tr>
                                                <td>Quantity</td>
                                                <td>Satuan</td>
                                                <td>Harga</td>
                                                <td>Opsi</td> 
                                            </tr>
                                            <tr v-for="item,index in tiered_prices"
                                                :key="item.id">
                                                <td style="width:20%">
                                                    <money v-model="item.quantity"
                                                        class="form-control"                                                                              
                                                        @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                                    />    
                                                </td>
                                                <td style="width:35%">
                                                    <select class="form-control form-control-sm"
                                                        v-model="item.selling_unit">
                                                        <option value="">
                                                            Pilih 
                                                        </option>
                                                        <option v-for="itemUnit in lookup_units"
                                                            :key="itemUnit.id"
                                                            :value="itemUnit.name">
                                                            {{itemUnit.name}}
                                                        </option>
                                                    </select>
                                                </td>                                                    
                                                <td style="width:35%">
                                                    <money v-model="item.price"
                                                        class="form-control"                                                                              
                                                        @keydown.native="$event.key === '-' ? $event.preventDefault() : null"
                                                    />    
                                                </td>                                        
                                                <td style="width:10%"
                                                    class="text-center">
                                                    <i class="fas fa-trash" 
                                                        style="cursor:pointer"
                                                        @click="deleteTieredPrice(index)">
                                                    </i>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                                <portal to="modal-coolligtbox">
                                                    <CoolLightBox 
                                                        :items="preview_images" 
                                                        :index="indexPreviewImages" 
                                                        @close="indexPreviewImages = null"/>
                                                </portal>

                                                <div class="images-warpper">
                                                    <img
                                                        class="image-warpper"
                                                        v-for="(image, imageIndex) in preview_images"
                                                        :key="imageIndex"
                                                        @click="indexPreviewImages = imageIndex"
                                                        :src="image"/>                                                                                                    
                                                </div>                                            
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
    </section>
</template>

<script>
import { mapActions,mapState } from 'vuex'
import CoolLightBox from 'vue-cool-lightbox'
import 'vue-cool-lightbox/dist/vue-cool-lightbox.min.css'

export default {
    middleware({ store, redirect, route}) {
        let user = store.state.auth.user;

        if(!user.parent_id){
            return ;
        }
    
        // master-product-id

        // 0 => master (MODULE)
        // 1 => product (MENU)
        // 2 => id (SUB MENU)

        let route_name = route.name.split("-");

        let main_role = user.group_role.roles.find(item => item.name == "product");

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
            title: 'Product',
        }
    },

    components: {
        CoolLightBox,
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

            form : {
                supplier_id : '',
                code : '',
                name : '',
                shape : '',
                detail_1 : '',
                detail_2 : '',
                finishing : '',
                dominant : '',
                purchase_unit : '',
                selling_unit : '',
                quantity_unit : 1.00,
                purchase_price : 0.00,
                cost_of_products_sold : 0.00,
                name_marketplace : '',
                description : '',
                // images : []
            },
            
            detail_1 : [],
            detail_2 : [],
            dominant : [],
            finishing : [],
            shape : [],

            product_prices : [],

            tiered_prices : [],

            preview_images : [],

            indexPreviewImages : null
        }
    },

    async created(){
        if(this.isEditable){
            try{        
                let response = await this.$axios.get("master/product/"+this.id);

                Object.keys(this.form).forEach(item => {
                    if(item === 'supplier_id'){
                        this.form.supplier_id = response.data.supplier;
                    }else{
                        this.form[item] = response.data[item];
                    }
                });

                response.data.images.forEach(item => {
                    this.preview_images.push(process.env.IMAGES_API_URL + "products/"+item);
                })        
                
                this.product_prices = response.data.product_prices;

                this.tiered_prices = response.data.tiered_prices;

                this.isLoadingPage = false;
            }catch(err){
                this.$router.push("/master/product");
            }
        }
    },

    async mounted(){
        await this.lookUp({
            url : "master/product/get-supplier",
            lookup : "suppliers",
            query : ""
        });

        await this.lookUp({
            url : "master/product/get-product-spesification",
            lookup : "product_spesifications",
            query : ""
        })

        await this.lookUp({
            url : "master/product/get-unit",
            lookup : "units",
            query : ""
        })

        await this.lookUp({
            url : "master/product/get-customer-group",
            lookup : "customer_groups",
            query : ""
        })

        if(this.lookup_product_spesifications.length){
            this.shape = this.lookup_product_spesifications.filter(item => item.type === "Bentuk");
            this.detail_1 = this.lookup_product_spesifications.filter(item => item.type === "Detail 1");
            this.detail_2 = this.lookup_product_spesifications.filter(item => item.type === "Detail 2");
            this.finishing = this.lookup_product_spesifications.filter(item => item.type === "Finishing");
            this.dominant = this.lookup_product_spesifications.filter(item => item.type === "Dominan");
        }
    },

    computed :{
        ...mapState('moduleApi',[
            'lookup_suppliers',
            'lookup_product_spesifications',
            'lookup_units',
            'lookup_customer_groups'
        ]),  
    },
    
    methods: {    
        ...mapActions('moduleApi',[
            'lookUp'
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
                    url : "master/product/get-supplier",
                    lookup : "suppliers",
                    query : "?search="+this.supplier_search+"&page="+this.lookup_suppliers.current_page+"&per_page=10"
                })

                this.isLoadingGetSupplier = false;      
            }
        },
        
        onCodeChange(){
            if(
                this.form.shape 
                && 
                this.form.detail_1 
                && 
                this.form.detail_2
                && 
                this.form.dominant
                && 
                this.form.finishing
            ){
                let shape = this.lookup_product_spesifications.find(item => item.type === "Bentuk" && item.name === this.form.shape);
                let detail_1 = this.lookup_product_spesifications.find(item => item.type === "Detail 1" && item.name === this.form.detail_1);
                let detail_2 = this.lookup_product_spesifications.find(item => item.type === "Detail 2" && item.name === this.form.detail_2);
                let finishing = this.lookup_product_spesifications.find(item => item.type === "Finishing" && item.name === this.form.finishing);
                let dominant = this.lookup_product_spesifications.find(item => item.type === "Dominan" && item.name === this.form.dominant);
            
                this.form.code = "PT." + shape.code
                    + '.' + detail_1.code
                    + '.' + detail_2.code
                    + '.' + dominant.code
                    + '.' + finishing.code; 
            }else{
                this.form.code = "";
            }
        },

        addProductPrice(){
            if(this.product_prices.length >= this.lookup_customer_groups.length){
                this.$toaster.warning("Harga Produk tidak boleh lebih dari pelanggan group");
                return;
            }

            this.product_prices.push({
                name : '',
                title : '',
                price : 0.00
            });
        },

        deleteProductPrice(indexItem){
            this.product_prices = this.product_prices.filter((_,index) => index != indexItem);
        },

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

        addTieredPrice(){
            this.tiered_prices.push({
                quantity : 0,
                selling_unit : "",
                price : 0.00
            });
        },

        deleteTieredPrice(indexItem){
            this.tiered_prices = this.tiered_prices.filter((_,index) => index != indexItem);
        },

        checkProductPrice(event){
            if(this.product_prices.filter(item => item.name == event.target.value).length > 1){
                this.$toaster.warning("Harga ini telah ditetapkan sebelumnya");
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
                if(item == "supplier_id"){
                    formData.append(item,typeof this.form.supplier_id == 'object'
                        ? this.form.supplier_id.id 
                        : '',)
                }else{
                    formData.append(item,this.form[item]);
                }
            })
        
            this.tiered_prices.forEach((item,index) => {                                    
                formData.append("tiered_prices["+index+"][quantity]",item.quantity);
                formData.append("tiered_prices["+index+"][selling_unit]",item.selling_unit);
                formData.append("tiered_prices["+index+"][price]",item.price);
            });
                
            this.product_prices.forEach((item,index) => {  
                formData.append("product_prices["+index+"][name]",item.name);
                formData.append("product_prices["+index+"][title]",item.title);
                formData.append("product_prices["+index+"][price]",item.price);
            })
       
            this.$axios.post(url,formData)
            .then(res => {
                this.$toaster.success("Berhasil " + (this.isEditable ? "Update" : "Tambah") + " Produk");

                if(this.isEditable){
                    this.form.code = res.data.code;
                }
            })
            .catch(err => {
                this.$globalErrorToaster(this.$toaster,err);
            })
            .finally(() => {
                this.isLoadingForm = false;
            })            
        }      
    }
}
</script>

<style scoped>
.images-warpper{
    text-align: center;
    width:100%
}

.images-warpper > .image-warpper{
    margin-bottom: 20px;
    width: 100px;
    object-fit: contain;
    margin-right: 20px;
    cursor: pointer;
}
</style>